<?php
// Register and create customer
add_action( 'wp_ajax_nopriv_hansa_register_customer', 'hansa_register_customer_handler' );
function hansa_register_customer_handler() {
    require_once ABSPATH . WPINC . '/registration.php';
    require_once ABSPATH . WPINC . '/user.php';

    $reg_email    = sanitize_email( $_POST['email'] );
    $reg_name     = $_POST['first_name'];
    $reg_surname  = $_POST['last_name'];
    $reg_phone  = $_POST['phone'];
    $birth_day    = (int) $_POST['dd'];
    $birth_month  = (int) $_POST['mm'];
    $birth_year   = (int) $_POST['yyyy'];
    $is_owner     = isset($_POST['is_owner']);
    $is_subscribe = $_POST['is_subscribe'];
    $user_password1 = $_POST['password1'];
    $user_password2 = $_POST['password2'];
    if($is_owner) {
        $company_name = $_POST['company_name'];
        $company_vat = $_POST['company_vat'];
        $company_reg = $_POST['company_reg'];
        $company_addr1 = $_POST['company_addr1'];
        $company_addr2 = $_POST['company_addr2'];
        $company_city = $_POST['company_city'];
        $company_postcode = $_POST['company_postcode'];
        $company_country = $_POST['company_country'];
    }

    $validation_errors = array(
        'email' => array(),
        'password' => array(),
        'first_name' => array(),
        'last_name' => array(),
        'birthdate' => array()
    );

    $is_errors = false;

    if ( ! is_email( $reg_email ) ) {
        $is_errors = true;
        $validation_errors['email'][] = 'The email address in not valid.';
    } else {
        if ( email_exists( $reg_email ) ) {
            $is_errors = true;
            $validation_errors['email'][] = 'Email is already in use.';
        }
    }

    if($user_password1 != $user_password2) {
        $is_errors = true;
        $validation_errors['password'][] = 'Passwords are not match.';
    } else {
        if ( (strlen( $user_password1 ) < 8) || (! preg_match( "#[0-9]+#", $user_password1 ) ) || (! preg_match( "#[A-Z]+#", $user_password1 )) || (! preg_match( "#[a-z]+#", $user_password1 )) || (! preg_match( "#[^\da-zA-Z]#", $user_password1 ))) {
            $is_errors = true;
            $validation_errors['password'][] = 'Password is too weak. Password must be at least 8 characters, contain one uppercase, one lowercase, one number and a special character.';
        }
    }


    if ( ! preg_match( "/^([a-zA-Z0-9 ']+)$/", $reg_name ) ) {
        $is_errors = true;
        $validation_errors['first_name'][] = 'First name should contain only letters and space.';
    }

    if ( ! preg_match( "/^([a-zA-Z0-9  \s']+)$/", $reg_surname ) ) {
        $is_errors = true;
        $validation_errors['last_name'][] = 'Last name should contain only letters and space.';
    }

    if ( ! checkdate( $birth_month, $birth_day, $birth_year ) ) {
        $is_errors = true;
        $validation_errors['birthdate'][] = 'Choose a date of birth';
    }

    if($is_errors) {
        $validation_errors['status'] = 0;
        echo json_encode($validation_errors);
    } else {
        $userdata = array(
            'user_login' => str_replace('+','',$reg_email),
            'first_name' => $reg_name,
            'last_name'  => $reg_surname,
            'role'       => 'customer',
        );
        $user_id = wc_create_new_customer( $reg_email, str_replace('+','',$reg_email), $user_password1, $userdata);
        update_user_meta($user_id,'birthdate',date('Y-m-d', strtotime($birth_year . '-' . $birth_month . '-' . $birth_day)));
        if($user_id) {
            $user = get_user_by('id',$user_id);
            if($is_owner) {
                $user->set_role('b2b_customer');
            }
        }
        if($reg_phone) {
            update_user_meta($user_id, 'billing_phone', $reg_phone);
        }
        if($user_id && $is_owner) {
            update_user_meta($user_id,'billing_company',$company_name);
            update_user_meta($user_id,'billing_address_1',$company_addr1);
            update_user_meta($user_id,'billing_address_2',$company_addr2);
            update_user_meta($user_id,'billing_city',$company_city);
            update_user_meta($user_id,'billing_postcode',$company_postcode);
            update_user_meta($user_id,'billing_country',$company_country);
            update_user_meta($user_id,'billing_vat',$company_vat);
            update_user_meta($user_id,'billing_reg_number',$company_reg);
            WC()->mailer()->get_emails()['WC_Email_Customer_New_Account']->trigger( $user_id );
        }
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        echo json_encode($user_id);
    }
    wp_die();
}


