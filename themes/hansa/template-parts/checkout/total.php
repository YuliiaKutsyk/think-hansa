<div class="checkout-total_holder">
	<div class="checkot-summary_row">
		<p>Total</p>
		<p class="ch-total-value"><?php echo wc_price(WC()->cart->total); ?></p>
	</div>
	<?php if(loyale_is_points_available() && !hansa_is_b2b_user()) { 
		$order_total_points = loyale_get_price_points(WC()->cart->total - WC()->cart->get_shipping_total() - hansa_get_addons_total());
	?>
		<div class="checkot-summary_row">
			<p>Points</p>
			<p>+ <?php echo $order_total_points; ?>pts</p>
			<input type="hidden" name="order_total_points" value="<?php echo $order_total_points; ?>">
		</div>
	<?php } ?>
</div>