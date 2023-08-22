<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */
defined( 'ABSPATH' ) || exit;
?>

<?php if(is_wc_endpoint_url( 'lost-password' )) { ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
<?php
}
do_action( 'woocommerce_before_reset_password_form' );
$user_id = get_current_user_id();
$rp_login = '';
$rp_key = '';
if(!isset($_SESSION['uua'])) {
	if($user_id) {
		$user_data = get_userdata( $user_id );
		$rp_login   = $user_data ? $user_data->user_login : '';
		$rp_key = get_password_reset_key( $user_data );
		$user = WC_Shortcode_My_Account::check_password_reset_key( $rp_key, $rp_login );
		if($user) {
			$_SESSION['uua'] = true;
		}
	}
} else {
	unset($_SESSION['uua']);
}
?>
<div class="profile-content_top">
    <div class="profile-content_icon">
      <img src="<?php echo get_template_directory_uri();?>/assets/img/Change-Password.svg" alt="center" class="contain-image">
    </div>
    <h6 class="ptofile-content_title">Change Password</h6>
</div>
<form method="post" class="woocommerce-ResetPassword lost_reset_password profile-form">

	<div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="Your New Password" />
	</div>
	<div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="Re-enter Your New Password" />
	</div>

	<?php if(!is_wc_endpoint_url( 'lost-password' )) { ?>
		<input type="hidden" name="reset_key" value="<?php echo esc_attr( $rp_key ); ?>" />
		<input type="hidden" name="reset_login" value="<?php echo esc_attr( $rp_login ); ?>" />
	<?php } else { ?>
		<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
		<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />
	<?php } ?>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_resetpassword_form' ); ?>

	<div class="woocommerce-form-row form-row">
		<input type="hidden" name="wc_reset_password" value="true" />
		<button type="submit" class="submit-form woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
	</div>

	<?php wp_nonce_field( 'reset_password', '_wpnonce' ); ?>
	<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

</form>
<?php do_action( 'woocommerce_after_reset_password_form' ); ?>

	<?php if(is_wc_endpoint_url( 'lost-password' )) { ?>
		</div>
	</div>
</div>
<?php } ?>