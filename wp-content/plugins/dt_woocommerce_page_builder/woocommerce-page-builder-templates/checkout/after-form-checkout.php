<?php
/**
 * After Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-page-builder-templates/checkout/after-form-checkout.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

