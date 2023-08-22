<?php 
  $user_id = get_current_user_id();
  $addresses = hansa_profile_get_addresses($user_id);
?>

<div class="profile-content_top">
  <div class="profile-content_icon">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Addresses.svg" alt="center" class="contain-image">
  </div>
  <h6 class="ptofile-content_title">Addresses</h6>
</div>
<?php if($addresses) { ?>
  <div class="profile-form">
    <?php 
      foreach($addresses as $addr) { 
        $addr_data = unserialize($addr->userdata);
        $addr_str = $addr_data['shipping_address_1'];
        $addr_str .= $addr_data['shipping_address_2'] ? ', ' . $addr_data['shipping_address_2'] : '';
        $addr_str .= ', ' . $addr_data['shipping_city'];
    ?>
      <div class="form-row adress-row" data-id="<?php echo $addr->id; ?>">
        <input type="checkbox" value="<?php echo $addr_str; ?>" id="shipping_adress-2">
        <label for="shipping_adress-2" class="checkbox-label_row"><?php echo $addr_str; ?></label>
        <a href="#" class="remove-current_adress remove-item_button"></a>
        <a href="#" class="edit-current_address">Edit</a>
      </div>

<div class="edit-addr-form add-item_form">
  <div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-first_name--add">First Name</label>
      <input type="text" class="required" id="enq-first_name--add" name="billing_first_name" value="<?php echo $addr_data['shipping_first_name']; ?>" placeholder="Your First Name">
    </div>
    <div class="form-row_part--half">
      <label for="enq-last_name--add">Last Name</label>
      <input type="text" class="required" id="enq-last_name--add" name="billing_last_name" value="<?php echo $addr_data['shipping_last_name']; ?>" placeholder="Your Last Name">
    </div>
  </div>
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-address_add--1">Address Line 1</label>
      <input type="text" class="required" id="enq-address_add--1" name="billing_address_1" value="<?php echo $addr_data['shipping_address_1']; ?>" placeholder="Address Line 1">
    </div>
    <div class="form-row_part--half">
      <label for="enq-address_add--2">Address Line 2 (optional)</label>
      <input type="text" id="enq-address_add--2" name="billing_address_2" value="<?php echo $addr_data['shipping_address_2']; ?>" placeholder="Address Line 2 (optional)">
    </div>
  </div>
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-city_add">Town | City</label>
      <input type="text" class="required" id="enq-city_add" name="billing_city" value="<?php echo $addr_data['shipping_city']; ?>" placeholder="Your Town | City">
    </div>
    <div class="form-row_part--half">
      <label for="enq-postcode_add">Post Code</label>
      <input type="text" class="required" id="enq-postcode_add" name="billing_postcode" value="<?php echo $addr_data['shipping_postcode']; ?>" placeholder="Post Code">
    </div>
  </div>
</div>
<div class="form-row">
  <label for="enq-country_add">Country</label>
  <input type="text" id="enq-country_add" value="Malta" readonly placeholder="Select Country">
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-company_add">Company</label>
      <input type="text" class="required" id="enq-company_add" value="<?php echo $addr_data['shipping_company']; ?>" name="billing_company" placeholder="Company Name">
    </div>
    <div class="form-row_part--half">
      <label for="enq-vat_add">VAT No.</label>
      <input type="text" class="required" id="enq-vat_add" value="<?php echo $addr_data['shipping_vat']; ?>" name="billing_vat" placeholder="Company VAT No.">
    </div>
  </div>
</div>
  <input type="submit" data-id="<?php echo $addr->id; ?>" value="Update Address" class="profile-edit-addr-btn black-button">
</div>
    <?php } ?>
  </div>
  <a href="#" class="grey-button add-new_adress">Add a New Address</a>
<?php } else { ?>
  <div class="account-empty_data" style="display: block;">
    <h4>You dont have any saved addresses yet</h4>
    <a href="#" class="grey-button add-new_adress">Add a New Address</a>
  </div>
<?php } ?>
<div class="add-new_adress_form add-item_form">
  <div class="top-row">
    <h6>Add a New Delivery Address</h6>
    <a href="#" class="grey-button cancel-adress_button">Cancel</a>
  </div>
  <div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-first_name--add">First Name</label>
      <input type="text" class="required" id="enq-first_name--add" name="billing_first_name" placeholder="Your First Name">
    </div>
    <div class="form-row_part--half">
      <label for="enq-last_name--add">Last Name</label>
      <input type="text" class="required" id="enq-last_name--add" name="billing_last_name" placeholder="Your Last Name">
    </div>
  </div>
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-address_add--1">Address Line 1</label>
      <input type="text" class="required" id="enq-address_add--1" name="billing_address_1" placeholder="Address Line 1">
    </div>
    <div class="form-row_part--half">
      <label for="enq-address_add--2">Address Line 2 (optional)</label>
      <input type="text" id="enq-address_add--2" name="billing_address_2" placeholder="Address Line 2 (optional)">
    </div>
  </div>
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-city_add">Town | City</label>
      <input type="text" class="required" id="enq-city_add" name="billing_city" placeholder="Your Town | City">
    </div>
    <div class="form-row_part--half">
      <label for="enq-postcode_add">Post Code</label>
      <input type="text" class="required" id="enq-postcode_add" name="billing_postcode" placeholder="Post Code">
    </div>
  </div>
</div>
<div class="form-row">
  <label for="enq-country_add">Country</label>
  <input type="text" id="enq-country_add" value="Malta" readonly placeholder="Select Country">
</div>
<div class="form-row">
  <div class="form-row_inner">
    <div class="form-row_part--half">
      <label for="enq-company_add">Company</label>
      <input type="text" class="required" id="enq-company_add" name="billing_company" placeholder="Company Name">
    </div>
    <div class="form-row_part--half">
      <label for="enq-vat_add">VAT No.</label>
      <input type="text" class="required" id="enq-vat_add" name="billing_vat" placeholder="Company VAT No.">
    </div>
  </div>
</div>
  <input type="submit" value="Save Address" class="profile-save-addr-btn black-button">
</div>

<div class="delete-confirm popup">
  <div class="delete-confirm__inner">
    <div class="delete-confirm__close"></div>
    <div class="delete-confirm__title">Remove Address</div>
    <div class="delete-confirm__text">Are you sure you want to remove this address?</div>
    <div class="delete-confirm__btns">
      <div class="delete-confirm__back">Go Back</div>
      <div class="delete-confirm__btn addr">Remove Address</div>
    </div>
  </div>
</div>