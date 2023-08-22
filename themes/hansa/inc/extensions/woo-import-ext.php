<?php
/**
* Source: https://github.com/woocommerce/woocommerce/wiki/Product-CSV-Importer-&-Exporter#adding-custom-import-columns-developers
**/

/**
 * Register the 'Custom Column' column in the importer.
 *
 * @param array $options
 * @return array $options
 */
function add_column_to_importer( $options ) {

	// column slug => column name
	$options['volume'] = 'Volume';
	$options['supplier'] = 'Supplier';
	$options['vintage'] = 'Vintage';
	$options['country'] = 'Country';
	$options['abv'] = 'ABV';
	$options['producer'] = 'Producer';
	$options['grape'] = 'Grape';
	$options['region'] = 'Region';
	$options['slug'] = 'Slug';
	$options['addons'] = 'Addons';

	return $options;
}
add_filter( 'woocommerce_csv_product_import_mapping_options', 'add_column_to_importer' );

/**
 * Add automatic mapping support for 'Custom Column'. 
 * This will automatically select the correct mapping for columns named 'Custom Column' or 'custom column'.
 *
 * @param array $columns
 * @return array $columns
 */
function add_column_to_mapping_screen( $columns ) {
	
	// potential column name => column slug
	$columns['productCode'] = 'sku';
	$columns['productName'] = 'name';
	$columns['ucIIvolume'] = 'volume';
	$columns['Year'] = 'vintage';
	$columns['country'] = 'country';
	$columns['ucIIalcohol'] = 'abv';
	$columns['ucIIProducer'] = 'producer';
	$columns['ucIIGrapeVariety'] = 'grape';

	return $columns;
}
add_filter( 'woocommerce_csv_product_import_mapping_default_columns', 'add_column_to_mapping_screen' );

/**
 * Set taxonomy.
 *
 * @param  array  $parsed_data
 * @return array
 */

add_filter( 'woocommerce_product_import_inserted_product_object', 'woocommerce_add_custom_taxonomy', 10, 2 );
function woocommerce_add_custom_taxonomy( $product, $data ) {
  
	// set a variable with your custom taxonomy slug
	$custom_taxes = array('volume','supplier','vintage','country','abv','producer','grape', 'region');
	if ( is_a( $product, 'WC_Product' ) ) {
		foreach($custom_taxes as $custom_taxonomy) {
			if( ! empty( $data[ $custom_taxonomy ] ) ) {
	    		$custom_taxonomy_values = $data[ $custom_taxonomy ];
	    		$product->save();
	    		$custom_taxonomy_values = explode(",", $custom_taxonomy_values);
	    		$terms = array();
	    		foreach($custom_taxonomy_values as $custom_taxonomy_value){
	        		if(!get_term_by('name', $custom_taxonomy_value, $custom_taxonomy)){
	            			$custom_taxonomy_args= array(
	                			'cat_name' => $custom_taxonomy_value,
	                			'taxonomy' => $custom_taxonomy,
	            			);
	            			$custom_taxonomy_value_cat = wp_insert_category($custom_taxonomy_args);
	            			array_push($terms, $custom_taxonomy_value_cat);
	        		}else{
	            			$custom_taxonomy_value_cat = get_term_by('name', $custom_taxonomy_value, $custom_taxonomy)->term_id;
	            			array_push($terms, $custom_taxonomy_value_cat);
	        		}
	    		}
				wp_set_object_terms( $product->get_id(),  $terms, $custom_taxonomy );
			}
		}
		if( ! empty( $data[ 'slug' ] ) ) {
			$product->set_slug($data[ 'slug' ]);
			$product->save();
		}
	}
	return $product;
}

