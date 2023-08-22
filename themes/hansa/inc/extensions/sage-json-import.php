<?php

add_action( 'rest_api_init', 'hansa_add_import_endpoints' );
function hansa_add_import_endpoints() {
    register_rest_route( 'sage-import/v1', '/products', array(
        'methods' => 'POST',
        'callback' => 'hansa_import_json_products',
        'permission_callback' => 'validate_request_has_valid_wc_api_keys',
    ) );
    register_rest_route( 'sage-import/v1', '/prices', array(
        'methods' => 'POST',
        'callback' => 'hansa_import_json_prices',
        'permission_callback' => 'validate_request_has_valid_wc_api_keys',
    ) );
    register_rest_route( 'sage-import/v1', '/customers', array(
        'methods' => 'POST',
        'callback' => 'hansa_import_json_customers',
        'permission_callback' => 'validate_request_has_valid_wc_api_keys',
    ) );
    register_rest_route( 'sage-import/v1', '/orders', array(
        'methods' => 'GET',
        'callback' => 'hansa_rest_get_orders',
        'permission_callback' => 'validate_request_has_valid_wc_api_keys',
    ) );
}

function validate_request_has_valid_wc_api_keys(): bool {
    // WC not enabled, target reflection class is probably not registered
    if ( ! function_exists( 'WC' ) ) {
        return false;
    }

    $method = new ReflectionMethod( 'WC_REST_Authentication', 'perform_basic_authentication' );
    $method->setAccessible( true );

    return $method->invoke( new WC_REST_Authentication ) !== false;
}

