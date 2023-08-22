<?php
// Get min and max price of products
function get_minmax_price() {
	global $wpdb;

	$args = wc()->query->get_main_query();

	$tax_query  = isset( $args->tax_query->queries ) ? $args->tax_query->queries : array();
	$meta_query = isset( $args->query_vars['meta_query'] ) ? $args->query_vars['meta_query'] : array();

	foreach ( $meta_query + $tax_query as $key => $query ) {
		if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
			unset( $meta_query[ $key ] );
		}
	}

	$meta_query = new \WP_Meta_Query( $meta_query );
	$tax_query  = new \WP_Tax_Query( $tax_query );

	$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
	$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

	$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
	$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
	$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('product')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('_price')
			AND price_meta.meta_value > '' ";
	$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

	$search = \WC_Query::get_main_search_query_sql();
	if ( $search ) {
		$sql .= ' AND ' . $search;
	}

	$prices = $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.

	return [
		'min' => floor( $prices->min_price ),
		'max' => ceil( $prices->max_price )
	];
}

// Check if product in cart
function is_product_in_cart($product_id) {
	$in_cart = false;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    if( $cart_item['product_id'] == $product_id){
	    	$in_cart = $cart_item_key;
	    	break;
	    }
	}
	return $in_cart;
}

function hansa_get_cart_addons_items_q($addon_id) {
	$cart_item_q = 0;
	foreach ( WC()->cart->get_cart() as $ci_key => $cart_item ) {
		if(isset($cart_item['custom_data']['addons'])) {
			$addons_ids = $cart_item['custom_data']['addons'];
			if(in_array($addon_id, $addons_ids)) {
				$cart_item_q += $cart_item['quantity'];
			}
		}
	}
	return $cart_item_q;
}

// Check if product in order
function is_product_in_order($order_id, $product_id) {
	$in_order = false;
	$order = wc_get_order($order_id);
	foreach ( $order->get_items() as $item_id => $item ) {
		$item_product_id = $item->get_product_id();
	    if( $item_product_id == $product_id){
	    	$in_order = true;
	    }
	}
	return $in_order;
}

// Get cart item custom data
function get_cart_item_custom_data($item_key, $field) {
	$value = '';
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    if( $cart_item_key == $item_key){
	    	if(isset($cart_item['custom_data'][$field]) && !empty($cart_item['custom_data'][$field])) {
	        	$value = $cart_item['custom_data'][$field];
	        }
	    }
	}
	return $value;
}

// Check if coupon valid for cart
function is_coupon_valid( $coupon_code ) {
    $coupon = new WC_Coupon( $coupon_code );   
    $discounts = new WC_Discounts( WC()->cart );
    $response = $discounts->is_coupon_valid( $coupon );
    return is_wp_error( $response ) ? false : true;     
}

/**
 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
 * to the end of the array.
 *
 * @param array $array
 * @param string $key
 * @param array $new
 *
 * @return array
 */
function array_insert_after( array $array, $key, array $new ) {
	$keys = array_keys( $array );
	$index = array_search( $key, $keys );
	$pos = false === $index ? count( $array ) : $index + 1;
	return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

function get_b2b_pricelists() {
	$pricelists = array();
	$pricelists = array_map('trim',explode(PHP_EOL,get_option('wc_settings_tab_import_pricelists')));
	$pricelists = array_map('htmlspecialchars_decode',$pricelists);
	$pricelists_ids = array_map(function($pl){
        return $pl[0];
    },$pricelists);
	return $pricelists_ids;
}

function get_b2b_pricelist_names() {
	$pricelists = array();
	$pricelists = array_map('trim',explode(PHP_EOL,get_option('wc_settings_tab_import_pricelists')));
	$pricelists = array_map('htmlspecialchars_decode',$pricelists);
	return $pricelists;
}

function get_query_statuses() {
	$statuses = array('publish');
	if(current_user_can('administrator')) {
		array_push($statuses, 'private');
	}
	return $statuses;
}

function product_deepest_level_cat($post_categories) {
    foreach ($post_categories as $category) {
        $cat_ids[] = $category->term_id;
    }   
    $tree_args = array(
        'current_category' => $cat_ids,
        'depth'             => 50,
        'hierarchical'     => true,
        'echo' => 0,
        );                  
                
    $category_list = wp_list_categories($tree_args);                
    $dom = new DOMDocument;
    @$dom->loadHTML($category_list);
    $links = $dom->getElementsByTagName('a');
    $new_cat_array = array();
    foreach ($links as $link) { 
        $menu = get_term_by('name', $link->nodeValue, 'category');
        if (in_array($menu->term_id, $cat_ids)) {
            $deepest_cat_id = $menu->term_id;
        }                   
    }           
    return $deepest_cat_id;
}

function get_user_product_price_field() {
	$field = '_price';
	if(is_user_logged_in()) {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$is_approved = get_user_meta($user_id, 'is_approved', true);
	  	if ( in_array( 'b2b_customer', (array) $user->roles ) && $is_approved) {
	  		$user_pricelist = get_user_meta($user_id,'user_pricelist',true);
			if(!empty($user_pricelist)) {
				$pl_data = explode('-',$user_pricelist);
				$pl_id = array_shift($pl_data);
				$field = 'b2b_price_' . $pl_id;
			}
	  	}
	}
	return $field;
}

function hansa_set_primary_term($taxonomy, $postID, $term){
	if ( class_exists('WPSEO_Primary_Term') ) {
		// Set primary term.
		$primaryTermObject = new WPSEO_Primary_Term($taxonomy, $postID);
		$primaryTermObject->set_primary_term($term);
	}
}

function hansa_get_product_top_cats($product_id) {
	$top_cats = array();
	$cats = get_the_terms($product_id, 'product_cat');
	foreach($cats as $cat) {
		$cat_id = $cat->term_id;
		if(!get_ancestors($cat_id,'product_cat')) {
			array_push($top_cats, $cat_id);
		}
	}
	return $top_cats;
}

function hansa_get_addons_total($order_id = 0) {
	$addon_total = 0;
	if($order_id == 0) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
			if($is_addon) {
				$addon = wc_get_product($product_id);
				$addon_price = $addon->get_price();
				$addon_total += $addon_price * $cart_item['quantity'];
			}
		}
	} else {
		$order = wc_get_order($order_id);
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
			if($is_addon) {
				$addon = wc_get_product($product_id);
				$addon_price = $addon->get_price();
				$addon_total += $addon_price * $item->get_quantity();
			}
		}
	}
	return $addon_total;
}

function hansa_count_cart_cat_items( $cat_name ) {
    $cat_count = 0; 
    foreach(WC()->cart->get_cart() as $cart_item) {
        if( has_term( $cat_name, 'product_cat', $cart_item['product_id'])) {
            $cat_count += $cart_item['quantity'];
        }  
    }
    return $cat_count;
}

function hansa_is_b2b_user() {
	$is_b2b = false;
	if(is_user_logged_in()) {
		$user = wp_get_current_user();
		$is_b2b = in_array( 'b2b_customer', (array) $user->roles );
	}
	return $is_b2b;
}