// Reset customer password
add_action( 'wp_ajax_nopriv_hansa_reset_password', 'hansa_reset_password_handler' );
function hansa_reset_password_handler() {
    $email = sanitize_email($_POST['email']);
    if(is_email($email)) {
        if ( !email_exists( $email ) ) {
            $is_errors = true;
            $validation_errors['email'][] = 'Email not attached to any user.';
        }
    } else {
        $is_errors = true;
        $validation_errors['email'][] = 'The email address in not valid.';
    }

    if($is_errors) {
        $validation_errors['status'] = 0;
        echo json_encode($validation_errors);
    } else {
        $user = get_user_by( 'email', $email );
        if($user) {
            $reset_key = get_password_reset_key( $user );
            WC()->mailer()->get_emails()['WC_Email_Customer_Reset_Password']->trigger( $user->user_login, $reset_key );
            echo json_encode('1');
        }
    }
    wp_die();
}

// Delete customer review
add_action( 'wp_ajax_hansa_remove_product_review', 'hansa_remove_product_review_handler' );
function hansa_remove_product_review_handler() {
    $review_id = (int)$_POST['review_id'];
    $is_deleted = false;
    if(is_int($review_id)) {
        $is_deleted = wp_delete_comment($review_id, true);
    }
    echo $is_deleted;
    wp_die();
}

// Add new shipping address in profile
add_action( 'wp_ajax_hansa_add_profile_address', 'hansa_profile_add_address' );
function hansa_profile_add_address() {
    global $wpdb;
    $user_id = get_current_user_id();

    $addr_data = array(
        'shipping_first_name' => esc_html($_POST['shipping_first_name']),
        'shipping_last_name' => esc_html($_POST['shipping_last_name']),
        'shipping_address_1' => esc_html($_POST['shipping_address_1']),
        'shipping_address_2' => esc_html($_POST['shipping_address_2']),
        'shipping_city' => esc_html($_POST['shipping_city']),
        'shipping_country' => 'MT',
        'shipping_postcode' => esc_html($_POST['shipping_postcode']),
        'shipping_vat' => esc_html($_POST['shipping_vat']),
        'shipping_company' => esc_html($_POST['shipping_company'])
    );

    $tablename = $wpdb->prefix . 'wc_multiple_addresses';
    $user_id = get_current_user_id();
    $addr_data = array(
        'userdata' => serialize($addr_data),
        'userid' => $user_id,
        'type' => 'shipping'
    );
    $wpdb->insert($tablename,$addr_data,array('%s','%d','%s'));
    echo $wpdb->insert_id;
    wp_die();
}

// Delete shipping address in profile
add_action( 'wp_ajax_hansa_remove_profile_address', 'hansa_remove_profile_address' );
function hansa_remove_profile_address() {
    global $wpdb;
    $address_id = (int)$_POST['addr_id'];
    $tablename = $wpdb->prefix . 'wc_multiple_addresses';
    $sql       = "DELETE  FROM {$tablename} WHERE id='" . $address_id . "'";
    $result = $wpdb->query( $sql );
    return $result;
}