// Endpoint for importing json products
function hansa_import_json_products( WP_REST_Request $request ) {
    $products = $request->get_json_params();
    $created_ids = array();
    $updated_ids = array();
    $i = 1;

    remove_old_sage_log_files();
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $file_path = $upload_dir . '/sage-import-' . date('d-m-Y') . '.log';
    $file = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] " . 'Import started(Products).'. "\n" );
    fclose($file);

    foreach($products as $product) {
        //log product:
        // product #{iterate}: {prod details}
        $sku = $product['productCode'];
        $is_updating = 'failed';
        $action = '';
        $file = fopen( $file_path, 'a');
        fwrite( $file, '  [' . current_time( 'mysql' ) . "] Product #" . $i . " (" . $sku . ") importing" ); 
        fclose($file);
        $post_id = 0;
        $_product = null;
    	$title = $product['ucIIWebDecription2'];
    	$stock = (int)$product['StockAmount'];
    	$category = trim($product['MainCat']);
    	$subcategory = trim($product['SubCat']);
        $subsubcategory = trim($product['ucIISubCat2']);
    	$country = trim($product['country']);
    	$region = trim($product['region']);
    	$abv = trim($product['ucIIalcohol']);
    	$grape = trim($product['ucIIGrapeVariety']);
    	$price = $product['Price'];
    	$vintage = trim($product['Year']);
        $producer = trim($product['ucIIProducer']);
        $volume = trim($product['ucIIvolume']);
        $vatrate = (float)$product['VATRATE'];
        $is_visible = $product['online'];
        $is_discontinued = $product['Discontinued'];
        $is_service_item = $product['ServiceItem'];
        $addons = trim($product['AddOnItem']);

		$product_id = wc_get_product_id_by_sku($sku);
		if(!$product_id) {
            $_product = new WC_Product; 
            $_product->set_name($title);
            $_product->set_sku($sku);
            $_product->set_status('publish');
            $post_id = $_product->save();
            if(!$is_visible) {
                $_product->set_catalog_visibility('hidden');
            } else {
                $_product->set_catalog_visibility('visible');
            }
            array_push($created_ids, $post_id);
            $action = 'created';
		} else {
            $_product = wc_get_product($product_id);
            $_product->set_name($title);
            $_product->set_sku($sku);
            $_product->set_status( 'publish' ); 
            if(!$is_visible) {
                $_product->set_catalog_visibility('hidden');
            } else {
                $_product->set_catalog_visibility('visible');
            }
            $post_id = $product_id;
            array_push($updated_ids, $post_id);
            $action = 'updated';
		}
        if($post_id) {
            $_product->set_sku($sku);
            $_product->set_regular_price($price);
            $_product->set_tax_status('taxable');
            if(!$is_service_item) {
                $_product->set_stock_quantity($stock);
                if($is_discontinued) {
                    $_product->set_stock_status('outofstock');
                    update_post_meta($post_id,'is_discontinued','yes');
                } else {
                    $_product->set_stock_status();
                    delete_post_meta($post_id,'is_discontinued');
                }
                if($stock > 0) {
                    $_product->set_stock_status();
                    $_product->set_backorders('yes');
                } else {
                    $_product->set_stock_status('outofstock');
                    $_product->set_backorders('no');
                }
            } else {
                $_product->set_stock_status();
                $_product->set_manage_stock('false');
                delete_post_meta($post_id,'is_discontinued');
            }
            if($vatrate > 0) {
                if($vatrate == 5) {
                    $_product->set_tax_class('reduced-rate');
                } else {
                    $_product->set_tax_class('standard');
                }
            } else {
                $_product->set_tax_class('zero-rate');
            }
            if(!empty($addons)) {
                update_post_meta($post_id,'product_addons',$addons);
            }
            $_product->save();
            if($category) {
                $parent_cat_id = update_product_terms($post_id, $category, 'product_cat');
                if($parent_cat_id && has_term( 'uncategorized', 'product_cat', $post_id )) {
                    wp_remove_object_terms($post_id, 'uncategorized', 'product_cat');
                }

                // Set primary category if more than 1 top level categories
                if(count(hansa_get_product_top_cats($post_id)) > 1) {
                    $primary_cat = yoast_get_primary_term_id('product_cat',$post_id);
                    if($primary_cat) {
                        wp_remove_object_terms( $post_id, $primary_cat, 'product_cat');
                    }
                    hansa_set_primary_term('product_cat', $post_id, $parent_cat_id);
                }
                
                if($subcategory) {
                    $subcat_id = update_product_terms($post_id, $subcategory, 'product_cat', $parent_cat_id);
                }
                if($subsubcategory) {
                    update_product_terms($post_id, $subsubcategory, 'product_cat', $subcat_id);
                }
            }
            if($country) {
                update_product_terms($post_id, $country, 'country');
            }
            if($region) {
                update_product_terms($post_id, $region, 'region');
            }
            if($abv) {
                update_product_terms($post_id, $abv, 'abv');
            }
            if($grape) {
                update_product_terms($post_id, $grape, 'grape');
            }
            if($volume) {
                update_product_terms($post_id, $volume, 'volume');
            }
            if($producer) {
                update_product_terms($post_id, $producer, 'producer');
            }
            if($vintage) {
                update_product_terms($post_id, $vintage, 'vintage');
            }
            $is_success = $action;
        }
        $file  = fopen( $file_path, 'a');
        fwrite( $file, '/' . $action . "\n" ); 
        fclose($file);
        $i++;
    }
    //Log end: --------------
    $file  = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] Import successful. Total products updated: " . $i . "\n\n" ); 
    fclose($file);
    $response = array();
    $response['created'] = $created_ids;
    $response['updated'] = $updated_ids;
    return $response;
}

