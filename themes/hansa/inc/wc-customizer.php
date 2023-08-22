<?php

// Remove Woocommerce styles
add_action( 'wp_enqueue_scripts', 'hansa_wc_script_cleaner', 99 );
function hansa_wc_script_cleaner() {
remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce-general');
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		// wp_dequeue_script( 'selectWoo' );
		// wp_deregister_script( 'selectWoo' );
		// wp_dequeue_script( 'wc-add-payment-method' );
		// wp_dequeue_script( 'wc-lost-password' );
		// wp_dequeue_script( 'wc_price_slider' );
		// wp_dequeue_script( 'wc-single-product' );
		// wp_dequeue_script( 'wc-add-to-cart' );
		// wp_dequeue_script( 'wc-cart-fragments' );
		// wp_dequeue_script( 'wc-credit-card-form' );
		// wp_dequeue_script( 'wc-checkout' );
		// wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		// wp_dequeue_script( 'wc-cart' );
		// wp_dequeue_script( 'wc-chosen' );
		// wp_dequeue_script( 'woocommerce' );
		// wp_dequeue_script( 'prettyPhoto' );
		// wp_dequeue_script( 'prettyPhoto-init' );
		// wp_dequeue_script( 'jquery-blockui' );
		// wp_dequeue_script( 'jquery-placeholder' );
		// wp_dequeue_script( 'jquery-payment' );
		// wp_dequeue_script( 'fancybox' );
		// wp_dequeue_script( 'jqueryui' );
}

// Woocommerce Loop product item customized
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );


add_action( 'woocommerce_before_shop_loop_item', 'hansa_wc_template_loop_thumbnail', 10 );
function hansa_wc_template_loop_thumbnail() {
	global $product;
	$product_id = $product->get_id();

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
	echo '<a href="' . $product->get_permalink() . '" class="slider-thumb">';
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
	if($image) {
		$image = $image[0];
	} else {
		$image = wc_placeholder_img_src();
	}
	echo '<img src="'. $image . '" class="contain-image">';
	$product_tags = wc_get_product_terms($product_id,'product_tag');
	if($product_tags) {
		echo '<span class="attribute-icons">';
		foreach($product_tags as $p_tag) {
			$icon = get_field('product_tag_icon','term_' . $p_tag->term_id);
			if($icon) {
				echo '<span class="attribute-icon">';
				echo '<img src="' . $icon['url'] . '">';
				echo '</span>';
			}
		}
		echo '</span>';
	}
	$vintage = get_the_terms( $product_id, 'vintage' );
	if($vintage && $vintage[0]->name != 'NV') {
		echo '<span class="vintage-label"><p>Vintage</p><h6>' . $vintage[0]->name .'</h6></span>';
	}
	echo '</a>';
}

add_action( 'woocommerce_before_shop_loop_item', 'hansa_wc_template_loop_price_and_points', 15 );
function hansa_wc_template_loop_price_and_points() {
	global $product;
	$product_id = $product->get_id();
	$regular_price = $product->get_price();
	$retail_price = (float)get_post_meta($product_id,'b2b_price_3', true);
?>
	<div class="description">
		<div class="description-top">
        <?php if($product->get_price() > 0){ ?>
		<div class="price-wrap">
			<div class="left">
				<?php if($retail_price > $regular_price) { ?>
					<div class="current-price"><?php echo strip_tags(wc_price($regular_price)); ?></div>
					<div class="sale-price"><?php echo strip_tags(wc_price($retail_price)); ?></div>
				<?php } else { ?>
					<div class="current-price"><?php echo strip_tags(wc_price($regular_price)); ?></div>
				<?php } ?>
			</div>
			<?php if(!hansa_is_b2b_user()) { ?>
				<div class="points-value">+ <?php echo loyale_get_product_points($product_id); ?>pts</div>
			<?php } ?>
		</div>
        <?php } ?>
<?php
}

add_action( 'woocommerce_shop_loop_item_title', 'hansa_wc_template_loop_title', 10 );
function hansa_wc_template_loop_title() {
	global $product;
	echo '<h6><a href="' . $product->get_permalink() . '">';
	echo $product->get_title();
	echo '</a></h6>';
}

add_action( 'woocommerce_after_shop_loop_item_title', 'hansa_wc_template_loop_after_title', 10 );
function hansa_wc_template_loop_after_title() {
	global $product;
	$product_id = $product->get_id();
	$volume = get_the_terms( $product_id, 'volume' );
	$country = get_the_terms( $product_id, 'country' );
	$region = get_the_terms( $product_id, 'region' );
	$producer = get_the_terms( $product_id, 'producer' );
	$delimiter = '';
	if($region && $country) {
		$delimiter = '|';
	}
	if($volume) {
?>
	<p class="displacement"><?php echo $volume[0]->name; ?></p>
<?php } if($producer) { ?>
	<div class="product-company"><?php echo $producer[0]->name; ?></div>
<?php } if($region || $country) { ?>
	<div class="product-date"><?php echo $region ? $region[0]->name : ''; ?>
		<?php
			echo $delimiter;
			echo $country ? $country[0]->name : '';
		?>
	</div>
<?php } ?>
	</div>
	<div class="qty-wrap <?php echo !$product->is_in_stock() ? 'disabled' : ''; ?>">
		<div class="quantity-choser">
			<div class="quantity-less"></div>
			<input type="text" value="1" class="qty-number" name="qtyValue" readonly />
			<div class="quantity-more"></div>
			<div class="mobile-quantity_input">
              <input type="text" value="1" readonly />
              <input type="text" value="2" readonly />
              <input type="text" value="3" readonly />
              <input type="text" value="4" readonly />
              <input type="text" value="5" readonly />
            </div>
		</div>
		<?php
			echo sprintf('<a href="%s" data-quantity="1" class="%s" %s>%s</a>', esc_url($product->add_to_cart_url()), esc_attr(implode(' ', array_filter(array('add-to-cart',
			    'button',
			    'product_type_' . $product->get_type(),
			    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : ''
			)))), wc_implode_html_attributes(array(
			    'data-product_id' => $product->get_id(),
			    'data-product_sku' => $product->get_sku(),
			    'aria-label' => $product->add_to_cart_description(),
			    'rel' => 'nofollow'
			)), esc_html('Add to cart'));
		?>
	</div>
</div>
<?php
}

// Woocommerce Shop page customizing
remove_action('woocommerce_sidebar','woocommerce_get_sidebar',10);
remove_action('woocommerce_before_main_content','woocommerce_get_sidebar',10);
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb',20);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);

// Add custom fields to customer profile
add_filter('woocommerce_customer_meta_fields','hansa_add_customer_field_in_profile');
function hansa_add_customer_field_in_profile($fields) {
	$fields_arr = $fields['billing']['fields'];
	$temp = array_slice($fields_arr, 0, 3, true) +
    array("billing_vat" => array(
		'label'       => __( 'Company VAT', 'woocommerce' ),
		'description' => '',
	)) +
	array("billing_reg_number" => array(
		'label'       => __( 'Company Registration No.', 'woocommerce' ),
		'description' => '',
	)) +
	array("billing_ref" => array(
		'label'       => __( 'Reference', 'woocommerce' ),
		'description' => '',
	)) +
    array_slice($fields_arr, 3, count($fields_arr) - 1, true) ;
    $fields['billing']['fields'] = $temp;
	return $fields;
}

// Redirect from unlogged profile pages
add_action( 'wp', 'hansa_account_redirect' );
function hansa_account_redirect() {
  if ( is_page('my-account') && !is_user_logged_in() && !is_wc_endpoint_url( 'lost-password' )) {
      wp_redirect( home_url('/login') );
      die();
  }
}


// Add account custom endpoints
add_action( 'init', 'hansa_custom_endpoint' );
function hansa_custom_endpoint() {
	add_rewrite_endpoint( 'change-password', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'points', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'reviews', EP_ROOT | EP_PAGES );

	$user = wp_get_current_user();
	$is_b2b = in_array('b2b_customer', (array)$user->roles);
	if(!$is_b2b) {
		add_rewrite_endpoint( 'addresses', EP_ROOT | EP_PAGES );
	}

}

