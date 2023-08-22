<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
$shipping_packages =  WC()->cart->get_shipping_packages();
$shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );
$zone_name = $shipping_zone->get_zone_name();
$chosen_shipping = WC()->session->get('chosen_shipping_methods')[0];
$billing_country = WC()->session->get('customer')['country'];
$user_id = get_current_user_id();
$saved_addr = hansa_profile_get_addresses($user_id);
?>
<div class="woocommerce-shipping-fields" data-shipping-zone="<?php echo $zone_name; ?>">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 id="ship-to-different-address">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" checked type="checkbox" name="ship_to_different_address" value="1" />
			</label>
		</h3>

		<div class="shipping_address add-new_adress_form<?php echo $saved_addr ? ' hidden ' : ' visible-100 '; ?>add-item_form <?php echo $billing_country != 'MT' ? 'visible-100' : ''; ?>">
			<?php if($billing_country == 'MT') { ?>
				<div class="top-row">
					<h6><span>Add a New Delivery</span> Address</h6>
					<div class="grey-button cancel-adress_button">Cancel</div>
				</div>
			<?php } else { ?>
				<div class="top-row">
					<h6><span>Delivery</span> Address</h6>
				</div>
			<?php } ?>
			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper">
				<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );

				foreach ( $fields as $key => $field ) {
					if($key == 'shipping_country') {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					} else {
						if($billing_country == 'MT') {
							woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
						} else {
							woocommerce_form_field( $key, $field, false );
						}
					}
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

			<?php if($billing_country == 'MT') { ?>
				<div class="form-row save-addr-row">
					<label for="enq-save_adress" class="checkbox-label">Save address information to my account for future purchases</label>
					<input type="checkbox" id="enq-save_adress">
				</div>
				<button class="save-adress_input black-button">Save Address</button>
				<button class="edit-adress_input black-button" style="display: none;">Edit Address</button>
			<?php } ?>

		</div>

	<?php endif; ?>
</div>