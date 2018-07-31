<?php
if( !defined( 'ABSPATH' ) )
    exit;

$content = $atts['video']['content'];
$video_meta_id =  $atts['video']['id'];

$img_src = $atts['video']['thumbn'];

?>

<div class="ywcfav_video_content embedded" data-video_info="<?php echo $video_meta_id.',host'.$product_id;?>">
    <div class="ywcfav_video_iframe">
      <?php echo urldecode( $content );?>
    </div>

<script type="text/javascript">
    jQuery(document).ready(function($){

        var iframe= $('iframe'),
            img_content = $('.product .images'),
            width = img_content.width()* 1,
            height = Math.round( ( width/16 ) *9 );

        iframe.css({'width': width, 'height':height});
        $('.ywcfav_video_content').css({'height' : $('.ywcfav_video_iframe').height()});
    });
</script>
</div>