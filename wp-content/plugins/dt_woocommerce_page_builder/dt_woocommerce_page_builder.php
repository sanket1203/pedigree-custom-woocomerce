<?php
/*
Plugin Name: DT WooCommerce Page Builder
Plugin URI: http://dawnthemes.com/
Description: is the ideal Visual Composer add-on to effortlessly layout for WooCommerce and more.
Version: 3.0.1
Author: DawnThemes 
Author URI: http://dawnthemes.com/
Copyright @2017 by DawnThemes
License: License GNU General Public License version 2 or later
Text-domain: dt_woocommerce_page_builder
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Current DT WooCommerce Page Builder
 */
if ( ! defined( 'DT_WOO_PAGE_BUILDER_VERSION' ) ) {
	/**
	 *
	 */
	define( 'DT_WOO_PAGE_BUILDER_VERSION', '3.0.1' );
}

if ( ! defined( 'DT_WOO_PAGE_BUILDER_URL' ) )
	define( 'DT_WOO_PAGE_BUILDER_URL' , plugin_dir_url(__FILE__));

if ( ! defined( 'DT_WOO_PAGE_BUILDER_DIR' ) )
	define( 'DT_WOO_PAGE_BUILDER_DIR' , plugin_dir_path(__FILE__));

require_once DT_WOO_PAGE_BUILDER_DIR . 'includes/functions.php';

/*
 * Check is active require plugin
 */
