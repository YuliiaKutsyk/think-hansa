<div class="checkout-summary_rows subtotal-summary">
  <div class="checkot-summary_row">
    <p>Sub-total Excl Taxes</p>
    <p class="ch-subtotal-value"><?php wc_cart_totals_subtotal_html(); ?></p>
  </div>
  <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
	<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
		<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
			<div class="checkot-summary_row tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<p><?php echo esc_html( $tax->label ); ?></p>
				<p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="checkot-summary_row">
			<p><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></p>
			<p class="ch-tax-value"><?php wc_cart_totals_taxes_total_html(); ?></p>
		</div>
	<?php endif; ?>
<?php endif; ?>
<?php 
  $shipping_total = WC()->cart->get_shipping_total();
?>
  <div class="checkot-summary_row delivery-charge-row">
  	<?php if($shipping_total > 0) { ?>
	    <p>Delivery Charge</p>
	    <p><?php echo wc_price($shipping_total);?></p>
	<?php } ?>
	</div>
</div>