// Add change password endpoint template
add_action( 'woocommerce_account_change-password_endpoint', 'hansa_acc_changepass_endpoint_content' );
function hansa_acc_changepass_endpoint_content() {
    wc_get_template('myaccount/form-reset-password.php');
}

// Add points endpoint template
add_action( 'woocommerce_account_points_endpoint', 'hansa_acc_points_endpoint_content' );
function hansa_acc_points_endpoint_content() {
    wc_get_template('myaccount/points.php');
}

// Add points endpoint template
add_action( 'woocommerce_account_addresses_endpoint', 'hansa_acc_addresses_endpoint_content' );
function hansa_acc_addresses_endpoint_content() {
    wc_get_template('myaccount/addresses.php');
}

// Add points endpoint template
add_action( 'woocommerce_account_reviews_endpoint', 'hansa_acc_reviews_endpoint_content' );
function hansa_acc_reviews_endpoint_content() {
    wc_get_template('myaccount/reviews.php');
}

// Customize account navigation links
add_filter ( 'woocommerce_account_menu_items', 'hansa_remove_my_account_links' );
function hansa_remove_my_account_links( $menu_links ){
	$new = array(
		'edit-account' => 'Profile',
		'change-password' => 'Change Password',
		'orders'       => 'My Orders',
		'addresses'    => 'Addresses',
		'reviews' => 'My Reviews',
		'customer-logout' => 'Logout',
	);
	if(is_user_logged_in()) {
		$user_id = get_current_user_id();
		$is_loyale = get_user_meta($user_id, 'loyale_customer_id', true);
		$point_arr = array('points' => 'My Points');
		if($is_loyale) {
			$new = array_insert_after( $new, 'change-password', $point_arr );
		}
	}

	$menu_links = $new;

    $user = wp_get_current_user();
    $is_b2b = in_array('b2b_customer', (array)$user->roles);
    if($is_b2b) {
    	unset($menu_links['addresses']);
    }
	return $menu_links;
}

// Redirect from main profile page to edit details
add_action( 'parse_request', 'hansa_wc_redirect_account_dashboard', 10, 1 );
function hansa_wc_redirect_account_dashboard( $wp ) {
    if ( !is_admin() ) {
        if ( $wp->request === 'my-account' ) {
            wp_redirect( site_url( '/my-account/edit-account' ) );
            exit;
        }
    }
}

// Remove required fields in edit account
add_filter('woocommerce_save_account_details_required_fields', 'hansa_save_account_details_required_fields' );
function hansa_save_account_details_required_fields( $required_fields ){
    unset( $required_fields['account_display_name'] );
    return $required_fields;
}

// Update fields in edit account
add_action('woocommerce_save_account_details', 'hansa_update_account_details_fields' );
function hansa_update_account_details_fields($user_id){
	$birth_day = $_POST['birth_day'];
	$birth_month = $_POST['birth_month'];
	$birth_year = $_POST['birth_year'];
	if(checkdate( $birth_month, $birth_day, $birth_year )) {
		update_user_meta($user_id,'birthdate',date('Y-m-d', strtotime($birth_year . '-' . $birth_month . '-' . $birth_day)));
	}
	$is_owner = $_POST['is_owner'];
	update_user_meta($user_id,'is_owner',$is_owner);
	update_user_meta($user_id,'billing_company',$_POST['billing_company']);
	update_user_meta($user_id,'billing_address_1',$_POST['billing_addr1']);
	update_user_meta($user_id,'billing_address_2',$_POST['billing_addr2']);
	update_user_meta($user_id,'billing_city',$_POST['billing_city']);
	update_user_meta($user_id,'billing_postcode',$_POST['billing_postcode']);
	update_user_meta($user_id,'billing_vat',$_POST['billing_vat']);
	update_user_meta($user_id,'billing_reg_number',$_POST['billing_cnumber']);
	update_user_meta($user_id,'billing_country',$_POST['billing_country']);
}

// Add review title field
add_action( 'comment_form_logged_in_after', 'hansa_add_review_title_field_on_comment_form' );
add_action( 'comment_form_after_fields', 'hansa_add_review_title_field_on_comment_form' );
function hansa_add_review_title_field_on_comment_form() {
	echo '<div class="form-row">
              <span class="wpcf7-form-control-wrap">
                <label for="enq-review_headline">Full Name</label>
                <input type="text" id="enq-review_headline" name="full_name" placeholder="Full Name">
              </span>
            </div>';
	echo '<div class="form-row">
              <span class="wpcf7-form-control-wrap">
                <label for="enq-review_headline">Review Headline</label>
                <input type="text" id="enq-review_headline" name="title" placeholder="Your Review Headline">
              </span>
            </div>';
}

// Save review fields
add_action( 'comment_post', 'hansa_save_comment_review_title_field' );
function hansa_save_comment_review_title_field( $comment_id ){
    if( isset( $_POST['title'] ) )
      update_comment_meta( $comment_id, 'title', esc_attr( $_POST['title'] ) );
    if( isset( $_POST['full_name'] ) )
      update_comment_meta( $comment_id, 'full_name', esc_attr( $_POST['full_name'] ) );
}

// Start session on wordpress init
add_action('init', 'hansa_register_session');
function hansa_register_session()
{
  if( !session_id() && !headers_sent() && !is_admin()) {
    session_start();
  }
}

// Woocommerce Single page customizing
add_action('woocommerce_before_single_product_content', 'woocommerce_breadcrumb', 50);
add_filter( 'woocommerce_breadcrumb_defaults', 'hansa_woocommerce_breadcrumbs' );
function hansa_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<section class="breadcrumbs-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumbs" itemprop="breadcrumb">',
            'wrap_after'  => '</ul></div></div></div></section>',
            'before'      => '<li>',
            'after'       => '</li>',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
            'delimiter'        => '<li class="separator">&gt;</li>',
        );
}
remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_images',20);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action('woocommerce_single_product_content','woocommerce_template_single_price',10);

// Add tab and fields to product settings
add_filter( 'woocommerce_product_data_tabs', 'hansa_add_my_custom_product_data_tab' , 99 , 1 );
function hansa_add_my_custom_product_data_tab( $product_data_tabs ) {
    $product_data_tabs['hansa-settings'] = array(
        'label' => __( 'B2B prices', 'hansa' ),
        'target' => 'hansa_product_prices_data',
    );
    return $product_data_tabs;
}

add_action( 'woocommerce_product_data_panels', 'hansa_product_price_data_fields' );
function hansa_product_price_data_fields() {
    global $woocommerce, $post;
    ?>
    <!-- id below must match target registered in above add_my_custom_product_data_tab function -->
    <div id="hansa_product_prices_data" class="panel woocommerce_options_panel">
        <?php
        	$i = 0;
        	$pricelists = get_b2b_pricelist_names();
        	$pricelists_ids = get_b2b_pricelists();
        	if($pricelists_ids) {
        		foreach($pricelists_ids as $pl_id) {
        			if(!empty($pl_id)) {
						woocommerce_wp_text_input(
							array(
								'id'        => 'b2b_price_' . $pl_id,
								'value'     => get_post_meta($post->ID, 'b2b_price_' . $pl_id, true),
								'label'     => 'Price ('. get_woocommerce_currency_symbol() .')<br> [<strong>'. $pricelists[$i] .'</strong>]'
							)
						);
        			}
        			$i++;
        		}
        	}
        ?>
    </div>
    <?php
}

