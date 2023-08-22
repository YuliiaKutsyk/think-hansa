<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/payment-methods.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = wc_get_account_payment_methods_types();

do_action( 'woocommerce_before_account_payment_methods', $has_methods ); ?>

	<div class="profile-content_top">
		<div class="profile-content_icon">
		  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Card.svg" alt="center" class="contain-image">
		</div>
		<h6 class="ptofile-content_title">Payment Methods</h6>
	</div>
	<?php 
		$TP_Payment = new WC_TP_Gateway();
		$credit_cards = $TP_Payment->get_users_saved_card_details();
						
	?>
<?php if ( ! empty( $credit_cards[0]['_tp_transaction_maskedpan'] ) && ! empty( $credit_cards[0]['_tp_transaction_paymenttypedescription'] ) ) {

		// $TP_Payment->update_credit_card_details_info();

} else { ?>
	<!-- <div class="account-empty_data">
	    <h4>You dont have any saved payment methods yet</h4>
  	</div> -->
<?php } ?>

<form action="" method="post" name="saved-card-details" id="saved-card-details">
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				</p><h3 style="padding: 20px 0 0 0;">Saved credit/debit card details</h3>
																
				<div style="padding: 10px 0 25px 0;">
					<table>
						<tbody><tr style="
    /* display: none; */
">
							<td>
								<strong>Card Number</strong>
							</td>
							<td style="width: 75px;">
								<strong>Selected</strong>
							</td>							
							<td style="width: 50px;">
								<strong>Delete</strong>
							</td>
						</tr>
															<tr>
										<td>
											<input type="hidden" name="update_cards[1]" value="1">
											<input type="hidden" name="_tp_transaction_saved_card_id[1]" value="1660810907">
											<input type="hidden" name="_tp_transaction_maskedpan[1]" value="222240######0005">
											<input type="hidden" name="_tp_transaction_paymenttypedescription[1]" value="MASTERCARD">
											<input type="hidden" name="_tp_transaction_reference[1]" value="58-9-2583012">

											222240######0005 (MASTERCARD)
										</td>
										<td style="text-align: center;">
																						<input type="checkbox" name="active_card[1]" class="active_card" value="1660810907" checked="">
										</td>									
										<td style="text-align: center;">
											<input type="checkbox" name="delete_card[1]" value="1660810907">
										</td>
									</tr>
														
					</tbody></table>
					<button type="submit" class="woocommerce-Button button" name="update_card_details" value="Update details">Update card details</button>
				</div>
				<script>jQuery(document).ready(function(){jQuery('.active_card').click(function(){jQuery('.active_card').not(this).prop('checked',false);});});</script>
			<p></p>
		</form>

<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>

<?php if ($has_methods && WC()->payment_gateways->get_available_payment_gateways() ) : ?>
	<a href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>" class="grey-button">Add a New Card</a>
<?php endif; ?>
