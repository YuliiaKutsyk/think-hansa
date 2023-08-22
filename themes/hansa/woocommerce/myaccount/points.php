<?php
  $user_id = get_current_user_id();
  $has_orders = wc_get_customer_order_count( $user_id ) ? true : false;
  $points = loyale_get_customer_points();
  $points_redemption = loyale_get_points_redemption();
  $points_redemption = $points_redemption > 0 ? $points_redemption : 1;
  $redeem_balance = (float)$points / $points_redemption;
  $customer_orders = get_posts( array(
    'numberposts' => - 1,
    'meta_key'    => '_customer_user',
    'orderby'     => 'date',
    'order'       => 'DESC',
    'meta_value'  => get_current_user_id(),
    'post_type'   => wc_get_order_types(),
    'post_status' => array_keys( wc_get_order_statuses() )
  ));
?>

<div class="profile-content_top">
  <div class="profile-content_icon">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/My-Points.svg" alt="center" class="contain-image">
  </div>
  <h6 class="ptofile-content_title">My Points</h6>
</div>
<div class="account-points_top">
  <div class="points-top_holder">
    <div class="left">
      <p class="top">Current Balance</p>
      <h4 class="points-balance"><?php echo number_format($points, 0, '', ','); ?> <span>pts</span></h4>
    </div>
    <div class="right">
      <h6 class="points-value"><?php echo wc_price($redeem_balance);?></h6>
    </div>
  </div>
</div>
<?php if($has_orders) { ?>
  <div class="account-points_holder">
    <div class="account-points_items">
      <h6 class="points-items_title">Statement</h6>
      <?php foreach($customer_orders as $o) { 
        $order_id = $o->ID;
        $order = wc_get_order($order_id);
        $redeem_amount = get_post_meta($order_id,'redeem_amount',true);
        $total_points = get_post_meta($order_id,'order_total_points',true);
        if($redeem_amount && is_numeric($redeem_amount)) {
      ?>
        <div class="points-item">
          <div class="achievement-status recieved-achievement"></div>
          <div class="right">
            <div class="points-item_titles">
              <h6>Redeem</h6>
              <p>Order #<?php echo $order_id; ?> - <?php echo esc_html(  $order->get_date_created()->date( 'l d M Y, g:i A' ) ); ?></p>
            </div>
            <div class="points-item_values">
              <h6 class="achievement-pts">- <?php echo loyale_get_points_value($redeem_amount); ?>pts</h6>
              <p class="achievement-euro"><?php echo wc_price($redeem_amount); ?> Redeemed</p>
            </div>
          </div>
        </div>
      <?php } ?>
        <div class="points-item">
          <div class="achievement-status"></div>
          <div class="right">
            <div class="points-item_titles">
              <h6><?php echo $order->get_shipping_method(); ?></h6>
              <p>Order #<?php echo $order_id; ?> - <?php echo esc_html(  $order->get_date_created()->date( 'l d M Y, g:i A' ) ); ?></p>
            </div>
            <div class="points-item_values">
              <?php if($total_points) { ?>
                <h6 class="achievement-pts">+ <?php echo $total_points; ?>pts</h6>
              <?php } ?>
              <p class="achievement-euro"><?php echo wc_price($order->get_total()); ?> Spent</p>
            </div>
          </div>
        </div>
      <?php } ?>

    </div>
  </div>
<?php } else { ?>
  <div class="account-empty_data" style="display: block;">
    <h4>You haven't placed any orders yet</h4>
    <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="grey-button shop-address">Start Shopping</a>
  </div>
<?php } ?>