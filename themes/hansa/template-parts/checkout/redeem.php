<?php
if(loyale_is_points_available()) {
	$total_user_points = loyale_get_customer_points();
	$points_redemption = loyale_get_points_redemption();
	$points_redemption = $points_redemption > 0 ? $points_redemption : 1;
	$redeem_balance = (float)$total_user_points / $points_redemption;
	$redeem_amount = WC()->session->get('redeem_amount');
	$redeem_amount = $redeem_amount !== null ? $redeem_amount : 0;
?>
<div class="promocode-wrap redeem">
<label for="points_code">Redeem Points
  <p class="points-balance">Balance: <?php echo wc_price($redeem_balance); ?></p>
</label>
<div class="coupon-wrap redeem">
  <input input="text" name="points_code" class="input-text" id="redeem_input" value="<?php echo get_woocommerce_currency_symbol() . $redeem_amount; ?>" max="<?php echo $redeem_balance; ?>" placeholder="Enter Redeem Amount">
  <button type="submit" class="button black-button" id="redeem-button" name="apply_points" value="Apply points">Apply</button>
</div>
<?php if($redeem_amount > 0) { ?>
      <div class="promocode-message redeem" >
        <p>Redeem <?php echo $redeem_amount * loyale_get_points_redemption(); ?>pts</p>
        <a href="#" class="remove-redeem">Remove</a>
      </div>
    <?php } ?>
</div>
<?php } ?>