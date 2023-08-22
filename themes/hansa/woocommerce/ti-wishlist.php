<?php
/**
 * The Template for displaying wishlist if a current user is owner.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist.php.
 *
 * @version             1.24.5
 * @package           TInvWishlist\Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
wp_enqueue_script('tinvwl');
?>
<div class="tinv-wishlist woocommerce tinv-wishlist-clear">
	<section class="category-filters">
	    <div class="container">
	      <div class="row">
	        <div class="col-md-12">
	          <h1 class="mb0"><?php echo the_title(); ?></h1>
	        </div>
	      </div>
	    </div>
	  </section>
	<?php if (function_exists('wc_print_notices') && isset(WC()->session)) {
		wc_print_notices();
	} ?>
	<?php
	$wl_paged = absint(get_query_var('wl_paged'));
	$form_url = tinv_url_wishlist($wishlist['share_key'], $wl_paged, true);
	?>
	<section class="products-section">
		<div class="container">
			<div class="row">
		        <div class="col-md-12">
		          <p class="items-found"><?php echo count($products); ?> Products Found</p>
		        </div>
	      	</div>
			<?php do_action('tinvwl_wishlist_contents_before'); ?>
			<div class="row">
				<?php
					global $product, $post;
					// store global product data.
					$_product_tmp = $product;
					// store global post data.
					$_post_tmp = $post;

					foreach ($products as $wl_product) {

						if (empty($wl_product['data'])) {
							continue;
						}

						// override global product data.
						$product = apply_filters('tinvwl_wishlist_item', $wl_product['data']);
						// override global post data.
						$post = get_post($product->get_id());

						unset($wl_product['data']);
						if ($wl_product['quantity'] > 0 && apply_filters('tinvwl_wishlist_item_visible', true, $wl_product, $product)) {
							$product_url = apply_filters('tinvwl_wishlist_item_url', $product->get_permalink(), $wl_product, $product);
							do_action('tinvwl_wishlist_row_before', $wl_product, $product);
							wc_get_template_part('content', 'product');
							do_action('tinvwl_wishlist_row_after', $wl_product, $product);
						} // End if().
					} // End foreach().
					// restore global product data.
					$product = $_product_tmp;
					// restore global post data.
					$post = $_post_tmp;
				?>
			</div>
		</div>
	</section>
	<?php do_action('tinvwl_after_wishlist', $wishlist); ?>
	<div class="tinv-lists-nav tinv-wishlist-clear">
		<?php do_action('tinvwl_pagenation_wishlist', $wishlist); ?>
	</div>
</div>
