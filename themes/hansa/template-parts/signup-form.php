<?php 
  $min_birth_year = 1910;
  $max_birth_year = (int)date('Y');
  $days = 31;
  $months = 12;
?>

<form action="#" class="sign-form">
    <div class="form-row">
      <label for="enq-email_signup">Email</label>
      <input type="text" class="required" id="enq-email_signup" name="register_email" placeholder="Email" />
    </div>
    <div class="form-row">
      <label for="enq-first_name">First Name</label>
      <input type="text" class="required" id="enq-first_name" name="register_name" placeholder="Your First Name" />
    </div>
    <div class="form-row">
      <label for="enq-last_name">Last Name</label>
      <input type="text" class="required" id="enq-last_name" name="register_surname" placeholder="Your Last Name" />
    </div>
    <div class="form-row">
      <label for="enq-last_name">Contact Number</label>
      <input type="tel" class="required" id="enq-phone" name="register_phone" placeholder="Your Number" />
    </div>
    <div class="form-row">
      <label for="enq-password">Password</label>
      <input type="password" class="required" id="enq-password" name="register_pass1" placeholder="Your Password" />
    </div>
    <div class="form-row">
      <label for="enq-password_confirm">Confirm Password</label>
      <input type="password" class="required" id="enq-password_confirm" name="register_pass2" placeholder="Your Password" />
    </div>
    <div class="form-row">
      <label for="">Date of Birth</label>
      <div class="form-row_inner">
        <div class="form-row_part--third date-dropdown_holder">
          <input type="text" class="required" name="register_day" readonly value="Day" class="day-input" />
          <div class="date-dropdown">
            <?php for($i = 1; $i <= $days; $i++) { ?>
              <div class="day-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-row_part--third date-dropdown_holder">
          <input type="text" class="required" name="register_month" readonly value="Month" class="month-input" />
          <div class="date-dropdown">
            <?php for($i = 1; $i <= $months; $i++) { ?>
              <div class="month-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-row_part--third date-dropdown_holder">
          <input type="text" class="required" name="register_year" readonly value="Year" class="year-input" />
          <div class="date-dropdown">
            <?php for($i = $max_birth_year; $i >= $min_birth_year; $i--) { ?>
              <div class="year-picker"><?php echo $i; ?></div>
            <?php } ?>
          </div>
        </div>
      </div>
      <p class="age-checking_error">You must be over 18 years of age</p>
    </div>
    <div class="form-row">
      <label for="check-profile" class="checkbox-label checkbox-label_associate">Tick here if you are a bar or restaurant owner|associate</label>
      <input type="checkbox" id="check-profile" name="register_is_owner" val="1" />
    </div>
    <div class="associate-form_part">
      <div class="form-row">
        <label for="enq-company">Company</label>
        <input type="text" class="required-on-check" id="enq-company" name="register_cname" placeholder="Company Name" />
      </div>
      <div class="form-row">
        <label for="enq-vat">VAT No.</label>
        <input type="text" class="required-on-check" id="enq-vat" name="register_vat" placeholder="Company VAT No." />
      </div>
      <div class="form-row">
        <label for="enq-company_reg">Company Registration No.</label>
        <input type="text" id="enq-company_reg" name="register_cnumber" placeholder="Company Registration No." />
      </div>
      <div class="form-row">
        <div class="form-row_inner">
          <div class="form-row_part--half">
            <label for="enq-address_1">Address Line 1</label>
            <input class="required-on-check" type="text" id="enq-address_1" name="register_addr1" placeholder="Address Line 1" />
          </div>
          <div class="form-row_part--half">
            <label for="enq-address_2">Address Line 2 (optional)</label>
            <input type="text" id="enq-address_2" name="register_addr2" placeholder="Address Line 2 (optional)" />
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-row_inner">
          <div class="form-row_part--half">
            <label for="enq-city">Town | City</label>
            <input class="required-on-check" type="text" id="enq-city" name="register_city" placeholder="Select Town | City" />
          </div>
          <div class="form-row_part--half">
            <label for="enq-postcode">Post Code</label>
            <input class="required-on-check" type="text" name="register_postcode" id="enq-postcode" placeholder="Post Code" />
          </div>
        </div>
      </div>
      <?php 
        $countries_obj   = new WC_Countries();
        $countries = $countries_obj->__get('countries');
      ?>
      <div class="form-row country">
        <label for="enq-country">Country</label>
        <select name="register_country" id="enq-country">
          <?php foreach($countries as $code => $country) { ?>
            <option <?php echo $code == 'MT' ? 'selected' : ''; ?> value="<?php echo $code; ?>"><?php echo $country; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <?php if(false) { ?>
      <div class="form-row">
        <label for="check-subscribe" class="checkbox-label">Subscribe to our Newsletter</label>
        <input class="required-on-check" type="checkbox" name="register_subscribe" id="check-subscribe" />
      </div>
    <?php } ?>
    <button class="black-button register-user-btn">Create an Account</button>
</form>