// Add fields to product
add_action( 'woocommerce_product_options_general_product_data', 'hansa_add_product_data_fields' );
function hansa_add_product_data_fields() {
    global $woocommerce, $post;
    $product_id = $post->ID;
	woocommerce_wp_checkbox(
		array(
			'id'        => 'is_new',
			'value'     => get_post_meta($post->ID, 'is_new', true),
			'label'     => 'Is new product?'
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'        => 'is_discontinued',
			'value'     => get_post_meta($post->ID, 'is_discontinued', true),
			'label'     => 'Is discontinued?'
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'        => 'is_customizable',
			'value'     => get_post_meta($post->ID, 'is_customizable', true),
			'label'     => 'Is customizable?'
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'        => 'is_gift',
			'value'     => get_post_meta($post->ID, 'is_gift', true),
			'label'     => 'Is gift?'
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'        => 'is_custom_message',
			'value'     => get_post_meta($post->ID, 'is_custom_message', true),
			'label'     => 'Is has custom<br> message field?'
		)
	);

	woocommerce_wp_text_input(
		array(
			'id'        => 'product_addons',
			'value'     => get_post_meta($post->ID, 'product_addons', true),
			'label'     => 'Product addons'
		)
	);

	?>
	<p class="form-field">
        <label for="bundle_ids"><?php _e( 'Bundled products', 'woocommerce' ); ?></label>
        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="bundle_ids" name="bundle_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
            <?php
            $product_object = new WC_Product($post->ID);
            $product_ids = get_post_meta($post->ID, 'bundle_ids', true);
            if($product_ids && is_array($product_ids)) {
	            foreach ( $product_ids as $product_id ) {
	                $product = wc_get_product( $product_id );
	                if ( is_object( $product ) ) {
	                    echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
	                }
	            }
            }
            ?>
        </select> <?php echo wc_help_tip( __( 'If you want this product to be bundle you can just add bundled products here.', 'woocommerce' ) ); ?>
</p>
	<?php
}

// Save product fields
add_action( 'woocommerce_process_product_meta', 'woocommerce_process_product_meta_fields_save' );
function woocommerce_process_product_meta_fields_save( $post_id ){
    if(isset( $_POST['is_new'] )) {
    	update_post_meta( $post_id, 'is_new', $_POST['is_new'] );
    } else {
    	delete_post_meta( $post_id, 'is_new');
    }
    if(isset( $_POST['is_custom_message'] )) {
    	update_post_meta( $post_id, 'is_custom_message', $_POST['is_custom_message'] );
    } else {
    	delete_post_meta($post_id,'is_custom_message');
    }
    if(isset( $_POST['is_gift'] )) {
    	update_post_meta( $post_id, 'is_gift', $_POST['is_gift'] );
    } else {
    	delete_post_meta($post_id,'is_gift');
    }
    if(isset($_POST['bundle_ids']) && !empty($_POST['bundle_ids'])) {
    	update_post_meta( $post_id, 'bundle_ids', $_POST['bundle_ids'] );
    } else {
    	delete_post_meta( $post_id, 'bundle_ids' );
    }
    if(!isset($_POST['is_gift'])) {
    	update_post_meta( $post_id, 'is_custom_message', false );
    }
    if(isset( $_POST['is_customizable'] )) {
    	update_post_meta( $post_id, 'is_customizable', $_POST['is_customizable'] );
    } else {
    	delete_post_meta($post_id,'is_customizable');
    }
    if(isset( $_POST['product_addons'] )) {
    	update_post_meta( $post_id, 'product_addons', $_POST['product_addons'] );
    } else {
    	delete_post_meta($post_id,'product_addons');
    }
    if(isset( $_POST['is_discontinued'] )) {
    	update_post_meta( $post_id, 'is_discontinued', $_POST['is_discontinued'] );
    } else {
    	delete_post_meta($post_id,'is_discontinued');
    }
    $pricelists_ids = get_b2b_pricelists();
	if($pricelists_ids) {
		foreach($pricelists_ids as $pl_id) {
			if(!empty($pl)) {
    			if(isset( $_POST['b2b_price_' . $pl_id] )) {
			    	update_post_meta( $post_id, 'b2b_price_' . $pl_id, $_POST['b2b_price_' . $pl_id] );
			    }
			}
		}
	}
}

// Customize main query with filters
add_filter( 'pre_get_posts', 'hansa_custom_post_query' );
function hansa_custom_post_query( $query ) {
	if(is_admin() || !$query->is_main_query()) {
		return;
	}
	if($query->is_search()) {
		$query->set('post_type','product');
		if(!$query->get('orderby')) {
			$query->set('order','DESC');
			$query->set('orderby','relevance');
		}
		$tax_query = array(
            array(
				'taxonomy'  => 'product_visibility',
				'terms'     => array('exclude-from-search','exclude-from-catalog'),
				'field'     => 'slug',
				'operator'  => 'NOT IN'
			)
		);
		$query->set('tax_query',$tax_query);
	}
	if(isset($_GET['orderby']) && !empty($_GET['orderby'])) {
		switch ($_GET['orderby']) {
			case 'title':
				$query->set('orderby','title');
				$query->set('order','ASC');
				break;
			case 'rating':
				$query->set('orderby','meta_value_num');
				$query->set('meta_key','_wc_average_rating');
				break;
			case 'date':
				$query->set('orderby','date');
				break;
			case 'price-asc':
				$query->set('orderby','meta_value_num');
				$query->set('meta_key','_price');
				$query->set('order','ASC');
				break;
			case 'price-desc':
				$query->set('orderby','meta_value_num');
				$query->set('meta_key','_price');
				$query->set('order','DESC');
				break;
			default:
				$query->set('orderby','relevance');
				break;
		}
	}
	// $query->set('posts_per_page',intval(get_option('posts_per_page')));
	$posts_per_page = intval(get_option('posts_per_page'));
	if(isset($_GET['page_n'])) {
		if($_GET['page_n'] > 1) {
			$query->set('posts_per_page',$_GET['page_n'] * $posts_per_page);
		}
	} else {
		$query->set('posts_per_page',$posts_per_page);
	}

	if(!$query->is_main_query() || !is_tax('product_cat')) {
		return;
	}

	$f_alises = array(
		'subcat' => 'product_cat',
		'tags' => 'product_tag',
		'volumes' => 'volume',
		'grapes' => 'grape',
		'regions' => 'region',
		'vintages' => 'vintage',
		'countries' => 'country',
		'abvs' => 'abv',
		'producers' => 'producer'
	);
	$tax_query = $query->get( 'tax_query' );
	if ( ! is_array( $tax_query ) ) {
	    $tax_query = array();
	}
	foreach($f_alises as $alias => $value) {
		if(isset($_GET[$alias]) && !empty($_GET[$alias])) {
			$ids = explode(',',$_GET[$alias]);
			$tax_query[] = array(
				'taxonomy' => $value,
				'field' => 'term_id',
				'terms' => $ids,
	        	'operator'=> 'IN'
			);
		}
	}
	$query->set('tax_query',$tax_query);
	if(isset($_GET['price']) && !empty($_GET['price'])) {
		$prices = explode(',', urldecode($_GET['price']));
    	$min = $prices[0];
    	$max = $prices[1];
		$meta_query = array(
	        array(
	            'key' => '_price',
	            'value' => array($min, $max),
	            'compare' => 'BETWEEN',
	            'type' => 'NUMERIC'
	        )
	    );
		$query->set('meta_query', $meta_query);
	}
}

// Redirect WooCommerce Shop URL
add_action( 'template_redirect', 'hansa_shop_url_redirect' );
function hansa_shop_url_redirect() {
    if( is_shop() ){
        wp_redirect( site_url() ); // Assign custom internal page here
        exit();
    }
}