// Edit shipping address in profile
add_action( 'wp_ajax_hansa_update_profile_address', 'hansa_profile_update_address' );
function hansa_profile_update_address() {
    global $wpdb;
    $tablename = $wpdb->prefix . 'wc_multiple_addresses';
    $user_id = get_current_user_id();
    $address_id = (int)$_POST['address_id'];
    if($address_id) {
        $addr_data = array(
            'shipping_first_name' => esc_html($_POST['shipping_first_name']),
            'shipping_last_name' => esc_html($_POST['shipping_last_name']),
            'shipping_address_1' => esc_html($_POST['shipping_address_1']),
            'shipping_address_2' => esc_html($_POST['shipping_address_2']),
            'shipping_city' => esc_html($_POST['shipping_city']),
            'shipping_country' => 'MT',
            'shipping_postcode' => esc_html($_POST['shipping_postcode']),
            'shipping_vat' => esc_html($_POST['shipping_vat']),
            'shipping_company' => esc_html($_POST['shipping_company'])
        );

        $condition = array(
            'id'     => $address_id,
            'userid' => $user_id,
            //'type'   => 'billing'
        );

        $result = $wpdb->update( $tablename, array(
            'userdata' => serialize($addr_data)
        ), $condition );

        echo $result;
    }
    wp_die();
}

// Add new shipping address in profile
add_action( 'wp_ajax_hansa_add_address_to_session', 'hansa_add_address_to_session' );
function hansa_add_address_to_session() {
    global $wpdb;
    $user_id = get_current_user_id();

    $addr_data = array(
        'shipping_first_name' => esc_html($_POST['shipping_first_name']),
        'shipping_last_name' => esc_html($_POST['shipping_last_name']),
        'shipping_address_1' => esc_html($_POST['shipping_address_1']),
        'shipping_address_2' => esc_html($_POST['shipping_address_2']),
        'shipping_city' => esc_html($_POST['shipping_city']),
        'shipping_country' => 'MT',
        'shipping_postcode' => esc_html($_POST['shipping_postcode']),
        'shipping_vat' => esc_html($_POST['shipping_vat']),
        'shipping_company' => esc_html($_POST['shipping_company'])
    );
    $is_save = isset($_POST['is_save']) ? true : false;

    $user_id = get_current_user_id();
    $addr_data = array(
        'userdata' => serialize($addr_data),
        'userid' => $user_id,
        'type' => 'shipping'
    );

    if($is_save) {
        $tablename = $wpdb->prefix . 'wc_multiple_addresses';
        $wpdb->insert($tablename,$addr_data,array('%s','%d','%s'));
        echo $wpdb->insert_id;
    } else {
        // if(isset(WC()->session->get( 'saved_addr')) {
        //       	WC()->session->set( 'saved_addr', $group_input_value );
        // }
    }
    wp_die();
}

// Get products by search string
add_action( 'wp_ajax_hansa_search_products', 'hansa_search_products_handler' );
add_action( 'wp_ajax_nopriv_hansa_search_products', 'hansa_search_products_handler' );
function hansa_search_products_handler() {
    $posts_per_page = intval(get_field('header_search_n','option'));
    $str = $_POST['s'];
    $result = '';
    if(!empty($str)) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            's' => $str,
            'extend_where' => hansa_get_extended_search_params($str),
            'post_status' => get_query_statuses(),
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => array('champagne-labels','empties'),
                    'operator'=> 'NOT IN'
                ),
                array(
                    'taxonomy'  => 'product_visibility',
                    'terms'     => array('exclude-from-search','exclude-from-catalog'),
                    'field'     => 'slug',
                    'operator'  => 'NOT IN'
                )
            ),
            'meta_query' => array(
                array(
                    'key' => 'is_gift',
                    'compare' => 'NOT EXISTS'
                )
            )
        );
        $s_query = new WP_Query($args);
        if($s_query->have_posts()) {
            while($s_query->have_posts()) {
                $s_query->the_post();
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                $points_html = '';
                if(!hansa_is_b2b_user()) {
                    $points_html = '<div class="points-value">+ '. loyale_get_product_points($product_id) . 'pts</div>';
                }
                $image_url = get_the_post_thumbnail_url(get_the_ID());
                if(!$image_url) {
                    $image_url = wc_placeholder_img_src();
                }
                $result .= '<div class="search-product"><a href="' . get_the_permalink() . '" class="product-thumb"><img src="' . $image_url . '" alt="center" class="contain-image"></a><div class="product-description"><h4><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4><div class="price-wrap"><div class="left"><div class="current-price">' . strip_tags(wc_price($product->get_price())) . '</div></div>' . $points_html . '</div></div></div>';
            }
            wp_reset_postdata();
        } else {
            $result = false;
        }
    }
    echo $result;
    wp_die();
}