// Endpoint for importing json prices
function hansa_import_json_prices( WP_REST_Request $request ) {
    $prices = $request->get_json_params();
    $updated_ids = array();
    $pricelists_ids = get_b2b_pricelists();

    remove_old_sage_log_files();
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $file_path = $upload_dir . '/sage-import-' . date('d-m-Y') . '.log';
    $file = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] " . 'Import started(Prices).'. "\n" );
    fclose($file);
    $i = 1;
    foreach($prices as $price) {
        $sku = $price['StockCode'];

        $is_updating = 'failed';
        $file = fopen( $file_path, 'a');
        fwrite( $file, '  [' . current_time( 'mysql' ) . "] Price for product #" . $i . " (" . $sku . ") importing" ); 
        fclose($file);

        $price_list = trim($price['PriceList']);
        $pricelist_id = intval(array_shift(explode('-',$price_list)));
        if(!in_array($pricelist_id, $pricelists_ids)) {
            array_push($pricelists_ids,$pricelist_id);
            $pricelists = htmlspecialchars_decode(get_option('wc_settings_tab_import_pricelists'));
            update_option('wc_settings_tab_import_pricelists',$pricelists . PHP_EOL . $price_list);
        }

        $price = (float)$price['PriceIncludingVAT'];

        $product_id = wc_get_product_id_by_sku($sku);
        if($product_id) {
            $_product = wc_get_product($product_id);
            if($_product) {
                update_post_meta($product_id,'b2b_price_' . $pricelist_id, $price);
                array_push($updated_ids,$product_id);
            }
            $is_updating = 'updated';
        }  
        $file  = fopen( $file_path, 'a');
        fwrite( $file, "/" . $is_updating . "\n" ); 
        fclose($file);
        $i++;
    }
    $file  = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] Import successful. Total products updated: " . $i . "\n\n" ); 
    fclose($file);
    $response = array();
    $response['updated'] = $updated_ids;
    return $response;
}

// Endpoint for importing json customers
function hansa_import_json_customers( WP_REST_Request $request ) {
    $customers = $request->get_json_params();
    $updated_ids = array();
    $created_ids = array();
    $pricelists_ids = get_b2b_pricelists();

    remove_old_sage_log_files();
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $file_path = $upload_dir . '/sage-import-' . date('d-m-Y') . '.log';
    $file = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] " . 'Import started (Customers).'. "\n" );
    fclose($file);
    $i = 1;

    foreach($customers as $customer) {
        $customer_ref = trim($customer['Account']);
        $is_updating = 'failed';
        if($customer_ref) {
            $file = fopen( $file_path, 'a');
            fwrite( $file, '  [' . current_time( 'mysql' ) . "] Customer #" . $i . " (" . $customer_ref . ") importing" ); 
            fclose($file);
        }

        $customer_email = $customer['EMail'];
        $customer_list = htmlspecialchars_decode(trim($customer['PriceListName']));
        $customer_data = array(
            'billing_address_1' => $customer['AddressLine1'],
            'billing_address_2' => $customer['AddressLine2'],
            'billing_city' => $customer['AddressLine3'],
            'billing_postcode' => $customer['AddressLine4'],
            'billing_phone' => $customer['Telephone'],
            'user_pricelist' => $customer_list
        );
        $pricelist_id = array_shift(explode('-',$customer_list));
        if(!in_array($pricelist_id, $pricelists_ids)) {
            array_push($pricelists_ids,$pricelist_id);
            $pricelists = htmlspecialchars_decode(get_option('wc_settings_tab_import_pricelists'));
            update_option('wc_settings_tab_import_pricelists',$pricelists . PHP_EOL . $customer_list);
        }
        if($customer_list == '1-Trade Clients') {
            $customer_data['billing_company'] = $customer['Name'];
            $customer_data['billing_vat'] = $customer['Tax_Number'];
        } else {
            $customer_data['billing_first_name'] = strtok($customer['Name'],  ' ');
            $customer_data['billing_last_name'] = substr($customer['Name'], strpos($customer['Name'], " ") + 1);
        }
        if($customer_ref) {
            $user_id = 0;
            $users = get_users(array(
                'meta_key' => 'billing_ref',
                'meta_value'   => $customer_ref,
                'meta_compare' => '=',
            ));
            if($users) {
                $user = $users[0];
            }

            if($user) {
                $user_id = $user->ID;
                if(isset($customer_data['billing_first_name']) || isset($customer_data['billing_last_name'])) {
                    $userdata = array(
                        'ID' => $user_id,
                        'first_name'    => $customer_data['billing_first_name'],
                        'last_name'     => $customer_data['billing_last_name']
                    );
                    wp_update_user($userdata);
                }
                array_push($updated_ids,$user_id);
                foreach($customer_data as $key => $value) {
                    if(!empty($value)) {
                        update_user_meta($user_id,$key,$value);
                    }
                }
                $is_updating = 'updated';
            }
            $file  = fopen( $file_path, 'a');
            fwrite( $file, "/" . $is_updating . "\n" ); 
            fclose($file);
        }
        $i++;
    }
    $file  = fopen( $file_path, 'a');
    fwrite( $file, '[' . current_time( 'mysql' ) . "] Import successful. Total customers updated: " . $i . "\n\n" ); 
    fclose($file);
    $response = array();
    $response['created'] = $created_ids;
    $response['updated'] = $updated_ids;
    return $response;
}