add_shortcode('import_saved_addr_csv_form', 'import_saved_addr_csv_form');
function import_saved_addr_csv_form() {
	global $wpdb;
  if (isset($_POST['submit'])) {
    $csv_file = $_FILES['csv_file'];
    $start_from = intval($_POST['start_from']) > 0 ? intval($_POST['start_from']) : 1;
    $csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));
    $i = $start_from;

    $total_rows = count($csv_to_array);

    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $file_path = $upload_dir . '/user-saved-addr-import-' . date('d-m-Y') . '.log';
    $file = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] " . 'Saved address import started.'. "\n" );
    fclose($file);

    foreach ($csv_to_array as $key => $value) {
			if(($i > $start_from + 2000) || ($i > $total_rows)) {
				break;
			}
			if (empty($key) || $i < $start_from) {
				continue;
			    $file = fopen( $file_path, 'a');
			    fwrite( $file, '  [' . current_time( 'mysql' ) . "] Saved address #" . $i . " skipped". "\n" ); 
			    fclose($file);
			}
			$user_id = email_exists( $value[2] );
			if ($user_id) {
				$addr_data = array(
					'shipping_first_name' => esc_html($value[5]),
					'shipping_last_name' => esc_html($value[6]),
					'shipping_address_1' => esc_html($value[10]),
					'shipping_address_2' => esc_html($value[11]),
					'shipping_city' => esc_html($value[13]),
					'shipping_country' => 'MT',
					'shipping_postcode' => esc_html($value[15]),
					'shipping_vat' => esc_html($value[9]),
					'shipping_reg_number' => esc_html($value[8]),
					'shipping_company' => esc_html($value[7]),
					'shipping_phone' => esc_html($value[16]),
					'shipping_mobile' => esc_html($value[17]),
				);

				$addr_data = array(
					'userdata' => serialize($addr_data),
					'userid' => $user_id,
					'type' => 'shipping'
				);

				$tablename = $wpdb->prefix . 'wc_multiple_addresses';
				$wpdb->insert($tablename,$addr_data,array('%s','%d','%s'));

		    $file = fopen( $file_path, 'a');
		    fwrite( $file, '  [' . current_time( 'mysql' ) . "] Saved address #" . $wpdb->insert_id . " imported". "\n" ); 
		    fclose($file);
		    update_option('hansa_saved_addr_import_total',$i);
			}
			$i++;
		}
    $file  = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] Import successful. Saved addresses added: " . $i . "\n\n" ); 
    fclose($file);
  } else {
  	$start_from = get_option('hansa_saved_addr_import_total') > 0 ? get_option('hansa_saved_addr_import_total') : 1;
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<input type="number" name="start_from" value="' . $start_from . '">';
    echo '<input type="file" name="csv_file">';
    echo '<input type="submit" name="submit" value="submit">';
    echo '</form>';
  }
}

add_shortcode('import_user_meta_csv_form', 'import_user_meta_csv_form');
function import_user_meta_csv_form() {
  if (isset($_POST['submit'])) {
    $csv_file = $_FILES['csv_file'];
    $start_from = intval($_POST['start_from']) > 0 ? intval($_POST['start_from']) : 1;
    $csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));
    $i = $start_from;

    $total_rows = count($csv_to_array);

    echo $total_rows - $start_from;

    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $file_path = $upload_dir . '/user-meta-import-' . date('d-m-Y') . '.log';
    $file = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] " . 'User meta import started.'. "\n" );
    fclose($file);

    foreach ($csv_to_array as $value) {
			$user_id = email_exists( $value[2] );
			if ($user_id) {
				$is_billing = trim($value[18]);
				if($is_billing == 'YES') {
					$is_ref = get_user_meta($user_id, 'billing_ref', true);
					if (!empty($value[3]) && !$is_ref) {
					  update_user_meta($user_id, 'billing_ref', trim($value[3]));
					}
					$is_ref = get_user_meta($user_id, 'billing_ref', true);
					if($is_ref) {
						$user = new WP_User($user_id);
						$user->remove_role( 'customer' );
						$user->add_role( 'b2b_customer' );
						update_user_meta($user_id, 'is_approved', 'yes');
					}
					$is_pricelist = get_user_meta($user_id, 'user_pricelist', true);
					if (!empty($value[4]) && !$is_pricelist) {
					  update_user_meta($user_id, 'user_pricelist', trim($value[4]));
					}
					$is_reg_number = get_user_meta($user_id, 'billing_reg_number', true);
					if (!empty($value[8]) && !$is_reg_number) {
					  update_user_meta($user_id, 'billing_reg_number', trim($value[8]));
					}
					$is_vat = get_user_meta($user_id, 'billing_vat', true);
					if (!empty($value[9]) && !$is_vat) {
					  update_user_meta($user_id, 'billing_vat', trim($value[9]));
					}
					// if (!empty($value[17])) {
					//   update_user_meta($user_id, 'billing_mobile', trim($value[17]));
					// }
			    $file = fopen( $file_path, 'a');
			    fwrite( $file, '  [' . current_time( 'mysql' ) . "] User meta #" . $i . " imported" ); 
			    fclose($file);
				}
			}
		}
    $file  = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] Import successful. User meta added: " . $i . "\n\n" ); 
    fclose($file);
  } else {
  	$start_from = get_option('hansa_user_meta_import_total') > 0 ? get_option('hansa_user_meta_import_total') : 1;
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<input type="number" name="start_from" value="' . $start_from . '">';
    echo '<input type="file" name="csv_file">';
    echo '<input type="submit" name="submit" value="submit">';
    echo '</form>';
  }
}