// Checkout customization
// Customize checkout fields
add_filter( 'woocommerce_checkout_fields', 'hansa_wc_edit_checkout_fields' );
function hansa_wc_edit_checkout_fields( $fields ) {
	$fields_types = array('shipping','billing');

	$user = wp_get_current_user();
	$is_b2b = in_array( 'b2b_customer', (array) $user->roles );

	foreach($fields_types as $field_type) {

		$fields[$field_type][$field_type . '_email']['priority'] = 10;
		$fields[$field_type][$field_type . '_first_name']['priority'] = 20;
		$fields[$field_type][$field_type . '_last_name']['priority'] = 30;
		$fields[$field_type][$field_type . '_address_1']['priority'] = 40;
		$fields[$field_type][$field_type . '_address_2']['priority'] = 50;
		$fields[$field_type][$field_type . '_city']['priority'] = 60;
		$fields[$field_type][$field_type . '_postcode']['priority'] = 70;
		$fields[$field_type][$field_type . '_country']['priority'] = 80;
		$fields[$field_type][$field_type . '_phone']['priority'] = 90;
		$fields[$field_type][$field_type . '_company']['priority'] = 100;

		$fields[$field_type][$field_type . '_email']['priority'] = 10;
		$fields[$field_type][$field_type . '_first_name']['priority'] = 20;
		$fields[$field_type][$field_type . '_last_name']['priority'] = 30;
		$fields[$field_type][$field_type . '_address_1']['priority'] = 40;
		$fields[$field_type][$field_type . '_address_2']['priority'] = 50;
		$fields[$field_type][$field_type . '_city']['priority'] = 60;
		$fields[$field_type][$field_type . '_postcode']['priority'] = 70;
		$fields[$field_type][$field_type . '_country']['priority'] = 80;
		$fields[$field_type][$field_type . '_phone']['priority'] = 90;
		$fields[$field_type][$field_type . '_company']['priority'] = 100;

		$fields[$field_type][$field_type . '_postcode']['required'] = false;

		$fields[$field_type][$field_type . '_email']['class'] = array('form-row-wide');
		$fields[$field_type][$field_type . '_address_1']['class'] = array('form-row-first');
		$fields[$field_type][$field_type . '_address_2']['class'] = array('form-row-last');
		$fields[$field_type][$field_type . '_city']['class'] = array('form-row-first');
		$fields[$field_type][$field_type . '_postcode']['class'] = array('form-row-last');
		$fields[$field_type][$field_type . '_company']['class'] = array('form-row-first');
		$fields[$field_type][$field_type . '_phone']['class'] = array('form-row-wide');

		 $fields[$field_type][$field_type . '_address_2']['label_class'] = array();

		$fields[$field_type][$field_type . '_address_1']['label'] = 'Address Line 1';
		$fields[$field_type][$field_type . '_address_2']['label'] = 'Address Line 2';
		$fields[$field_type][$field_type . '_email']['label'] = 'Email';
		$fields[$field_type][$field_type . '_phone']['label'] = 'Contact Number';
		$fields[$field_type][$field_type . '_city']['label'] = 'Town | City';
		$fields[$field_type][$field_type . '_postcode']['label'] = 'Post Code';
		$fields[$field_type][$field_type . '_country']['label'] = 'Country';
		$fields[$field_type][$field_type . '_company']['label'] = 'Company';

		$fields[$field_type][$field_type . '_address_1']['placeholder'] = 'Address Line 1';
		$fields[$field_type][$field_type . '_address_2']['placeholder'] = 'Address Line 2';
		$fields[$field_type][$field_type . '_phone']['placeholder'] = 'Contact Number';
		$fields[$field_type][$field_type . '_city']['placeholder'] = 'Select Town | City';
		$fields[$field_type][$field_type . '_company']['placeholder'] = 'Company Name';
		$fields[$field_type][$field_type . '_postcode']['placeholder'] = 'Post Code';

	    unset( $fields[$field_type][$field_type . '_state'] );

	    $fields[$field_type][$field_type . '_vat'] = array(
	        'label' => __('VAT No.', 'woocommerce'),
	        'placeholder' => _x('Company VAT No.', 'placeholder', 'woocommerce'),
	        'required' => false,
	        'clear' => false,
	        'type' => 'text',
	        'class' => array('form-row-last')
	    );

	    $fields[$field_type][$field_type . '_country']['priority'] = 80;

	    $fields[$field_type][$field_type . '_ref'] = array(
	        'label' => false,
	        'placeholder' => false,
	        'required' => false,
	        'clear' => false,
	        'type' => 'hidden',
	        'value' => get_user_meta(get_current_user_id(),'billing_ref',true),
	        'class' => array()
	    );
	}

    $fields['shipping']['shipping_country']['type'] = 'text';
    $fields['shipping']['shipping_country']['default'] = 'Malta';
    $fields['shipping']['shipping_country']['custom_attributes'] = array('readonly'=>'readonly');

	$fields['order']['order_comments']['placeholder'] = 'Leave a note for Hansa';
	$fields['order']['order_comments']['label'] = 'Special Requests | Comments';

    return $fields;
}

add_filter( 'woocommerce_default_address_fields' , 'QuadLayers_optional_postcode_checkout' );
function QuadLayers_optional_postcode_checkout( $p_fields ) {
	$p_fields['postcode']['required'] = false;
	return $p_fields;
}

// Customize order notes field
add_filter( 'woocommerce_form_field', 'hansa_filter_wc_form_field_textarea', 10, 4 );
function hansa_filter_wc_form_field_textarea( $field, $key, $args, $value ) {
    // Based on key
    if ( $key == 'order_comments' ) {
		$field = '<label for="' . esc_attr( $key ) . '">' . esc_html( $args['label'] ) . '</label>';
		$field .= '<textarea name="' . esc_attr( $key ) .'" id="' . esc_attr( $key ) .'" placeholder="' . $args['placeholder'] . '"></textarea>';
    }

    return $field;
}

// Set default billing country value
add_filter( 'default_checkout_shipping_country', 'hansa_change_default_checkout_country' );
function hansa_change_default_checkout_country() {
  return 'MT';
}

// Remove "optional" from field label
add_filter( 'woocommerce_form_field' , 'hansa_remove_checkout_optional_text', 10, 4 );
function hansa_remove_checkout_optional_text( $field, $key, $args, $value ) {
	if( is_checkout() && ! is_wc_endpoint_url() ) {
		$optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
		$field = str_replace( $optional, '', $field );
	}
return $field;
}

// Save custom cart data to order items
add_action( 'woocommerce_checkout_create_order_line_item', 'hansa_save_cart_custom_data_to_order', 10, 4 );
function hansa_save_cart_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	if(isset($values['custom_data'])) {
		foreach($values['custom_data'] as $key => $value) {
			$item->add_meta_data( $key, $value, true );
		}
	}
}

// Display order item meta labels
add_filter('woocommerce_order_item_display_meta_key', 'hansa_wc_order_item_display_meta_key', 20, 3 );
function hansa_wc_order_item_display_meta_key( $display_key, $meta, $item ) {
    // Change displayed label for specific order item meta key
    if( is_admin() && $item->get_type() === 'line_item' && $meta->key === 'customer_message' ) {
        $display_key = __("Message", "woocommerce" );
    }
    return $display_key;
}

// Move payment section
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'hansa_checkout_payments', 'woocommerce_checkout_payment', 20 );

// Remove default coupon form
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

// Update checkout fragments
add_filter( 'woocommerce_update_order_review_fragments', 'hansa_shipping_table_update');
function hansa_shipping_table_update( $fragments ) {
    ob_start();
    // Update shipping methods
    ?>
    <div class="hansa-shipping-table">
        <?php wc_cart_totals_shipping_html(); ?>
    </div>
    <?php
    $woocommerce_shipping_methods = ob_get_clean();

    // Update subtotal
    ob_start();
	wc_get_template('template-parts/checkout/subtotal.php');
	$woocommerce_subtotal = ob_get_clean();
	// Update total
    ob_start();
	wc_get_template('template-parts/checkout/total.php');
	$woocommerce_total = ob_get_clean();
	// Update coupon
    ob_start();
	wc_get_template('template-parts/checkout/coupon.php');
	$woocommerce_coupon = ob_get_clean();
	// Update redeem
    ob_start();
	wc_get_template('template-parts/checkout/redeem.php');
	$woocommerce_redeem = ob_get_clean();

	// Update shipping address
    ob_start();
	do_action( 'woocommerce_checkout_shipping' );
	$woocommerce_shipping_address = ob_get_clean();

	// Update address options
    ob_start();
	wc_get_template('template-parts/checkout/address-rows.php');
	$woocommerce_address_rows = ob_get_clean();


    $fragments['.hansa-shipping-table'] = $woocommerce_shipping_methods;
    $fragments['.woocommerce-shipping-fields'] = $woocommerce_shipping_address;
    $fragments['.subtotal-summary'] = $woocommerce_subtotal;
    $fragments['.checkout-total_holder'] = $woocommerce_total;
    $fragments['.promocode-wrap.coupon'] = $woocommerce_coupon;
    $fragments['.promocode-wrap.redeem'] = $woocommerce_redeem;
    $fragments['.checkout-options_rows'] = $woocommerce_address_rows;
    return $fragments;
}

