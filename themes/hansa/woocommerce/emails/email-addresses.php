<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?>
<div style="background: #fff; padding: 25px; margin-top: 15px; margin-bottom: 40px;">
	<h3 style="font-size: 19px; color: #000; padding-bottom: 15px; border-bottom: 1px solid #E9E9E9; margin-bottom: 25px; font-weight: 500;"><img style="vertical-align: middle; margin-right: 12px;" src="<?php echo get_template_directory_uri(); ?>/assets/img/order-n-icon.svg" alt=""><span>Order Information</span></h3>
	<table style="width: 100%;">
		<tr style="margin-bottom: 40px;">
			<td style="vertical-align: top; width: 50%;">
				<span style="font-size: 16px; color: #000; font-weight: 500; margin-bottom: 10px; display: block;">Contact Information</span>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_email(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_phone(); ?></span>
			</td>
			<td style="vertical-align: top; width: 50%;">
				<span style="font-size: 16px; color: #000; font-weight: 500; margin-bottom: 10px; display: block;">Payment Method</span>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_payment_method_title(); ?></span>
			</td>
		</tr>
		<tr style="margin-bottom: 40px;">
			<td style="vertical-align: top; width: 50%;">
				<span style="font-size: 16px; color: #000; font-weight: 500; margin-bottom: 10px; display: block;">Billing Address</span>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_email(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_address_1() . ', ' . $order->get_billing_address_2(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_city() . ' ' . $order->get_billing_postcode(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo WC()->countries->countries[ $order->get_billing_country() ]; ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_phone(); ?></span>
			</td>
			<td style="vertical-align: top; width: 50%;">
				<span style="font-size: 16px; color: #000; font-weight: 500; margin-bottom: 10px; display: block;">Delivery Address</span>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_billing_email(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_shipping_address_1() . ', ' . $order->get_shipping_address_2(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_shipping_city() . ' ' . $order->get_shipping_postcode(); ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo WC()->countries->countries[ $order->get_shipping_country() ]; ?></span><br>
				<span style="font-size: 14px; color: #4C4C4C; line-height: 22px;"><?php echo $order->get_shipping_phone(); ?></span>
			</td>
		</tr>
	</table>
</div>