// Add custom message to gift product
add_action( 'wp_ajax_hansa_add_gift_message', 'hansa_add_gift_message_handler' );
function hansa_add_gift_message_handler() {
    $message = strip_tags(trim($_POST['message']));
    $product_id = (int)$_POST['product_id'];
    $cart_item_id = $_POST['cart_id'];
    if(!empty($message)) {
        $cart = WC()->cart->cart_contents;
        $cart_item = $cart[$cart_item_id];
        $cart_item['custom_data']['customer_message'] = $message;
        WC()->cart->cart_contents[$cart_item_id] = $cart_item;
        WC()->cart->set_session();
    }
    echo json_encode($product_id . '|' . $cart_item_id . '|' . $message);
    wp_die();
}

// Adding gift to cart on checkout
add_action( 'wp_ajax_hansa_add_gift_to_cart', 'hansa_add_gift_to_cart_handler' );
function hansa_add_gift_to_cart_handler(){
    $message = strip_tags(trim($_POST['message']));
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $cart_item_key = is_product_in_cart($product_id);
    $is_added = '';
    if($cart_item_key) {
        $is_added = WC()->cart->set_quantity( $cart_item_key, $quantity );
    } else {
        $custom_data = array();
        if(!empty($message)) {
            $custom_data = array(
                'customer_message' => $message
            );
        }
        $is_added = WC()->cart->add_to_cart( $product_id ,$quantity,  0, array(), $custom_data );
    }
    echo $is_added;
    wp_die();
}

// Update checkout totals
add_action( 'wp_ajax_hansa_update_checkout_totals', 'hansa_update_checkout_totals_handler' );
function hansa_update_checkout_totals_handler() {
    $totals = array();
    $totals['subtotal'] = wc_price(WC()->cart->subtotal_ex_tax);
    $totals['total'] = wc_price(WC()->cart->total);
    $totals['taxes'] = wc_price(WC()->cart->get_taxes_total());
    $totals['discount'] = wc_price(WC()->cart->get_discount_total());
    echo json_encode($totals);
    wp_die();
}

//Apply redeem from session on cart
add_filter( 'woocommerce_calculated_total', 'discounted_calculated_total', 10, 2 );
function discounted_calculated_total( $total, $cart ) {

    $amount_to_discount_subtotal = $cart->subtotal;

    $session_redeem = WC()->session->get('redeem_amount');
    $redeem_amount = $session_redeem !== null ? $session_redeem : 0;

    $new_total = $total - $redeem_amount;

    if ( is_cart() ) {
        $new_total = $total;
    }
    return round( $new_total, $cart->dp );
}

// Apply redeem
add_action( 'wp_ajax_hansa_apply_redeem', 'hansa_apply_redeem_handler' );
function hansa_apply_redeem_handler() {
    $redeem_amount = (float)$_POST['redeem_value'];
    $response = array();
    $response['html'] = '';
    $response['is_applied'] = false;
    $total_user_points = loyale_get_customer_points();
	$points_redemption = loyale_get_points_redemption();
	$points_redemption = $points_redemption > 0 ? $points_redemption : 1;
	$redeem_balance = (float)$total_user_points / $points_redemption;
    if($redeem_amount > $redeem_balance){
        $redeem_amount = $redeem_balance;
    }
    if($redeem_amount > 0) {
        WC()->session->set('redeem_amount',$redeem_amount);
        WC()->cart->calculate_totals();
        $response['is_applied'] = true;
        $response['html'] = '<div class="promocode-message redeem"><p>Redeem ' . loyale_get_price_points($redeem_amount) . 'pts</p><a href="#" class="remove-redeem">Remove</a></div>';
    }
    echo json_encode($response);
    wp_die();
}

