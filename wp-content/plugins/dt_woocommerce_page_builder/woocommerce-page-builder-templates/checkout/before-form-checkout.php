<?php
/**
 * Before Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-page-builder-templates/checkout/before-form-checkout.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'dt_woocommerce_page_builder' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout dtwpb-woocommerce-checkout " action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	
