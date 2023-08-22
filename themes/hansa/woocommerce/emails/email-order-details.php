<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<div style="background: #fff; padding: 25px;">
<h3 style="font-size: 19px; color: #000; padding-bottom: 15px; border-bottom: 1px solid #E9E9E9; margin-bottom: 25px; font-weight: 500;"><img style="vertical-align: top; margin-right: 12px;" src="<?php echo get_template_directory_uri(); ?>/assets/img/Cart.svg" alt=""><span>Order Summary</span></h3>
<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: Montserrat, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border: 0;" border="">
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
		</tbody>
	</table>
	<table style="color: #636363; vertical-align: middle; width: 100%; font-family: Montserrat, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border: 0; margin-top: 25px;">
		
		<tbody style="margin-top: 25px;">
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $key => $total ) {
					$i++;
					$bigger_style = false;
					if($key == 'order_total' || $key == 'order_points') {
						$bigger_style = true;
					}
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; border: none; <?php echo $bigger_style ? 'font-size: 17px;' : 'font-size: 14px;'; ?> color: #000; font-weight: 500; padding: 4px; <?php echo $key == 'order_total' ? 'padding-top: 20px;': '';?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align: right; border: none; color: #4C4C4C; <?php echo $bigger_style ? 'font-size: 17px;' : 'font-size: 14px;'; ?> padding: 4px; <?php echo $key == 'order_total' ? 'padding-top: 20px;': '';?> font-weight: 500;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
				?>
		</tbody>
	</table>
</div>
</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