add_action( 'wp_ajax_hansa_remove_redeem', 'hansa_remove_redeem_handler' );
function hansa_remove_redeem_handler() {
    WC()->session->set('redeem_amount',null);
    WC()->cart->calculate_totals();
    $response = array();
    echo true;
    wp_die();
}


// Apply coupon
add_action( 'wp_ajax_hansa_apply_coupon', 'hansa_apply_coupon_handler' );
function hansa_apply_coupon_handler() {
    $coupon_code = $_POST['coupon_code'];
    $response = array();
    $response['is_applied'] = false;
    $response['html'] = '';
    if(!empty($coupon_code)) {
        if(is_coupon_valid($coupon_code)) {
            $response['is_applied'] = WC()->cart->apply_coupon($coupon_code);
            $amount = WC()->cart->get_coupon_discount_amount( $coupon_code, WC()->cart->display_cart_ex_tax );
            $response['html'] = '<div class="promocode-message"><p>Congrats! <span class="ch-coupon-value">' . wc_price($amount) . '</span> Off!</p><a href="#" class="remove-coupon" data-code="' . $coupon_code . '">Remove</a></div>';
        }
    }
    echo json_encode($response);
    wp_die();
}

// Remove coupon
add_action( 'wp_ajax_hansa_remove_coupon', 'hansa_remove_coupon_handler' );
function hansa_remove_coupon_handler() {
    $coupon_code = $_POST['coupon_code'];
    $response = false;
    $response = array();
    if(!empty($coupon_code) && WC()->cart->has_discount($coupon_code)) {
        $response = WC()->cart->remove_coupon($coupon_code);
        WC()->cart->calculate_totals();
        $response['is_removed'] = true;
    }
    echo $response;
    wp_die();
}