if( ! function_exists('dtwpb_is_active') ){
	function dtwpb_is_active(){
		$active_plugins = (array) get_option( 'active_plugins' , array() );
		
		if( is_multisite() )
			$active_plugins = array_merge($active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

global $dtwpb_product_page, $dtwpb_product_cat_custom_page;

class DTWPB_Manager{
	
	public function __construct(){
		add_action('init', array(&$this, 'init'));
		add_action( 'after_setup_theme', array( &$this, 'include_template_functions' ), 11 );
	}
	
	public function init(){
		load_plugin_textdomain( 'dt_woocommerce_page_builder' , false, basename(DT_WOO_PAGE_BUILDER_DIR) . '/languages');
		
		// require woocommerce
		if( !dtwpb_is_active() ){
			add_action('admin_notices', array(&$this, 'woocommerce_notice'));
			return;
		}

		// require vc
		if(!defined('WPB_VC_VERSION')){
			add_action('admin_notices', array(&$this, 'showVcVersionNotice'));
			return;
		}else{
			require_once DT_WOO_PAGE_BUILDER_DIR .'includes/vc-class.php';
		}
		
		if(is_admin()){
			include_once DT_WOO_PAGE_BUILDER_DIR . 'includes/admin.php';
		}else{
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_styles'));
			add_action('wp_enqueue_scripts',array(&$this,'enqueue_scripts'));
			
			//check - get tab id
			add_action('dtwpb_account_orders_in_tab', array($this,'dtwpb_account_orders_in_tab'),10,1);
			add_action('dtwpb_wc_get_endpoint_url', array($this, 'dtwpb_wc_get_endpoint_url'), 10, 1);
			add_action('dtwpb_woocommerce_account_view_order_backorder', array(&$this,'dtwpb_woocommerce_account_view_order_backorder'),10,1);
			
			// custom product page - can be overriden to yourtheme/woocommerce-page-builder-templates/content-single-product.php.
			add_filter('wc_get_template_part', array(&$this,'wc_get_template_part'),50,3);
			
			add_action('dtwpb_woocommerce_before_shop_loop', 'wc_print_notices');
			
			/*
			 * Filter wc_get_template - can be overriden to yourtheme
			 * - yourtheme/woocommerce-page-builder-templates/cart
			 * - yourtheme/woocommerce-page-builder-templates/checkout
			 * - yourtheme/woocommerce-page-builder-templates/myaccount
			 */ 
			add_filter('wc_get_template', array(&$this, 'filter_wc_get_template'), 99, 5);
			
			// Fixing WordPress 404 Custom Post Type Archive Pagination Issues with Posts Per Page
			add_action( 'pre_get_posts', array(&$this, 'custom_posts_per_page') );
		}
	}
	
	public function include_template_functions(){
		if( class_exists( 'WooCommerce' ) ):
			include_once( 'includes/dt-template-functions.php' );
			include_once( 'includes/dt-template-hooks.php' );
		endif;
	}
	
	/**
	 * 
	 * @param array $output
	 * @param WPBakeryShortCode $shortcode
	 * @param array $atts
	 * @return string
	 */
	function dtwpb_account_orders_in_tab($tab_id){
		global $dtwbp_my_account_current_order_id;
		if(empty($dtwbp_my_account_current_order_id))
			$dtwbp_my_account_current_order_id = $tab_id;
		add_filter('woocommerce_my_account_my_orders_actions', array($this,'woocommerce_my_account_my_orders_actions'),10,2);
	}
	function woocommerce_my_account_my_orders_actions($actions, $order){
		global $dtwbp_my_account_current_order_id;
		$new_actions = array();
		foreach ($actions as $key=>$action){
			// remove duplicate tab id
			$action['url'] = str_replace('#'.$dtwbp_my_account_current_order_id['tab_id'], '', $action['url']);
			$action['url']=$action['url'].'#'.$dtwbp_my_account_current_order_id['tab_id'];
			$new_actions[$key]=$action;
		}
		return $new_actions;
	}
	
	function dtwpb_wc_get_endpoint_url($tab_id){
		global $dtwpb_wc_get_endpoint_url_tab_id;
		if( empty($dtwpb_wc_get_endpoint_url_tab_id) )
			$dtwpb_wc_get_endpoint_url_tab_id = $tab_id;
		
		add_filter('woocommerce_get_endpoint_url', array($this,'dtwpb_woocommerce_get_endpoint_url'), 10,4);
	}
	
	function dtwpb_woocommerce_get_endpoint_url($url, $endpoint, $value, $permalink){
		global $dtwpb_wc_get_endpoint_url_tab_id;
		$url = $url . '#'.$dtwpb_wc_get_endpoint_url_tab_id['tab_id'];
		return $url;
	}
	
	public function dtwpb_woocommerce_account_view_order_backorder($myaccount_url){
		?>
		<h2><a href="<?php echo esc_url($myaccount_url);?>" title="<?php echo apply_filters('woocommerce_account_view_order_backorder', esc_html__('Back to Order list', 'dt_woocommerce_page_builder'));?>"><?php echo apply_filters('woocommerce_account_view_order_backorder', esc_html__('Back to Order list', 'dt_woocommerce_page_builder'));?></a></h2>
		<?php
	}
	
	public function woocommerce_notice(){
		$plugin  = get_plugin_data(__FILE__);
		echo '
		  <div class="updated">
		    <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a></strong> plugin to be installed and activated on your site.', 'dt_woocommerce_page_builder'), $plugin['Name']) . '</p>
		  </div>';
	}
	
	public function showVcVersionNotice(){
		$plugin_data = get_plugin_data(__FILE__);
		echo '
		<div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> Compatible with <strong>Visual Composer</strong> plugin. So You can install <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be used into Visual Composer page builder.', 'dt_woocommerce_page_builder'), $plugin_data['Name']).'</p>
        </div>';
	}
	
	public function enqueue_styles(){
		wp_enqueue_style('dtwpb', DT_WOO_PAGE_BUILDER_URL .'assets/css/style.css');
	}
	
	public function enqueue_scripts(){
		wp_enqueue_script('dtwpb',DT_WOO_PAGE_BUILDER_URL.'assets/js/script.min.js',array('jquery'),DT_WOO_PAGE_BUILDER_VERSION,true);
		
	}
	
	public function wc_get_template_part( $template, $slug, $name ){
		global $post, $dtwpb_product_page;
		
		if( $slug === 'content' && $name === 'single-product' ){
			
			$product_template_id = 0;
			if( $dtwpb_single_product_page = get_post_meta($post->ID, 'dtwpb_single_product_page', true) ):
				$product_template_id = $dtwpb_single_product_page;
			else:
				$terms = wp_get_post_terms($post->ID, 'product_cat');
				foreach ($terms as $term):
					if( $dtwpb_cat_product_page = get_woocommerce_term_meta( $term->term_id, 'dtwpb_cat_product_page', true ) ):
						$product_template_id = $dtwpb_cat_product_page;
					endif;
				endforeach;
			endif;
			
			// Overridden to yourtheme/woocommerce-page-builder-templates/content-single-product.php.
			$file 	= 'content-single-product.php';
			$find[] = 'woocommerce-page-builder-templates/' . $file;
			if( !empty($product_template_id) ){
				if($wpb_custom_css = get_post_meta( $product_template_id, '_wpb_post_custom_css', true )){
					echo '<style type="text/css">'.$wpb_custom_css.'</style>';
				}
				if($wpb_shortcodes_custom_css = get_post_meta( $product_template_id, '_wpb_shortcodes_custom_css', true )){
					echo '<style type="text/css">'.$wpb_shortcodes_custom_css.'</style>';
				}
				$dtwpb_product_page = get_post($product_template_id);
				if($dtwpb_product_page){
					$template       = locate_template( $find );
					if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$template = DT_WOO_PAGE_BUILDER_DIR . '/woocommerce-page-builder-templates/' . $file;
			
					return $template;
				}
			}
		}
		
		return $template;
	}
	
	public function filter_wc_get_template($located, $template_name, $args, $template_path, $default_path){
		// Custom Product category
		global $wp_query, $dtwpb_product_cat_custom_page;
		
		if( is_product_category() && $template_name == 'archive-product.php'){
			
			
			$product_cat_custom_page_id = 0;
			$term_id = $wp_query->get_queried_object()->term_id;
			$product_cat_custom_page_id = get_woocommerce_term_meta($term_id, 'dtwpb_product_cat_custom_page', true);
			if( empty($product_cat_custom_page_id) ){
				return $located;
			}elseif ( !empty($product_cat_custom_page_id) ){
				// Overridden to yourtheme/woocommerce-page-builder-templates/archive-product.php.
				$file 	= 'archive-product.php';
				$find[] = 'woocommerce-page-builder-templates/' . $file;
				
				if($wpb_custom_css = get_post_meta( $product_cat_custom_page_id, '_wpb_post_custom_css', true )){
					echo '<style type="text/css">'.$wpb_custom_css.'</style>';
				}
				if($wpb_shortcodes_custom_css = get_post_meta( $product_cat_custom_page_id, '_wpb_shortcodes_custom_css', true )){
					echo '<style type="text/css">'.$wpb_shortcodes_custom_css.'</style>';
				}
				$dtwpb_product_cat_custom_page = get_post($product_cat_custom_page_id); 
				if($dtwpb_product_cat_custom_page){
					$located       = locate_template( $find );
					if ( ! $located || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$located = DT_WOO_PAGE_BUILDER_DIR . '/woocommerce-page-builder-templates/' . $file;
						
					return $located;
				}
				
				
			}
		}elseif( isset($args['woocommerce-page-builder-custom-templates']) ){
			
			switch ($template_name){
				case 'cart/cart.php'; case 'cart/cart-empty.php'; case 'checkout/before-form-checkout.php'; case 'checkout/after-form-checkout.php'; case 'myaccount/form-login.php'; case 'myaccount/form-register.php':
					
					$find[] = 'woocommerce-page-builder-templates/' . $template_name;
					$located       = locate_template( $find );
					if ( ! $located || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$located = DT_WOO_PAGE_BUILDER_DIR . '/woocommerce-page-builder-templates/' . $template_name;
						
					return $located;
					break;
				
				default:
					//return $located;
					break;
			}
		}elseif ( is_account_page() && $template_name == 'myaccount/payment-methods.php' ){
			
		}
		return $located;
	}
	
	/**
	 * Wordpress has a known bug with the posts_per_page value and overriding it using
	 * query_posts. The result is that although the number of allowed posts_per_page
	 * is abided by on the first page, subsequent pages give a 404 error and act as if
	 * there are no more custom post type posts to show and thus gives a 404 error.
	 *
	 * This fix is a nicer alternative to setting the blog pages show at most value in the
	 * WP Admin reading options screen to a low value like 1.
	 *
	 */
	public function custom_posts_per_page( $query ){
		global $wp_query;
		
		$term = $wp_query->get_queried_object();
		$term_id = ($term && !empty($term->term_id)) ? $term->term_id : 0;
		$product_cat_custom_page_id = get_woocommerce_term_meta($term_id, 'dtwpb_product_cat_custom_page', true);
		
		if ( !empty($product_cat_custom_page_id) && $query->is_archive('product_cat') ) {
			set_query_var('posts_per_page', apply_filters('dtwpb_custom_posts_per_page', get_option('posts_per_page')));
		}
	}
}


new DTWPB_Manager();