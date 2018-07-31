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
$aspect_ratio = get_option( 'ywcfav_aspectratio' );
$aspect_ratio = explode( '_', $aspect_ratio );
$width_ratio  = $aspect_ratio[0];
$height_ratio = $aspect_ratio[1];
$color = str_replace( '#','',get_option( 'ywcfav_vimeo_color' ) );
$title = get_option( 'ywcfav_vimeo_show_title' ) == 'yes' ? 'title=1' : 'title=0';
$stoppable = empty( $atts['video_stoppable'] ) ? 'false' : $atts['video_stoppable'];
?>
<div class="ywcfav_video_modal_content vimeo">

    <div class="ywcfav_video_iframe">
        <iframe id="<?php echo $video_meta_id;?>" type="text/html" width="100%" src="<?php echo $http;?>://player.vimeo.com/video/<?php echo $video_id;?>?api=1&player_id=<?php echo $video_meta_id;?>&<?php echo $title;?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen >

        </iframe>
    </div>


<script type="text/javascript">
jQuery(document).ready(function($){

    var video_iframe  = $('.ywcfav_video_iframe'),
        iframe  =   $('#<?php echo $video_meta_id;?>');

        iframe = iframe[0];
    var player  =   $f(iframe);

        player.addEvent( 'ready',function(){

            player.api('setColor','<?php echo $color;?>');
            video_iframe.show();
            var volume = <?php echo $atts['volume'];?>;

            player.api( 'setVolume', volume );
            var autoplay = <?php echo $autoplay;?>;

            if( autoplay ){
                player.api('play');
            }

        player.addEvent( 'pause', onPlayerPause );
        player.addEvent( 'finish', onPlayerFinish );

    });

    function onPlayerPause( id ){

        var stoppable = <?php echo $stoppable;?>,
            player = $f( id );

        if( !stoppable )
            player.api('play');

    }

    function onPlayerFinish( id ){

        var loop = <?php echo $loop;?>,
            player = $f( id );

        if( loop )
            player.api( 'play' );


    }


});
</script>
</div>