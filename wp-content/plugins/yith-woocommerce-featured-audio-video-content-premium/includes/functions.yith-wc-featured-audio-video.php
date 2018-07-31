<?php

if( !defined( 'ABSPATH' ) )
    exit;

if( !function_exists( 'yith_wcfav_locate_template' ) ) {
    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $path
     * @param array $var
     * @return void
     * @since 1.0.0
     */
    function yith_wcfav_locate_template( $path, $var = NULL ){
        global $woocommerce;

        if( function_exists( 'WC' ) ){
            $woocommerce_base = WC()->template_path();
        }
        elseif( defined( 'WC_TEMPLATE_PATH' ) ){
            $woocommerce_base = WC_TEMPLATE_PATH;
        }
        else{
            $woocommerce_base = $woocommerce->plugin_path() . '/templates/';
        }

        $template_woocommerce_path =  $woocommerce_base . $path;
        $template_path = '/' . $path;
        $plugin_path = YWCFAV_TEMPLATE_PATH . '/' . $path;

        $located = locate_template( array(
            $template_woocommerce_path, // Search in <theme>/woocommerce/
            $template_path,             // Search in <theme>/
        ) );

        if( ! $located && file_exists( $plugin_path ) ){
            return apply_filters( 'yith_wcfav_locate_template', $plugin_path, $path );
        }

        return apply_filters( 'yith_wcfav_locate_template', $located, $path );
    }
}

if( !function_exists( 'yith_wcfav_get_template' ) ) {
    /**
     * Retrieve a template file.
     *
     * @param string $path
     * @param mixed $var
     * @param bool $return
     * @return void
     * @since 1.0.0
     */
    function yith_wcfav_get_template( $path, $var = null, $return = false ) {
        $located = yith_wcfav_locate_template( $path, $var );


        if ( $var && is_array( $var ) )
            extract( $var );

        if( $return )
        { ob_start(); }

        // include file located
        include( $located );

        if( $return )
        { return ob_get_clean(); }
    }
}

if( !function_exists( 'yith_Hex2RGB' ) ){

    function yith_Hex2RGB( $color ){
        $color = str_replace( '#', '', $color );
        if ( strlen( $color ) != 6){ return array( 0,0,0 ); }
        $rgb = array();
        for ( $x=0;$x<3;$x++ ){
            $rgb[$x] = hexdec( substr( $color,( 2*$x ),2 ) );
        }
        return $rgb;
    }

}



if( !function_exists( 'get_minimum_size_accept' ) ) {
    function get_minimum_size_accept()
    {

        $size = array(
            'width' => 320,
            'height' => 300
        );

        return apply_filters('yith_featured_audio_video_minimum_size_accept', $size);
    }
}

if( !function_exists( 'ywcfav_video_type_by_url' ) ) {
    /**
     * Retrieve the type of video, by url
     *
     * @param string $url The video's url
     * @return mixed A string format like this: "type:ID". Return FALSE, if the url isn't a valid video url.
     *
     * @since 1.1.0
     */
    function ywcfav_video_type_by_url($url)
    {

        $parsed = parse_url(esc_url($url));

        switch( $parsed['host'] ) {

            case 'www.youtube.com' :
            case    'youtu.be':
                $id = ywcfav_get_yt_video_id($url);
                return "youtube:$id";

            case 'vimeo.com' :
            case 'player.vimeo.com' :
                preg_match('/.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/', $url, $matches);
                $id = $matches[5];
                return "vimeo:$id";

            default :
                return apply_filters('yith_woocommerce_featured_video_type', false, $url);

        }
    }
}
if( !function_exists( 'ywcfav_get_yt_video_id' ) ) {
    /**
     * Retrieve the id video from youtube url
     *
     * @param string $url The video's url
     * @return string The youtube id video
     *
     * @since 1.1.0
     */
    function ywcfav_get_yt_video_id($url)
    {

        $pattern =
            '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x';
        $result = preg_match($pattern, $url, $matches);
        if (false !== $result) {
            return $matches[1];
        }
        return false;
    }
}

