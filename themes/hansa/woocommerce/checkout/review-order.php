<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
$is_gift_in_cart = false;
?>
<div class="checkout-right_card">
		<div class="checkout-summary_toptitle">
          <h6>Order Summary</h6>
        </div>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
  		$addons_total = 0;
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = $_product->get_id();
			$is_gift = boolval(get_post_meta($product_id,'is_gift',true));
			$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
			if($is_gift) {
				$is_gift_in_cart = true;
			}
			if($is_addon) {
				continue;
			}
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key )  && !$is_gift ) {
				?>
				<div class="checkout-product <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $cart_item['product_id'] ), 'single-post-thumbnail' );
						if($image) {
							$image = $image[0];
						} else {
							$image = wc_placeholder_img_src();
						}
					?>
                                        
              <div class="product-thumb">
                <img src="<?php  echo $image; ?>" alt="<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>" class="contain-image">
              </div>
              <div class="product-description">
                <h4>
                  <span><?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) , $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                  <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;';
                  	echo '(' . strip_tags(wc_price($_product->get_price())) . ')';
                  	?>
                </h4>
                	<?php
                  	$product_addons = get_post_meta($product_id,'product_addons',true);
										if($product_addons) {
                                            $addons_total = 0;
											$addons = explode(',',$product_addons);
											foreach($addons as $addon) {
												$addon = trim($addon);
												$addon_id = wc_get_product_id_by_sku($addon);
												if($addon_id) {
													if(is_product_in_cart($addon_id)) {
														$addon_product = wc_get_product($addon_id);
														$addon_price = $addon_product->get_price();
														echo '<p class="ch-addon-item">';
														echo '<span>x' . $cart_item['quantity'] . '</span>';
														echo '+' . $addon_product->get_title() . ' (' . strip_tags(wc_price($addon_price)) . ')';
														echo '</p>';
														$addons_total += $addon_price;
													}
												}
											}
										}
									?>
                <div class="price-wrap">
                  <div class="left">
                    <div class="current-price">
                  <?php echo strip_tags(wc_price(($addons_total + $_product->get_price()) * $cart_item['quantity'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                  </div>
                  <?php if(loyale_is_points_available() && !hansa_is_b2b_user()) { ?>
                  	<div class="points-value">+ <?php echo loyale_get_product_points($product_id); ?>pts</div>
                	<?php } ?>
                </div>
              </div>
            </div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
</div>
<?php 
	$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'is_gift',
					'value' => 'yes',
					'compare' => '='
				)
			)
	);
	$gifts = new WP_Query( $args );
	if($gifts->have_posts()) {
?>
	<div class="checkout-right_card gifts-block">
	    <div class="gift-toggle">
	      <label for="gift-checkbox_input" class="gift-checkbox checkbox-label <?php echo $is_gift_in_cart ? 'active' : ''; ?>">This order is a gift</label>
	      <input type="checkbox" <?php echo $is_gift_in_cart ? 'checked' : ''; ?> id="gift-checkbox_input">
	    </div>
	    <div class="checkout-gifts_addons<?php echo $is_gift_in_cart ? ' flexed' : ''; ?>">
	    	<?php while($gifts->have_posts()) {
	    		$gifts->the_post();
	    		$gift_id = get_the_ID();
	    		$gift = wc_get_product($gift_id);
	    		$price = $gift->get_price();
	    		$title = $gift->get_title();
	    		$price_str = $price > 0 ? wc_price($price) : 'Free';
	    		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $gift_id ), 'single-post-thumbnail' );
	    		$image = $image ? $image[0] : wc_placeholder_img_src();
	    		$quantity = 0;
	    		$is_message = get_post_meta($gift_id, 'is_custom_message', true);
	    		$is_in_cart = is_product_in_cart($gift_id);
	    		$item_key = '';
	    		$message = '';
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					    if( $is_in_cart && $gift_id == $cart_item['product_id']){
					    	$item_key = $cart_item_key;
					        $quantity = $cart_item['quantity'];
		    				$message = get_cart_item_custom_data($item_key, 'customer_message');
					        break;
					    }
					}
			?>
				<div class="checkout-product gift <?php echo $gift->get_sku() == 'GIFT009' ? 'first ':''; ?><?php echo $is_in_cart ? 'in-cart' : ''; ?>" data-cart-id="<?php echo $item_key; ?>" data-id="<?php echo $gift_id; ?>">
					<div class="product-thumb">
					  <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="contain-image">
					</div>
					<div class="product-description">
					  <h4><?php echo $title; ?></h4>
					  <div class="gift-price"><?php echo $price_str; ?></div>
					</div>
					<div class="quantity-choser">
					  <div class="quantity-less"></div>
					  <input type="text" value="<?php echo $quantity; ?>" class="qty-number gift-card_quantity" name="qtyValue" readonly="">
					  <div class="quantity-more"></div>
					</div>
					<?php if($is_message) { ?>
						<textarea <?php echo $is_in_cart ? 'style="display: inline-block;"' : ''; ?> name="giftCardText" class="gift-card_text" placeholder="Gift Card Message"><?php echo $message; ?></textarea>
					<?php } ?>
				</div>
			<?php } ?>
	    </div><!-- /checkout-gifts_addons -->
	</div>
<?php } ?>
<div class="checkout-right_card">
    <?php 
    	wc_get_template('template-parts/checkout/subtotal.php');
    	do_action('hansa_checkout_coupon_form', $checkout); 
    	wc_get_template('template-parts/checkout/coupon.php');
    	wc_get_template('template-parts/checkout/redeem.php');
    	?>
</div>
<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
	<div class="checkout-right_card">
		<div class="additional-coments">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>

<div class="checkout-right_card">
	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<?php 
		wc_get_template( 'checkout/terms.php' ); 
		wc_get_template( 'template-parts/checkout/total.php' ); 
	?>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>
