<?php
if( !defined( 'ABSPATH' ) )
    exit;

$src =  $atts['video']['content'];

$src = wp_get_attachment_url( $src );
$format='';

if( $src!='' ){
    $index  =   strlen( $src )-strrpos( $src, '.' );
    $format = substr( $src, -($index-1) );
    if( $format=='ogv' ) $format='ogg';
}

$video_meta_id =  $atts['video']['id'];
$video_meta_id = isset( $atts['index'] )? $video_meta_id.'_'.$atts['index'] : $video_meta_id;

$img_src = $atts['video']['thumbn'];

$controls = $atts['controls']   ? 'controls' : '';
$loop     = $atts['loop']?  'loop' : '';
$autoplay   =    $atts['autoplay'] ? 'autoplay' : '';
$aspect_ratio = str_replace('_',':',get_option( 'ywcfav_aspectratio' ) );
//$width = get_option( 'ywcfav_width_player' );
?>
<div class="ywcfav_video_modal_content host" data-id="<?php echo $atts['product_id'];?>" data-video_id="<?php echo $video_meta_id ;?>">
    <video style="visibility:hidden;" id="<?php echo $video_meta_id;?>" width="100%" class="video-js vjs-default-skin vjs-big-play-centered product-video" data-setup="{}"
           poster="<?php echo $img_src;?>" <?php echo $controls. ' '.$autoplay.' '.$loop;?> preload="auto" >
        <?php if( !empty( $src ) ) :?>
            <source src="<?php echo $src;?>" type="video/<?php echo $format;?>" />
        <?php endif;?>
    </video>


</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {

            videojs('<?php echo $video_meta_id;?>').ready(function () {

                    $('#'+this.id() ).css('visibility','visible');
                    $('[id^="'+this.id()+'_"]').css('visibility','visible');
                    this.on('error', function(){

                        var e = this.error();

                      if(e.code==150) {
                          $('.vjs-error-display').find('div').html('<?php _e('This video has been blocked because its contents are under copyright.','yith-woocommerce-featured-video' );?>');
                      }
                    });


                        this.on('pause', function () {
                            var stoppable = <?php echo $atts['video_stoppable'];?>;
                             if( !stoppable )
                                this.play();

                            $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
                        });

                        this.on( 'ended', function(){

                            $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
                        });

                        this.on( 'playing', function(){

                            $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').hide();
                        });

                    this.volume(<?php echo $atts['volume'];?>);


                this.aspectRatio( '<?php echo $aspect_ratio;?>' );

                }
            );

    });
</script>
