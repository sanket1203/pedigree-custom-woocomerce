<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
$type_link = ywcfav_check_is_zoom_magnifier_is_active() && 'yes' == get_option( 'ywcfav_zoom_magnifer_option' ) ? 'zoom_mode' : 'modal_mode';

if ( !empty( $all_video ) ):?>

	<div class="ywcfav_thumbnails_video_container">
		<div class="ywcfav_slider_info">
			<div class="ywcfav_slider_name"><?php _e( 'Featured Videos', 'yith-woocommerce-featured-video');?></div>
				<div class="ywcfav_slider_control">
					<div class="ywcfav_left"></div>
					<div class="ywcfav_right"></div>
				</div>
			</div>
		<div class="ywcfav_slider_wrapper">
			<div class="ywcfav_slider">
				<div class="ywcfav_slider_video" data-n_columns="<?php echo $columns;?>">
				<?php
				$i=0;
					foreach( $all_video as $video ){

						$att_id   =   $video['id'];
						$thumbnail =$video['thumbn'];

                        
						$size = wc_get_image_size( 'shop_thumbnail' );
						$image = '';
						if( is_numeric( $thumbnail ) ) {

							$image = wp_get_attachment_image( $thumbnail, apply_filters( 'ywfav_video_image_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
									'title' => $video['name'],
									'alt' => $video['name'],
							));
						}
						else{
							//$size = wc_get_image_size( 'shop_thumbnail' );
							$image = sprintf( '<img src="%1$s"  title="%2$s" alt="%2$s"  width="%3$s" height="%4$s"/>', $thumbnail, $video['name'],$size['width'], $size['height'] );
						}

						$args_url   =   array(
								'action'    =>  'print_video_modal',
								'ywcfav_video_id'   =>  $att_id,
								'index'	=>  $i,
								'product_id'        =>  $post->ID
						);

						$button_class= '';

						switch( $video['host'] ){

							case 'youtube':
								$button_class = 'youtube_btn';
								break;
							case 'vimeo' :
								$button_class = 'vimeo_btn';
								break;
							default:
								$button_class = 'default_btn';
								break;
						}


						if( 'zoom_mode' == $type_link && !ywcfav_check_is_product_is_exclude_from_zoom()  ){

							$terms_link = sprintf( '<a class="video_modal ywcfav_video_as_zoom" href="#" rel="nofollow" data-product_id="%s" data-video_id="%s">%s<div class="ywcfav_button %s"></div></a>',
													$post->ID, $att_id, $image, $button_class );
						}
						else{
							$terms_url =  esc_url( add_query_arg( $args_url, admin_url( 'admin-ajax.php' ) ) );
							$terms_link = sprintf( '<a class="video_modal ywcfav_video_show_modal" data-type="ajax" rel="nofollow" href="%s" target="_blank" ">%s<div class="ywcfav_button %s"></div></a>', $terms_url, $image, $button_class );
						}
						$div_item = sprintf( '<div class="ywcfav_item" style="width: %s;">%s</div>', $size['width'].'px', $terms_link );
						echo $div_item;
						$i++;
					}
				?>
				</div>
			</div>
		</div>
	</div>
<?php endif;?>