<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :
		$gathered_points = '';
		$order_id = $order->get_id();
		$full_billing_address = $order->get_billing_address_1() . ', ' . $order->get_billing_address_2() . ', ' . $order->get_billing_city() . ' ' . $order->get_billing_postcode() . ' Malta';
		$full_shipping_address = $order->get_shipping_address_1() . ', ' . $order->get_shipping_address_2() . ', ' . $order->get_shipping_city() . ' ' . $order->get_shipping_postcode() . ' Malta';

		if(loyale_is_points_available()) {
			$points = get_post_meta($order_id,'order_total_points',true);
			if($points) {
				$gathered_points = ' ' . '+' . $points . 'pts';
			}
		}

        if(!get_post_meta($order_id, 'is_google_analytics', true)) {
            $script = "
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('event', 'purchase', {
                'currency': 'EUR',
                'value': " . $order->get_total() . ",
                'items': [";
            foreach ($order->get_items() as $item) {
                $script .= "{
                            'item_id': '" . $item->get_id() . "',
                            'item_name': '" . $item->get_name() . "',
                            'price': ' . $item->get_total() . '
                        },";
            }
            $script .= "],
            });
        </script>
    ";
            echo $script;
            update_post_meta( $order_id, 'is_google_analytics', 1);
        }

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

		<?php endif; ?>

		<section class="cart-titles">
		    <div class="container">
		      <div class="row">
		        <div class="col-md-12">
		          <h1>Thank you, <?php echo $order->get_billing_first_name(); ?>!</h1>
		          <p>Order #<?php echo $order_id; ?></p>
		        </div>
		      </div>
		    </div>
	  	</section>

	  	<section class="order-complete_page">
		    <div class="container">
		      <div class="row checkout-form">
		        <div class="col-md-7 col-sm-12 col-xs-12 order-left_wrap">
		          <div class="checkout-form_wrap">
		            <div class="checkout-form_toptitle">
		              <h6 class="order-main_title">Your Order is Confirmed</h6>
		            </div>
		            <div class="order-options_wrap">
		              <div class="profile-order_options mb0">
		                <p>You’ll receive a confirmation email on <?php echo $order->get_billing_email(); ?> with your order number and additional information shortly.</p>
		                <p>Need any help? <a href="<?php echo site_urL(); ?>/contact">Contact Us</a></p>
		              </div>
		            </div>
		            <a href="<?php echo site_url();?>/my-account/print-order/<?php echo $order_id; ?>/?print-order-type=invoice" class="print-button profile-order_button button print">Print Receipt</a>
		          </div>
		          <div class="checkout-form_wrap">
		            <div class="checkout-form_toptitle">
		              <h6 class="order-info_title">Order Information</h6>
		            </div>

		            <div class="profile-order_options mb0">
		              <div class="column">
		                <h6>Contact Information</h6>
		                <p><?php echo $order->get_billing_email(); ?></p>
		                <p><?php echo $order->get_billing_phone(); ?></p>
		              </div>
		              <div class="column">
		                <h6>Payment Method</h6>
		                <p><?php echo $order->get_payment_method_title(); ?> - <b><?php echo wc_price($order->get_total()) . $gathered_points; ?> </b></p>
		              </div>
		              <div class="column">
		                <h6>Billing Address</h6>
		                <p><?php echo $order->get_billing_email(); ?></p>
		                <p><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></p>
		                <p><?php echo $full_billing_address; ?></p>
		                <p><?php echo $order->get_billing_phone(); ?></p>
		              </div>
		              <div class="column">
		                <h6>Shipping Address</h6>
		                <p><?php echo $order->get_billing_email(); ?></p>
		                <p><?php echo $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(); ?></p>
		                <p><?php echo $full_shipping_address; ?></p>
		                <p><?php echo $order->get_shipping_phone(); ?></p>
		              </div>
		              <div class="column">
		                <h6>Delivery Method</h6>
		                <p><?php echo $order->get_shipping_method(); ?></p>
		              </div>
		            </div>
		          </div>

		          <a href="<?php echo site_url(); ?>" class="black-button">Return Home</a>
		        </div>

		        <div class="col-md-5 col-sm-12 col-xs-12 order-right_wrap">
		          <div class="checkout-right_card">
		            <div class="checkout-summary_toptitle">
		              <h6>Order Summary</h6>
		            </div>
		            <?php 
		            	foreach ( $order->get_items() as $item_id => $item ) {
  							$addons_total = 0;
							$product_id = $item->get_product_id();
							$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
							if($is_addon) {
								continue;
							}
							$product = $item->get_product();
							$product_name = $item->get_name();
							$quantity = $item->get_quantity();
							$total = $item->get_total();
                            $regular_price = $product->get_price();
							$allmeta = $item->get_meta_data();
							$product_type = $item->get_type();
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
							if(!$image) {
								$image_src = wc_placeholder_img_src();
							} else {
								$image_src = $image[0];
							}
					?>
			            <div class="checkout-product">
			              <div class="product-thumb">
			                <img src="<?php echo $image_src; ?>" alt="<?php echo $product_name; ?>" class="contain-image">
			              </div>
			              <div class="product-description">
			                <h4>
			                  <span>x<?php echo $quantity; ?></span>
			                  <span style="color: #000;"><?php echo $product_name; ?></span>
			                </h4>
			                <?php 
			                	$product_addons = get_post_meta($product_id,'product_addons',true);
								if($product_addons) {
									$addons = explode(',',$product_addons);
									foreach($addons as $addon) {
										$addon = trim($addon);
										$addon_id = wc_get_product_id_by_sku($addon);
										if($addon_id) {
											if(is_product_in_order($order_id,$addon_id)) {
												$addon_product = wc_get_product($addon_id);
												$addon_price = $addon_product->get_price();
												echo '<p class="ch-addon-item">';
												echo '<span>x' . $quantity . '</span>';
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
			                    <div class="current-price"><?php echo $regular_price > 0 ? wc_price(($regular_price + $addons_total) * $quantity) : ' Free'; ?></div>
			                  </div>
			                  <?php if(loyale_is_points_available() && !hansa_is_b2b_user()) { ?>
			                  	<div class="points-value">+ <?php echo loyale_get_price_points($total); ?>pts</div>
		                  	<?php } ?>
			                </div>
			              </div>
			            </div>
					<?php
						}
		            ?>
		          </div><!-- /checkout-right_card -->

		          <div class="checkout-right_card">
		            <div class="checkout-summary_rows">
		              <div class="checkot-summary_row">
		                <p>Sub-total Excl Taxes</p>
		                <p><?php echo wc_price($order->get_subtotal()); ?></p>
		              </div>
		              <div class="checkot-summary_row">
		                <p>Vat</p>
		                <p><?php echo wc_price($order->get_total_tax()); ?></p>
		              </div>
		              <?php 
		              	$redeem_amount = get_post_meta($order_id,'redeem_amount',true);
		              	if($redeem_amount > 0) { ?>
			              <div class="checkot-summary_row">
			                <p>Redeem Amount</p>
			                <p><?php echo wc_price($redeem_amount); ?></p>
			              </div>
			          <?php }
		              	$shipping_total = $order->get_shipping_total();
		              	if($shipping_total > 0) { ?>
			              <div class="checkot-summary_row">
			                <p>Deliver Charge</p>
			                <p><?php echo wc_price($shipping_total); ?></p>
			              </div>
			          <?php } ?>
		            </div>
		            <div class="checkout-total_holder mb0">
		              <div class="checkot-summary_row">
		                <p>Total</p>
		                <p><?php echo wc_price($order->get_total()); ?></p>
		              </div>
	                  <?php if(loyale_is_points_available()) { ?>
			              <div class="checkot-summary_row">
			                <p>Points</p>
			                <p><?php echo $gathered_points; ?></p>
			              </div>
					  <?php } ?>
		            </div>
		          </div><!-- /checkout-right_card -->
		        </div>
		      </div>
		    </div>
		  </section>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
