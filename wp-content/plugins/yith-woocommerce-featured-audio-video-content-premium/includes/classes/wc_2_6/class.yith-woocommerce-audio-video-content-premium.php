<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_WC_Audio_Video_Premium' ) ){

    class YITH_WC_Audio_Video_Premium extends  YITH_WC_Audio_Video
    {

        protected static $_instance;

        public function __construct()
        {
            parent::__construct();


            remove_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_video_field' ) );
            remove_action( 'woocommerce_process_product_meta', array( $this, 'save_video_url' ), 10 );


            add_action('wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action('admin_init', array( $this, 'register_plugin_for_updates' ) );
            add_action( 'init', array( $this, 'include_helper_class' ) );
            add_action( 'admin_init', array( $this, 'save_video_audio_placeholder' ), 20 );
            add_filter('ywcfav_add_premium_tab', array( $this, 'add_premium_tab' ) );

            add_action('wp_print_scripts', array( $this, 'get_custom_player_style' ) );
            add_action('admin_enqueue_scripts', array( $this, 'enqueue_premium_style_script' ) );
            add_action('wp_enqueue_scripts', array( $this, 'enqueue_premium_frontend_style_script' ), 20 );

            add_filter('woocommerce_product_write_panel_tabs', array( $this, 'print_audio_video_product_panels' ), 98 );
            add_action('woocommerce_process_product_meta', array( $this, 'save_product_meta' ), 25, 2 );
            add_action('woocommerce_product_after_variable_attributes', array( $this, 'print_variable_video_product'), 20, 3);
            add_action('woocommerce_save_product_variation', array( $this, 'save_product_variation_meta'), 10, 2);
            add_filter('woocommerce_available_variation', array( $this, 'set_featured_video_in_variation'), 20, 3);

            add_action('wp_ajax_change_product_variation_image', array($this, 'change_product_variation_image'));
            add_action('wp_ajax_nopriv_change_product_variation_image', array($this, 'change_product_variation_image'));

            add_action( 'wp_ajax_print_video_modal', array( $this, 'print_video_modal' ) );
            add_action( 'wp_ajax_nopriv_print_video_modal', array( $this, 'print_video_modal' ) );


            add_action( 'wp_ajax_print_audio_modal', array( $this, 'print_audio_modal' ) );
            add_action( 'wp_ajax_nopriv_print_audio_modal', array( $this, 'print_audio_modal' ) );

            //show the "gallery" video in single product page
          
           add_action( 'woocommerce_product_thumbnails', array( $this, 'woocommerce_show_product_video_thumbnails' ),30 );
           add_action( 'woocommerce_product_thumbnails', array( $this, 'woocommerce_show_product_audio_thumbnails' ),35 );
            //Save thumbn for video embedded via ajax
            add_action('wp_ajax_save_thumbnail_video', array( $this, 'save_thumbnail_video'));
            add_action('wp_ajax_nopriv_save_thumbnail_video', array( $this, 'save_thumbnail_video' ) );

            //add new video row
            add_action( 'wp_ajax_add_new_video_row', array( $this, 'add_new_video_row' ) );
            add_action( 'wp_ajax_nopriv_add_new_video_row', array( $this, 'add_new_video_row' ) );

            //add new audio row
            add_action( 'wp_ajax_add_new_audio_row', array( $this, 'add_new_audio_row' ) );
            add_action( 'wp_ajax_nopriv_add_new_audio_row', array( $this, 'add_new_audio_row' ) );
            
            add_action( 'wp_ajax_add_new_video_variation', array( $this, 'add_new_video_variation' ) );
            add_action( 'wp_ajax_nopriv_add_new_video_variation', array( $this, 'add_new_video_variation' ) );

            //Reset featured image
            add_action('wp_ajax_reset_featured_video', array($this, 'reset_featured_video'));
            add_action('wp_ajax_nopriv_reset_featured_video', array($this, 'reset_featured_video'));

            //add metaboxes in woocommerce product
            add_action( 'add_meta_boxes', array( $this, 'add_product_select_featured_content_meta_boxes' ) );

            add_filter( 'yith_featured_video_premium_enabled', array( $this, 'product_has_featured_content' ) );

            remove_action( 'yith_wczm_featured_video_enabled', array( $this, 'product_has_video' ) );
            add_filter( 'yith_ywzm_zoom_wrap_additional_css', array( $this, 'add_zoom_class' ), 10 );

            if( ywcfav_check_is_zoom_magnifier_is_active() ){
                
                add_action( 'wp_ajax_change_video_in_featured', array( $this, 'change_video_in_featured' ) );
                add_action( 'wp_ajax_nopriv_change_video_in_featured', array( $this, 'change_video_in_featured' ) );
                add_action( 'wp_ajax_change_audio_in_featured', array( $this, 'change_audio_in_featured' ) );
                add_action( 'wp_ajax_nopriv_change_audio_in_featured', array( $this, 'change_audio_in_featured' ) );
       
            }

        }

        /** return single instance of class
         * @author YIThemes
         * @since 1.0.0
         * @return YITH_WC_Audio_Video_Premium
         */
        public static function get_instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /* Register plugins for activation tab
       *
       * @return void
       * @since    1.0.0
       * @author   Andrea Grillo <andrea.grillo@yithemes.com>
       */
        public function register_plugin_for_activation()
        {
            if (!class_exists('YIT_Plugin_Licence')) {
                require_once YWCFAV_DIR.'plugin-fw/licence/lib/yit-licence.php';
                require_once YWCFAV_DIR.'plugin-fw/licence/lib/yit-plugin-licence.php';
            }
            YIT_Plugin_Licence()->register(YWCFAV_INIT, YWCFAV_SECRET_KEY, YWCFAV_SLUG);
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since    1.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_updates()
        {
            if (!class_exists('YIT_Upgrade')) {
                require_once( YWCFAV_DIR.'plugin-fw/lib/yit-upgrade.php');
            }
            YIT_Upgrade()->register(YWCFAV_SLUG, YWCFAV_INIT);
        }


        public function add_premium_tab($tabs)
        {

            unset($tabs['premium-landing']);

            $tabs['general-settings'] = __( 'Modal Settings', 'yith-woocommerce-featured-video');
            $tabs['video-settings']   =   __( 'Video Settings', 'yith-woocommerce-featured-video' );
            $tabs['audio-settings']         =   __( 'Audio Settings', 'yith-woocommerce-featured-video');
            $tabs['addon-settings']       =   __( 'Addons', 'yith-woocommerce-featured-video' );
            return $tabs;
        }




        /**Overridden
         * @author YIThemes
         * @param $html
         * @param $id
         *
         */
        public function set_featured_video($html, $id)
        {

            $product_has_featured_content = get_post_meta($id, '_ywcfav_featured_content', true);


            if (empty($product_has_featured_content))
                return $html;
            else {
                $type = $product_has_featured_content['type'];
                if ((!ywcfav_check_is_zoom_magnifier_is_active() || ywcfav_check_is_product_is_exclude_from_zoom())) {
                    if ((has_post_thumbnail($id))) {
                        $class_img = apply_filters('ywcfav_featured_wc_class', 'woocommerce-main-image zoom');
                        $pos = strpos($html, $class_img);
                        if ($pos) {
                            $html = substr_replace( $html, 'ywcfav_has_featured ', $pos, 0 );
                        }
                        $html = str_replace( 'prettyPhoto[product-gallery]', '', $html );
                    } else {

                        $html = sprintf('<img src="%s" alt="%s" class="ywcfav_has_featured" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce'));
                    }
                }

                $html2 = '';
                if ('video' === $type) {

                    $content_featured = $this->find_featured_video($id, $product_has_featured_content['id']);
                    $show_in_modal = get_option('ywcfav_video_in_modal') == 'yes';


                    if ($show_in_modal) {


                        $html2 = $this->get_video_in_modal_template($content_featured, $id);
                    } else {

                        $html2 = $this->get_video_template($content_featured, $id);
                    }

                } else {
                    $show_in_modal = get_option('ywcfav_soundcloud_in_modal') == 'yes';
                    $content_featured = $this->find_featured_audio($id, $product_has_featured_content['id']);
                    if ($show_in_modal) {

                        $html2 = $this->get_audio_in_modal_template($content_featured, $id);
                    } else {
                        $html2 = $this->get_audio_template($content_featured, $id);
                    }
                }
                return $html . $html2;
            }

        }


        public function get_featured_size(){

            global $woocommerce;
            if (function_exists('wc_get_image_size')) {
                $size = wc_get_image_size(apply_filters('single_product_large_thumbnail_size', 'shop_single'));
            } else {
                $size = $woocommerce->get_image_size( apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
            }

            if( empty( $size['height'] ) )
                $size['height'] = '100%';

            return $size;
        }

        /**
         * return audio params
         * @author YIThemes
         * @since 1.1.0
         * @param $id
         * @param $featured
         * @return array
         */
        public function build_featured_audio_params( $id, $featured ){

            $size =   $this->get_featured_size();
            $width = $size['width'];
            $height = $size['height'];
            $autoplay       =   get_option( 'ywcfav_soundcloud_auto_play' )    ==  'yes' ? 'true' : 'false' ;
            $show_artwork   =   get_option( 'ywcfav_soundcloud_show_artwork' )    ==  'yes' ? 'true' : 'false' ;
            $show_comment   =   get_option( 'ywcfav_soundcloud_show_comment' ) == 'yes' ? 'true' : 'false' ;
            $show_share     =   get_option( 'ywcfav_soundcloud_show_sharing' )    ==  'yes' ? 'true' : 'false';
            $color          =   get_option( 'ywcfav_soundcloud_color' );
            $volume         =   get_option( 'ywcfav_soundcloud_volume' )*(100);
            $color          =   str_replace( '#','', $color );

            $args = array(
                'audio'    => $featured,
                'product_id'    =>  $id,
                'width'     => $width,
                'height'    =>  $height,
                'show_artwork' =>   $show_artwork,
                'auto_play' =>  $autoplay,
                'show_comments' =>  $show_comment,
                'color' =>  $color,
                'show_share'    =>  $show_share,
                'volume'        =>  $volume
             );

            $args['atts']   =   $args;

            return $args;
        }

        /** return params for featured video
         * @author YIThemes
         * @since 1.1.0
         * @param $id
         * @param $featured
         * @return array
         */
        public function build_featured_video_params( $id, $featured )
        {
            $controls = get_option('ywcfav_show_controls') == 'yes' ? true : '';
            $autoplay = get_option('ywcfav_autoplay') == 'yes' ? true : '';

            $loop = get_option('ywcfav_loop') == 'yes' ? true : '';
            $preload = get_option('ywcfav_preload');
            $volume = get_option('ywcfav_volume');
            $video_stoppable = get_option('ywcfav_video_stoppable') == 'yes' ? 'true' : 'false';
            $size = $this->get_featured_size();

            $args = array(
                'width' => $size['width'],
                'height' => $size['height'],
                'crop'  => $size['crop'],
                'controls' => $controls,
                'product_id'      =>    $id,
                'autoplay' => $autoplay,
                'loop' => $loop,
                'preload' => $preload,
                'volume' => $volume,
                'video' => $featured,
                'video_stoppable' => $video_stoppable,

            );

            return $args;
        }

        /**
         * find the featured video
         * @author YIThemes
         * @since 1.1.0
         * @param $product_id
         * @param $video_id
         * @return bool
         */
        public function find_featured_video( $product_id, $video_id ) {

            $videos = get_post_meta( $product_id, '_ywcfav_video', true );
            
            if( empty( $videos ) ) return false;
            foreach( $videos as $i => $video ) {

                if ( $video['id'] == $video_id ) {
                    return $videos[$i];
                }
            }
            return false;
        }

        /**
         * find the featured audio
         * @author YIThemes
         * @since 1.1.0
         * @param $product_id
         * @param $audio_id
         * @return bool
         */
        public function find_featured_audio( $product_id, $audio_id ){

            $audios = get_post_meta( $product_id, '_ywcfav_audio', true );
            if( empty( $audios ) ) return false;
            foreach( $audios as $i=>$audio ){
                if( $audio['id'] == $audio_id )
                    return $audios[$i];
            }

            return false;
        }


        public function get_custom_player_style()
        {

            $custom_player = get_option('ywcfav_player_type_style');

            if ($custom_player == 'custom') {

                $main_font_color = get_option('ywcfav_main_font_colors');
                $control_bg_color = yith_Hex2RGB(get_option('ywcfav_control_bg_color'));
                $control_bg_color_alpha = str_replace(',', '.', get_option('ywcfav_control_bg_color_alpha'));
                $slider_color = get_option('ywcfav_slider_color');
                $slider_bg_color = yith_Hex2RGB(get_option('ywcfav_slider_bg_color'));
                $slider_bg_color_alpha = str_replace(',', '.', get_option('ywcfav_slider_bg_color_alpha'));
                $big_play_border_color = get_option('ywcfav_big_play_border_color');
                ?>
                <style type="text/css">

                    .vjs-default-skin {
                        color: <?php echo $main_font_color;?>;
                    }

                    .vjs-default-skin .vjs-control-bar, .vjs-default-skin .vjs-menu-button .vjs-menu .vjs-menu-content {

                        background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                    }

                    .vjs-default-skin .vjs-volume-level, .vjs-default-skin .vjs-play-progress {

                        background-color: <?php echo $slider_color;?>;
                    }

                    .vjs-default-skin .vjs-slider {

                        background-color: rgba( <?php echo $slider_bg_color[0];?>,<?php echo $slider_bg_color[1];?>,<?php echo $slider_bg_color[2];?>,<?php echo $slider_bg_color_alpha;?> );
                    }

                    .vjs-default-skin .vjs-big-play-button {

                        background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                        border-color: <?php echo $big_play_border_color;?>;
                    }

                    .ywcfav_video_container .ywcfav_placeholder_container span:before, .ywcfav_video_modal_container .ywcfav_placeholder_modal_container span:before, .ywcfav_video_embd_container .ywcfav_video_embd_placeholder span:before,
                    .ywcfav_audio_modal_container .ywcfav_audio_placeholder_modal_container span:before, .ywcfav_audio_container .ywcfav_audio_placeholder_container span:before
                    {
                        color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                    }

                    .ywcfav_play{
                        background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                        border-color: <?php echo $big_play_border_color;?>;

                    }

                    .ywcfav_play:before{
                        color: <?php echo $main_font_color;?> !important;
                    }

                    .ywcfav_video_container .ywcfav_placeholder_container:hover .ywcfav_play, .ywcfav_video_modal_container .ywcfav_placeholder_modal_container:hover .ywcfav_play, .ywcfav_video_embd_container .ywcfav_video_embd_placeholder:hover .ywcfav_play, .ywcfav_audio_modal_container .ywcfav_audio_placeholder_modal_container:hover .ywcfav_play, .ywcfav_audio_container .ywcfav_audio_placeholder_container:hover .ywcfav_play,
                    .vjs-default-skin:hover .vjs-big-play-button
                    {
                        border-color: <?php echo $big_play_border_color;?>;
                        background-color: rgba( <?php echo $control_bg_color[0];?>,<?php echo $control_bg_color[1];?>,<?php echo $control_bg_color[2];?>,<?php echo $control_bg_color_alpha;?> );
                    }

                </style>
            <?php
            }
        }

        public function enqueue_premium_style_script()
        {

            wp_enqueue_script('ywcfav_script', YWCFAV_ASSETS_URL . 'js/ywcfav_admin' . $this->_suffix . '.js', array('jquery'), YWCFAV_VERSION, true);

            $video_placeholder_id = get_option( 'ywcfav_video_placeholder_id' );
            $audio_placeholder_id = get_option( 'ywcfav_audio_placeholder_id' );

            $error_message = sprintf( '%s.<br>%s.', __( 'The thumbnail image is too small, in this way the featured image will show a grainy picture', 'yith-woocommerce-featured-video' ),
                                                    __( 'Please, load a bigger thumbnail image', 'yith-woocommerce-featured-video' ) );
            $min_size = get_minimum_size_accept();
            $ywcfav = array(
                'admin_url' => admin_url('admin-ajax.php', is_ssl() ? 'https' : 'http'),
                'video_placeholder_img_src' => YWCFAV_ASSETS_URL.'images/videoplaceholder.jpg',
                'audio_placeholder_img_src' => YWCFAV_ASSETS_URL.'images/audioplaceholder.jpg',
                'video_placeholder_img_id' => $video_placeholder_id,
                'audio_placeholder_img_id' => $audio_placeholder_id,
                'error_txt'  =>  __('ERROR','yith-woocommerce-featured-video'),
                'error_full_txt'  => $error_message,
                'min_width' =>  $min_size['width'],
                'error_video' => __( 'Please select a Video', 'yith-woocommerce-featured-video' ),
                'actions'   => array(
                    'save_thumbnail_video' => 'save_thumbnail_video',
                    'add_new_video_row'     => 'add_new_video_row',
                    'add_new_audio_row' => 'add_new_audio_row',
					'add_new_video_variation' => 'add_new_video_variation'
                )
            );


            wp_localize_script('ywcfav_script', 'ywcfav', $ywcfav);

            wp_enqueue_style('ywcfav_admin_style', YWCFAV_ASSETS_URL . 'css/ywcfav_admin.css', array(), YWCFAV_VERSION);

        }

        public function enqueue_premium_frontend_style_script()
        {
            //include script in only product page
            if( is_product() ) {

                wp_enqueue_script('videojs', YWCFAV_ASSETS_URL. 'js/external_libraries/video.min.js', array( 'jquery' ), false, true );
                wp_enqueue_script('soundcloud_api', YWCFAV_ASSETS_URL. 'js/external_libraries/soundcloud_api.js', array( 'jquery' ), false, true );
                wp_enqueue_script('venobox_api', YWCFAV_ASSETS_URL. 'js/external_libraries/jquery.venobox.js', array( 'jquery' ), false, true );
               // wp_enqueue_script('jquerycommonlibraries', YWCFAV_ASSETS_URL . 'js/external_libraries/jquery.commonlibraries.js', array('jquery'), '1.0.0', true);

                wp_enqueue_style( 'videojs_style', YWCFAV_ASSETS_URL.'/css/videojs/video-js.min.css' );
                wp_enqueue_style( 'venobox_style', YWCFAV_ASSETS_URL.'/css/venobox.css' );

                wp_enqueue_script( 'ywcfav_owl_carousel', YWCFAV_ASSETS_URL.'/js/external_libraries/owl.carousel.min.js', array('jquery'), false, true );
                wp_enqueue_style( 'ywcfav_owl_carousel_style', YWCFAV_ASSETS_URL.'/css/owl-carousel/owl.carousel.css' );

              

                wp_enqueue_script('ywcfav_slider', YWCFAV_ASSETS_URL . 'js/ywcfav_slider'. $this->_suffix . '.js', array('jquery'), YWCFAV_VERSION, true );
                wp_enqueue_script('ywcfav_script_frontend', YWCFAV_ASSETS_URL . 'js/wc_2_6/ywcfav_frontend'. $this->_suffix . '.js', array('jquery'), YWCFAV_VERSION, true);


                $effect =   get_option( 'ywcfav_modal_effect' );

                if( $effect > 0 ) {
                    wp_enqueue_style( 'venobox_effects', YWCFAV_ASSETS_URL.'css/effects/effect-'.$effect.'.css', array(), YWCFAV_VERSION );
                }



                $ywcfav = array(
                    'admin_url' => admin_url('admin-ajax.php', is_ssl() ? 'https' : 'http'),
                    'change_product_variation_image' => 'change_product_variation_image',
                    'reset_featured_video' => 'reset_featured_video',
                    'change_video_in_featured'  => 'change_video_in_featured',
                    'change_audio_in_featured' => 'change_audio_in_featured',
                    'aspect_ratio'      => get_option( 'ywcfav_aspectratio' ),
                    'zoom_magnifier_active' => ywcfav_check_is_zoom_magnifier_is_active(),
                    'zoom_magnifier_exclude' => ywcfav_check_is_product_is_exclude_from_zoom(),
                    'zoom_option'       => get_option( 'ywcfav_zoom_magnifer_option' ),
                    'video_autoplay'    => get_option( 'ywcfav_autoplay' ),
                    'audio_autoplay'    => get_option( 'ywcfav_soundcloud_auto_play' )
                );

                wp_localize_script('ywcfav_script_frontend', 'ywcfav_frontend', $ywcfav);
            }
        }

        public function print_audio_video_product_panels()
        {
            ?>
            <style type="text/css">
                #woocommerce-product-data ul.wc-tabs .ywcfav_video_data_tab a:before, #woocommerce-product-data ul.wc-tabs .ywcfav_audio_data_tab a:before {
                    content: '';
                    display: none;
                }

            </style>
            <li class="ywcfav_video_data_tab">
                <a href="#ywcfav_video_data">
                    <i class="dashicons dashicons-video-alt2"></i>&nbsp;&nbsp;<?php _e('Video', 'yith-woocommerce-featured-video');?>
                </a>
            </li>
            <li class="ywcfav_audio_data_tab">
                <a href="#ywcfav_audio_data">
                    <i class="dashicons dashicons-format-audio"></i>&nbsp;&nbsp;<?php _e( 'Audio', 'yith-woocommerce-featured-video');?>
                </a>
            </li>

            <?php
            add_action('woocommerce_product_write_panels', array($this, 'write_audio_video_product_panels'));
        }

        public function write_audio_video_product_panels()
        {

            include_once(YWCFAV_TEMPLATE_PATH . 'metaboxes/yith-wcfav-video-metabox.php');
            include_once(YWCFAV_TEMPLATE_PATH . 'metaboxes/yith-wcfav-audio-metabox.php');
        }

        public function save_product_meta($post_id, $post)
        {

            if ( isset( $_POST['ywcfav_video'] ) )
                 update_post_meta($post_id, '_ywcfav_video', $_POST['ywcfav_video']);
            else
                delete_post_meta( $post_id, '_ywcfav_video' );

            if( isset( $_POST['ywcfav_audio'] ) )
                update_post_meta( $post_id, '_ywcfav_audio', $_POST['ywcfav_audio'] );
            else
                delete_post_meta( $post_id, '_ywcfav_audio' );


            if( isset( $_POST['ywcfav_select_featured'] ) && !empty( $_POST['ywcfav_select_featured'] ) ){

                    $content = $_POST['ywcfav_select_featured'];

                    $type = ( strpos($content, 'ywcfav_video') === false ) ? 'audio' : 'video';

                    $args = array('id' => $content, 'type' => $type);

                     update_post_meta($post_id, '_ywcfav_featured_content', $args);

                }
            else {

                update_post_meta( $post_id, '_ywcfav_featured_content', '');
            }


        }

        public function save_product_variation_meta($variation_id, $i)
        {

            if (isset($_POST['video_info'][$i]))
                update_post_meta($variation_id, '_ywcfav_variation_video', $_POST['video_info'][$i]);

            else
                delete_post_meta($variation_id, '_ywcfav_variation_video');
        }

        /**call ajax for save thumbnail video
         * @author YIThemes
         * @since 1.0.0
         * @use wp_ajax_save_thumbnail_video
         * @return json
         *
         */
        public function save_thumbnail_video()
        {

            if ( isset($_POST['ywcfav_host']) ) {

                $host = $_POST['ywcfav_host'];
                $name= $_POST['ywcfav_name'];
                $img_url = '';
                $result = 'ok';

                switch ($host) {

                    case 'vimeo' :
                        $img_url = 'http://vimeo.com/api/v2/video/' . $_POST['ywcfav_id'] . '.xml';
                        $xml = simplexml_load_file($img_url);
                        $img_url = (string)$xml->video->thumbnail_large;

                        $tmp = getimagesize($img_url);

                        if (is_wp_error($tmp))
                            $result = 'no';

                        break;
                    case 'youtube':
                        $img_url = 'https://img.youtube.com/vi/' . $_POST['ywcfav_id'] . '/maxresdefault.jpg';
                        $get_response = wp_remote_get($img_url);
                        $result = $get_response['response']['code'] == '200' ? 'ok' : 'no';

                        break;
                }

                $img_id = '';

                if( 'ok' === $result ){


                    $img_id = ywcfav_save_remote_image( $img_url, $name );
                }

                wp_send_json(
                    array(
                        'result' => $result,
                        'id_img' => (string)$img_id,
                    )
                );
                die;
            }
        }


        public function save_video_audio_placeholder(){

            $video_id = get_option( 'ywcfav_video_placeholder_id', false );
            $audio_id = get_option( 'ywcfav_audio_placeholder_id',false );
            $video_src = false;
            $audio_src = false;


            if( $video_id ) {
                $video_src = wp_get_attachment_image_src( $video_id );
            }

            if( $audio_id ) {
                $audio_src = wp_get_attachment_image_src( $audio_id );
            }
            
            if(  false === $video_src  ){

                $video_id = ywcfav_save_remote_image( YWCFAV_ASSETS_URL.'images/videoplaceholder.jpg', 'videoplaceholder' );

                update_option( 'ywcfav_video_placeholder_id', $video_id );

            }

            if( false== $audio_src ){

                $audio_id = ywcfav_save_remote_image( YWCFAV_ASSETS_URL.'images/audioplaceholder.jpg', 'audioplaceholder' );

                update_option( 'ywcfav_audio_placeholder_id', $audio_id );
            }
        }
        public function print_variable_video_product($loop, $variation_data, $variation){
            $args = array('loop' => $loop, 'variation_data' => $variation_data, 'variation' => $variation);
            $args['video_variation'] = $args;

            wc_get_template( 'metaboxes//yith-wcfav-video-product-variations.php', $args, '', YWCFAV_TEMPLATE_PATH );

        }


        public function woocommerce_show_product_video_thumbnails() {

					global $post;
					$featured_content = get_post_meta( $post->ID, '_ywcfav_featured_content', true );
					$all_video = get_post_meta( $post->ID, '_ywcfav_video', true );

					if( !empty( $featured_content ) && !ywcfav_check_is_zoom_magnifier_is_active()  ){

							$type = $featured_content['type'];
							$id   = $featured_content['id'];
														
							if( $type==='video')
								$all_video = ywcfav_removeElementWithValue( $all_video, 'id', $id );
						
							
					}
					$all_video['all_video'] = $all_video;
						
					wc_get_template( 'woocommerce/single-product/product-video-thumbnails.php', $all_video, '', YWCFAV_TEMPLATE_PATH );
						
}
        public function woocommerce_show_product_audio_thumbnails() {

            global $post;
            $featured_content = get_post_meta( $post->ID, '_ywcfav_featured_content', true );
            $all_audio = get_post_meta( $post->ID, '_ywcfav_audio', true );

            if( !empty( $featured_content ) && !ywcfav_check_is_zoom_magnifier_is_active()){

                $type = $featured_content['type'];
                $id   = $featured_content['id'];

                if( $type==='audio')
                    $all_audio = ywcfav_removeElementWithValue( $all_audio, 'id', $id );


            }
            $all_audio['all_audio'] = $all_audio;

            wc_get_template( 'woocommerce/single-product/product-audio-thumbnails.php', $all_audio, '', YWCFAV_TEMPLATE_PATH );

        }

        public function set_featured_video_in_variation( $variation_data, $product, $variation )
        {


            $video_variation = yit_get_prop( $variation, '_ywcfav_variation_video' );

            if (    !empty( $video_variation   )   ) {

                $variation_data['video_variation'] = $video_variation['id'];

            }
            return $variation_data;
        }

        public function change_product_variation_image(){


            if( isset( $_POST['video_id'] ) ){

                $variation_id =   $_POST['product_id'];
                
                $video_variation = get_post_meta( $variation_id, '_ywcfav_variation_video', true );
                $template = '';
                
               if( !empty( $video_variation ) ){

					$show_in_modal = get_option( 'ywcfav_video_in_modal' ) == 'yes';
					if( $show_in_modal ){

                        $template = $this->get_video_in_modal_template( $video_variation, $variation_id );
					}
					else {
					
						$template = $this->get_video_template( $video_variation, $variation_id );
					}

                }
                
                wp_send_json( array( 'template'   =>  $template ) );
            }
        }

        public function reset_featured_video(){

            if( isset( $_POST['product_id'] ) ){

                $product_id = $_POST['product_id'];
                $template='';
                $result = 'no_featured_content';

                $has_featured_content = get_post_meta( $product_id, '_ywcfav_featured_content', true );

                if( !empty( $has_featured_content ) ){

                    $type = $has_featured_content['type'];
                    $feature_id = $has_featured_content['id'];
                    if( 'video' === $type ){

                        $featured_content = $this->find_featured_video( $product_id, $feature_id );
                        $result = 'video';
                        $show_in_modal = get_option( 'ywcfav_video_in_modal' ) == 'yes';
                        if( $show_in_modal ){

                            $template = $this->get_video_in_modal_template( $featured_content, $product_id );
                        }
                        else {

                            $template = $this->get_video_template( $featured_content, $product_id );
                        }


                    }else{

                        $show_in_modal = get_option( 'ywcfav_soundcloud_in_modal' ) == 'yes';
                        $featured_content = $this->find_featured_audio( $product_id, $feature_id );
                        $result = 'audio';

                        if( $show_in_modal ){
                            $template = $this->get_audio_in_modal_template( $featured_content, $product_id );
                        }else {
                           $template = $this->get_audio_template( $featured_content, $product_id );
                        }
                    }
                }

                wp_send_json( array(
                   'template'   =>  $template,
                    'result'    =>  $result
                ));

            }

        }

        public function print_video_modal(){

            if( isset( $_REQUEST['ywcfav_video_id'] ) && isset( $_REQUEST['product_id'] ) ){

                $video_id   =   $_REQUEST['ywcfav_video_id'];
                $product_id =   $_REQUEST['product_id'];

                $video=  $this->_find_video_by_id( $product_id, $video_id );
                $args = $this->build_featured_video_params( $product_id, $video );
                $args['index'] = isset( $_REQUEST['index'] ) ? $_REQUEST['index'] : '';

                 $template = 'template_gallery_video_player.php';

                $args['atts'] = $args;

               wc_get_template( $template, $args, '', YWCFAV_TEMPLATE_PATH );
                die();
            }

        }

        public function print_audio_modal(){

            if( isset( $_REQUEST['ywcfav_audio_id'] ) && isset( $_REQUEST['product_id'] ) ){
                $audio_id   =   $_REQUEST['ywcfav_audio_id'];
                $product_id =   $_REQUEST['product_id'];

                $audio  =   $this->_find_audio_by_id( $product_id, $audio_id );

                if( is_array( $audio ) ){
                    $args           =   $this->build_featured_audio_params( $product_id, $audio );
                    $args['index']  = isset( $_REQUEST['index'] ) ? $_REQUEST['index'] : '';
                    $args['atts'] = $args;

                   wc_get_template('modal_view/modal_audio_player.php', $args,'', YWCFAV_TEMPLATE_PATH );
                    die();

                }
            }
        }


        private function _find_audio_by_id( $product_id, $audio_id ){

            $all_audio  =   get_post_meta( $product_id, '_ywcfav_audio', true );

            if( empty( $all_audio ) ) return false;

            foreach( $all_audio as $i=>$audio ){
                if( $audio['id'] == $audio_id )
                    return $audio;
            }

            return false;
        }

        private function _find_video_by_id( $product_id, $video_id ){

            $all_video  =   get_post_meta( $product_id, '_ywcfav_video', true );

            $product =  wc_get_product( $product_id );

           if(  $product instanceof  WC_Product_Variation ) {

               return get_post_meta($product_id, '_ywcfav_variation_video', true);
           }
            if( empty( $all_video ) ) return false;

            foreach( $all_video as $i=> $video )
                    if( $video['id'] == $video_id )
                        return $video;
            return false;
        }

        /**
         * add new video row in single product
         * @author YIThemes
         * @since 1.1.0
         */
        public function add_new_video_row(){

            if( isset( $_POST['video_id'] ) ){


                $video = array(
                    'name'      =>  $_POST['video_name'],
                    'thumbn'    =>  $_POST['video_img'],
                    'featured'  =>  'no',
                    'id'        =>  $_POST['video_id'],
                    'host'      =>  $_POST['video_host'],
                    'content'   => $_POST['video_content'],
                    'type'      =>  $_POST['video_type'],

                );

                $video_params = array( 'video_params' => $video, 'loop' => $_POST['loop'], 'product_id' => $_POST['product_id'] );

                ob_start();
                 wc_get_template( 'metaboxes/views/html-product-video.php', $video_params, '', YWCFAV_TEMPLATE_PATH   );
                $template = ob_get_contents();
                ob_end_clean();

                wp_send_json( array('result' =>   $template ) );
                die;

            }
        }
        public function add_new_video_variation(){
        	
			if( isset( $_POST['video_id'] ) ){
			
			
				$video = array(
						'name'      =>  $_POST['video_name'],
						'thumbn'    =>  $_POST['video_img'],
						'featured'  =>  'no',
						'id'        =>  $_POST['video_id'],
						'host'      =>  $_POST['video_host'],
						'content'   => $_POST['video_content'],
						'type'      =>  $_POST['video_type'],
			
				);
			
				$video_params = array( 'video_params' => $video, 'loop' => $_POST['loop'], 'product_id' => $_POST['product_id'] );
			
				ob_start();
				wc_get_template( 'metaboxes/views/html-product-variation-video.php', $video_params, '', YWCFAV_TEMPLATE_PATH   );
				$template = ob_get_contents();
				ob_end_clean();
			
				wp_send_json( array('result' =>   $template ) );
				die;
        }
}

        /**
         * add new audio row in single product
         * @author YIThemes
         * @since 1.1.0
         */
        public function add_new_audio_row(){

            if( isset( $_POST['audio_id'] ) ){


                $audio = array(
                    'name'      =>  $_POST['audio_name'],
                    'thumbn'    =>  $_POST['audio_img'],
                    'featured'  =>  'no',
                    'id'        =>  $_POST['audio_id'],
                    'url'   => $_POST['audio_content'],

                );

                $audio_params = array( 'audio_params' => $audio, 'loop' => $_POST['loop'], 'product_id' => $_POST['product_id'] );

                ob_start();
                wc_get_template( 'metaboxes/views/html-product-audio.php', $audio_params, '', YWCFAV_TEMPLATE_PATH   );
                $template = ob_get_contents();
                ob_end_clean();

                wp_send_json( array('result' =>   $template ) );
                die;

            }

        }

        /**
         * add product metabox
         * @author YIThemes
         * @since 1.1.0
         */
        public function add_product_select_featured_content_meta_boxes(){

            add_meta_box( 'yith-ywcfav-metabox', __( 'Featured Video or Audio', 'yith-woocommerce-featured-video' ), array( $this, 'featured_audio_video_meta_box_content' ), 'product', 'side', 'core' );
        }

        /**
         * print product metabox
         * @author YIThemes
         * @since 1.1.0
         */
        public function featured_audio_video_meta_box_content(){

            wc_get_template( 'metaboxes/yith-wcfav-select-video-featured-metabox.php', array(), '', YWCFAV_TEMPLATE_PATH );
        }

        public function product_has_featured_content(){


            global $post;

            $has_featured_content = get_post_meta( $post->ID, '_ywcfav_featured_content', true );
            $has_video = get_post_meta( $post->ID, '_ywcfav_video', true );
            $has_audio = get_post_meta( $post->ID, '_ywcfav_audio', true );

            $variation_has_video = false;

            $product = wc_get_product( $post->ID );

            $child = $product->get_children();

            if( count( $child )> 0 ){

                foreach( $child as $variation_id ){

                    $has_variation_video = get_post_meta( $variation_id, '_ywcfav_variation_video', true );

                    if( !empty( $has_variation_video ) ){

                        $variation_has_video = true;
                        break;
                    }
                }
            }


            return ( !empty( $has_audio ) || !empty( $has_featured_content ) || !empty( $has_video )   || $variation_has_video );
        }

        /**
         * @param $class
         * @return string
         * @author YIThemes
         * @since 1.1.3
         */
        public function add_zoom_class( $class ){

            global $product;

            $has_feature = yit_get_prop( $product, '_ywcfav_featured_content', true );

            if( !empty( $has_feature ) )
                return 'ywcfav_has_featured';
            else
                return $class;
        }

        /**
         * call ajax for show slider video in featured
         * @author YIThemes
         * @since 1.1.3
         */
        public function change_video_in_featured()
        {

            if (isset($_REQUEST['video_id'])) {

                $video_id = $_REQUEST['video_id'];
                $product_id = $_REQUEST['product_id'];
                $video = $this->_find_video_by_id($product_id, $video_id);
                $template = '';

                if ($video) {

                    $show_in_modal = get_option('ywcfav_video_in_modal') == 'yes';
                    if ($show_in_modal) {
                        $template = $this->get_video_in_modal_template($video, $product_id);
                    } else {
                        $template = $this->get_video_template($video, $product_id);
                    }

                }

                wp_send_json(array('template' => $template, 'type' => 'video'));
            }

        }

        /**
         * call ajax for show slider audio in featured
         * @author YIThemes
         * @since 1.1.3
         */
        public function change_audio_in_featured()
        {

            if (isset($_REQUEST['audio_id'])) {

                $audio_id = $_REQUEST['audio_id'];
                $product_id = $_REQUEST['product_id'];

                $audio = $this->_find_audio_by_id($product_id, $audio_id);

                if ($audio) {

                    $show_in_modal = get_option('ywcfav_soundcloud_in_modal') == 'yes';
                    if ($show_in_modal) {
                        $template = $this->get_audio_in_modal_template($audio, $product_id);
                    } else {
                        $template = $this->get_audio_template($audio, $product_id);
                    }
                    wp_send_json(array('template' => $template, 'type' => 'audio'));

                }
            }
        }

        /**
         * include helper class
         * @author YIThemes
         * @since 1.1.4
         */
        public function include_helper_class(){

            require_once( YWCFAV_INC.'classes/class.yith-wc-audio-video-helper.php' );
            YITH_Audio_Video_Helper();
        }

        /**
         * get video in modal template
         * @author YIThemes
         * @since 1.1.4
         * @param $video_params
         * @param $product_id
         * @return string
         */
        public function get_video_in_modal_template($video_params, $product_id)
        {
            $size = $this->get_featured_size();
            $width = $size['width'];
            $height = $size['height'];
            $args = array(
                'video_id' => $video_params['id'],
                'width' => $width,
                'height' => $height,
                'product_id' => $product_id,
                'type_class' => $video_params['host'],
                'thumbn' => $video_params['thumbn'],
                'name' => $video_params['name']
            );

            $template = 'template_video_in_modal.php';

            $args['atts'] = $args;
            ob_start();
            wc_get_template($template, $args, '', YWCFAV_TEMPLATE_PATH);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        /**
         * get video template
         * @author YIThemes
         * @since 1.1.4
         * @param $video_params
         * @param $product_id
         * @return string
         */
        public function get_video_template( $video_params, $product_id ){

            $args = $this->build_featured_video_params( $product_id, $video_params );
            $type_template = $video_params['host'] == 'host' ? '_' : '_' . $video_params['host'] . '_';
            $template = 'template_video'.$type_template.'player.php';
            $args['atts'] = $args;
            ob_start();
            wc_get_template($template, $args, '', YWCFAV_TEMPLATE_PATH);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        /**get audio in modal template
         * @param $audio_params
         * @param $product_id
         * @return string
         */
        public function  get_audio_in_modal_template( $audio_params, $product_id ){
            $size = $this->get_featured_size();

            $width = $size['width'];
            $height = $size['height'];
            $args = array(
                'audio_id' => $audio_params['id'],
                'width'     => $width,
                'height'    =>  $height,
                'product_id' => $product_id,
                'thumbn' => $audio_params['thumbn'],
                'name'  => $audio_params['name'],
            );

            $template = 'template_audio_in_modal.php';
            $args['atts'] = $args;
            ob_start();
            wc_get_template($template, $args, '', YWCFAV_TEMPLATE_PATH);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        /**get audio template
         * @author YIThemes
         * @since 1.1.4
         * @param $audio_params
         * @param $product_id
         * @return string
         */
        public function get_audio_template( $audio_params, $product_id ){
            $args = $this->build_featured_audio_params($product_id, $audio_params);
            $template = 'template_audio_player.php';
            $args['atts'] = $args;
            ob_start();
            wc_get_template($template, $args, '', YWCFAV_TEMPLATE_PATH);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
    }

}