<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
  return;
}

// Get ID of current product, to exclude it from the related products query
$current_product_id = $product->get_id();

if ( ! $related = wc_get_related_products( $current_product_id, 4 ) ) {
  return;
}


$cats_array = array(0);

// get categories
$terms = wp_get_post_terms( $product->get_id(), 'product_cat' );

//Get an array of their IDs
$term_ids = wp_list_pluck($terms,'term_id');

//Get array of parents - 0 is not a parent
$parents = array_filter(wp_list_pluck($terms,'parent'));

//Get array of IDs of terms which are not parents.
$term_ids_not_parents = array_diff($term_ids,  $parents);

$args = apply_filters( 'woocommerce_related_products_args', array(
  'post_type' => 'product',
  'post__not_in' => array( $current_product_id ),   // exclude current product
  'ignore_sticky_posts' => 1,
  'no_found_rows' => 1,
  'posts_per_page' => 4,
  'orderby' => $orderby,
  'tax_query' => array(
    array(
        'taxonomy' => 'product_cat',
        'field' => 'id',
        'terms' => $term_ids_not_parents
    ),
  )
));

$products                    = new WP_Query( $args );
$woocommerce_loop['name']    = 'related';
$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $columns );
$i = 0;
$max = 4;
if ( $products->have_posts() ) : ?>
	<section class="related-products">
    <div class="container">
		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'We Suggest', 'woocommerce' ) );

		if ( $heading ) :
			?>
	      <div class="row">
	        <div class="col-md-12">
	          <h4 class="section-title"><?php echo esc_html( $heading ); ?></h4>
	        </div>
	      </div>
		<?php endif; ?>
      <div class="row related-static_row categories-row">
		
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php if($i < $max) wc_get_template_part( 'content', 'product' ); ?>

			<?php $i++; endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

      </div>
	</section>
	<?php
endif;

wp_reset_postdata();
