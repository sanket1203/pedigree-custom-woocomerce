<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-page-builder-templates/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="u-column1 col-1">

	<h2><?php _e( 'Login', 'dt_woocommerce_page_builder' ); ?></h2>

	<form method="post" class="login">

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
			<label for="username"><?php _e( 'Username or email address', 'dt_woocommerce_page_builder' ); ?> <span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
		</p>
		<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
			<label for="password"><?php _e( 'Password', 'dt_woocommerce_page_builder' ); ?> <span class="required">*</span></label>
			<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" />
		</p>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<p class="form-row">
			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Login', 'dt_woocommerce_page_builder' ); ?>" />
			<label for="rememberme" class="inline">
				<input class="woocommerce-Input woocommerce-Input--checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'dt_woocommerce_page_builder' ); ?>
			</label>
		</p>
		<p class="woocommerce-LostPassword lost_password">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'dt_woocommerce_page_builder' ); ?></a>
		</p>

		<?php do_action( 'woocommerce_login_form_end' ); ?>

	</form>
</div>