if( !function_exists( 'ywcfav_removeElementWithValue' ) ){
	
	function ywcfav_removeElementWithValue( $array, $key, $value ){
		foreach( $array as $subKey => $subArray ){
			if($subArray[$key] == $value){
				unset( $array[$subKey] );
			}
		}
		return $array;
	}
}

if( !function_exists( 'ywcfav_update_plugin' ) ){
	function ywcfav_update_plugin(){

		$current_version = get_option( 'ywcfav_plugin_update','0' );

		if( version_compare( $current_version, '1.0.0','<' ) ){


			$page = 1;
			$args = array(
					'post_type' => 'product',
					'posts_per_page' => 15,
					'post_status' => 'publish',
					'paged' => $page,
					'fields' => 'ids',

			);
			
			$all_product = get_posts($args);
			
			while ( count( $all_product )>0 ){
				
				foreach ( $all_product as $product_id ){
					
					$all_video = get_post_meta( $product_id , '_ywcfav_video', true );
					$all_audio = get_post_meta( $product_id,'_ywcfav_audio', true );
					
					if( !empty( $all_video ) ){
						
						$all_video = ywcfav_update_video_simple_product($product_id, $all_video );
						
						update_post_meta( $product_id, '_ywcfav_video', $all_video );
					}
					
					if( !empty( $all_audio ) ){
						
						$all_audio = ywcfav_update_audio_simple_product( $product_id, $all_audio );
						
						update_post_meta( $product_id, '_ywcfav_audio', $all_audio );
					}
					
					$product = wc_get_product( $product_id );
					
					if( $product instanceof WC_Product_Variable ){
						
						$all_variation = $product->get_children();
						
						foreach( $all_variation as  $variation_id ){
							
							$video_variation = get_post_meta( $variation_id, '_ywcfav_variation_video', true );
							
							if( !empty( $video_variation ) ){
								
								$video_variation = ywcfav_update_video_variation_product($variation_id, $video_variation );
								update_post_meta( $variation_id, '_ywcfav_variation_video', $video_variation );
							}
						}
					}
				}
				
				$page++;
				
				$args['paged'] = $page;
				
				$all_product = get_posts( $args );
			}
			$current_version = '1.0.0';
		}

		update_option( 'ywcfav_plugin_update', YWCFAV_DB_VERSION );
	}
}

add_action( 'admin_init', 'ywcfav_update_plugin', 25 );

if( !function_exists( 'ywcfav_update_video_simple_product' ) ){
	
	function ywcfav_update_video_simple_product( $product_id, $all_video ){
		
		$find_featured = false;
		for( $i=0; $i<count( $all_video ); $i++ ){


			if( !isset( $all_video[$i]['name'] ) || $all_video[$i]['name'] == '' ){
				
				$all_video[$i]['name'] = 'Video '.($i+1);

			}
			
			if( isset(  $all_video[$i]['featured'] ) && $all_video[$i]['featured'] == 'featured' && !$find_featured ){
				
				$args = array('id' => $all_video[$i]['id'], 'type' => 'video');
				update_post_meta( $product_id, '_ywcfav_featured_content', $args);
				$find_featured = true;
			}


			$video_type = $all_video[$i]['type'];
			
			switch ( $video_type ){
				
				case 'ID':
					$all_video[$i]['type'] = 'id';
					break;
					case 'URL':
						$all_video[$i]['type'] = 'url';
					break;
				case 'EMBEDDED':
					$all_video[$i]['type'] = 'embedded';
					$all_video[$i]['host'] = 'embedded';
					break;
				case 'UPLOAD';
					$all_video[$i]['host'] = 'host';
				break;		
			}

			$video_thumb = $all_video[$i]['thumbn'];

			if( !is_numeric( $video_thumb ) ){

				if( ( strpos( $video_thumb,'videoplaceholder.jpg') > 0 ) || ( strpos( $video_thumb,'audioplaceholder.jpg') > 0 ) ){

					$video_thumb = get_option( 'ywcfav_video_placeholder_id' );
					$all_video[$i]['thumbn'] = $video_thumb;

				}else {
					$video_name = $all_video[$i]['name'];
					$video_thumb = ywcfav_save_remote_image($video_thumb, $video_name);
					$all_video[$i]['thumbn'] = $video_thumb;
				}

			}

		}

		return $all_video;
	}
}