function hansa_rest_get_orders ( WP_REST_Request $request ) {
    $params = $request->get_params();
    $orders = null;
    $orders_data = array();
    $statuses = wc_get_order_statuses();
    $query_statuses = array();
    foreach($statuses as $key => $status) {
        if($key != 'wc-synched') {
            array_push($query_statuses,$key);
        }
    }
    if(!isset($params['from']) && !isset($params['to']) && !isset($params['status'])) {
        $args = array(
            'status' => 'completed',
            'limit' => 100
        );
        $orders = wc_get_orders($args);
        foreach($orders as $order) {
            $order_id = $order->get_id();
            if(is_a( $order, 'WC_Order' )) {
                $order_obj = hansa_build_custom_order_object($order_id);
                array_push($orders_data, $order_obj);
            }
            // $order->set_status('synched');
            // $order->save();
        }
    } else {
        $from = false;
        $to = false;
        if(isset($params['from'])) {
            $from = strtotime($params['from']);
            if(!$from) {
                return new WP_Error( 'Wrong date', 'One of parameters has not correct date format', array( 'status' => 404 ) );
            }
        }
        if(isset($params['to'])) {
            $to = strtotime($params['to']);
            if(!$to) {
                return new WP_Error( 'Wrong date', 'One of parameters has not correct date format', array( 'status' => 404 ) );
            }
        }
        $args = array(
            'limit' => 100
        );
        if($from) {
            $args['date_after'] = date('Y-m-d',$from);
        }
        if($to) {
            $args['date_before'] = date('Y-m-d',$to);
        }
        if(isset($params['status'])) {
            $query_status = 'wc-' . $params['status'];
            if(array_key_exists($query_status,$statuses)) {
                $args['status'] = array('wc-' . $params['status']);
            } else {
                return new WP_Error( 'Wrong status', 'Order status ' . $params['status'] . ' doesn\'t exist', array( 'status' => 404 ) );
            }
        }
        $orders = wc_get_orders($args);
        foreach($orders as $order) {
            $order_id = $order->get_id();
            $order_obj = hansa_build_custom_order_object($order_id);
            array_push($orders_data, $order_obj);
        }
    }
    $orders_data = json_encode($orders_data);
    return json_decode($orders_data);
}

