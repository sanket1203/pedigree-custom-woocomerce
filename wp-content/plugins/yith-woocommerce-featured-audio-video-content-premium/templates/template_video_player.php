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
$thumbnail  = $atts['video']['thumbn'];

if( is_numeric( $thumbnail ) ) {
    $img_src = wp_get_attachment_image_src($atts['video']['thumbn'], apply_filters('ywfav_video_image_size','shop_single' ) );
    $img_src = $img_src[0];
}
else
    $img_src = $thumbnail;

$controls = $atts['controls']   ? 'controls' : '';
$loop     = $atts['loop']?  'loop' : '';
$autoplay   =    $atts['autoplay'] ? 'autoplay' : '';
$aspect_ratio = str_replace('_',':',get_option( 'ywcfav_aspectratio' ) );
$stoppable = empty( $atts['video_stoppable'] ) ? 'false' : $atts['video_stoppable'];
?>
<div class="ywcfav_video_content host" data-video_info="<?php echo $video_meta_id.',host'.$product_id;?>" data-id="<?php echo $atts['product_id'];?>" data-video_id="<?php echo $video_meta_id ;?>">
    <video style="visibility:hidden;" id="<?php echo $video_meta_id;?>" class="video-js vjs-default-skin vjs-big-play-centered product-video" data-setup="{}"
           poster="<?php echo $img_src;?>" <?php echo $controls. ' '.$autoplay.' '.$loop;?> preload="auto" >
        <?php if( !empty( $src ) ) :?>
            <source src="<?php echo $src;?>" type="video/<?php echo $format;?>" />
        <?php endif;?>
    </video>



<script type="text/javascript">
    jQuery(document).ready(function($) {
        var img_container = $('.product .images'),
            width = img_container.width()*1;



            videojs('<?php echo $video_meta_id;?>').ready(function () {

                this.width( width );
                    $('#'+this.id() ).css('visibility','visible');
                    $('[id^="'+this.id()+'_"]').css('visibility','visible');

                    this.on('error', function(){

                        var e = this.error();

                          if(e.code==150) {
                              $('.vjs-error-display').find('div').html('<?php _e('This video has been blocked because its contents are under copyright.','yith-woocommerce-featured-video' );?>');
                          }
                    });

                    this.on('pause', function () {
                            var stoppable = <?php echo $stoppable;?>;
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
</div>