<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_edit_account_form' );
$min_birth_year = 1910;
$max_birth_year = (int)date('Y');
$days = 31;
$months = 12;
$user_id = $user->ID;
?>
<div class="profile-content_top">
	<div class="profile-content_icon">
	  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Profile.svg" alt="center" class="contain-image">
	</div>
	<h6 class="ptofile-content_title">Profile</h6>
</div>
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" required class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>
	<div class="clear"></div>

	<div class="woocommerce-form-row woocommerce-form-row--first form-row">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</div>
	<div class="woocommerce-form-row woocommerce-form-row--last form-row">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</div>
	<div class="clear"></div>

	<?php $birthdate = strtotime(get_user_meta($user_id,'birthdate',true)); ?>
	<div class="form-row">
	  <label for="">Date of Birth</label>
	  <div class="form-row_inner">
	    <div class="form-row_part--third date-dropdown_holder">
	      <input type="text" readonly="" name="birth_day" value="<?php echo date('d',$birthdate); ?>" class="day-input">
	      <div class="date-dropdown">
		      	<?php for($i = 1; $i <= $days; $i++) { ?>
	              <div class="day-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
	            <?php } ?>
	      </div>
	    </div>
	    <div class="form-row_part--third date-dropdown_holder">
	      <input type="text" readonly="" name="birth_month" value="<?php echo date('m',$birthdate); ?>" class="month-input">
	      <div class="date-dropdown">
            <?php for($i = 1; $i <= $months; $i++) { ?>
              <div class="month-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
            <?php } ?>
	      </div>
	    </div>
	    <div class="form-row_part--third date-dropdown_holder">
	      <input type="text" readonly="" name="birth_year" value="<?php echo date('Y',$birthdate); ?>" class="year-input">
	      <div class="date-dropdown">
	            <?php for($i = $max_birth_year; $i >= $min_birth_year; $i--) { ?>
	              <div class="year-picker"><?php echo $i; ?></div>
	            <?php } ?>
	      </div>
	    </div>
	  </div>
	</div>
	<?php
		$user = wp_get_current_user();
		$is_owner = false;
		if ( in_array( 'b2b_customer', (array) $user->roles ) ) {
			$is_owner = true;
		}
		$billing_company = get_user_meta($user_id,'billing_company',true);
		$billing_vat = get_user_meta($user_id,'billing_vat',true);
		$billing_reg = get_user_meta($user_id,'billing_reg_number',true);
		$billing_addr1 = get_user_meta($user_id,'billing_address_1',true);
		$billing_addr2 = get_user_meta($user_id,'billing_address_2',true);
		$billing_city = get_user_meta($user_id,'billing_city',true);
		$billing_postcode = get_user_meta($user_id,'billing_postcode',true);
		$billing_country = get_user_meta($user_id,'billing_country',true);
	?>
  <?php if($is_owner) { ?>
    <div class="associate-form_part" style="display: block;">
      <div class="form-row">
        <label for="enq-company">Company</label>
        <input type="text" class="required-on-check" id="enq-company" required value="<?php echo $billing_company; ?>" name="billing_company" placeholder="Company Name" />
      </div>
      <div class="form-row">
        <label for="enq-vat">VAT No.</label>
        <input type="text" class="required-on-check" id="enq-vat" name="billing_vat" required value="<?php echo $billing_vat; ?>" placeholder="Company VAT No." />
      </div>
      <div class="form-row">
        <label for="enq-company_reg">Company Registration No.</label>
        <input type="text" class="required-on-check" required value="<?php echo $billing_reg; ?>" id="enq-company_reg" name="billing_cnumber" placeholder="Company Registration No." />
      </div>
      <div class="form-row">
        <div class="form-row_inner">
          <div class="form-row_part--half">
            <label for="enq-address_1">Address Line 1</label>
            <input class="required-on-check" type="text" id="enq-address_1" required value="<?php echo $billing_addr1; ?>" name="billing_addr1" placeholder="Address Line 1" />
          </div>
          <div class="form-row_part--half">
            <label for="enq-address_2">Address Line 2 (optional)</label>
            <input type="text" id="enq-address_2" value="<?php echo $billing_addr2; ?>" name="billing_addr2" placeholder="Address Line 2 (optional)" />
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-row_inner">
          <div class="form-row_part--half">
            <label for="enq-city">Town | City</label>
            <input class="required-on-check" type="text" id="enq-city" required name="billing_city" value="<?php echo $billing_city; ?>" placeholder="Select Town | City" />
          </div>
          <div class="form-row_part--half">
            <label for="enq-postcode">Post Code</label>
            <input class="required-on-check" type="text" value="<?php echo $billing_postcode; ?>" required name="billing_postcode" id="enq-postcode" placeholder="Post Code" />
          </div>
        </div>
      </div>
      <?php 
        $countries_obj   = new WC_Countries();
        $countries = $countries_obj->__get('countries');
      ?>
      <div class="form-row country">
        <label for="enq-country">Country</label>
        <select name="billing_country" id="enq-country">
        	<?php if(!$billing_country) { ?>
	        	<option disabled="true" selected value>Select country</option>
	        <?php } ?>
          <?php foreach($countries as $code => $country) { ?>
            <option <?php echo $code == $billing_country ? 'selected' : ''; ?> value="<?php echo $code; ?>"><?php echo $country; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  <?php } ?>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<div>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="submit-form woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Update Profile', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