// Add terms checkbox on checkout
add_action('woocommerce_review_order_before_order_total','hansa_checkout_terms_checkbox');
function hansa_checkout_terms_checkbox() {
	echo '<div class="gift-toggle checkout-summary_rows acceptance">
              <input type="checkbox" required id="terms-checkbox_input">
              <label for="terms-checkbox_input" class="terms-checkbox checkbox-label">I accept the <a href="#">Terms &amp; Conditions</a></label>
            </div>';
}

// Remove default checkout submit button
add_filter( 'woocommerce_order_button_html', 'hansa_remove_wc_order_button_html' );
function hansa_remove_wc_order_button_html() {
    return '';
}

// Add submit button to checkout sidebar
add_action( 'woocommerce_review_order_after_order_total', 'hansa_output_payment_button' );
function hansa_output_payment_button() {
    $order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
    echo '<button type="submit" class="button alt black-button not-accepted" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">Place order</button>';
}

// Customize thankyou page
remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);

// Hide coupon field on cart page
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );
function hide_coupon_field_on_cart( $enabled ) {

	if ( is_cart() ) {
		$enabled = false;
	}

	return $enabled;
}

// Show only free delivery if order earned properly total
add_filter( 'woocommerce_package_rates', 'hansa_show_free_delivery', 100 );
function hansa_show_free_delivery( $rates ) {
	$new_rates = array();
	foreach ( $rates as $rate_id => $rate ) {
		// Only modify rates if free_shipping is present.
		if ('free_shipping' === $rate->method_id) {
			$new_rates[ $rate_id ] = $rate;
			break;
		}
	}

	if ( ! empty( $new_rates ) ) {
		//Save local pickup if it's present.
		foreach ( $rates as $rate_id => $rate ) {
			if ('local_pickup' === $rate->method_id ) {
				$new_rates[ $rate_id ] = $rate;
				break;
			}
		}
		return $new_rates;
	}
	return $rates;
}

// Update free shipping availability depending on subtotal without taxes
add_filter('woocommerce_shipping_free_shipping_is_available', 'hansa_update_free_shipping_availability', 10, 2);
function hansa_update_free_shipping_availability($is_available, $package){
    if(is_checkout()) {
    	$free_shipping_settings = get_option('woocommerce_free_shipping_3_settings');
    	// $free_shipping_settings = get_option('woocommerce_free_shipping_5_settings');
	    if(intval($free_shipping_settings['min_amount']) <= WC()->cart->subtotal) {
			$is_available = true;
    	} else {
			$is_available = false;
		}
    }
	return $is_available;
}

// Change price if b2b customer
add_filter('woocommerce_product_get_price','hansa_b2b_price', 99, 2 );
function hansa_b2b_price( $price, $product ) {
	if(is_user_logged_in()) {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$is_b2b = in_array( 'b2b_customer', (array) $user->roles );
		$is_approved = get_user_meta($user_id, 'is_approved', true);
		if ($is_b2b && $is_approved) {
			$product_id = $product->get_id();
			$user_pricelist = get_user_meta($user_id,'user_pricelist',true);
			if(!empty($user_pricelist)) {
				$pl_data = explode('-',$user_pricelist);
				$pl_id = array_shift($pl_data);
				$b2b_price = (float)get_post_meta($product_id,'b2b_price_' . $pl_id, true);
				if($b2b_price > 0) {
					$price = $b2b_price;
				}
			}
		}
	}
    return (float)$price;
}

// Add price for custom products labeling
add_action( 'woocommerce_before_calculate_totals', 'hansa_set_b2b_cart_price', 20, 1 );
function hansa_set_b2b_cart_price( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    if(is_user_logged_in()) {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$is_b2b = in_array( 'b2b_customer', (array) $user->roles );
		$is_approved = get_user_meta($user_id, 'is_approved', true);
		if ($is_b2b && $is_approved) {
			$user_pricelist = get_user_meta($user_id,'user_pricelist',true);
			if(!empty($user_pricelist)) {
				$pl_data = explode('-',$user_pricelist);
				$pl_id = array_shift($pl_data);
			    foreach( $cart->get_cart() as $cart_item ) {
					$product_id = $cart_item['data']->get_id();
					$b2b_price = (float)get_post_meta($product_id,'b2b_price_' . $pl_id, true);
		        	if($b2b_price > 0) {
		        		$cart_item['data']->set_price( $b2b_price );
		        	}

			    }
			}
		}

	}
}

// Remove policy checkout text
remove_action('woocommerce_checkout_terms_and_conditions','wc_checkout_privacy_policy_text',20);

// Register order statuses
add_action( 'init', 'hansa_register_new_order_statuses' );
function hansa_register_new_order_statuses() {
    register_post_status( 'wc-synched', array(
        'label'                     => _x( 'Synched', 'Order status', 'woocommerce' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Synched <span class="count">(%s)</span>', 'Synched<span class="count">(%s)</span>', 'woocommerce' )
    ) );
}

// Add order statuses to array
add_filter( 'wc_order_statuses', 'hansa_new_wc_order_statuses' );
function hansa_new_wc_order_statuses( $order_statuses ) {
    $order_statuses['wc-synched'] = _x( 'Synched', 'Order status', 'woocommerce' );

    return $order_statuses;
}

// Save checkout meta to order
add_action('woocommerce_checkout_update_order_meta','hansa_save_order_custom_field');
function hansa_save_order_custom_field($order_id) {
	if(isset($_POST['billing_ref'])) {
		update_post_meta( $order_id, 'billing_ref', sanitize_text_field( $_POST['billing_ref']));
	}
	if(loyale_is_points_available()) {
		if(WC()->session->get('redeem_amount') != null) {
			update_post_meta( $order_id, 'redeem_amount', WC()->session->get('redeem_amount'));
		}
		if(isset($_POST['order_total_points'])) {
			update_post_meta( $order_id, 'order_total_points', (float)$_POST['order_total_points']);
		}
	}
}

// Display redeem in order totals in admin
add_action('woocommerce_admin_order_totals_after_discount','hansa_add_redeem_to_order_admin');

function hansa_add_redeem_to_order_admin($order_id) {
	$order = wc_get_order($order_id);
	if($order) {
		$redeem = get_post_meta($order_id,'redeem_amount',true);
		if($redeem && $redeem > 0) {
?>
	<tr>
		<td class="label"><?php esc_html_e( 'Redeem Amount:', 'woocommerce' ); ?></td>
		<td width="1%"></td>
		<td class="total">
			<?php echo '-' . wc_price( $redeem, array( 'currency' => $order->get_currency() ) ); ?>
		</td>
	</tr>
<?php
	}}
}

// Thank you page actions
add_action( 'woocommerce_thankyou', 'hansa_after_order_complete' );
function hansa_after_order_complete( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $user = get_user_by('id', $user_id);
	$loyale_customer_id = get_user_meta($user_id, 'loyale_customer_id', true);
	$is_b2b = in_array('b2b_customer', (array) $user->roles);
	if($loyale_customer_id && !$is_b2b) {
    	loyale_send_order( $order_id );
	}
    WC()->session->set('redeem_amount', null);

    // if ( $order && ('cod' === $order->get_payment_method()) ) {
    //     $order->update_status( 'completed' );
    // }
}

// Display order fields in admin
add_action( 'woocommerce_admin_order_data_after_billing_address', 'hansa_display_admin_order_meta', 10, 1 );
function hansa_display_admin_order_meta($order){
    echo '<p><strong>'.__('Customer Reference').':</strong><br/>' . get_post_meta( $order->get_id(), 'billing_ref', true ) . '</p>';
}

// Add total points in admin order
add_action('woocommerce_admin_order_totals_after_total','hansa_add_total_pts_to_order_admin');
function hansa_add_total_pts_to_order_admin($order_id) {
	$order = wc_get_order($order_id);
	if($order) {
		$total_pts = get_post_meta($order_id,'order_total_points',true);
		if($total_pts && $total_pts > 0) {
?>
	<tr>
		<td class="label"><?php esc_html_e( 'Points Earned:', 'woocommerce' ); ?></td>
		<td width="1%"></td>
		<td class="total">
			<?php echo '<p><strong>'.'+' . $total_pts . 'pts</strong></p>'; ?>
		</td>
	</tr>
<?php
	}}
}

// Change placeholder image
add_filter('woocommerce_placeholder_img_src', 'hansa_custom_wc_placeholder_img_src');
function hansa_custom_wc_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	// replace with path to your image
	$src = get_template_directory_uri() . '/assets/img/ProductPlaceholder.png';

	return $src;
}