if( !function_exists( 'ywcfav_update_audio_simple_product' ) ){

	function ywcfav_update_audio_simple_product( $product_id, $all_audio ){

		$find_featured = false;
		for( $i=0; $i<count( $all_audio ); $i++ ){
				
			if( !isset( $all_audio[$i]['name'] ) || $all_audio[$i]['name']   == '' ){

				$all_audio[$i]['name'] = 'Audio '.($i+1);
			}
				
			if( isset(  $all_audio[$i]['featured'] ) && $all_audio[$i]['featured'] == 'featured' && !$find_featured ){

				$args = array('id' => $all_audio[$i]['id'], 'type' => 'audio');
				update_post_meta( $product_id, '_ywcfav_featured_content', $args);
				$find_featured = true;

			}

			$audio_thumb =  $all_audio[$i]['thumbn'];

			if( !is_numeric( $audio_thumb ) ){

				if( ( strpos( $audio_thumb,'videoplaceholder.jpg') > 0 ) || ( strpos( $audio_thumb,'audioplaceholder.jpg') > 0 ) ){

					$audio_thumb = get_option( 'ywcfav_audio_placeholder_id' );
					$all_audio[$i]['thumbn'] = $audio_thumb;

				}else {
					$audio_name = $all_audio[$i]['name'];
					$audio_thumb = ywcfav_save_remote_image($audio_thumb, $audio_name);
					$all_audio[$i]['thumbn'] = $audio_thumb;
				}

			}

		}

		return $all_audio;
	}
}

if( !function_exists( 'ywcfav_update_video_variation_product' ) ){

	function ywcfav_update_video_variation_product( $variation_id, $all_video ){
		
			if( !isset( $all_video['name'] ) || $all_video['name'] == '' ){

				$all_video['name'] = 'Video Variation #'.($variation_id);
			}
			$video_type = $all_video['type'];
				
			switch ( $video_type ){
			
				case 'ID':
					$all_video['type'] = 'id';
					break;
				case 'URL':
					$all_video['type'] = 'url';
					break;
				case 'EMBEDDED':
					$all_video['type'] = 'embedded';
					$all_video['host'] = 'embedded';
					break;
				case 'UPLOAD';
				$all_video['host'] = 'host';
				break;
			}

		$video_thumb = $all_video['thumbn'];

		if( !is_numeric( $video_thumb ) ){

			if( ( strpos( $video_thumb,'videoplaceholder.jpg') > 0 ) || ( strpos( $video_thumb,'audioplaceholder.jpg') > 0 ) ){

				$video_thumb = get_option( 'ywcfav_video_placeholder_id' );
				$all_video['thumbn'] = $video_thumb;

			}else {
				$video_name = $all_video['name'];
				$video_thumb = ywcfav_save_remote_image($video_thumb, $video_name);
				$all_video['thumbn'] = $video_thumb;
			}

		}

		return $all_video;
	}
}

if( !function_exists( 'ywcfav_save_remote_image' ) ){

	function ywcfav_save_remote_image( $url, $newfile_name='' ){

        $url = str_replace('https','http', $url );
		$tmp = download_url( (string)$url );
       
		$file_array = array();
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', (string)$url, $matches);
		$file_name = basename( $matches[0] );
		if( ''!== $newfile_name ) {
			$file_name_info = explode('.', $file_name);
			$file_name = $newfile_name . '.' . $file_name_info[1];
		}



		if( !function_exists('remove_accents') )
			require_once( ABSPATH . 'wp-includes/formatting.php' );

		$file_name = sanitize_file_name( remove_accents( $file_name ) ) ;
		$file_name = str_replace('-','_',$file_name  );

		$file_array['name'] = $file_name;
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if (is_wp_error($tmp)) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';

		}

		// do the validation and storage stuff
		return  media_handle_sideload($file_array, 0);
	}

}

