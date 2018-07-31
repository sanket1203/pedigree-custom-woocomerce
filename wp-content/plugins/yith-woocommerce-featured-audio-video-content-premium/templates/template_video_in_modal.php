<?php
if( !defined( 'ABSPATH' ) )
    exit;


$thumbnail  = $atts['thumbn'];

$image = '';
if( is_numeric( $thumbnail ) ) {

    $image = wp_get_attachment_image( $thumbnail, apply_filters('ywfav_video_image_size','shop_single' ), 0, $attr = array(
        'title' => $atts['name'],
        'alt' => $atts['name'],
        'class' => 'ywcfav_video_img'
    ));
}
else{

    $image = sprintf( '<img src="%1$s" class="ywcfav_video_img" title="%2$s" alt="%2$s" />', $thumbnail, $atts['name'] );
}

$args_url   =   array(
    'action'    =>  'print_video_modal',
    'ywcfav_video_id'   =>  $atts['video_id'],
    'product_id'        =>  $atts['product_id']
);

$terms_url =  esc_url( add_query_arg( $args_url, admin_url( 'admin-ajax.php' ) ) );
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );

$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];

$button_class= '';

switch( $atts['type_class'] ){

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
?>

<div class="ywcfav_video_modal_container <?php echo $atts['type_class'];?>">
    <div class="ywcfav_video_wrapper">
        <?php  echo sprintf( '<a class="ywcfav_video_show_modal" data-type="ajax" rel="nofollow" href="%s" target="_blank">%s <div class="ywcfav_button %s"></div></a>', $terms_url, $image, $button_class );?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        var img_container = $('.product .images'),
            poster = $('.ywcfav_video_show_modal img:eq(0)'),
            width = img_container.width()*1,
            height = ( width/<?php echo $width_ratio;?> )*<?php echo $height_ratio;?>;


        poster.css({'width': width, 'height':height});

    });
</script>