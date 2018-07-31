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
$show_info = get_option( 'ywcfav_youtube_show_info', 'yes' ) == 'yes' ? 'showinfo=1' : 'showinfo=0';
$auto_hide = get_option( 'ywcfav_youtube_autohide', 'yes' ) == 'yes' ? 'autohide=2': 'autohide=0';
$rel = get_option( 'ywcfav_youtube_rel', 'yes' ) == 'yes'  ? 'rel=1' : 'rel=0';
$theme = get_option( 'ywcfav_youtube_theme', 'dark' );
$color = get_option( 'ywcfav_youtube_color' ,'red' );
$color = 'color='.$color;
$theme = 'theme='.$theme;
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );

$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];
$stoppable = empty( $atts['video_stoppable'] ) ? 'false' : $atts['video_stoppable'];

?>

<div class="ywcfav_video_content youtube" data-video_info="<?php echo $video_meta_id.',youtube,'.$product_id;?>">
    <div class="ywcfav_video_wrapper">
        <?php echo $image;?>
        <div class="ywcfav_button youtube_btn"></div>
    </div>
    <div class="ywcfav_video_iframe">

        <iframe id="<?php echo $video_meta_id;?>" type="text/html" onload="onYouTubeIframeAPIReady()" src="<?php echo $http;?>://www.youtube.com/embed/<?php echo $video_id;?>?enablejsapi=1&<?php echo $controls;?>&<?php echo $show_info;?>&<?php echo $auto_hide;?>&<?php echo $theme;?>&<?php echo $color;?>&<?php echo $rel;?>" frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen="1">

        </iframe>
    </div>

<script type="text/javascript">
var player,
           video_wrapper = jQuery('.ywcfav_video_wrapper'),
           img_container = jQuery('.product .images'),
           poster = jQuery('.ywcfav_video_img'),
           frame_width = img_container.width()*1,
           frame_height =  Math.round( ( frame_width/<?php echo $width_ratio;?> )*<?php echo $height_ratio;?> );

           poster.css( {'width':frame_width, 'height':frame_height });

           function onYouTubeIframeAPIReady() {
                player = new YT.Player('<?php echo $video_meta_id;?>',
                        {
                            playerVars: { 'controls': <?php echo $controls;?> },
                            events: {
                                'onReady': onPlayerReady,
                                'onStateChange' : onPlayerStateChange
                        }
                        });


                player.setSize( frame_width, frame_height );

            }

            function onPlayerReady( event ){

            	jQuery('.ywcfav_video_iframe').show();
                var volume = <?php echo $atts['volume']*100;?>;
                event.target.setVolume( volume );

                var autoplay = <?php echo $autoplay;?>;


                    if( autoplay ){
                        video_wrapper.css({'visibility': 'hidden'});
                        event.target.playVideo();
                    }


                //add click event on custom play button

                video_wrapper.on('click', function(e){
                	jQuery(this).css({'visibility': 'hidden'});
                    
                    event.target.playVideo();
                });
            }

            function onPlayerStateChange( event ){

                 if( event.data === YT.PlayerState.PAUSED ){

                     var stoppable = <?php echo $stoppable;?>;

                     if( !stoppable )
                        event.target.playVideo();

                     jQuery('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();

                 }
                else if( event.data == YT.PlayerState.ENDED ){

                     var loop = <?php echo $loop;?>

                     if( loop )
                        event.target.playVideo();

                     jQuery('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
                 }
                else if( event.data == YT.PlayerState.PLAYING ){

                	jQuery('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').hide();
                 }
            }

</script>
</div>