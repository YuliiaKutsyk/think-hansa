<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
$loyale_scheme_id = get_field('loyale_scheme_id','option');
$user = false;
$loyale_id = false;
if(is_user_logged_in()) {
    $loyale_id = get_user_meta(get_current_user_id(),'loyale_customer_id',true);
    $user = wp_get_current_user();
    $user_email = $user->user_email;
    $user_name = $user->first_name;
    $user_surname = $user->last_name;
}
?>
<section class="profile-main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="profile-title">Profile</h1>
                <div class="mobile-ptofile_titles">
                    <a href="/mobile-menu" class="back-button"></a>
                    <h4 class="profile-title profile-title_mobile">Profile</h4>
                </div>
                <div class="account-page_wrap">
                    <?php do_action( 'woocommerce_account_navigation' ); ?>
                    <div class="account-page_right">
                        <?php if(is_wc_endpoint_url( 'edit-account' ) && !$loyale_id && current_user_can('loyalty')) { ?>
                            <div class="account-page_loyale">
                                <div class="account-page_loyale-title">Start Earning Rewards</div>
                                <div class="account-page_loyale-text">Sign in with Loyale to start earning and redeeming points.</div>
                                <div data-loyale-sso data-loyale-user='{"email":"<?php echo $user_email; ?>","firstName":"<?php echo $user_name; ?>","lastName":"<?php echo $user_surname; ?>"}' data-loyale-other="<?php echo get_the_permalink(); ?>"></div>
                            </div>
                        <?php } ?>
                        <div class="account-page_content">
                            <?php
                                /**
                                 * My Account content.
                                 *
                                 * @since 2.6.0
                                 */
                                do_action( 'woocommerce_account_content' );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>