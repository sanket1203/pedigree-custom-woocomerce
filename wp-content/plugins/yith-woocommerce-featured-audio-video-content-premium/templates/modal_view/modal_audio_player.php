<?php
if( !defined( 'ABSPATH' ) )
    exit;


$audio_meta_id =  $atts['audio']['id'];
$audio_meta_id = isset( $atts['index'] )? $audio_meta_id.'_'.$atts['index'] : $audio_meta_id;
$img_src = $atts['audio']['thumbn'];


$show_artwork = empty( $atts['show_artwork'] ) ? 'false' : 'true';
$auto_play = empty( $atts['auto_play'] ) ? 'false' : 'true';
$show_comments = empty( $atts['show_comments'] ) ? 'false' : 'true';
$show_share = empty( $atts['show_share'] ) ? 'false' : 'true';

$url = urlencode( $atts['audio']['url'] );
//$width = get_option( 'ywcfav_width_player' );
//$height = get_option( 'ywcfav_height_player');
?>

<div class="ywcfav_audio_modal_container">
    <iframe id="<?php echo $audio_meta_id;?>" width="100%" src="https://w.soundcloud.com/player/?url=<?php echo $url;?>" frameborder="no" scrolling="no" ></iframe>

<script type="text/javascript">
    jQuery(document).ready(function($){

        var widget = SC.Widget('<?php echo $audio_meta_id;?>');

        
        widget.load('<?php echo $url.'&color='.$atts['color'];?>',
            {   'show_artwork' : <?php echo $show_artwork;?>,
                'auto_play' : <?php echo $auto_play;?>,
                'sharing'  : <?php echo $show_share;?>,
                'show_comments' : <?php echo $show_comments;?>
            }
        );


        widget.bind(SC.Widget.Events.READY, function () {

            // load new widget
            widget.getSounds( function(sounds){

                if( sounds.length>1)

                    $('#<?php echo $audio_meta_id;?>').height(450);

            });

            widget.setVolume(<?php echo $atts['volume'];?>);
        });

    });
</script>
</div>