add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'handle_custom_query_var', 10, 2 );
function handle_custom_query_var( $query, $query_vars ) {
    if ( ! empty( $query_vars['old_order_id'] ) ) {
        $query['meta_query'][] = array(
            'key' => 'old_order_id',
            'value' => esc_attr( $query_vars['old_order_id'] ),
        );
    }

    return $query;
}

add_shortcode('import_orders_csv_form', 'import_orders_csv_form');
function import_orders_csv_form() {
  if (isset($_POST['submit'])) {
    $csv_file = $_FILES['csv_file'];
    $start_from = intval($_POST['start_from']) > 0 ? intval($_POST['start_from']) : 1;
    $csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));
    $i = $start_from;

    $total_rows = count($csv_to_array);

    echo $total_rows - $start_from;

    foreach ($csv_to_array as $value) {
			if(($i > $start_from + 2000) || ($i > $total_rows)) {
				break;
			}
			if ($i < $start_from) {
				continue;
			}
			$order_id = intval($value[0]);
			if(!empty($order_id)) {
				$orders = wc_get_orders( 
					array( 'old_order_id' => $order_id, 'limit' => 1 )
				);
				$order = null;
				$order_ref = '';
				if($orders) {
					$order = $orders[0];
					$product_title = trim($value[2]);
					if(!empty($product_title)) {
						$product_obj = get_page_by_title( $product_title, OBJECT, 'product' );
						if($product_obj) {
							$product_id = $product_obj->ID;
							if(!hansa_is_product_in_order($order,$product_id)) {
								$product = wc_get_product($product_id);
								if($product) {
									$order->add_product( $product, 1 );
		  						$order->calculate_totals();
				  				$order->update_status('completed');
								}
							} else {
								continue;
							}
						}
					}
				} else {
					$customer_email = trim($value[4]);
					$args = array();
					if(!empty($customer_email)) {
						$customer_id = email_exists($customer_email);
						if($customer_id) {
		  				$order = wc_create_order(
		  					array('customer_id' => $customer_id)
		  				);
						}
					}
					$billing_address = array(
			      'first_name' => trim($value[3]),
			      'last_name'  => '',
			      'email'      => $customer_email,
			      'address_1'  => trim($value[5]),
			      'address_2'  => trim($value[6]),
			      'city'       => trim($value[8]),
			      'postcode'   => trim($value[10]),
			      'country'    => trim($value[9])
				  );
				  $order->set_address( $billing_address, 'billing' );
				  $shipping_address = array(
			      'first_name' => trim($value[3]),
			      'last_name'  => '',
			      'address_1'  => trim($value[11]),
			      'address_2'  => trim($value[12]),
			      'city'       => trim($value[14]),
			      'postcode'   => trim($value[16]),
			      'country'    => 'MT'
				  );
				  $order->set_address( $shipping_address, 'shipping' );
				  $product_title = trim($value[2]);
					if(!empty($product_title)) {
						$product_obj = get_page_by_title( $product_title, OBJECT, 'product' );
						if($product_obj) {
							$product_id = $product_obj->ID;
							$product = wc_get_product($product_id);
							if($product) {
								$order->add_product( $product, 1 );
							}
						}
					}
				  $order->update_status('completed');
				  if($customer_id) {
				  	$order_ref = get_user_meta($customer_id,'billing_ref',true);
				  	if($order_ref) {
				  		update_post_meta($order->get_id(),'billing_ref',$order_ref);
				  	}
				  }
				  update_post_meta($order->get_id(),'old_order_id',trim($value[0]));
				}
			}
  		update_option('hansa_orders_import_total',$i);
			$i++;
		}
  } else {
  	$start_from = get_option('hansa_orders_import_total') > 0 ? get_option('hansa_orders_import_total') : 1;
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<input type="number" name="start_from" value="' . $start_from . '">';
    echo '<input type="file" name="csv_file">';
    echo '<input type="submit" name="submit" value="submit">';
    echo '</form>';
  }
}

function hansa_is_product_in_order($order,$product_id) {
	$in_order = false;
	$items = $order->get_items();
	foreach ( $items as $item_id => $item ) {
	   $current_id = $item->get_product_id();
	   if ( $product_id === $current_id ) {
	      $in_order = true;
	      break;
	   }
	}
	return $in_order;
}