<?php
/**
 * The Template for displaying empty wishlist.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist-empty.php.
 *
 * @version             1.25.5
 * @package           TInvWishlist\Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

?>
<div class="tinv-wishlist woocommerce">
	<section class="empty-search_section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
			<?php if (function_exists('wc_print_notices') && isset(WC()->session)) {
				wc_print_notices();
			} ?>
          <div class="empty-search_wrap">
            <img src="<?php echo get_template_directory_uri();?>/assets/img/Broken-Heart.svg" alt="" class="nosearch-icon">
            <h1>You have no Saved Products</h1>
            <p>Start saving as you shop by selecting the little heart.</p>
            <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="blank-button">Start Shoping</a>
          </div>
        </div>
      </div>
    </div>
  </section>
	<?php do_action('tinvwl_wishlist_is_empty'); ?>
</div>
