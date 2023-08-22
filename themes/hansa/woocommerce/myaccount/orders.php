<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
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

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>
<div class="profile-content_top">
	<div class="profile-content_icon">
	  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Receipt.svg" alt="center" class="contain-image">
	</div>
	<h6 class="ptofile-content_title">My Orders</h6>
</div>
<?php if ( $has_orders ) : ?>
	<div class="profile-orders__list">
		<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order );
				$order_id = $order->get_id();
				$full_billing_address = $order->get_billing_address_1() . ', ' . $order->get_billing_address_2() . ', ' . $order->get_billing_city() . ' ' . $order->get_billing_postcode() . ' Malta';
				$full_shipping_address = $order->get_shipping_address_1() . ', ' . $order->get_shipping_address_2() . ', ' . $order->get_shipping_city() . ' ' . $order->get_shipping_postcode() . ' Malta';

        		$redeem_amount = get_post_meta($order_id,'redeem_amount',true);
        		$total_points = get_post_meta($order_id,'order_total_points',true);
				?>

		<div class="profile-orders woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order" data-order-id="<?php echo $order_id; ?>">
                <div class="profle-order_titles">
	                  <div class="left">
	                    <h4>Order #<?php echo $order_id; ?></h4>
	                    <p><?php echo esc_html(  $order->get_date_created()->date( 'l d M Y' ) ); ?></p>
	                  </div>
	                  <div class="right">
	                    <a href="#" class="repeat-order_button profile-order_button">Repeat Order</a>
	                    <a href="<?php echo site_url();?>/my-account/print-order/<?php echo $order_id; ?>/?print-order-type=invoice" class="print-button profile-order_button button print">Print Receipt</a>
	                  </div>
	                </div>
	                <div class="profile-order_options">
	                  <div class="column">
	                    <h6>Delivery Method</h6>
	                    <p><?php echo $order->get_shipping_method(); ?></p>
	                  </div>
	                  <div class="column">
	                    <h6>Payment Method</h6>
	                    <p><?php echo $order->get_payment_method_title(); ?> - <b><?php echo wc_price($order->get_total()); ?> <?php $total_points > 0 ? '+ ' . $total_points . 'pts' : ''; ?></b></p>
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
	                </div>
	                <div class="profile-order_items">
                	<?php 
		            	foreach ( $order->get_items() as $item_id => $item ) {
							$product_id = $item->get_product_id();
							$product = $item->get_product();
							$product_name = $item->get_name();
							$quantity = $item->get_quantity();
							$total = $item->get_total();
							$allmeta = $item->get_meta_data();
							$somemeta = $item->get_meta( 'customer_message', true );
							$product_type = $item->get_type();
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
							if(!$image) {
								$image_src = wc_placeholder_img_src();
							} else {
								$image_src = $image[0];
							}
					?>
	                  <div class="profile-order_item">
	                    <div class="order-item_thumbnail">
	                      <img src="<?php echo $image_src; ?>" alt="<?php echo $product_name; ?>" class="contain-image">
	                    </div>
	                    <div class="order-item_titles">
	                      <h4><span class="quantity">x<?php echo $quantity; ?></span><?php echo $product_name; ?></h4>
	                      <div class="prices-wrap">
	                        <p class="main-price"><?php echo $total > 0 ? wc_price($total) : ' Free'; ?></p>
	                        <?php if($total_points) { ?>
	                        	<p class="ordder-item_points">+ <?php echo loyale_get_price_points($total); ?>pts</p>
                        	<?php } ?>
	                      </div>
	                    </div>
	                  </div>
                  	<?php } ?>
	                </div>
	                <div class="profile-order_summ">
	                  <div class="order-summ_row">
	                    <h6>Sub-total Excl Taxes</h6>
	                    <p><?php echo wc_price($order->get_subtotal()); ?></p>
	                  </div>
	                  <div class="order-summ_row">
	                    <h6>VAT</h6>
	                    <p><?php echo wc_price($order->get_total_tax()); ?></p>
	                  </div>
	                  <?php 
		              	$shipping_total = $order->get_shipping_total();
		              	if($shipping_total > 0) { ?>
			              <div class="order-summ_row">
			                <p>Deliver Charge</p>
			                <p><?php echo wc_price($shipping_total); ?></p>
			              </div>
			          <?php } ?>
	                </div>
	                <div class="profile-order_total">
	                  <div class="order-summ_row">
	                    <h4>Total</h4>
	                    <p><?php echo wc_price($order->get_total()); ?></p>
	                  </div>
	                  <?php if($total_points) { ?>
		                  <div class="order-summ_row">
		                    <h4>Points</h4>
		                    <p>+ <?php echo $total_points; ?>pts</p>
		                  </div>
		              <?php } ?>
	                </div>
              	</div>
				<?php
			}
		?>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="account-empty_data" style="display: block;">
		<h4>You haven't placed any orders yet</h4>
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="grey-button shop-address">Start Shopping</a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
