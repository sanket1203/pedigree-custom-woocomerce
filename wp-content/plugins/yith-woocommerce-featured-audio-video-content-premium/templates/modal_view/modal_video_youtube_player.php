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
$video_meta_id = isset( $atts['index'] )? $video_meta_id.'_'.$atts['index'] : $video_meta_id;

$img_src = $atts['video']['thumbn'];
$autoplay = empty( $atts['autoplay'] ) ? 0 : 1;
$loop =     empty( $atts['loop'] ) ? 0 : 1;
$controls = empty( $atts['controls'] ) ? 'controls=0' : 'controls=1';
//$width = get_option( 'ywcfav_width_player' );
$show_info = get_option( 'ywcfav_youtube_show_info', 'yes' ) == 'yes' ? 'showinfo=1' : 'showinfo=0';
$auto_hide = get_option( 'ywcfav_youtube_autohide', 'yes' ) == 'yes' ? 'autohide=2': 'autohide=0';
$rel = get_option( 'ywcfav_youtube_rel', 'yes' ) == 'yes'  ? 'rel=1' : 'rel=0';
$theme = get_option( 'ywcfav_youtube_theme', 'dark' );
$color = get_option( 'ywcfav_youtube_color' ,'red' );
$color = 'color='.$color;
$theme = 'theme='.$theme;
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );
$stoppable = empty( $atts['video_stoppable'] ) ? 'false' : $atts['video_stoppable'];
$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];
?>

<div class="ywcfav_video_modal_content youtube">
    <div class="ywcfav_video_iframe">

        <iframe id="<?php echo $video_meta_id;?>" type="text/html" onload="onYouTubeIframeAPIReady()" width="100%" src="<?php echo $http;?>://www.youtube.com/embed/<?php echo $video_id;?>?enablejsapi=1&<?php echo $controls;?>&<?php echo $show_info;?>&<?php echo $auto_hide;?>&<?php echo $theme;?>&<?php echo $color;?>&<?php echo $rel;?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen="1">

        </iframe>
    </div>

<script type="text/javascript">

       var player;
       function onYouTubeIframeAPIReady() {



           player = new YT.Player('<?php echo $video_meta_id;?>',
                        {
                            playerVars: { 'controls': <?php echo $controls;?> },
                            events: {
                                'onReady': onPlayerReady,
                                'onStateChange' : onPlayerStateChange
                        }
                        });

            }

            function onPlayerReady( event ){

                var volume = <?php echo $atts['volume']*100;?>;
                event.target.setVolume( volume );

                var autoplay = <?php echo $autoplay;?>;

                    if( autoplay ){
                        event.target.playVideo();
                    }
            }

            function onPlayerStateChange( event ){

                 if( event.data === YT.PlayerState.PAUSED ){

                     var stoppable = <?php echo $stoppable;?>;

                     if( !stoppable )
                        event.target.playVideo();

                 }
                else if( event.data == YT.PlayerState.ENDED ){

                     var loop = <?php echo $loop;?>

                     if( loop )
                        event.target.playVideo();

                 }
            }

</script>
</div>