<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            do_action( 'woocommerce_before_checkout_form', $checkout );

            // If checkout registration is disabled and not logged in, the user cannot checkout.
            if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
                ?>
                <div class="empty-search_wrap">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Small.svg" alt="" class="nosearch-icon">
                    <h1>You are not signed in.</h1>
                    <p>You must be logged in to checkout.</p>
                    <a href="<?php echo site_url(); ?>/login" class="blank-button">Sign In</a>
                </div>
                <?php
                echo '</div></div></div>';
                return;
            }
            ?>
        </div>
    </div>
</div>
<?php
$shipping_packages =  WC()->cart->get_shipping_packages();
$shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );
$zone_name = $shipping_zone->get_zone_name();
$billing_country = WC()->session->get('customer')['country'];
if(WC()->customer->get_shipping_country()!= 'MT'){
    WC()->customer->set_shipping_country('MT');
}
?>
<section class="cart-titles checkout-titles">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
        <div class="col-md-7 col-sm-12 col-xs-12">
            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="col2-set" id="customer_details">
                    <div class="col-1 checkout-form_wrap">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>
                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <div class="checkout-form_wrap checkout-delivery-block">
                            <div class="checkout-form_toptitle">
                                <h6 class="delivery-title">Delivery Options</h6>
                            </div>

                            <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

                            <div id="hansa-shipping-table" class="hansa-shipping-table">
                                <?php wc_cart_totals_shipping_html(); ?>
                            </div>

                            <?php
                            do_action( 'woocommerce_review_order_after_shipping' );

                            wc_get_template('template-parts/checkout/address-rows.php');
                            ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="checkout-form_wrap">
                        <div class="checkout-form_toptitle">
                            <h6 class="payment-title">Payment Method</h6>
                        </div>
                        <?php do_action('hansa_checkout_payments'); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12">
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>
    </form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
