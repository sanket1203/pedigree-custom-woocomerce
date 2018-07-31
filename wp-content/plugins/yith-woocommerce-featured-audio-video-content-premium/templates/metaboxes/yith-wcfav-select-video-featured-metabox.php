<?php
if( !defined( 'ABSPATH' ) )
    exit;

global $post;

$product_video = get_post_meta( $post->ID, '_ywcfav_video', true );
$product_audio = get_post_meta( $post->ID, '_ywcfav_audio', true );

$featured_content_meta = get_post_meta( $post->ID, '_ywcfav_featured_content', true );
$featured_content = empty( $featured_content_meta ) ? '' : $featured_content_meta['id'];
?>

<div class="ywcfav_select_featured_content">
    <select name="ywcfav_select_featured" class="select_featured">
       <option value="" <?php selected('', $featured_content );?> ><?php _e('Choose featured content', 'yith-woocommerce-featuerd-video' );?></option>
        <?php if( !empty( $product_video ) ):
                foreach( $product_video as $video ):?>
                    <option value="<?php esc_attr_e( $video['id'] );?>" <?php selected( $video['id'], $featured_content );?>><?php echo $video['name'];?></option>
        <?php endforeach;endif;?>
        <?php if(!empty( $product_audio ) ):
                foreach( $product_audio as $audio ):?>
                    <option value="<?php esc_attr_e( $audio['id'] );?>" <?php selected( $audio['id'], $featured_content );?>><?php echo $audio['name'];?></option>
        <?php endforeach;endif;?>
    </select>
</div>