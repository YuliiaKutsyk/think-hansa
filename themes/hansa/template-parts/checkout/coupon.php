<?php 
	$applied_coupon_code = '';
	$applied_coupons = WC()->cart->get_applied_coupons();
	if(!empty($applied_coupons)) {
		$applied_coupon_code = $applied_coupons[0];
	}
?>
<div class="promocode-wrap coupon">
	<label for="coupon_code">Gift Voucher | Promo Code</label>
	<div class="coupon-wrap coupon">
		<input type="text" name="coupon_code_ph" class="input-text" id="coupon_code_ph" value="<?php echo $applied_coupon_code; ?>" placeholder="Enter Code">
		<button class="button black-button" id="coupon-button" name="apply_coupon_ph" value="Apply coupon">Apply</button>
	</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : 
		$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
	?>
		<div class="promocode-message">
			<p>Congrats! <span class="ch-coupon-value"><?php echo wc_price($amount); ?></span> Off!</p>
			<a href="#" class="remove-coupon" data-code="<?php echo $coupon->get_code(); ?>">Remove</a>
		</div>
	<?php endforeach; ?>
</div>