// Update products taxomony with term name
function update_product_terms($product_id, $term_name, $tax_name, $parent_id = 0) {
    $cat_id = 0;
    if(!empty($term_name)) {
        $cat_name = trim($term_name);
        $cat_obj = null;
        $is_append = $parent_id > 0 ? true : false;
        if(!$is_append) {
            $cat_obj = get_term_by('name',$cat_name,$tax_name);
        } else {
            $parent_children = get_terms([ 'taxonomy' => $tax_name, 'parent'   => $parent_id ]);
            foreach($parent_children as $term) {
                if($term->name == $cat_name) {
                    $cat_obj = $term;
                }
            }
        }
        $insert_value = array();
        if($tax_name == 'product_cat') {
            $is_append = true;
        }
        if($cat_obj) {
            $cat_id = $cat_obj->term_id;
            array_push($insert_value, $cat_id);
        } else {
            $new_cat = wp_insert_term(
                $cat_name,
                $tax_name,
                array(
                    'slug'        => strtolower($cat_name),
                    'parent'      => $parent_id,
                )
            );
            if(!is_wp_error($new_cat)) {
                $cat_id = $new_cat['term_id'];
            } else {
                $upload_dir = wp_upload_dir();
                $upload_dir = $upload_dir['basedir'];
                $file_path = $upload_dir . '/sage-import-' . date('d-m-Y') . '.log';
                $file  = fopen( $file_path, 'a');
                fwrite( $file, "\n\n" . '[' . current_time( 'mysql' ) . "] #ID(" . $product_id .") ERROR: " . $new_cat->get_error_message() . "\n\n" ); 
                fclose($file);
            }
            if($cat_id) {
                array_push($insert_value, intval($cat_id));
            }
        }
        if(!empty($insert_value)) {
            wp_set_object_terms( $product_id, $insert_value, $tax_name, $is_append);
        }
    }
    return $cat_id;
}