// Extend Woocommerce search
add_filter( 'posts_search', 'hansa_wc_extended_search', 999, 2 );
function hansa_wc_extended_search( $search, $query ) {
    global $wpdb, $wp;

    $qvars = $wp->query_vars;

    if ( is_admin() || !isset($qvars['s']) || empty($qvars['s'])) {
        return $search;
    }

    // SETTINGS:
    $taxonomies = array('product_tag', 'product_cat', 'volume','vintage','country','abv','producer','grape', 'region'); // Here set your custom taxonomies in the array
    $meta_keys  = array('_sku'); // Here set your product meta key(s) in the array

    // Initializing tax query
    $tax_query = array(
    	'relation' => 'AND',
    	array(
			'taxonomy'  => 'product_visibility',
			'terms'     => array('exclude-from-search','exclude-from-catalog'),
			'field'     => 'slug',
			'operator'  => 'NOT IN'
		)
    );
    $tax_query_inner  = count($taxonomies) > 1 ? array('relation' => 'OR') : array();

    // Loop through taxonomies to set the tax query
    foreach( $taxonomies as $taxonomy ) {
        $tax_query_inner[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'name',
            'terms'    => esc_attr($qvars['s'])
        );
    }

    $tax_query[] = $tax_query_inner;

    // Get the product Ids from taxonomy(ies)
    $tax_query_ids = (array) get_posts( array(
        'posts_per_page'  => -1,
        'post_type'       => 'product',
        'post_status'     => get_query_statuses(),
        'fields'          => 'ids',
        'tax_query'       => $tax_query,
    ) );

    // Initializing meta query
    $meta_query = count($meta_keys) > 1 ? array('relation' => 'OR') : array();

    // Loop through taxonomies to set the tax query
    foreach( $meta_keys as $meta) {
        $meta_query[] = array(
            'key'     => $meta,
            'value'   => esc_attr($qvars['s']),
        );
    }

    // Get the product Ids from custom field(s)
    $meta_query_ids = (array) get_posts( array(
        'posts_per_page'  => -1,
        'post_type'       => 'product',
        'post_status'     => get_query_statuses(),
        'fields'          => 'ids',
        'meta_query'      => $meta_query,
        'tax_query' => array(
            array(
				'taxonomy'  => 'product_visibility',
				'terms'     => array('exclude-from-search','exclude-from-catalog'),
				'field'     => 'slug',
				'operator'  => 'NOT IN'
			)
        )
    ) );

    $product_ids = array_unique( array_merge( $tax_query_ids, $meta_query_ids ) ); // Merge Ids in one array  with unique Ids
    if ( sizeof( $product_ids ) > 0 ) {
        $search = str_replace( 'AND (((', "AND ((({$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . ")) OR (", $search);
    }
    return $search;
}

// Get extended search MySQL string
function hansa_get_extended_search_params($s) {
	global $wpdb;
	$search = '';
    $taxonomies = array('product_tag', 'product_cat', 'volume','vintage','country','abv','producer','grape', 'region'); // Here set your custom taxonomies in the array
    $meta_keys  = array('_sku'); // Here set your product meta key(s) in the array

    // Initializing tax query
    $tax_query = array(
    	'relation' => 'AND',
    	array(
			'taxonomy'  => 'product_visibility',
			'terms'     => array('exclude-from-search','exclude-from-catalog'),
			'field'     => 'slug',
			'operator'  => 'NOT IN'
		)
    );
    $tax_query_inner  = count($taxonomies) > 1 ? array('relation' => 'OR') : array();

    // Loop through taxonomies to set the tax query
    foreach( $taxonomies as $taxonomy ) {
        $tax_query_inner[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'name',
            'terms'    => esc_attr($s),
        );
    }

    $tax_query[] = $tax_query_inner;

    // Get the product Ids from taxonomy(ies)
    $tax_query_ids = (array) get_posts( array(
        'posts_per_page'  => -1,
        'post_type'       => 'product',
        'post_status'     => get_query_statuses(),
        'fields'          => 'ids',
        'tax_query'       => $tax_query,
    ) );

    // Initializing meta query
    $meta_query = count($meta_keys) > 1 ? array('relation' => 'OR') : array();

    // Loop through taxonomies to set the tax query
    foreach( $meta_keys as $meta ) {
        $meta_query[] = array(
            'key'     => $meta,
            'value'   => esc_attr($s),
        );
    }

    // Get the product Ids from custom field(s)
    $meta_query_ids = (array) get_posts( array(
        'posts_per_page'  => -1,
        'post_type'       => 'product',
        'post_status'     => get_query_statuses(),
        'fields'          => 'ids',
        'meta_query'      => $meta_query,
        'tax_query' => array(
            array(
				'taxonomy'  => 'product_visibility',
				'terms'     => array('exclude-from-search','exclude-from-catalog'),
				'field'     => 'slug',
				'operator'  => 'NOT IN'
			)
        )
    ) );

    $product_ids = array_unique( array_merge( $tax_query_ids, $meta_query_ids ) ); // Merge Ids in one array  with unique Ids

    if ( sizeof( $product_ids ) > 0 ) {
        $search = "{$wpdb->posts}.ID IN (" . implode( ',', $product_ids ) . ")";
    }
    return $search;
}

// Extend WP_Query params
add_filter( 'posts_where', 'hansa_extend_wp_query_where', 10, 2 );
function hansa_extend_wp_query_where( $where, $wp_query ) {
    if ( $extend_where = $wp_query->get( 'extend_where' ) ) {
        $where .= " OR " . $extend_where;
    }
    return $where;
}

// Add addons on add to cart products with addons
add_filter( 'woocommerce_add_to_cart', 'hansa_add_product_addons_to_cart', 10, 3);
function hansa_add_product_addons_to_cart( $cart_item_key, $product_id, $request_quantity ) {
	$is_addons = get_post_meta($product_id,'product_addons',true);
	if($is_addons) {
		$addons = explode(',', $is_addons);
		if($addons) {
			foreach($addons as $addon_sku) {
				$addon_sku = trim($addon_sku);
				$addon_id = wc_get_product_id_by_sku($addon_sku);
				if($addon_id) {
					$cart_product_id = is_product_in_cart($addon_id);
					$cart_item = WC()->cart->cart_contents[$cart_item_key];
					if(isset($cart_item['custom_data']['addons'])) {
						if(!in_array($addon_id,$cart_item['custom_data']['addons'])) {
							array_push($cart_item['custom_data']['addons'],$addon_id);
						}
					} else {
						$cart_item['custom_data']['addons'] = array();
						array_push($cart_item['custom_data']['addons'],$addon_id);
					}
					WC()->cart->cart_contents[$cart_item_key] = $cart_item;
					WC()->cart->set_session();
					if(!$cart_product_id) {
						WC()->cart->add_to_cart( $addon_id, $request_quantity );
					} else {
						WC()->cart->set_quantity( $cart_product_id, hansa_get_cart_addons_items_q($addon_id) );
					}
				}
			}
		}
	}
}

