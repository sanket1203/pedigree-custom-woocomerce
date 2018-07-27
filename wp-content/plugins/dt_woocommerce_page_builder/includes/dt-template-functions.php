<?php
/**
 * DT WooCommerce Page Builder Template functions
 *
 * Functions for the templating system.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function dtwpb_before_checkout_form(){
	// Show non-cart errors
	wc_print_notices();
	
	// Check cart has contents
	if ( WC()->cart->is_empty() ) {
		return;
	}
	
	// Check cart contents for errors
	do_action( 'woocommerce_check_cart_items' );
	
	// Calc totals
	WC()->cart->calculate_totals();
	
	// Get checkout object
	$checkout = WC()->checkout();
	
	if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) {
	
		wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
	
	}else{
		
		$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ) ? true : false;
		
		if ( wc_notice_count( 'error' ) == 0 && $non_js_checkout )
			wc_add_notice( __( 'The order totals have been updated. Please confirm your order by pressing the Place Order button at the bottom of the page.', 'dt_woocommerce_page_builder' ) );
		
			wc_get_template( 'checkout/before-form-checkout.php', array( 'checkout' => $checkout, 'woocommerce-page-builder-custom-templates' => 1 ), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
	}
}

function dtwpb_after_checkout_form(){
	$checkout = WC()->checkout();
	wc_get_template( 'checkout/after-form-checkout.php', array( 'checkout' => $checkout, 'woocommerce-page-builder-custom-templates' => 1 ), DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/', DT_WOO_PAGE_BUILDER_DIR . 'woocommerce-page-builder-templates/' );
}

function dtwpb_filter_checkout_page(){
	if( is_checkout() && get_option('dtwpb_woocommerce_checkout_custem_template', 'no') == 'yes' ){
		add_filter( 'the_content', 'dtwpb_the_checkout_page_content' );
	}
}
add_action( 'template_redirect', 'dtwpb_filter_checkout_page' );

function dtwpb_the_checkout_page_content($content){
	global $wp,$post;
	if(!isset($wp->query_vars['order-pay']) && !isset( $wp->query_vars['order-received'] )){
		$custom_content = '';
		ob_start();
		?>
		<div class="woocommerce">
		<?php
		do_action('dtwpb_woocommerce_before_checkout_form');
			
		echo $content;
	
		do_action('dtwpb_woocommerce_after_checkout_form');
		?>
		</div>
		<?php
		$custom_content = ob_get_clean();
		// otherwise returns the database content
		return $custom_content;
	}else{
		return '[woocommerce_checkout]';
	}
}

// function woocommerce_thankyou_order_id_custom(){
// 	global $wp;
// 	if(isset( $wp->query_vars['order-received'] ) ){
// 		$thank_you_page_url = get_permalink(192);
// 		$order_id = $wp->query_vars['order-received'];
// 		$thank_you_page_url = add_query_arg(array('order-received'=>$order_id),$thank_you_page_url);
// 		if(isset($_GET['key']))
// 			$thank_you_page_url = add_query_arg(array('key'=>$_GET['key']),$thank_you_page_url);
// 		wp_safe_redirect($thank_you_page_url);
// 		exit;
// 	}
// }
// add_action('woocommerce_thankyou_order_id', 'woocommerce_thankyou_order_id_custom');

/*
 * Custom MyAccount page
 */
function dtwpb_filter_custom_myaccount_page(){
	if( is_account_page()){
		add_filter( 'the_content', 'dtwpb_woocommerce_before_custom_myaccount_page');
	}
}
add_action('template_redirect', 'dtwpb_filter_custom_myaccount_page');


function dtwpb_woocommerce_before_custom_myaccount_page($content){
	global $wp;
	
	// Check cart class is loaded or abort
	if ( is_null( WC()->cart ) ) {
		return $content;
	}
	
	if ( ! is_user_logged_in() ) {
		$dtwpb_woocommerce_myaccount_before_login_page_id = get_option('dtwpb_woocommerce_myaccount_before_login_page_id');
		$woocommerce_myaccount_page_id = get_option('woocommerce_myaccount_page_id');
		
		
		if ( isset( $wp->query_vars['lost-password'] ) ) {
			return '[woocommerce_my_account]';
		}elseif($woocommerce_myaccount_page_id !== $dtwpb_woocommerce_myaccount_before_login_page_id && $dtwpb_woocommerce_myaccount_before_login_page_id){
			$message = apply_filters( 'woocommerce_my_account_message', '' );
			
			if ( ! empty( $message ) ) {
				wc_add_notice( $message );
			}
			
			// Start output buffer since the html may need discarding for BW compatibility
			ob_start();
			
			// Collect notices before output
			$notices = wc_get_notices();
			?>
			<div class="woocommerce dtwpb-woocommerce-myaccount-before-login-page">
				<?php
				wc_set_notices( $notices );
				wc_print_notices();
				
				do_action( 'woocommerce_before_customer_login_form' ); ?>
				<div id="customer_login">
				<?php
				$before_login_page = get_post($dtwpb_woocommerce_myaccount_before_login_page_id);
				$custom_content = $before_login_page->post_content;
				
				echo $custom_content;
				?>
				</div>
				<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
			</div>
			<?php
			
			return ob_get_clean();
		}else{
			return $content;
		}
		return $content;
	}else{
		// Start output buffer since the html may need discarding for BW compatibility
		ob_start();
		?>
		<div class="woocommerce">
		<?php
		// Collect notices before output
		$notices = wc_get_notices();
		wc_set_notices( $notices );
		wc_print_notices();
		
		echo $content;
		?>
		</div>
		<?php
		return ob_get_clean();
	}
}