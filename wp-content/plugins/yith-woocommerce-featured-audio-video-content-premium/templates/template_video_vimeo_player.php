<?php
if( !defined( 'ABSPATH' ) )
    exit;

$src =  $atts['video']['content'];
$http = is_ssl() ? 'https' : 'http';
if( strtolower( $atts['video']['type'] ) == 'url')
    list( $video_type, $video_id ) = explode( ':', ywcfav_video_type_by_url( $src ) );
else
  $video_id =   $src;

$video_meta_id =  $atts['video']['id'];
$thumbnail  = $atts['video']['thumbn'];
$image = '';
if( is_numeric( $thumbnail ) ) {

    $image = wp_get_attachment_image( $thumbnail, apply_filters('ywfav_video_image_size','shop_single' ) , 0, $attr = array(
        'title' => $atts['video']['name'],
        'alt' => $atts['video']['name'],
        'class' => 'ywcfav_video_img'
    ));
}
else{

    $image = sprintf( '<img src="%1$s" class="ywcfav_video_img" title="%2$s" alt="%2$s" />', $thumbnail, $atts['video']['name'] );
}

$autoplay = empty( $atts['autoplay'] ) ? 0 : 1;
$loop =     empty( $atts['loop'] ) ? 0 : 1;
$controls = empty( $atts['controls'] ) ? 'controls=0' : 'controls=1';
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );
$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];
$color = str_replace( '#','',get_option( 'ywcfav_vimeo_color' ) );
$title = get_option( 'ywcfav_vimeo_show_title' ) == 'yes' ? 'title=1' : 'title=0';
$stoppable = empty( $atts['video_stoppable'] ) ? 'false' : $atts['video_stoppable'];
$allow_full_screen_css = wp_is_mobile() ? '' : 'webkitallowfullscreen mozallowfullscreen allowfullscreen';
?>
<div class="ywcfav_video_content vimeo" data-video_info="<?php echo $video_meta_id.',vimeo,'.$product_id;?>">
 <div class="ywcfav_video_wrapper">
        <?php echo $image;?>
        <div class="ywcfav_button vimeo_btn"></div>
    </div>
    <div class="ywcfav_video_iframe">
        <iframe id="<?php echo $video_meta_id;?>" type="text/html"  src="<?php echo $http;?>://player.vimeo.com/video/<?php echo $video_id;?>?api=1&player_id=<?php echo $video_meta_id;?>&<?php echo $title;?>" frameborder="0" <?php echo $allow_full_screen_css;?> >

        </iframe>
    </div>

<script type="text/javascript">
jQuery(document).ready(function($){

    var img_container = $('.product .images'),
        video_wrapper = $('.ywcfav_video_wrapper'),
        video_iframe  = $('.ywcfav_video_iframe'),
        iframe  =   $('#<?php echo $video_meta_id;?>'),
        width = img_container.width()*1,
        height =  Math.round( ( width/<?php echo $width_ratio;?> )*<?php echo $height_ratio;?> ),
        poster = $('.ywcfav_video_img');

        poster.css( {'width':width, 'height':height });
        iframe.css({'width':width, 'height':height});

        iframe = iframe[0];
    var player  =   $f(iframe);

        player.addEvent( 'ready',function(){

            video_iframe.show();
            var volume = <?php echo $atts['volume'];?>;

            player.api( 'setVolume', volume );
            var autoplay = <?php echo $autoplay;?>;

            if( autoplay ){
                video_wrapper.css({'visibility': 'hidden'});
                player.api('play');
            }

            player.api('setColor','<?php echo $color;?>');

            video_wrapper.on('click', function(){
                video_wrapper.css({'visibility': 'hidden'});
                player.api('play');
            });

        player.addEvent( 'pause', onPlayerPause );
        player.addEvent( 'finish', onPlayerFinish );
        player.addEvent( 'playProgress', onPlayerProgress );
    });

    function onPlayerPause( id ){

        var stoppable = <?php echo $stoppable;?>,
            player = $f( id );

        if( !stoppable )
            player.api('play');

        $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();

    }

    function onPlayerFinish( id ){

        var loop = <?php echo $loop;?>,
            player = $f( id );

        if( loop )
            player.api( 'play' );

        $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
    }

    function onPlayerProgress( data, id ){

        $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').hide();
    }

});
</script>
</div>