// Update addon quantity on update quantity of product in cart
add_action( 'woocommerce_after_cart_item_quantity_update', 'hansa_update_addons_quantity', 10, 3);
function hansa_update_addons_quantity( $cart_item_key, $quantity, $old_quantity) {
    $product_id = 0;
    $current_cart_item = WC()->cart->cart_contents[$cart_item_key];
	if(isset($current_cart_item['custom_data']['addons'])) {
		$addons_ids = $current_cart_item['custom_data']['addons'];
		foreach($addons_ids as $addon_id) {
			if($addon_id) {
				foreach ( WC()->cart->get_cart() as $ci_key => $cart_item ) {
			    	if($cart_item['product_id'] == $addon_id) {
			    		$cart_item_q = 0;
			    		foreach ( WC()->cart->get_cart() as $ci_key2 => $cart_item2 ) {
			    			if(isset($cart_item2['custom_data']['addons'])) {
								$addons_ids2 = $cart_item2['custom_data']['addons'];
								if(in_array($addon_id, $addons_ids2)) {
									$cart_item_q += $cart_item2['quantity'];
								}
			    			}
			    		}
						WC()->cart->cart_contents[ $ci_key ]['quantity'] = $cart_item_q;
			    	}
				}
			}
		}
	} else {
		$is_addons = get_post_meta($current_cart_item['product_id'],'product_addons',true);
		if($is_addons) {
			$addons = explode(',', $is_addons);
			if($addons) {
				foreach($addons as $addon_sku) {
					$addon_id = wc_get_product_id_by_sku($addon_sku);
					if(!is_product_in_cart($addon_id)) {
						WC()->cart->add_to_cart( $addon_id, $quantity );
					}
				}
			}
		}
	}
}

add_action( 'woocommerce_remove_cart_item', 'hansa_remove_cart_additionals', 10, 2 );
function hansa_remove_cart_additionals( $cart_item_key, $cart ) {
    $product_id = $cart->cart_contents[ $cart_item_key ]['product_id'];
    $removed_cart_item = $cart->cart_contents[ $cart_item_key ];
	$is_addons = get_post_meta($product_id,'product_addons',true);
	$q = $removed_cart_item['quantity'];
	$addons = array();

    if(!has_term('champagne-labels','product_cat',$product_id) && !has_term('empties','product_cat',$product_id) && !has_term('empties-miscellaneous','product_cat',$product_id)) {
		if($is_addons) {
			$addons = explode(',',$is_addons);
			$addons_ids = array();
			$is_addons_ids = isset($removed_cart_item['custom_data']['addons']);
			if($is_addons_ids) {
				$addons_ids = $removed_cart_item['custom_data']['addons'];
			}
		}

	    foreach($cart->get_cart() as $item_key => $cart_item) {
	    	$item_q = $cart_item['quantity'];
	    	if(isset($cart_item['custom_data']['labeled_product_id'])) {
	    		$labeled_id = $cart_item['custom_data']['labeled_product_id'];
	    		if($product_id == $labeled_id) {
	    			$cart->remove_cart_item($item_key);
	    		}
	    	}

	    	if($is_addons && !empty($addons_ids)) {
	    		$_product_id = $cart->cart_contents[ $item_key ]['product_id'];
	    		if(in_array($_product_id, $addons_ids)) {
	    			if($q < $item_q) {
	    				$cart->set_quantity($item_key, $item_q - $q);
	    			} else {
	    				$cart->remove_cart_item($item_key);
	    			}
	    		}
	    	}
	    }
    }
};

// Edit email settings
add_filter('woocommerce_email_settings','hansa_add_email_settings');
function hansa_add_email_settings($settings) {
	$footer_img_array = array(
		'title'       => __( 'Footer image', 'woocommerce' ),
		'desc'        => __( 'URL to an image you want to show in the email footer. Upload images using the media uploader (Admin > Media).', 'woocommerce' ),
		'id'          => 'woocommerce_email_footer_image',
		'type'        => 'text',
		'css'         => 'min-width:400px;',
		'placeholder' => __( 'N/A', 'woocommerce' ),
		'default'     => '',
		'autoload'    => false,
		'desc_tip'    => true,
	);
	$old_settings = $settings;
	$settings_left = array_slice( $old_settings, 0, 10);
	$settings_right = array_slice( $old_settings, 10, count($old_settings) - 1);
	array_push($settings_left,$footer_img_array);
	$settings = array_merge($settings_left,$settings_right);
	return $settings;
}

// Send cancelled order email to customer
add_action('woocommerce_order_status_changed', 'hansa_send_custom_email_notifications', 10, 4 );
function hansa_send_custom_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled'){
        $wc_emails = WC()->mailer()->get_emails();
        $customer_email = $order->get_billing_email();
    }

    if ( $new_status == 'cancelled' ) {
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    }
}

// Remove stock from single product page
add_filter('woocommerce_get_stock_html','hansa_remove_stock_text', 10, 2);
function hansa_remove_stock_text($html, $text){
	return '';
}

// Add WC custom settings
add_filter( 'woocommerce_settings_tabs_array', 'hansa_add_settings_tab', 50 );
function hansa_add_settings_tab( $settings_tabs ) {
    $settings_tabs['settings_tab_import'] = __( 'Import', 'woocommerce-settings-tab-import' );
    return $settings_tabs;
}

add_action( 'woocommerce_settings_tabs_settings_tab_import', 'hansa_settings_tab' );
function hansa_settings_tab() {
	woocommerce_admin_fields( hansa_get_wc_import_settings() );
}

add_action( 'woocommerce_update_options_settings_tab_import', 'hansa_update_settings' );
function hansa_update_settings() {
	woocommerce_update_options( hansa_get_wc_import_settings() );
}