function hansa_build_custom_order_object($order_id) {
    $order_obj = array();
    $order = wc_get_order($order_id);
    $date = $order->get_date_created();
    $redeem_amount = (float)get_post_meta($order_id,'redeem_amount',true);
    $coupons = $order->get_coupon_codes();
    $paid_amount = 0;
    if($redeem_amount) {
        $paid_amount += $redeem_amount;
    }
    $paid_amount += $order->get_discount_total();
    if($order->get_payment_method() != 'cod') {
        $paid_amount += $order->get_total();
    }
    $user = $order->get_user();
    $is_b2b = in_array( 'b2b_customer', (array) $user->roles );
    $order_obj['OrderID'] = "";
    $order_obj['OrderRef'] = $order->get_id();
    $order_obj['OrderDate'] = $date->format('Y-m-d h:i:s');
    $order_obj['CustomerCode'] = get_post_meta($order_id,'_billing_ref',true);
    $order_obj['SageUserCode'] = 'Online Order';
    $order_obj['Notes'] = $order->get_customer_note();
    $order_obj['SubtotalExclVAT'] = strval($order->get_subtotal());
    $order_obj['VAT'] = $order->get_total_tax();
    $order_obj['ShippingCost'] = $order->get_shipping_total();
    $order_obj['AmountPaid'] = strval($paid_amount);
    $order_obj['ShippingMethod'] = $order->get_shipping_method();
    $order_obj['PaymentMethod'] = $order->get_payment_method_title();
    $order_obj['Gifts'] = array();
    // Adding gifts
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $product = wc_get_product($product_id);
        $is_gift = boolval(get_post_meta($product_id,'is_gift',true));
        if($is_gift) {
            $tax = WC_Tax::get_rates_for_tax_class( $item->get_tax_class() );
            $gift_message = wc_get_order_item_meta( $item_id, 'customer_message', true );
            $gift_q = $item->get_quantity();
            $price_excl_vat = $item->get_subtotal()/$gift_q;
            $tax_per_gift = $gift_q == 1 ? $item->get_subtotal_tax() : $item->get_subtotal_tax() / $gift_q;
            $gift_obj = array(
                'GiftCode' => $product->get_sku(),
                'GiftName' => $product->get_title(),
                'GiftQuantity' => $gift_q,
                'GiftUnitPriceExclTax' => number_format($price_excl_vat, 2, '.', ''),
                'GiftTotalTax' => strval($item->get_subtotal_tax()),
                'GiftVATPercent' => strval(array_shift($tax)->tax_rate),
                'GiftUnitPriceInclTax' => number_format($item->get_total()/$gift_q + $tax_per_gift, 2, '.', ''),
                'GiftMessage' => $gift_message
            );
            $order_obj['Gifts'][] = $gift_obj;
        }
    }
    $order_obj['DeliveryName'] = $order->get_shipping_first_name();
    $order_obj['DeliverySurname'] = $order->get_shipping_last_name();
    $order_obj['DeliveryCharge'] = "";
    $order_obj['DeliveryAddress1'] = $order->get_shipping_address_1();
    $order_obj['DeliveryAddress2'] = $order->get_shipping_address_2();
    $order_obj['DeliveryAddress3'] = $order->get_shipping_city();
    $order_obj['DeliveryAddress4'] = $order->get_shipping_postcode();
    $order_obj['DeliveryAddress5'] = "Malta";
    $order_obj['DeliveryTel'] = $order->get_shipping_phone();
    $order_obj['DeliveryMobile'] = null;
    $order_obj['BillingName'] = $order->get_billing_first_name();
    $order_obj['BillingSurname'] = $order->get_billing_last_name();
    $order_obj['BillingCompany'] = get_post_meta($order_id,'_billing_company',true);
    $order_obj['BillingVAT'] = get_post_meta($order_id,'_billing_vat',true);
    $order_obj['BillingAddress1'] = $order->get_billing_address_1();
    $order_obj['BillingAddress2'] = $order->get_billing_address_2();
    $order_obj['BillingAddress3'] = $order->get_billing_city();
    $order_obj['BillingAddress4'] = $order->get_billing_postcode();
    $order_obj['BillingAddress5'] = "Malta";
    $order_obj['BillingEmail'] = $order->get_billing_email();
    $order_obj['BillingTelephone'] = $order->get_billing_phone();
    $order_obj['BillingMobile'] = null;

    if($redeem_amount || $coupons) {
        $order_obj['coupons'] = array();
        foreach($order->get_items('coupon') as $coupon_id => $coupon) {
            $coupon_data = array(
                'CouponName' => $coupon,
                'CouponDiscount' => strval(wc_get_order_item_meta( $coupon, 'discount_amount', true ))
            );
            array_push($order_obj['coupons'], $coupon_data);
        }
        if($redeem_amount) {
            $redeem_data = array(
                'CouponName' => 'loyale',
                'CouponDiscount' => strval($redeem_amount)
            );
            array_push($order_obj['coupons'], $redeem_data);
        }
    }

    $order_obj['products'] = array();
    // Adding products
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $product = wc_get_product($product_id);
        $addons = get_post_meta($product_id, 'product_addons', true);
        $is_product = !(has_term('champagne-labels','product_cat',$product_id) || get_post_meta($product_id,'is_gift',true));
        $is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
        if($is_product) {
            if($is_addon) {
                continue;
            }
            $tax = WC_Tax::get_rates_for_tax_class( $item->get_tax_class() );
            $q = $item->get_quantity();
            $tax_per_item = $q == 1 ? $item->get_subtotal_tax() : $item->get_subtotal_tax() / $q;
            $price_excl_vat = $item->get_subtotal()/$q;
            $product_obj = array(
                'productCode' => $product->get_sku(),
                'Productdescription' => $product->get_short_description(),
                'Quantity' => $q,
                'UnitPriceExclTax' => number_format($price_excl_vat, 2, '.', ''),
                'TotalTax' => strval($item->get_subtotal_tax()),
                'VATPercent' => number_format(array_shift($tax)->tax_rate, 2, '.', ''),
                'UnitPriceInclVAT' => number_format($item->get_total()/$q + $tax_per_item, 2, '.', '')
            );
            $product_obj['customLabel'] = array();
            foreach ( $order->get_items() as $label_id => $label_item ) {
                $label_product_id = $label_item->get_product_id();
                if(has_term('champagne-labels','product_cat',$label_product_id)) {
                    $labeled_id = wc_get_order_item_meta( $label_id, 'labeled_product_id', true );
                    if($product_id == $labeled_id) {
                        $label_product = wc_get_product($label_product_id);
                        $lbl_q = $label_item->get_quantity();
                        $lbl_tax_per_item = $q == 1 ? $label_item->get_subtotal_tax() : $label_item->get_subtotal_tax() / $lbl_q;
                        $tax = WC_Tax::get_rates_for_tax_class( $label_item->get_tax_class() );
                        $is_label_1 = wc_get_order_item_meta( $label_id, 'label_1', true );
                        $is_label_2 = wc_get_order_item_meta( $label_id, 'label_2', true );
                        $label_str = $is_label_1 ? $is_label_1 : '';
                        if($is_label_1 && !empty($label_str)) {
                            $label_str .= ' | ';
                        }
                        $label_str .= $is_label_2 ? $is_label_2 : '';
                        $price_excl_vat = ($label_item->get_subtotal()/$lbl_q) - ($label_item->get_subtotal_tax()/$lbl_q);
                        $custom_obj = array(
                            'CLCode' => $label_product->get_sku(),
                            'CLDescription' => 'Custom Label',
                            'CLValue' => $label_str,
                            'CLQuantity' => $lbl_q,
                            'CLUnitPriceExclTax' => number_format($price_excl_vat, 2, '.', ''),
                            'CLTotalTax' => strval($label_item->get_subtotal_tax()),
                            'CLVATPercent' => strval(array_shift($tax)->tax_rate),
                            'CLUnitPriceInclVAT' => number_format($label_item->get_total()/$lbl_q + $lbl_tax_per_item, 2, '.', '')
                        );
                        array_push($product_obj['customLabel'], $custom_obj);
                    }
                }
            }
            $order_obj['products'][] = $product_obj;
            if($addons) {
                $addons_ids = explode(',',$addons);
                foreach($addons_ids as $addon_sku) {
                    $addon_sku = trim($addon_sku);
                    $item_temp = null;
                    $addon_id = wc_get_product_id_by_sku($addon_sku);
                    $addon_product = wc_get_product($addon_id);
                    foreach ( $order->get_items() as $item_id2 => $item2 ) {
                        $p_id = $item2->get_product_id();
                        if($addon_id == $p_id) {
                            $item_temp = $item2;
                            break;
                        }
                    }
                    if($item_temp) {
                        $tax = WC_Tax::get_rates_for_tax_class( $item_temp->get_tax_class() );
                        $q2 = $item_temp->get_quantity();
                        $tax_per_item = $q2 == 1 ? $item_temp->get_subtotal_tax() : $item_temp->get_subtotal_tax() / $q2;
                        $price_excl_vat = $item_temp->get_subtotal()/$q2;
                        $addon_obj = array(
                            'productCode' => $addon_product->get_sku(),
                            'Productdescription' => $addon_product->get_short_description(),
                            'Quantity' => $q,
                            'UnitPriceExclTax' => number_format($price_excl_vat, 2, '.', ''),
                            'TotalTax' => number_format($tax_per_item * $q, 2, '.', ''),
                            'VATPercent' => number_format(array_shift($tax)->tax_rate, 2, '.', ''),
                            'UnitPriceInclVAT' => number_format($item_temp->get_total()/$q2 + $tax_per_item, 2, '.', '')
                        );
                        $order_obj['products'][] = $addon_obj;
                    }
                }
            }
        }
    }
    return $order_obj;
}

function remove_old_sage_log_files() {
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    $files = array();
    foreach (scandir($upload_dir) as $file) {
        if ($file !== '.' && $file !== '..') {
          if(strpos($file, 'sage-import') !== false) {
            $files[] = $file;
          }
        }
    }
    $files_remove = array();
    foreach($files as $file) {
      $temp = str_replace('sage-import-','',$file);
      $temp = str_replace('.log','',$temp);
      $file_date = strtotime($temp);
      $current_date = strtotime(date('d-m-Y'));
      $datediff = $current_date - $file_date;
      $datediff = round($datediff / (60 * 60 * 24));
      if($datediff > 3) {
        array_push($files_remove, $file);
      }
    }
    if(!empty($files_remove)) {
      foreach($files_remove as $file) {
        $file_path = $upload_dir . '/' . $file;
        unlink($file_path);
      }
    }
}