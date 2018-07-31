<?php
if( !defined( 'ABSPATH' ) )
    exit;
$content = $atts['video']['content'];
$video_meta_id =  $atts['video']['id'];

$img_src = $atts['video']['thumbn'];
//$width = get_option( 'ywcfav_width_player' );
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );
$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];
?>

<div class="ywcfav_video_modal_content embedded">
    <div class="ywcfav_video_iframe">
        <?php echo urldecode( $content );?>
    </div>


</div>