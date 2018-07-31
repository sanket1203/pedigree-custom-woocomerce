<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



global $post;

$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
$type_link = ywcfav_check_is_zoom_magnifier_is_active() && 'yes' == get_option( 'ywcfav_zoom_magnifer_option' ) ? 'zoom_mode' : 'modal_mode';

if ( !empty( $all_audio ) ):?>

<div class="ywcfav_thumbnails_audio_container">
    <div class="ywcfav_slider_info">
        <div class="ywcfav_slider_name"><?php _e( 'Featured Audios', 'yith-woocommerce-featured-video');?></div>
        <div class="ywcfav_slider_control">
            <div class="ywcfav_left"></div>
            <div class="ywcfav_right"></div>
        </div>
    </div>
    <div class="ywcfav_slider_wrapper">
        <div class="ywcfav_slider">
            <div class="ywcfav_slider_audio" data-n_columns="<?php echo $columns;?>"><?php

                $i = 0;
                foreach ( $all_audio as $audio ) {

                    $att_id   =   $audio['id'];

                    $thumbnail =$audio['thumbn'];

                    $size = wc_get_image_size( 'shop_thumbnail' );
                    $image = '';
                    if( is_numeric( $thumbnail ) ) {

                        $image = wp_get_attachment_image( $thumbnail, apply_filters( 'ywfav_audio_image_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
                            'title' => $audio['name'],
                            'alt' => $audio['name'],
                        ));
                    }
                    else{

                        $image = sprintf( '<img src="%1$s"  title="%2$s" alt="%2$s"  width="%3$s" height="%4$s"/>', $thumbnail, $audio['name'],$size['width'], $size['height'] );
                    }

                    $args_url   =   array(
                        'action'    =>  'print_audio_modal',
                        'ywcfav_audio_id'   =>  $att_id,
                        'product_id'        =>  $post->ID,
                        'index' => $i
                    );

                    if( 'zoom_mode' == $type_link && !ywcfav_check_is_product_is_exclude_from_zoom() ){

                        $terms_link = sprintf( '<a class="audio_modal ywcfav_audio_as_zoom" href="#" rel="nofollow" data-product_id="%s" data-audio_id="%s">%s<div class="ywcfav_button default_btn"></div></a>',
                            $post->ID, $att_id, $image );
                    }
                    else{
                        $terms_url =  esc_url( add_query_arg( $args_url, admin_url( 'admin-ajax.php' ) ) );
                        $terms_link = sprintf( '<a class="audio_modal ywcfav_audio_show_modal" data-type="ajax" rel="nofollow" href="%s" target="_blank" >%s<div class="ywcfav_button default_btn"></div></a>', $terms_url, $image );
                    }
                    $div_item = sprintf( '<div class="ywcfav_item" style="width: %s">%s</div>', $size['width'].'px;', $terms_link );
                    echo $div_item;
                    $i++;
                }

                ?>
            </div>
        </div>
    </div>
</div>
    <?php endif;?>