// Repeat order
add_action( 'wp_ajax_hansa_repeat_order', 'hansa_repeat_order_handler' );
function hansa_repeat_order_handler(){
    WC()->cart->empty_cart();
    $order_id = (int)$_POST['id'];
    $order = wc_get_order( $order_id );

    foreach ($order->get_items() as $item_key => $item ) {
        $item_id = $item->get_id();
        $product_id   = $item->get_product_id();
        $quantity     = $item->get_quantity();
        $message = wc_get_order_item_meta($item_id,'customer_message');
        $is_addon = has_term('empties','product_cat',$product_id);
        if(!$is_addon) {
            $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity);
            if( $cart_item_key && !empty($message)) {
                $cart = WC()->cart->cart_contents;
                $cart_item = $cart[$cart_item_key];
                $cart_item['custom_data']['customer_message'] = $message;
                WC()->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
    WC()->cart->set_session();

    echo 'Order repeated';
    wp_die();
}

// Ajax filtering
add_action('wp_ajax_hansa_ajax_filter_products','hansa_ajax_filter_products_handler');
add_action('wp_ajax_nopriv_hansa_ajax_filter_products','hansa_ajax_filter_products_handler');
function hansa_ajax_filter_products_handler() {
    if( !session_id() && !headers_sent() && !is_admin()) {
        session_start();
    }
    $filters = !empty($_POST['filters']) ? $_POST['filters'] : array();
    $parent_id = isset($_POST['parent_cat']) ? (int)$_POST['parent_cat']: 0;
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $search_str = isset($_POST['s']) ? $_POST['s'] : '';

    $page_n = isset($_POST['page_n']) ? (int)$_POST['page_n'] : null;

    $args = array(
        'post_type' => 'product',
        'post_status' => get_query_statuses(),
        'posts_per_page' => intval(get_option('posts_per_page')) * $page_n,
        'paged' => 1,
        'order' => 'DESC',
        'orderby' => 'relevance',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy'  => 'product_visibility',
                'terms'     => array('exclude-from-search','exclude-from-catalog'),
                'field'     => 'slug',
                'operator'  => 'NOT IN'
            )
        )
    );

    if($page > 1) {
        $args['paged'] = $page;
    } else {
        if($page_n) {
            $args['posts_per_page'] = $page_n * intval(get_option('posts_per_page'));
        }
    }

    $f_aliases = array(
        'subcat' => 'product_cat',
        'tags' => 'product_tag',
        'grapes' => 'grape',
        'countries' => 'country',
        'volumes' => 'volume',
        'vintages' => 'vintage',
        'abvs' => 'abv',
        'producers' => 'producer',
        'regions' => 'region'
    );

    $tax_query = $args['tax_query'];
    $subtax_query = array();
    if($parent_id) {
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'terms' => array($parent_id),
            'field' => 'term_id',
            'operator' => 'IN'
        );
    }

    if(!empty($search_str)) {
        $args['s'] = $search_str;
        $args['extend_where'] = hansa_get_extended_search_params($search_str);
    }

    if(isset($filters['orderby']) && !empty($filters['orderby'])) {
        switch ($filters['orderby']) {
            case 'title':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'rating':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                break;
            case 'date':
                $args['orderby'] = 'date';
                break;
            case 'price-asc':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                break;
            default:
                $args['orderby'] = 'relevance';
                break;
        }
    }

    if(empty($search_str)) {
        $meta_query = array();
        if(isset($filters['price']) && !empty($filters['price'])) {
            $prices = get_minmax_price();
            $min = $prices['min'];
            $max = $prices['max'];
            $price_range = explode(',',$filters['price']);
            if(isset($price_range[0])) {
                $min = intval($price_range[0]);
            }
            if(isset($price_range[1])) {
                $max = intval($price_range[1]);
            }
            $meta_query = array(
                array(
                    'key' => '_price',
                    'value' => array($min, $max),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                )
            );
        }

        $is_tax = false;
        $subtax_query = array(
            'relation' => 'AND'
        );
        if(!empty($filters)) {
            foreach($filters as $key => $value) {
                if(($key == 'orderby') || ($key == 'price')) {
                    continue;
                }
                if(!empty($value)) {
                    if(isset($f_aliases[$key]) || is_taxonomy($key)) {
                        $taxonomy = isset($f_aliases[$key]) ? $f_aliases[$key] : $key;
                        $ids = explode(',',$value);
                        $subtax_query[] = array(
                            'taxonomy' => $taxonomy,
                            'terms' => $ids,
                            'field' => 'term_id',
                            'operator' => 'IN'
                        );
                        $is_tax = true;
                    }
                }
            }
        }

        if($is_tax) {
            $tax_query[] = $subtax_query;
        }

        if(!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
        if(!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }
    }


    $c_query = new WP_Query($args);

    $html = '';

    ob_start();
    if($c_query->have_posts()) {
        while ( $c_query->have_posts() ) {
            $c_query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        wp_reset_postdata();
    } else {
        wc_get_template( 'loop/no-products-found.php' );
    }

    $html = ob_get_contents();
    ob_end_clean();

    $response['n'] = $c_query->found_posts;
    $response['max_p'] = $c_query->max_num_pages;

    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = '_price';
    $args['posts_per_page'] = 1;
    $args['order'] = 'DESC';
    $price_query = new WP_Query($args);
    $response['max_price'] = 0;
    $response['args'] = $args;
    if($price_query->have_posts()) {
        while($price_query->have_posts()) {
            $price_query->the_post();
            $product = wc_get_product(get_the_ID());
            $response['max_price'] = ceil($product->get_price());
        }
        wp_reset_postdata();
    }
    $response['html'] = $html;
    echo json_encode($response);
    wp_die();
}



add_action('wp_ajax_hansa_custom_product_change','hansa_custom_product_change_handler');
add_action('wp_ajax_nopriv_hansa_custom_product_change','hansa_custom_product_change_handler');
function hansa_custom_product_change_handler() {
    $product_id = $_POST['product_id'];
    $product = wc_get_product($product_id);
    $response = array();
    if($product) {
        $args = array ('post_type' => 'product', 'post_id' => $product_id);
        $comments = get_comments( $args );
        $response['reviews'] = wp_list_comments( array( 'callback' => 'woocommerce_comments','echo' => false ), $comments);
        $response['price'] = $product->get_price() + 10;
        $response['points'] = loyale_get_price_points($product->get_price() + 10);
        $response['rating'] = $product->get_average_rating();
        $response['reviews_count'] = $product->get_review_count();
        $response['curr_symbol'] = get_woocommerce_currency_symbol();
    }
    echo json_encode($response);
    wp_die();
}

add_action('wp_ajax_hansa_add_custom_product_to_cart','hansa_add_custom_product_to_cart_handler');
add_action('wp_ajax_nopriv_hansa_add_custom_product_to_cart','hansa_add_custom_product_to_cart_handler');
function hansa_add_custom_product_to_cart_handler() {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $label_1 = $_POST['label_1'];
    $label_2 = $_POST['label_2'];
    if(wc_get_product($product_id) && $quantity > 0) {
        $is_added = false;
        $custom_data = array();
        $custom_data = array(
            'label_1' => $label_1,
            'label_2' => $label_2,
            'labeled_product_id' => $product_id
        );
        $custom_product = get_page_by_title( 'Custom Label', OBJECT, 'product' );
        if($custom_product) {
            $custom_product_id = $custom_product->ID;
            $custom_product_cart_id = WC()->cart->add_to_cart( $custom_product_id, $quantity,  0, array(), array('custom_data' => $custom_data ) );
        }
        $cart_key = is_product_in_cart($product_id);
        if($cart_key) {
            $current_cart_item = WC()->cart->get_cart_item( $cart_key );
            $new_q = intval($current_cart_item['quantity']) + $quantity;
            WC()->cart->set_quantity($cart_key, $new_q);
        } else {
            $is_added = WC()->cart->add_to_cart( $product_id, $quantity);
        }

    }
    echo $is_added;
    wp_die();
}

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
function woocommerce_ajax_add_to_cart() {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}

add_action( 'wp_ajax_hansa_get_update_jwt', 'hansa_update_payment_jwt' );
add_action( 'wp_ajax_nopriv_hansa_get_update_jwt', 'hansa_update_payment_jwt' );
function hansa_update_payment_jwt() {
    session_start();
    $tp = new WC_TP_Gateway();
    echo $tp->update_payment_address_details(0, '', [], [], 0, WC()->cart->get_total());
    wp_die();
}

add_action( 'wp_ajax_hansa_session_delivery_addr', 'hansa_session_delivery_addr' );
add_action( 'wp_ajax_nopriv_hansa_session_delivery_addr', 'hansa_session_delivery_addr' );
function hansa_session_delivery_addr() {
    $addr = $_POST['addr'];
    if($addr) {
        session_start();
        $_SESSION['del_addr'] = $addr;
        wp_die(true);
    }
    wp_die(false);
}

add_action( 'wp_ajax_hansa_get_delivery_addr', 'hansa_get_delivery_addr' );
add_action( 'wp_ajax_nopriv_hansa_get_delivery_addr', 'hansa_get_delivery_addr' );
function hansa_get_delivery_addr() {
    session_start();
    if($_SESSION['del_addr']) {
        wp_die($_SESSION['del_addr']);
    }
    wp_die(false);
}

add_action( 'wp_ajax_hansa_generate_gtag_by_id', 'hansa_generate_gtag_by_id' );
add_action( 'wp_ajax_nopriv_hansa_generate_gtag_by_id', 'hansa_generate_gtag_by_id' );
function hansa_generate_gtag_by_id() {
    $product_data = array();
    $product_id = $_POST['product_id'];
    $product = wc_get_product($product_id);
    if($product) {
        $product_data['sku'] = $product->get_sku();
        $product_data['price'] = $product->get_price();
        $product_data['name'] = $product->get_title();
    }
    echo json_encode($product_data);
    wp_die();
}
