<?php
if( !defined( 'ABSPATH' ) )
    exit;

$video_type = $atts['video']['host'];

$atts['atts'] = $atts;


switch( $video_type ){

    case 'youtube':
        wc_get_template( 'modal_view/modal_video_youtube_player.php',  $atts,'', YWCFAV_TEMPLATE_PATH );
        break;
    case 'vimeo':
        wc_get_template( 'modal_view/modal_video_vimeo_player.php',  $atts,'', YWCFAV_TEMPLATE_PATH );
        break;
    case 'host':
    	wc_get_template( 'modal_view/modal_video_player.php',  $atts,'', YWCFAV_TEMPLATE_PATH );
        break;
    case 'embedded' :
        wc_get_template( 'modal_view/modal_video_embedded_player.php', $atts, '', YWCFAV_TEMPLATE_PATH );
}