<?php 
  $user_id = get_current_user_id();
  $user = wp_get_current_user();
  $is_b2b = in_array( 'b2b_customer', (array) $user->roles );
  $saved_addr = hansa_profile_get_addresses($user_id);
  $billing_country = WC()->session->get('customer')['country'];
  $is_pickup = in_array('local_pickup:1',WC()->session->get( 'chosen_shipping_methods' )) || in_array('local_pickup:0',WC()->session->get( 'chosen_shipping_methods' ));
?>
<div class="checkout-rows_holder checkout-addr-rows checkout-options_rows" <?php echo $is_pickup ? 'style="display:none;"' : ''; ?>>
  <?php if($billing_country == 'MT') { ?>
        <div class="checkout-row current_addr_checkbox">
          <input type="radio" value="1" id="current-billing_adress" name="shipping_type[]">
          <label for="current-billing_adress">Deliver to Billing Address</label>
        </div>
    <?php if(!empty($saved_addr) && !$is_b2b) {
    		$i = 0;
    		foreach($saved_addr as $addr) { 
  				$addr_data = unserialize($addr->userdata);
  		?>
        <div class="checkout-row saved-addr" data-id="<?php echo $addr->id; ?>" data-addr-data='<?php echo json_encode($addr_data); ?>'>
          <input type="radio" <?php echo $i == 0 ? 'checked' : ''; ?> name="shipping_type[]" value="<?php echo $addr->id; ?>" id="saved_addr_<?php echo $i; ?>">
          <label for="saved_addr_<?php echo $i; ?>" class=""><?php echo $addr_data['shipping_address_1'] . ', ' . $addr_data['shipping_address_2'] . ', ' . $addr_data['shipping_city']; ?></label>
          <div class="checkout-row_actions">
            <div class="edit-current_row edit-saved-addr">Edit</div>
            <div class="delete-current_addr delete-current_row"></div>
          </div>
        </div>
    <?php $i++; }} ?>
    <?php if(!$is_b2b) { ?>
      <div class="grey-button add-delivery_button checkout-form_toggle">Add a New Delivery Address</div>
    <?php }} ?>
</div>