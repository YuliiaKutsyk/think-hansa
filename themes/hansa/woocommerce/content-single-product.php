<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
$product_sku = $product->get_sku();
$product_id = $product->get_id();
$product_desc = get_post_field('post_content', $product_id);
$bundle_ids = get_post_meta($product_id, 'bundle_ids', true);
?>


<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('event', 'view_item', {
        'currency': 'EUR',
        'value': <?php echo $product->get_price(); ?>,
        'items': {
            'item_id': '<?php echo $product->get_sku(); ?>',
            'item_name': '<?php echo $product->get_name(); ?>',
            'price': <?php echo $product->get_price(); ?>
        },
    });
</script>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product_section', $product ); ?>>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="product-content_holder">

	<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		$image = get_the_post_thumbnail_url(get_the_ID(), 'full');
		$attachment_ids = $product->get_gallery_image_ids();
		if(!$image) {
			$image = wc_placeholder_img_src();
		}
	?>

    <div class="product-slider_wrap">
    	<?php
				if(get_post_meta($product_id,'is_discontinued',true)) {
					echo '<div class="out-ofstock_label product-item_label">Discontinued</div>';
				} else {
					if(!$product->is_in_stock() && $product->get_price() > 0) {
						echo '<div class="out-ofstock_label product-item_label">Out of stock</div>';
					} else {
                        if($product->get_price() == 0) {
                            echo '<div class="out-ofstock_label product-item_label">Price On Request</div>';
                        }
						else if(get_post_meta($product_id,'is_new',true)) {
							echo '<div class="new-label product-item_label">New</div>';
						}
					}
				} 
    		echo do_shortcode('[ti_wishlists_addtowishlist]'); 
    	?>
      <div class="product-slider" id="product-slider">
        <div class="product-slider_image" data-thumb="<?php echo $image; ?>">
          <a href="<?php echo $image; ?>" class="loop"></a>
          <img src="<?php echo $image; ?>" alt="image" class="contain-image">
        </div>
        <?php 
	        if($attachment_ids) {
				foreach( $attachment_ids as $attachment_id ) {
					$image_link = wp_get_attachment_url( $attachment_id );
		?>
			<div class="product-slider_image" data-thumb="<?php echo $image_link; ?>">
	          <a href="<?php echo $image_link; ?>" class="loop"></a>
	          <img src="<?php echo $image_link; ?>" alt="image" class="contain-image">
	        </div>
    	<?php }} ?>
      </div>
  </div>
  <div class="product-single_content">
	  <h1><?php the_title(); ?></h1>
	  <?php if(!empty($bundle_ids)) {
	  	if($product_sku) {
		?>
    	<p class="product-code">Code: <?php echo $product_sku; ?></p>
  	<?php }
  		if($product_desc) {
		?>
		    <div class="product-details_content"><?php echo $product_desc; ?></div>
    <?php }} ?>
    <?php if(empty($bundle_ids)) { 
			$producers = get_the_terms( $product_id, 'producer' );
			if($producers) {
				$i = 0;
  	?>
		  <div class="product-cat">
		  	<?php foreach($producers as $producer) {
		  		$delimiter = '';
		  		if($i > 0) {
		  			$delimiter = ', ';
		  		}
		  		echo $producer->name;
	  		$i++; } ?>
		  </div>
	  <?php } ?>
		  <div class="product-description">
		    <p>
		    	<?php 
		    		$product_details = '';
		    		$delimiter = '';
		    		$vintage = wc_get_product_terms($product_id, 'vintage');
		    		$country = wc_get_product_terms($product_id, 'country');
                    $regions = wc_get_product_terms( $product_id, 'region' );
		    		$supplier = wc_get_product_terms($product_id, 'country');
		    		$grape = wc_get_product_terms($product_id, 'grape');
		    		$tags = wc_get_product_terms($product_id, 'product_tag');
		    		$volume = wc_get_product_terms($product_id, 'volume');
		    		$abv = wc_get_product_terms($product_id, 'abv');
                    $tags = wc_get_product_terms( $product_id, 'product_tag' );
                    $food_match = wc_get_product_terms( $product_id, 'food_match' );
		    		if($vintage) {
		    			$product_details .= 'Year: ' . $vintage[0]->name;
		    			$delimiter = ' | ';
		    		}
		    		if($country) {
		    			$product_details .= $delimiter . 'Country: ' . $country[0]->name;
		    			$delimiter = ' | ';
		    		}
                    if($regions) {
                        $product_details .= $delimiter . 'Region: ' . $regions[0]->name;
                        $delimiter = ' | ';
                    }
		    		if($volume) {
		    			$product_details .= $delimiter . 'Volume: ' . $volume[0]->name;
		    			$delimiter = ' | ';
		    		}
		    		if($grape) {
		    			$product_details .= $delimiter . 'Grape: ';
		    			$i = 0;
		    			foreach($grape as $g) {
		    				if($i < 3) {
		    					if($i > 0) {
			    					$product_details .= ', ' . $grape[$i]->name;
			    				} else {
			    					$product_details .= $grape[$i]->name;
			    				}
			    				$i++;
		    				} else {
		    					break;
		    				}
		    			}
		    			$delimiter = ' | ';
		    		}
		    		if($abv) {
		    			$product_details .= $delimiter . 'ABV: ' . $abv[0]->name;
		    			$delimiter = ' | ';
		    		}
		    		if($tags) {
		    			$product_details .= $delimiter . 'Lifestyle: ';
		    			$i = 0;
		    			foreach($tags as $tag) {
		    				if($i < 3) {
		    					if($i > 0) {
			    					$product_details .= ', ' . $tags[$i]->name;
			    				} else {
			    					$product_details .= $tags[$i]->name;
			    				}
			    				$i++;
		    				} else {
		    					break;
		    				}
		    			}
		    			$delimiter = ' | ';
		    		}
		    		if($food_match) {
		    			$product_details .= $delimiter . 'Food Match: ';
		    			$i = 0;
		    			foreach($food_match as $fm) {
		    				if($i < 3) {
		    					if($i > 0) {
			    					$product_details .= ', ' . $food_match[$i]->name;
			    				} else {
			    					$product_details .= $food_match[$i]->name;
			    				}
			    				$i++;
		    				} else {
		    					break;
		    				}
		    			}
		    			$delimiter = ' | ';
		    		}
		    		echo $product_details;
		    	?>
		    </p>
		  </div>
		<?php } ?>
	  <!-- Variable section -->
	  <?php
	  	if(empty($bundle_ids)) {
	  	$amounts = array();
	  	$amounts_field = get_post_meta(get_the_ID(),'amounts',true);
	  	if(!empty(str_replace(',', '', $amounts_field))) {
	  		$amounts = explode(',', $amounts_field);
	  	}
	  	if(!empty($amounts)) {
	  		$i = 0;
	  ?>
		  <div class="product-pack_purchase">
		    <p class="pack-purchase_title">
		      Amount: <b>x<span class="pack-count_reciever"><?php echo $amounts[0]; ?></span></b>
		    </p>
		    <div class="pack-purchase_variants">
		    	<?php foreach($amounts as $a) { ?>
		      	<input type="text" class="pack-purchase_item <?php echo $i == 0 ? 'active': ''; ?>" value="<?php echo $a; ?>" placeholder="x<?php echo $a; ?>" readonly />
		      	<?php $i++; } ?>
		    </div>
		  </div>
  	<?php }} ?>

	  <?php
	  	if(!empty($bundle_ids)) {
	  ?>
		  <!-- Variable section -->
		  <div class="product-bundle_wrap">
		    <h6 class="product-bundle_title">What’s in the bundle?</h6>
		    <div class="bundle-constituents">
		    	<?php foreach($bundle_ids as $bundle_id) { 
		    		$bundle_product = wc_get_product($bundle_id);
						$bundle_image = wp_get_attachment_image_src( get_post_thumbnail_id( $bundle_id , 'large', true ));
						if($bundle_image) {
							$bundle_image = $bundle_image[0];
						} else {
							$bundle_image = wc_placeholder_img_src();
						}
	    		?>
			      <div class="bundle-item">
			        <div class="bundle-item_thumb">
			          <img src="<?php echo $bundle_image; ?>" alt="<?php echo $bundle_product->get_title(); ?>" class="contain-image">
			        </div>
			        <h4 class="bundle-item_title"><?php echo $bundle_product->get_title(); ?></h4>
			      </div>
			    <?php } ?>
		    </div>
		  </div>
	<?php } ?>
	<?php 
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' ); 
	?>
	  <div class="payment-titles">
	    <p>Secure payment</p>
	    <div class="payments-images">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/payments.png" alt="payments">
	    </div>
	  </div>
	  <div class="product-options">
	    <p class="options-row delivery-row">Free delivery for orders over €50</p>
	    <p class="options-row support-row">Customer support - email & phone</p>
	  </div>
	  <?php if(($product_desc || $product_sku) && empty($bundle_ids)) { ?>
		  <div class="product-details">
		    <h6 class="product-details_title">Product Details</h6>
		    <?php if($product_sku){ ?>
		    	<p class="product-code">Code: <?php echo $product_sku; ?></p>
	    	<?php } ?>
		    <div class="product-details_content"><?php echo $product_desc; ?></div>
		  </div>
	  <?php } ?>
	</div>
	
</div>
</div>
</div>
</div>
</section>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
