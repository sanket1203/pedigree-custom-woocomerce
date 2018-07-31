<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_WC_Audio_Video_Helper' ) ){

    class YITH_WC_Audio_Video_Helper{

        protected static  $_instance;

        /**
         * YITH_WC_Audio_Video_Helper constructor.
         * @author YIThemes
         * @since 1.1.4
         */
        public function __construct()
        {
            $theme = wp_get_theme();
            $theme_name = $theme['Name'];

            //Hooks for Total Theme
           if( class_exists('WPEX_Theme_Setup')) {

               add_filter('wpex_woo_placeholder_img_html', array($this, 'set_featured_content'));
               add_action('wpex_after_slider', array($this, 'add_featured_video_audio_slider'));
               add_action('wpex_after_slider_thumbnails', array($this, 'add_featured_video_audio_thumbnail_slider'));
               add_action('wpex_product_thumbanils', array( $this, 'show_product_video_thumbnails'), 30);
               add_action('wpex_product_thumbanils', array($this, 'show_product_audio_thumbnails'), 35);
           }
           
         

            
            //Hooks for Avada Theme
            if( class_exists('Avada') ){
                
            	require_once('third-party/class.yith-wc-avada-module.php');
            	YITH_WC_Avada_Module();
            }
            
            if( function_exists('lollumframework_add_admin') && strcasecmp( 'Big Point', $theme_name ) == 0 ){
                require_once('third-party/class.yith-wc-bigpoint-module.php');
                YITH_WC_Big_Point_Module();
            }

        }

        /**
         * @author YIThemes
         * @since 1.1.4
         * @return YITH_WC_Audio_Video_Helper
         */
        public static function get_instance()
        {
            if( is_null( self::$_instance ) ){
                self::$_instance = new self();
            }

            return self::$_instance;
        }


        /**
         * add feature
         * @author YIThemes
         * @since 1.1.4
         * @param $html
         * @return mixed
         */
        public function set_featured_content( $html, $attach_id=null ){

            global $post, $YITH_Featured_Audio_Video;
            $current_action = current_action();

            if( 'wpex_woo_placeholder_img_html' === $current_action ){

                $html = $YITH_Featured_Audio_Video->set_featured_video( $html, $post->ID );
            }
            return $html;
        }

        /**
         *add featured video or audio in slider
         * @author YIThemes
         * @since 1.1.4
         */
        public function add_featured_video_audio_slider(){

            $current_action = current_action();
            global $YITH_Featured_Audio_Video, $post;

            if( 'wpex_after_slider' === $current_action ) {

                $has_featured_content = get_post_meta( $post->ID, '_ywcfav_featured_content', true );

                if( !empty( $has_featured_content ) ){

                    $type = $has_featured_content['type'];
                    $id = $has_featured_content['id'];

                    if( 'video' === $type ){

                        $show_in_modal = get_option('ywcfav_video_in_modal') == 'yes';
                        $video = $YITH_Featured_Audio_Video->find_featured_video( $post->ID, $id );

                        if ($show_in_modal)
                            $content =  '<div class="wpex-slider-slide sp-slide">' . $YITH_Featured_Audio_Video->get_video_in_modal_template($video, $post->ID) . '</div>';
                        else
                            $content = '<div class="wpex-slider-slide sp-slide">' . $YITH_Featured_Audio_Video->get_video_template($video, $post->ID) . '</div>';
                    }
                    else{
                        $show_in_modal = get_option('ywcfav_soundcloud_in_modal') == 'yes';
                        $audio = $YITH_Featured_Audio_Video->find_featured_audio( $post->ID, $id );
                        if ($show_in_modal)
                            $content = '<div class="wpex-slider-slide sp-slide">' . $YITH_Featured_Audio_Video->get_audio_in_modal_template($audio, $post->ID) . '</div>';
                        else
                            $content = '<div class="wpex-slider-slide sp-slide">' . $YITH_Featured_Audio_Video->get_audio_template($audio, $post->ID) . '</div>';

                    }

                    echo $content;
                }
            }

        }

       

        public function add_featured_video_audio_thumbnail_slider(){
            $current_action = current_action();
            global $YITH_Featured_Audio_Video, $post;

            if( 'wpex_after_slider_thumbnails' === $current_action ){

                $has_featured_content = get_post_meta( $post->ID, '_ywcfav_featured_content', true );

                if( !empty( $has_featured_content ) ) {

                    $type = $has_featured_content['type'];
                    $id = $has_featured_content['id'];

                    if( 'video' === $type ){
                        $video = $YITH_Featured_Audio_Video->find_featured_video( $post->ID, $id );
                        $thumbnail_id = $video['thumbn'];
                        $name = $video['name'];


                    }else{
                        $audio = $YITH_Featured_Audio_Video->find_featured_audio( $post->ID, $id );
                        $thumbnail_id = $audio['thumbn'];
                        $name = $audio['name'];
                    }

                    wpex_post_thumbnail( array(
                        'attachment'    =>$thumbnail_id,
                        'size'          => 'shop_single',
                        'class'         => 'wpex-slider-thumbnail sp-thumbnail ywcfav_btn',
                        'alt' => $name
                    ) );
                }
            }
        }

        public function show_product_video_thumbnails(){

            $current_action = current_action();
            global $YITH_Featured_Audio_Video, $post;

            if( 'wpex_product_thumbanils' === $current_action ){

                 $YITH_Featured_Audio_Video->woocommerce_show_product_video_thumbnails();
            }
        }
        public function show_product_audio_thumbnails(){

            $current_action = current_action();
            global $YITH_Featured_Audio_Video, $post;

            if( 'wpex_product_thumbanils' === $current_action ){

                 $YITH_Featured_Audio_Video->woocommerce_show_product_audio_thumbnails();
            }
        }

      
    }
}

function YITH_Audio_Video_Helper(){

    return YITH_WC_Audio_Video_Helper::get_instance();
}