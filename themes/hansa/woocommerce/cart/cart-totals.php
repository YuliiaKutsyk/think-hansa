<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

$free_shipping_settings = get_option('woocommerce_free_shipping_3_settings');
$is_free_shipping = intval($free_shipping_settings['min_amount']) <= WC()->cart->subtotal;
?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

    <div class="cart-maintotal">
  		<h6>Total</h6>
      <div class="cart-summ" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
        <?php 
//          if($is_free_shipping) {
            echo wc_price(WC()->cart->subtotal); 
//          } else {
//            echo wc_price(WC()->cart->total);
//          }

        ?>
          
      </div>
    </div>
	<table cellspacing="0" class="shop_table shop_table_responsive">
		<div class="summary-options">
        <div class="cart-total">
          <p>Sub-total</p>
          <div class="cart-summ" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php echo wc_price(WC()->cart->subtotal - WC()->cart->get_subtotal_tax()); ?></div>
        </div>
        <div class="cart-total">
          <p>VAT</p>
          <div class="cart-summ" data-title="<?php esc_attr_e( 'VAT', 'woocommerce' ); ?>"><?php  wc_cart_totals_taxes_total_html(); ?></div>
        </div>
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
	         <div class="cart-total">
	          <p>Coupon</p>
	          <div class="cart-summ" data-title="<?php esc_attr_e( 'Coupon', 'woocommerce' ); ?>">-<?php echo wc_price(WC()->cart->get_coupon_discount_amount($code)); ?></div>
	        </div>
				<?php endforeach; ?>
				<?php if(WC()->session->get('redeem_amount') != null) { ?>
	         <div class="cart-total">
	          <p>Redeem Amount</p>
	          <div class="cart-summ" data-title="<?php esc_attr_e( 'Redeem Amount', 'woocommerce' ); ?>">-<?php echo wc_price(WC()->session->get('redeem_amount')); ?></div>
	        </div>
				<?php } ?>
        <?php if(loyale_is_points_available() && !hansa_is_b2b_user()) { ?>
          <div class="cart-total">
            <p>Points</p>
            <div class="cart-summ">+ <?php echo loyale_get_price_points(WC()->cart->total - WC()->cart->get_shipping_total() - hansa_get_addons_total()); ?>pts</div>
          </div>
        <?php } ?>
  	</div>


	</table>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
