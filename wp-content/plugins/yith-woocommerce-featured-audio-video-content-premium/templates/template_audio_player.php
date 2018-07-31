<?php
if( !defined( 'ABSPATH' ) )
    exit;


$audio_meta_id =  $atts['audio']['id'];

$img_src = $atts['audio']['thumbn'];

$auto_play = empty( $atts['auto_play'] ) ? 'false' : 'true';
$show_artwork = empty( $atts['show_artwork'] ) ? 'false' : 'true';

$show_comments = empty( $atts['show_comments'] ) ? 'false' : 'true';
$show_share = empty( $atts['show_share'] ) ? 'false' : 'true';


$url = urlencode( $atts['audio']['url'] );

?>

<div class="ywcfav_audio_container" data-video_info="<?php echo $audio_meta_id.',audio,'.$product_id;?>">
    <iframe id="<?php echo $audio_meta_id;?>" src="https://w.soundcloud.com/player/?url=<?php echo $url;?>" frameborder="no" scrolling="no" ></iframe>


<script type="text/javascript">
    jQuery(document).ready(function($){

        var div_img = $('.product .images'),
            width_div = div_img.width();

        var widget = SC.Widget('<?php echo $audio_meta_id;?>');

               widget.load('<?php echo $url.'&color='.$color;?>',
                                {   'show_artwork' : <?php echo $show_artwork;?>,
                                    'auto_play' : <?php echo $auto_play;?>,
                                    'sharing'  : <?php echo $show_share;?>,
                                    'show_comments' : <?php echo $show_comments;?>
                                }
                );

                widget.bind(SC.Widget.Events.READY, function () {

                    $('#<?php echo $audio_meta_id;?>').width( width_div );
                    
                    // load new widget
                    widget.getSounds( function(sounds){

                        if( sounds.length>1)

                            $('#<?php echo $audio_meta_id;?>').height(450);

                    });

                    widget.setVolume(<?php echo $atts['volume'];?>);
                });

            widget.bind( SC.Widget.Events.PLAY_PROGRESS, function(){
                $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').hide();
            });
            widget.bind( SC.Widget.Events.FINISH , function(){

                $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
            });
            widget.bind( SC.Widget.Events.PAUSE , function(){
                $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
            });
    });
</script>
</div>