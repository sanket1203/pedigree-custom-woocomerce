<?php 
if( !defined('ABSPATH') ){
exit;
}

if( !class_exists('YITH_WC_Avada_Module')){
	
	class YITH_WC_Avada_Module{
		
		public function __construct(){
			
			global $YITH_Featured_Audio_Video;
			
			$disable_woo_gallery = Avada()->settings->get('disable_woo_gallery');
				
			if( !$disable_woo_gallery ){
				
				remove_filter( 'woocommerce_single_product_image_html', array( $YITH_Featured_Audio_Video, 'set_featured_video' ), 10 );
				remove_filter('woocommerce_product_gallery_attachment_ids', array($YITH_Featured_Audio_Video, 'product_video_gallery_attachment_id'), 20 );
				add_filter('woocommerce_single_product_image_html', array( $this, 'set_featured_audio_video'), 10,3 );
				add_filter('woocommerce_single_product_image_thumbnail_html', array( $this, 'set_product_image_thumbnail' ),10,2 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_scripts'), 25 );
			}
		}
		
		
		/**
		 *
		 * @author YITHEMES
		 * @since 1.1.5
		 * @param string $html
		 * @param string $id
		 * @return string
		 */
		public function set_featured_audio_video($html, $attach_id, $product_thumbnails)
		{
			/**
			 * @var YITH_WC_Audio_Video_Premium $YITH_Featured_Audio_Video
			 */
			global $YITH_Featured_Audio_Video, $post;
			$product_has_featured_content = get_post_meta($post->ID, '_ywcfav_featured_content', true);
	
			  if( $post->ID == $attach_id  && !empty( $product_has_featured_content ) )  {

				 if( !$product_thumbnails ) {
					 $type = $product_has_featured_content['type'];

					 if( ( has_post_thumbnail( $attach_id ) ) ) {
						 $class_img = apply_filters( 'ywcfav_featured_wc_class', 'woocommerce-main-image zoom' );
						 $pos = strpos( $html, $class_img );
						 if( $pos ) {
							 $html = substr_replace( $html, 'ywcfav_has_featured ', $pos, 0 );
						 }
					 }
					 else {

						 $html = sprintf( '<li><img src="%s" alt="%s" class="ywcfav_has_featured" /></li>', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) );
					 }


					 $html2 = '';
					 if( 'video' === $type ) {

						 $content_featured = $YITH_Featured_Audio_Video->find_featured_video( $attach_id, $product_has_featured_content['id'] );
						 $show_in_modal = get_option( 'ywcfav_video_in_modal' ) == 'yes';


						 if( $show_in_modal ) {


							 $html2 = $YITH_Featured_Audio_Video->get_video_in_modal_template( $content_featured, $attach_id );
						 }
						 else {

							 $html2 = $YITH_Featured_Audio_Video->get_video_template( $content_featured, $attach_id );
						 }

					 }
					 else {
						 $show_in_modal = get_option( 'ywcfav_soundcloud_in_modal' ) == 'yes';
						 $content_featured = $YITH_Featured_Audio_Video->find_featured_audio( $attach_id, $product_has_featured_content['id'] );
						 if( $show_in_modal ) {

							 $html2 = $YITH_Featured_Audio_Video->get_audio_in_modal_template( $content_featured, $attach_id );
						 }
						 else {
							 $html2 = $YITH_Featured_Audio_Video->get_audio_template( $content_featured, $attach_id );
						 }
					 }

					 $html = str_replace( '</li>', '', $html );

					 return $html . $html2 . '</li>';
				 }else{

					 $html = $this->set_product_image_thumbnail( $html, $post->ID );

				 }
				}
			
				return $html;
		
		}
		
		/**
		 * @author YITHEMES
		 * @since 1.1.5
		 * @param $html
		 * @param $product_id
		 * @return string
		 */
		public function set_product_image_thumbnail( $html, $product_id ){
		
			/**
			 * @var YITH_WC_Audio_Video_Premium $YITH_Featured_Audio_Video
			 */
			global $YITH_Featured_Audio_Video, $post;
		
			if( $YITH_Featured_Audio_Video->product_has_featured_content() ){
		
				$has_featured_content = get_post_meta( $product_id, '_ywcfav_featured_content', true );
		
				if ( $post->ID == $product_id && !empty( $has_featured_content ) ) {

					$type = $has_featured_content[ 'type' ];
					$id_feat = $has_featured_content[ 'id' ];
		
					if ( 'video' === $type ) {
						$video = $YITH_Featured_Audio_Video->find_featured_video( $product_id, $id_feat );
						$thumbnail_id = $video[ 'thumbn' ];
						$name = $video[ 'name' ];
		
		
					} else {
						$audio = $YITH_Featured_Audio_Video->find_featured_audio( $product_id, $id_feat );
						$thumbnail_id = $audio[ 'thumbn' ];
						$name = $audio[ 'name' ];
					}
		
					
					$image =  wp_get_attachment_image( $thumbnail_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
							'title'	=> $name,
							'alt'	=> $name
					) );

					$html = sprintf('<li>%s</li>', $image );
		
				}
			}
		
			return $html;
		}
		
		public function enqueue_custom_scripts(){
		
		
			wp_deregister_script('ywcfav_script_frontend');
		
			wp_enqueue_script('ywcfav_script_frontend', YWCFAV_ASSETS_URL . 'js/avada_js/ywcfav_frontend_avada.js', array('jquery'), YWCFAV_VERSION, true);
			$ywcfav = array(
					'admin_url' => admin_url('admin-ajax.php', is_ssl() ? 'https' : 'http'),
					'change_product_variation_image' => 'change_product_variation_image',
					'reset_featured_video' => 'reset_featured_video',
					'change_video_in_featured'  => 'change_video_in_featured',
					'change_audio_in_featured' => 'change_audio_in_featured',
					'width_modal'                 =>  get_option( 'ywcfav_width_player'),
					'height_modal'                => get_option( 'ywcfav_height_player'),
					'aspect_ratio'      => get_option( 'ywcfav_aspectratio' ),
					'zoom_magnifier_active' => ywcfav_check_is_zoom_magnifier_is_active(),
					'zoom_magnifier_exclude' => ywcfav_check_is_product_is_exclude_from_zoom(),
					'zoom_option'       => get_option( 'ywcfav_zoom_magnifer_option' ),
					'video_autoplay'    => get_option( 'ywcfav_autoplay' ),
					'audio_autoplay'    => get_option( 'ywcfav_soundcloud_auto_play' )
			);
		
			wp_localize_script('ywcfav_script_frontend', 'ywcfav_frontend', $ywcfav);
		}
	}
}

function  YITH_WC_Avada_Module(){
	
	return new YITH_WC_Avada_Module();
}