if( !function_exists('ywcfav_check_is_zoom_magnifier_is_active' ) ){

	function ywcfav_check_is_zoom_magnifier_is_active(){

        $zoom_option = get_option('yith_wcmg_enable_plugin');
        $zoom_mobile_option = get_option('yith_wcmg_enable_mobile');
        
		return defined( 'YITH_YWZM_PREMIUM' ) && ( (  !wp_is_mobile() && $zoom_option == 'yes' ) || ( wp_is_mobile() && $zoom_mobile_option == 'yes'  ) );
	}
}

if( !function_exists( 'ywcfav_check_is_product_is_exclude_from_zoom' ) ){

	function ywcfav_check_is_product_is_exclude_from_zoom(){
        /**
         * @var YITH_WooCommerce_Zoom_Magnifier_Premium $yith_wcmg;
         */
		global $yith_wcmg;
		return ywcfav_check_is_zoom_magnifier_is_active() && $yith_wcmg->is_product_excluded();
	}
}

if( !function_exists( 'ywcfav_get_custom_player_style' ) ){
    function ywcfav_get_custom_player_style()
    {

        $custom_player = get_option('ywcfav_player_type_style');

        if ($custom_player == 'custom') {

            $main_font_color = get_option('ywcfav_main_font_colors');
            $control_bg_color = yith_Hex2RGB(get_option('ywcfav_control_bg_color'));
            $control_bg_color_alpha = str_replace(',', '.', get_option('ywcfav_control_bg_color_alpha'));
            $slider_color = get_option('ywcfav_slider_color');
            $slider_bg_color = yith_Hex2RGB(get_option('ywcfav_slider_bg_color'));
            $slider_bg_color_alpha = str_replace(',', '.', get_option('ywcfav_slider_bg_color_alpha'));
            $big_play_border_color = get_option('ywcfav_big_play_border_color');
            ?>
            <style type="text/css">

                .vjs-default-skin {
                    color: <?php echo $main_font_color;?>;
                }

                .vjs-default-skin .vjs-control-bar, .vjs-default-skin .vjs-menu-button .vjs-menu .vjs-menu-content {

                    background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                }

                .vjs-default-skin .vjs-volume-level, .vjs-default-skin .vjs-play-progress {

                    background-color: <?php echo $slider_color;?>;
                }

                .vjs-default-skin .vjs-slider {

                    background-color: rgba( <?php echo $slider_bg_color[0];?>,<?php echo $slider_bg_color[1];?>,<?php echo $slider_bg_color[2];?>,<?php echo $slider_bg_color_alpha;?> );
                }

                .vjs-default-skin .vjs-big-play-button {

                    background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                    border-color: <?php echo $big_play_border_color;?>;
                }

                .ywcfav_video_container .ywcfav_placeholder_container span:before, .ywcfav_video_modal_container .ywcfav_placeholder_modal_container span:before, .ywcfav_video_embd_container .ywcfav_video_embd_placeholder span:before,
                .ywcfav_audio_modal_container .ywcfav_audio_placeholder_modal_container span:before, .ywcfav_audio_container .ywcfav_audio_placeholder_container span:before
                {
                    color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                }

                .ywcfav_play{
                    background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                    border-color: <?php echo $big_play_border_color;?>;

                }

                .ywcfav_play:before{
                    color: <?php echo $main_font_color;?> !important;
                }

                .ywcfav_video_container .ywcfav_placeholder_container:hover .ywcfav_play, .ywcfav_video_modal_container .ywcfav_placeholder_modal_container:hover .ywcfav_play, .ywcfav_video_embd_container .ywcfav_video_embd_placeholder:hover .ywcfav_play, .ywcfav_audio_modal_container .ywcfav_audio_placeholder_modal_container:hover .ywcfav_play, .ywcfav_audio_container .ywcfav_audio_placeholder_container:hover .ywcfav_play,
                .vjs-default-skin:hover .vjs-big-play-button
                {
                    border-color: <?php echo $big_play_border_color;?>;
                    background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                }

            </style>
            <?php
        }
    }
}