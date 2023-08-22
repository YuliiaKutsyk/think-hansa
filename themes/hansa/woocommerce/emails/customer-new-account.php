<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;

$user = get_user_by('login',$user_login);
$user_id = $user->ID;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p><strong>First name:</strong> <?php echo $user->first_name; ?></p>
<p><strong>Last name:</strong> <?php echo $user->last_name; ?></p>
<p><strong>Birthdate:</strong> <?php echo date('d-m-Y',strtotime(get_user_meta($user_id,'birthdate',true))); ?></p>
<p><strong>Email:</strong> <?php echo $user->user_email; ?></p>
<p><strong>Company:</strong> <?php echo get_user_meta($user_id,'billing_company',true); ?></p>
<p><strong>Billing Address 1:</strong> <?php echo get_user_meta($user_id,'billing_address_1',true); ?></p>
<p><strong>Billing Address 2:</strong> <?php echo get_user_meta($user_id,'billing_address_2',true); ?></p>
<p><strong>Billing City:</strong> <?php echo get_user_meta($user_id,'billing_city',true); ?></p>
<p><strong>Billing Postcode:</strong> <?php echo get_user_meta($user_id,'billing_postcode',true); ?></p>
<p><strong>Billing VAT:</strong> <?php echo get_user_meta($user_id,'billing_vat',true); ?></p>
<p><strong>Billing Reg Number:</strong> <?php echo get_user_meta($user_id,'billing_reg_number',true); ?></p>
<br>
<p><?php echo admin_url( 'user-edit.php?user_id=' . $user_id); ?></p>
<?php 
do_action( 'woocommerce_email_footer', $email );
