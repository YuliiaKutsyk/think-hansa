<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<?php do_action( 'woocommerce_before_cart' ); ?>
		</div>
	</div>
</div>

<section class="cart-titles">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <h1>Cart</h1>
	    </div>
	  </div>
	</div>
</section>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
	<section class="cart-content">
    <div class="container">

	<?php do_action( 'woocommerce_before_cart_contents' ); ?>
      <div class="row">
        <div class="col-md-12">
        	<?php 
        		$cart_total_count_excl_empties = WC()->cart->get_cart_contents_count() - hansa_count_cart_cat_items('empties');
        	?>
          <p class="cart-items_found"><?php echo $cart_total_count_excl_empties; ?> Products Found</p>
        </div>
      </div>

        <div class="row">
          <div class="col-md-7 col-sm-12">
              <div class="cart-items">
              	<?php
									foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
										$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
										$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
										$is_additional_product = has_term('champagne-labels','product_cat',$product_id) || has_term('empties','product_cat',$product_id);
										$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
										$is_label = has_term('champagne-labels','product_cat',$product_id);
										$is_gift = boolval(get_post_meta($product_id,'is_gift',true));
										if($is_addon && !current_user_can('administrator')) {
											continue;
										}
										if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
											$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
											?>
											<div class="cart-item" data-product-id="<?php echo $product_id; ?>">
												<?php
													if(!$is_addon || current_user_can('administrator')) {
														echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
															'woocommerce_cart_item_remove_link',
															sprintf(
																'<a href="%s" class="delete-cart_item" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
																esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
																esc_html__( 'Remove this item', 'woocommerce' ),
																esc_attr( $product_id ),
																esc_attr( $_product->get_sku() )
															),
															$cart_item_key
														);
													}
												?>
												<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('large'), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													echo '<div class="cart-item_thumbnail">' . $thumbnail . '</div>'; // PHPCS: XSS ok.
												} else {
													printf( '<a href="%s" class="cart-item_thumbnail">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
												}
												$regular_price = $_product->get_price();
												$retail_price = (float)get_post_meta($product_id,'b2b_price_3', true);
												$points = 0;
												$points = loyale_get_price_points($regular_price);
												?>
							                  <div class="cart-item_description">
							                    <div class="cart-item_top">
							                      <div class="price-wrap">
							                        <div class="left">
							                        <?php if($retail_price > $regular_price) { ?>
							                          <div class="current-price"><?php echo strip_tags(wc_price($regular_price * $cart_item['quantity'])); ?></div>
							                          <div class="sale-price"><?php echo strip_tags(wc_price($retail_price * $cart_item['quantity'])); ?></div>
						                        	<?php } else { ?>
							                          <div class="current-price"><?php echo strip_tags(wc_price($regular_price * $cart_item['quantity'])); ?></div>
						                        	<?php } ?>
							                        </div>
							                        <?php if(loyale_is_points_available() && !hansa_is_b2b_user()) { ?>
							                        	<div class="points-value">+ <?php echo $points * $cart_item['quantity']; ?>pts</div>
						                        	<?php } ?>
							                      </div>
							                      <?php 
						            	if ( ! $product_permalink ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
													} else {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '
							                      <h4><a href="%s">%s</a></h4>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
													}
													do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
													$product_addons = get_post_meta($product_id,'product_addons',true);
														if($product_addons) {
															$addons = explode(',',$product_addons);
															foreach($addons as $addon) {
																$addon_id = wc_get_product_id_by_sku($addon);
																if($addon_id) {
																	if(is_product_in_cart($addon_id)) {
																		$addon_product = wc_get_product($addon_id);
																		$addon_price = wc_price($addon_product->get_price());
																		echo '<p class="cart-addon-item">';
																		echo '+' . $addon_product->get_title() . ' (' . $addon_price . ')';
																		echo '</p>';
																	}
																}
															}
														}
														if(!$is_label) {
															$volume = get_the_terms( $product_id, 'volume' );
															$country = get_the_terms( $product_id, 'country' );
															$region = get_the_terms( $product_id, 'region' );
															$producer = get_the_terms( $product_id, 'producer' );
															$delimiter = '';
															if($region && $country) {
																$delimiter = '|';
															}
															if($volume) {
		                      ?>
							                      <p class="displacement"><?php echo $volume[0]->name; ?></p>
							                      <?php } if($producer) { ?>
								                      <div class="product-company"><?php echo $producer[0]->name; ?></div>
								                    <?php } ?>
								                    <?php if($region || $country) { ?>
																			<div class="product-date"><?php echo $region ? $region[0]->name : ''; ?> <?php echo $delimiter; ?> <?php echo $country ? $country[0]->name : ''; ?></div>
																		<?php } ?> 
						                <?php } ?>
							                    </div>
							                    <?php if(!$is_addon || current_user_can('administrator')) { ?>
							                    <div class="cart-item_bottom">
							                      <div class="qty-wrap product-quantity">
							                        <div class="quantity-choser">
							                    	<?php
							              $max_q = $_product->get_max_purchase_quantity();
														if($is_label) {
															$max_total = 0;
															$max_labels = 0;
															$labeled_id = $cart_item['custom_data']['labeled_product_id'];
															foreach ( WC()->cart->get_cart() as $cart_item_key2 => $cart_item2 ) {
																if($cart_item_key == $cart_item_key2) {
																	continue;
																}
																if($cart_item2['product_id'] == $labeled_id) {
																	$max_total = $cart_item2['quantity'];
																	continue;
																}
																if(has_term('champagne-labels', 'product_cat', $cart_item2['product_id'])) {
																	$max_labels += $cart_item2['quantity'];
																}
															}
															$max_q = $max_total - $max_labels;
														}

														if ( $_product->is_sold_individually() ) {
															$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
														} else {
															$product_quantity = woocommerce_quantity_input(
																array(
																	'input_name'   => "cart[{$cart_item_key}][qty]",
																	'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'qty-number' ), $_product ),
																	'input_value'  => $cart_item['quantity'],
																	'max_value'    => $max_q,
																	'min_value'    => '1',
																	'product_name' => $_product->get_name(),
																),
																$_product,
																false
															);
														}
														echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
																		// 
																			$wishlist_products = TInvWL_Public_Wishlist_View::instance()->get_current_products();
																			$is_product_in_wl = false;
																			foreach($wishlist_products as $p) {
																				if($p['product_id'] == $product_id) {
																					$is_product_in_wl = true;
																					break;
																				}
																			}
																			?>
							                        </div>

							                    <?php if(!$is_additional_product) { ?>
																			<a role="button" tabindex="0" aria-label="" class="tinvwl_add_to_wishlist_button tinvwl-loop add-to-wishlist_button" data-tinv-wl-list="[]" data-tinv-wl-product="<?php echo $product_id; ?>" data-tinv-wl-productvariation="0" data-tinv-wl-productvariations="[0]" data-tinv-wl-producttype="simple" data-tinv-wl-action="addto">Save for Later</a>
																	<?php } ?>
</div>
							                    </div>
						                      <?php } ?>
							                  </div>
							                </div>
											<?php
										}
									}
								?>
              </div><!-- /cart-items -->

              <div class="cart-right_bottom">
                <h4>Continue Shopping</h4>
                <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="to-shop"></a>
              </div>
            </div>

          <div class="col-md-4 col-md-offset-1 col-sm-12">
            <div class="cart-summary">
              	<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
				?>
              <?php if(is_user_logged_in()) { ?>
              		<a href="<?php echo wc_get_checkout_url(); ?>" class="to-checkout_button black-button">Checkout</a>
          		<?php } else { ?>
          			<a href="<?php echo site_url(); ?>/login" class="to-checkout_button black-button">Sign In to Checkout</a>
        			<?php } ?>
              <div class="cart-summary_description">
                <p>Got a discount code? Add it in the next step.</p>
              </div>
            </div>
            <div class="payment-titles">
              <p>Secure payment</p>
              <div class="payments-images">
              	<img src="<?php echo get_template_directory_uri(); ?>/assets/img/payments.png" alt="payments">
              </div>
            </div>
            <div class="product-options">
              <p class="options-row delivery-row">Free delivery for orders over €50</p>
              <p class="options-row support-row">Customer support - email &amp; phone</p>
            </div>
          </div>
        </div><!-- /row -->
    </div>
  </section>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<tbody>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>