function hansa_get_wc_import_settings() {
    $settings = array(
        'section_title' => array(
            'name'     => __( 'B2B import settings', 'woocommerce-settings-tab-import' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'wc_settings_tab_import_section_title'
        ),
        'description' => array(
            'name' => __( 'Pricelists list', 'woocommerce-settings-tab-import' ),
            'type' => 'textarea',
            'desc' => __( 'Enter new price list or remove the existing one. One listname per line.', 'woocommerce-settings-tab-demo' ),
            'id'   => 'wc_settings_tab_import_pricelists',
            'custom_attributes' => array(
            	'rows' => 13
            )
        ),
        'section_end' => array(
             'type' => 'sectionend',
             'id' => 'wc_settings_tab_import_section_end'
        )
    );

    return apply_filters( 'wc_settings_tab_import_settings', $settings );
}

// Set default WC catalog ordering
add_filter('woocommerce_get_catalog_ordering_args', 'hansa_wc_catalog_orderby');
function hansa_wc_catalog_orderby( $args ) {
    $args['orderby'] = 'relevance';
    $args['order'] = 'desc';
    return $args;
}

// Add custom data to Label product in cart
add_action('woocommerce_after_cart_item_name','hansa_add_to_cart_custom_label_data', 10, 2);
function hansa_add_to_cart_custom_label_data($cart_item, $cart_item_key) {
	if(isset($cart_item['custom_data']['labeled_product_id'])) {
		$labeled_id = $cart_item['custom_data']['labeled_product_id'];
		$labeled_product = wc_get_product($labeled_id);
		if($labeled_product) {
			echo '<p>(' . $labeled_product->get_title() . ')</p>';
		}
	}
	if(isset($cart_item['custom_data']['label_1'])) {
			echo '<p>Line 1: <strong>' . $cart_item['custom_data']['label_1'] . '</strong></p>';
	}
	if(isset($cart_item['custom_data']['label_2'])) {
			echo '<p>Line 2: <strong>' . $cart_item['custom_data']['label_2'] . '</strong></p>';
	}
}

// Notify admin when a new customer account is created
add_filter( 'woocommerce_email_recipient_customer_new_account', 'hansa_new_account_sent_to_admin', 10, 2 );
function hansa_new_account_sent_to_admin( $recipient, $customer ){
    $is_b2b = in_array('b2b_customer',(array)$customer->roles);
	$new_account_email = WC()->mailer()->get_emails()['WC_Email_Customer_New_Account'];
	$new_recipient = $new_account_email->settings['na_recipient'];
    if(!$is_b2b) {
    	return false;
    }
	if(!empty($new_recipient)) {
		return $new_recipient;
	}
    return get_bloginfo('admin_email');
}

// Add new account email sending setting
add_action( 'woocommerce_settings_api_form_fields_customer_new_account', 'hansa_add_new_account_email_field',10,1);
function hansa_add_new_account_email_field($fields) {
	$fields['na_recipient'] = array(
		'title'=>'Recipient(s)',
		'description'=>'Email(s) to which new account notification will be sent.',
		'type'=>'text',
		'default'=> ''
	);
	return $fields;
}

// Update cart count in header
add_filter('woocommerce_add_to_cart_fragments', 'hansa_header_add_to_cart_fragment');
function hansa_header_add_to_cart_fragment( $fragments ) {
	ob_start();
?>
	<a href="<?php echo wc_get_cart_url(); ?>" class="header-cart mobile-header-cart">
	    <span class="minicart-holder"></span>
	    <span class="minicart-itemscount"><?php echo WC()->cart->get_cart_contents_count() - hansa_count_cart_cat_items('empties'); ?></span>
	</a>
<?php
	$fragments['a.mobile-header-cart'] = ob_get_clean();
	ob_start();
?>
	<a href="<?php echo wc_get_cart_url(); ?>" class="header-cart desktop-header-cart">
      <span class="minicart-holder"></span>
      <span class="minicart-itemscount"><?php echo WC()->cart->get_cart_contents_count() - hansa_count_cart_cat_items('empties'); ?></span>
    </a>
<?php
	$fragments['a.desktop-header-cart'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_get_order_item_totals', 'hansa_order_item_totals', 10, 3 );
function hansa_order_item_totals( $total_rows, $order, $tax_display ){

	$total_rows['cart_subtotal']['label'] = 'Subtotal excl. Tax';
	$total_rows['cart_subtotal']['value'] = wc_price($order->get_subtotal());
	$total_rows['tax']['label'] = 'Tax';
	$total_rows['tax']['value'] = wc_price($order->get_total_tax());
	$total_rows['order_total']['label'] = 'Total';

	unset($total_rows['payment_method']);

	$order_id = $order->get_id();
	$points = (int)get_post_meta($order_id,'order_total_points',true);

	if($points > 0) {
		$total_rows['order_points'] = array(
			'label' => 'Points',
			'value' => '+' . $points . 'pts'
		);
	}

	$new_total_rows = array();
	$new_total_rows['cart_subtotal'] = $total_rows['cart_subtotal'];
	$new_total_rows['tax'] = $total_rows['tax'];
	$new_total_rows['shipping'] = array(
		'label' => 'Delivery Method',
		'value' => $order-> get_shipping_method()
	);
	$new_total_rows['shipping_cost'] = array(
		'label' => 'Delivery Charge',
		'value' => wc_price($order->get_shipping_total())
	);
	$notes = $order->get_customer_note();
	$new_total_rows['order_note'] = array(
		'label' => 'Note',
		'value' => $notes ? $notes : 'Empty'
	);

	$redeem_amount = floatval(get_post_meta($order_id,'redeem_amount',true));
	if($redeem_amount > 0) {
		$new_total_rows['redeem_amount'] = array(
			'label' => 'Redeem Amount',
			'value' => '-' . wc_price($redeem_amount)
		);
	}

	$new_total_rows['order_total'] = $total_rows['order_total'];
	if($points > 0) {
		$new_total_rows['order_points'] = $total_rows['order_points'];
	}

    return $new_total_rows;
}

add_action( 'woocommerce_customer_reset_password', 'woocommerce_new_pass_redirect' );
function woocommerce_new_pass_redirect( $user ) {
  wp_redirect( get_permalink(229));
  exit;
}

// Customize login errors
add_filter( 'login_errors', 'hansa_login_errors_customizer', 10, 1);
function hansa_login_errors_customizer( $error ) {

    // Incorrect password.
    // Default: '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?'
    $error = str_replace('<a href="%2$s">Lost your password</a>?', '', $error);
    if ( strripos($error, 'Lost') ) {
        $error = '<strong>Error</strong>: The password or login you entered is incorrect.';
    }
    return $error;
}

add_filter( 'woocommerce_my_account_my_orders_query', 'unset_pending_payment_orders_from_my_account', 10, 1 );
function unset_pending_payment_orders_from_my_account( $args ) {
    $statuses = wc_get_order_statuses();
    unset( $statuses['wc-pending'] );
    $args['post_status'] = array_keys( $statuses );
    return $args;
}

remove_action( 'woocommerce_account_edit-account_endpoint', array( 'WC_TP_Gateway', 'update_credit_card_details_info' ), 10);

add_filter('woocommerce_checkout_update_order_review', 'clear_wc_shipping_rates_cache');
function clear_wc_shipping_rates_cache(){
    $packages = WC()->cart->get_shipping_packages();
    foreach ($packages as $key => $value) {
        $shipping_session = "shipping_for_package_$key";
        unset(WC()->session->$shipping_session);
    }
}

// Force 404 on hidden from catalog products
add_action( 'wp', 'hansa_404_hidden_products' );
function hansa_404_hidden_products() {
	global $wp_query;
	$product = wc_get_product(get_the_ID());
	if ($product) {
		if($product->get_catalog_visibility() == 'hidden') {
			$wp_query->set_404();
			status_header(404);
		}
	}
}

//remove product with zero quantity from cart on checkout page
add_action( 'woocommerce_before_checkout_form', 'remove_zero_quantity_product');
function remove_zero_quantity_product(){
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];
        $product_cart_id = WC()->cart->generate_cart_id( $product_id );
        $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
        if($quantity == 0) {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }

    // Final check of addons quantity
    $addons_list = array();
    $addons_ids = array();
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product_id = $cart_item['product_id'];
		$is_addons = get_post_meta($product_id,'product_addons',true);
		if($is_addons) {
			$addons = explode(',', $is_addons);
			if($addons) {
				foreach($addons as $addon_sku) {
					$addon_sku = trim($addon_sku);
					$addon_id = wc_get_product_id_by_sku($addon_sku);
					if($addon_id) {
						if(!in_array($addon_id, $addons_ids)) {
							$addons_list[$addon_id] = $cart_item['quantity'];
							array_push($addons_ids, $addon_id);
						} else {
							$addons_list[$addon_id] += $cart_item['quantity'];
						}
					}
				}
			}
		}
	}
	if($addons_list) {
    	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        	$product_id = $cart_item['product_id'];
        	if(in_array($product_id, $addons_ids)) {
        		if($cart_item['quantity'] != $addons_list[$product_id]) {
					WC()->cart->set_quantity( $cart_item_key, $addons_list[$product_id] );
        		}
        	}
    	}
	}
}

 /**
 * WooCommerce login not working on first try fix
 */
add_filter('nonce_user_logged_out', function($uid, $action) {
  if ($uid && $uid != 0 && $action && $action == 'woocommerce-login') {
     $uid = 0;
  }
   return $uid;
}, 100, 2);


add_filter('woocommerce_login_redirect', 'ui_wc_login_redirect', 99, 2);
function ui_wc_login_redirect($url, $user) {
  return site_url( '/my-account' );
}

add_action( 'template_redirect', 'hansa_define_default_payment_gateway' );
function hansa_define_default_payment_gateway(){
    if( is_checkout() && ! is_wc_endpoint_url() ) {
        // HERE define the default payment gateway ID
        $default_payment_id = 'tp_gateway';

        WC()->session->set( 'chosen_payment_method', $default_payment_id );
    }
}
