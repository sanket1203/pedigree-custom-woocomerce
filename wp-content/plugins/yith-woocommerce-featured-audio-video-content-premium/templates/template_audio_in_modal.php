<?php
if( !defined( 'ABSPATH' ) )
    exit;

$thumbnail  = $atts['thumbn'];

$image = '';
if( is_numeric( $thumbnail ) ) {

    $image = wp_get_attachment_image( $thumbnail, 'shop_single', 0, $attr = array(
        'title' => $atts['name'],
        'alt' => $atts['name'],
        'class' => 'ywcfav_audio_img'
    ));
}
else{

    $image = sprintf( '<img src="%1$s" class="ywcfav_audio_img" title="%2$s" alt="%2$s" />', $thumbnail, $atts['name'] );
}
$args_url   =   array(
    'action'    =>  'print_audio_modal',
    'ywcfav_audio_id'   =>  $atts['audio_id'],
    'product_id'        =>  $atts['product_id']
);

$terms_url =  esc_url( add_query_arg( $args_url, admin_url( 'admin-ajax.php' ) ) );
?>

<div class="ywcfav_audio_modal_container audio">
    <div class="ywcfav_audio_wrapper">
        <?php  echo sprintf( '<a class="ywcfav_audio_show_modal" data-type="ajax" rel="nofollow" href="%s" target="_blank">%s <div class="ywcfav_button default_btn"></div></a>', $terms_url, $image );?>
    </div>
</div>
