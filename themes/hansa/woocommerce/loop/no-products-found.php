<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="empty-search_section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="empty-search_wrap">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Small.svg" alt="" class="nosearch-icon">
            <h1>No Products Found</h1>
            <p>No products were found matching your selection.</p>
            <a href="<?php echo site_urL(); ?>" class="blank-button">Return Home</a>
          </div>
        </div>
      </div>
    </div>
  </section>
