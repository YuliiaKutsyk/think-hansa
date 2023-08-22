<?php
// Add loyale customer ID field to user profile
add_action('show_user_profile', 'hansa_add_user_loyale_id');
add_action('edit_user_profile', 'hansa_add_user_loyale_id');
function hansa_add_user_loyale_id( $user ) {
?>
	<h2>Loyale</h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="loyale_customer_id"><?php _e( 'Loyale Customer ID' ); ?></label>
            </th>
            <td>
                <input type="text" name="loyale_customer_id" id="loyale_customer_id" value="<?php echo esc_attr( get_the_author_meta( 'loyale_customer_id', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
         <tr>
            <th>
                <label for="loyale_gain_rate"><?php _e( 'Loyale Gain Rate' ); ?></label>
            </th>
            <td>
                <input type="text" name="loyale_gain_rate" id="loyale_gain_rate" value="<?php echo esc_attr( get_the_author_meta( 'loyale_gain_rate', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
         <tr>
            <th>
                <label for="loyale_rounding"><?php _e( 'Loyale Rounding' ); ?></label>
            </th>
            <td>
                <input type="text" name="loyale_rounding" id="loyale_rounding" value="<?php echo esc_attr( get_the_author_meta( 'loyale_rounding', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}

// Show user list "Loyale linked" column
add_filter( 'manage_users_columns', 'loyale_add_user_column' );
function loyale_add_user_column( $column ) {
    $column['is_loyale_linked'] = 'Loyale linked';
    return $column;
}

add_filter( 'manage_users_custom_column', 'loyale_display_is_user_linked', 10, 3 );
function loyale_display_is_user_linked( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'is_loyale_linked' :
        	$is_linked = false;
        	$customer_id = get_the_author_meta( 'loyale_customer_id', $user_id );
        	if($customer_id) {
        		$is_linked = true;
        	}
        	$output = '<span style="color: #FF7758; font-weight:500;">Not Linked</span>';
        	if($is_linked) {
        		$output = '<span style="color: #3eaf03; font-weight:500;">Linked</span>';
        	}
            return $output;
        default:
    }
    return $val;
}

// Save loyale customer ID to user profile
add_action( 'personal_options_update', 'update_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'update_extra_profile_fields' );
function update_extra_profile_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) )
        update_user_meta( $user_id, 'loyale_customer_id', $_POST['loyale_customer_id'] );
    if ( current_user_can( 'edit_user', $user_id ) )
        update_user_meta( $user_id, 'loyale_gain_rate', $_POST['loyale_gain_rate'] );
    if ( current_user_can( 'edit_user', $user_id ) )
        update_user_meta( $user_id, 'loyale_rounding', $_POST['loyale_rounding'] );
}

// Get Loyale admin token
function loyale_get_admin_token() {
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$token = false;

	$url = 'https://api.loyale.io/api/AdminToken';

    $response = wp_remote_post($url,array(
        'headers' => array(
			'Content-Type' => 'application/json',
			'Scheme' => $loyale_scheme_id
		),
        'body' => json_encode(array(
			'email'    => 'wordpress@hansa.com.mt',
			'password' => 'p-6Q!yPNPgwJb4C+'
        ))
    ));

    if(!is_wp_error($response)) {
    	$decoded = json_decode(wp_remote_retrieve_body($response));
    	$token = $decoded->token;
    }

    return $token;
}

// Get loyale customer data
function loyale_get_customer( $customer_id ) {
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$admin_token = loyale_get_admin_token();

	$url = 'https://api.loyale.io/api/Customer/' . $customer_id;
	$response = wp_remote_get($url,array(
        'headers' => array(
			'Content-Type' => 'application/json',
			'Scheme' => $loyale_scheme_id,
			'Authorization' =>  'Bearer ' . $admin_token
		)
    ));

    if( !is_wp_error( $response ) ) {
    	$customer = json_decode(wp_remote_retrieve_body($response), true);
    } else {
    	$customer = false;
    }
    return $customer;
}

// Update Loyale customer meta
function loyale_update_customer_meta($customer_id,$key,$name,$value){
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$admin_token = loyale_get_admin_token();

    $url = 'https://api.loyale.io/api/AdditionalCustomerFields';
    $response = wp_remote_request($url,array(
        'method' => 'PUT',
        'headers' => array(
			'Content-Type' => 'application/json',
			'Scheme' => $loyale_scheme_id,
			'Authorization' =>  'Bearer ' . $admin_token
		),
        'body' => json_encode(array(
	        "customerId" => $customer_id,
	        "schemeId"=> $loyale_scheme_id,
	        "name"=> $name,
	        "key"=> $key,
	        "value"=> $value
        ))
    ));
    if(!is_wp_error($response)) {
		$returnDecode = json_decode(wp_remote_retrieve_body($response), true);
    } else {
		$returnDecode = false;
    }
	return $returnDecode;
}

//Get loyale customer points balance
function loyale_get_customer_points() {
	$points = 0;
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$user_id = get_current_user_id();
	if($user_id) {
		$customer_id = get_user_meta($user_id, 'loyale_customer_id', true);
		$admin_token = loyale_get_admin_token();

	    $url = 'https://api.loyale.io/api/PointBalance?Filters=customerId==' . $customer_id . ',schemeId==' . $loyale_scheme_id;

	    $response = wp_remote_get($url,array(
	        'headers' => array(
				'Content-Type' => 'application/json',
				'Scheme' => $loyale_scheme_id,
				'Authorization' =>  'Bearer ' . $admin_token
			)
	    ));

		$decoded = json_decode( wp_remote_retrieve_body($response) );
	}
    if( !is_wp_error( $response ) ) {
    	$points = $decoded[0]->pointsValue;
    	WC()->session->set('customer_points',$points);
    } else {
    	$points = false;
    }
    return $points;
}

// Update points data on page load
add_action('wp','hansa_set_loyale_pts_data');
function hansa_set_loyale_pts_data() {
	$rates = loyale_get_gainrate(get_current_user_id());
	$gainRate = $rates['gainRate'];
	$rounding = $rates['rounding'];
	if(!isset($gainRate) || !isset($rounding)) {
		loyale_update_gainrate_handler();
	}
}

// Get points for product
function loyale_get_product_points($product_id) {
	$points = 0;
	$product = wc_get_product($product_id);
	if($product) {
		
		$rates = loyale_get_gainrate(get_current_user_id());
		$gainRate = $rates['gainRate'];
		$rounding = $rates['rounding'];
		$price = $product->get_price();
		switch ( $rounding ) {
			case 0:
				$price = round( $price );
				break;
			case 1:
				$price = ceil( $price );
				break;
			case 2:
				$price = floor( $price );
				break;
		}
		if ( isset( $gainRate ) ) {
			$points = $gainRate * (float)$price;
		}
	}
	return (int)$points;
}

// Get points for value
function loyale_get_price_points($price) {
	$points = 0;
	$rates = loyale_get_gainrate(get_current_user_id());
	$gainRate = $rates['gainRate'];
	$rounding = $rates['rounding'];
	switch ( $rounding ) {
		case 0:
			$price = round( $price );
			break;
		case 1:
			$price = ceil( $price );
			break;
		case 2:
			$price = floor( $price );
			break;
	}
	if ( isset( $gainRate ) ) {
		$points = $gainRate * (float)$price;
	}
	return (int)$points;
}

// Get value for points
function loyale_get_points_value($points) {
	$value = 0;
	$rates = loyale_get_gainrate(get_current_user_id());
	$gainRate = $rates['gainRate'];
	if ( isset( $gainRate ) && $gainRate > 0) {
		$value = $points / $gainRate;
	}
	return (int)$value;
}

//Get points redemption value
function loyale_get_points_redemption() {
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$value = 0;

	$url = 'https://api.loyale.io/api/Scheme/' . $loyale_scheme_id;
    $response = wp_remote_get($url,array(
        'headers' => array(
			'Content-Type' => 'application/json'
		)
    ));

    if(!is_wp_error($response)) {
    	$decoded = json_decode(wp_remote_retrieve_body($response));
    	$value = $decoded->pointRedemptionPerCurrency;
    }

	return $value;
}

function loyale_is_points_available() {
	$is_points = false;
	if(is_user_logged_in()) {
		$user_id = get_current_user_id();
		if(current_user_can('loyalty') && get_user_meta($user_id,'loyale_customer_id',true)) {
			$is_points = true;
		}
	}
	return $is_points;
}

// Send staff order to loyale
function loyale_send_order( $order_id ) {
	$loyale_scheme_id = get_field('loyale_scheme_id','option');
	$admin_token = loyale_get_admin_token();
	$order = wc_get_order( $order_id );
	if(get_post_meta( $order_id, 'is_sent_to_loyale', true)  != true) {
		$order_data = $order->get_data();
		$line_items = array();
		$k = 0;
	   	foreach ($order->get_items() as $item_key => $item ) {
	    	$item_id = $item->get_id();

	        $product      = $item->get_product();
	        $product_id   = $item->get_product_id();
	        $product_sku  = $product->get_sku();
			$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
			$primary_cat = yoast_get_primary_term_id('product_cat',$product_id);
			if(!$primary_cat) {
				$primary_cat = hansa_get_product_top_cats($product_id);
				if($primary_cat) {
					$primary_cat = $primary_cat[0];
				} else {
					$primary_cat = '';
				}
			}
			$order->add_order_note('Primary cat: ' . $primary_cat);
			if($is_addon) {
				continue;
			}
	    	$quantity     = $item->get_quantity();
	    	$line_items[$k]['id'] = $product_sku;
	    	$line_items[$k]['quantity'] = $quantity;
	    	$line_items[$k]['unitPrice'] = $product->get_price();
	    	$line_items[$k]['description'] = $product->get_title();
	    	$line_items[$k]['groupId'] = $primary_cat;
	    	$k++;
	    }

		$order_date_created = $order_data['date_created']->date( 'Y-m-d\TH:i:s' );

        $externalRefIdPrice        = md5( uniqid( rand(), 1 ) ) . "_hansa_" . $order_id;
        $externalRefIdRedeemAmount = md5( uniqid( rand(), 1 ) ) . "_hansa_" . $order_id;
        $user_id = $order->get_user_id();
		$loyale_id = get_user_meta($user_id, 'loyale_customer_id', true);
		if (!empty($loyale_id)){
		 	$url = 'https://api.loyale.io/api/Transaction';
			$redeem_amount = (float)get_post_meta($order_id,'redeem_amount',true);
			$redeem_amount = $redeem_amount > 0 ? $redeem_amount : 0;
			if($redeem_amount > 0) {
				$data_points = [
					"value"           => (float)$redeem_amount,
					"cashRedeemed"    => 0,
					"saleCurrency"    => "EUR",
					"lineItems"       => [
					],
					"couponsUsed"     => [
					],
					"customerId"      => $loyale_id,
					"valueType"       => 0,
					"transactionType" => 3,
					"posId"           => "loyale",
					"posType"         => "API",
					"outletId"        => '46ce7912-4970-4a45-864c-7a62c654326a',
					"externalRefId"   => $externalRefIdRedeemAmount,
					"description"     => "",
					"transactionDate" => $order_date_created
				];


				$order->add_order_note(json_encode($data_points));

		        $result = wp_remote_post($url,array(
			        'body' => json_encode($data_points),
			        'headers' => array(
						'Content-Type' => 'application/json',
						'Scheme' => $loyale_scheme_id,
						'Authorization' =>  'Bearer ' . $admin_token
					)
			    ));
    			$body = wp_remote_retrieve_body($result);
				$order->add_order_note($body);
			}

	        $total = $order->get_subtotal() + $order->get_total_tax() - $redeem_amount - hansa_get_addons_total($order_id);
	        $data = [
	            "value"           => (float) $total,
	            "cashRedeemed"    => 0,
	            "saleCurrency"    => "EUR",
	            "lineItems"       => $line_items,
	            "couponsUsed"     => [
	            ],
	            "customerId"      => $loyale_id,
	            "valueType"       => 0,
	            "transactionType" => 0,
	            "posId"           => "loyale",
				"outletId"        => '46ce7912-4970-4a45-864c-7a62c654326a',
	            "posType"         => "API",
	            "externalRefId"   => $externalRefIdPrice,
	            "description"     => "",
	            "transactionDate" => $order_date_created
	        ];

	        $result = wp_remote_post($url,array(
		        'body' => json_encode($data),
		        'headers' => array(
					'Content-Type' => 'application/json',
					'Scheme' => $loyale_scheme_id,
					'Authorization' =>  'Bearer ' . $admin_token
				)
		    ));
    		$body = wp_remote_retrieve_body($result);
	        $order->add_order_note( "Loyale response:\n" . $body);
			update_post_meta( $order_id, 'is_sent_to_loyale', true);
	    }
	}
}


if ( ! wp_next_scheduled( 'loyale_update_gainrate' ) ) {
    wp_schedule_event( strtotime('00:00:00'), 'daily', 'loyale_update_gainrate' );
}

// Update gainrate at midnight
add_action( 'loyale_update_gainrate', 'loyale_update_gainrate_handler', 10, 0 );
function loyale_update_gainrate_handler(){
	$loyale_scheme_id = get_field('loyale_scheme_id','option');

	$url = 'https://api.loyale.io/api/Transaction/GainRate';

    $response = wp_remote_get($url,array(
        'headers' => array(
			'Content-Type' => 'application/json',
			'Scheme' => $loyale_scheme_id,
			'outletId' => '46ce7912-4970-4a45-864c-7a62c654326a'
		)
    ));

	$vars = array();
    if( !is_wp_error( $response ) ) {
		$decoded = json_decode( wp_remote_retrieve_body($response) );
		update_option('loyale_gain_rate',$decoded->gainRate);
		update_option('loyale_rounding',$decoded->rounding);
    }
}

// Update gain rate for user attached to Loyale on login
add_action('wp_login', 'loyale_update_user_gainrate', 10, 2);
add_action('loyale_before_login', 'loyale_update_user_gainrate', 10, 2);
function loyale_update_user_gainrate( $user_login, $user ) {
	$user_id = $user->ID;
	$loyale_customer_id = get_user_meta($user_id,'loyale_customer_id',true);
	if($loyale_customer_id) {
		$admin_token = loyale_get_admin_token();
		$loyale_scheme_id = get_field('loyale_scheme_id','option');
		$url = 'https://api.loyale.io/api/Transaction/GainRate/' . $loyale_customer_id . '?outletId=46ce7912-4970-4a45-864c-7a62c654326a';

	    $response = wp_remote_get($url,array(
	        'headers' => array(
				'Content-Type' => 'application/json',
				'Scheme' => $loyale_scheme_id,
				'Authorization' =>  'Bearer ' . $admin_token
			)
	    ));

		$vars = array();
	    if( !is_wp_error( $response ) ) {
			$decoded = json_decode( wp_remote_retrieve_body($response) );
			update_user_meta($user_id, 'loyale_gain_rate', $decoded->gainRate);
			update_user_meta($user_id, 'loyale_rounding', $decoded->rounding);
	    }
	}
}

// Refund points and redeem on order failed,cancelled or refunded
add_action('woocommerce_order_status_changed', 'loyale_refund_points_from_order', 10, 3);
function loyale_refund_points_from_order($order_id, $old_status, $new_status) {
	$statuses = array('refunded','failed','cancelled');
    if(in_array($new_status, $statuses)) {
		$loyale_scheme_id = get_field('loyale_scheme_id','option');
		$admin_token = loyale_get_admin_token();
	    $order = wc_get_order($order_id);
	    $order_total = $order->get_total();	    
	    $is_sent_to_loyale = get_post_meta( $order_id, 'is_sent_to_loyale', true);
	    $is_refunded = get_post_meta( $order_id, 'is_refunded', true);

		if($is_sent_to_loyale && !$is_refunded) {
			$order_data = $order->get_data();
			$total_points = get_post_meta( $order_id, 'order_total_points', true);
	        $user_id = $order->get_user_id();
			$loyale_id = get_user_meta($user_id, 'loyale_customer_id', true);
			if($total_points && $loyale_id) {
				$order_date_created = date( 'Y-m-d\TH:i:s' );
		        $externalRefIdPrice        = md5( uniqid( rand(), 1 ) ) . "_hansa_" . $order_id;
		        $externalRefIdRedeemAmount = md5( uniqid( rand(), 1 ) ) . "_hansa_" . $order_id;

			    $url = 'https://api.loyale.io/api/Transaction';
				$redeem_amount = (float)get_post_meta($order_id,'redeem_amount',true);
				$points_redemption = loyale_get_points_redemption();
				if($redeem_amount > 0) {
					$data_points = [
						"value"           => intval($redeem_amount * $points_redemption),
						"cashRedeemed"    => 0,
						"saleCurrency"    => "EUR",
						"lineItems"       => [],
						"couponsUsed"     => [],
						"customerId"      => $loyale_id,
						"valueType"       => 1,
						"transactionType" => 1,
						"posId"           => "loyale",
						"posType"         => "API",
						"outletId"        => '46ce7912-4970-4a45-864c-7a62c654326a',
						"externalRefId"   => $externalRefIdRedeemAmount,
						"description"     => "Refund for order #" . $order_id,
						"transactionDate" => $order_date_created
					];


					$order->add_order_note(json_encode($data_points));

			        $result = wp_remote_post($url,array(
				        'body' => json_encode($data_points),
				        'headers' => array(
							'Content-Type' => 'application/json',
							'Scheme' => $loyale_scheme_id,
							'Authorization' =>  'Bearer ' . $admin_token
						)
				    ));
	    			$body = wp_remote_retrieve_body($result);
					$order->add_order_note("Refunded redeem:\n" . $body);
				}

				$earned_points = get_post_meta($order_id,'order_total_points', true);
		        $data = [
		            "value"           => intval($earned_points),
		            "cashRedeemed"    => 0,
		            "saleCurrency"    => "EUR",
		            "lineItems"       => [],
		            "couponsUsed"     => [],
		            "customerId"      => $loyale_id,
		            "valueType"       => 1,
		            "transactionType" => 2,
		            "posId"           => "loyale",
					"outletId"        => '46ce7912-4970-4a45-864c-7a62c654326a',
		            "posType"         => "API",
		            "externalRefId"   => $externalRefIdPrice,
		            "description"     => "Refund for order #" . $order_id,
		            "transactionDate" => $order_date_created
		        ];

		        $result = wp_remote_post($url,array(
			        'body' => json_encode($data),
			        'headers' => array(
						'Content-Type' => 'application/json',
						'Scheme' => $loyale_scheme_id,
						'Authorization' =>  'Bearer ' . $admin_token
					)
			    ));
	    		$body = wp_remote_retrieve_body($result);
		        $order->add_order_note( "Loyale refund response:\n" . $body);
		        update_post_meta( $order_id, 'is_refunded', true);
			}
		} else {
	        $order->add_order_note( "Cannot refund order on Loyale. Order wasn't sent on Loyale either was already refunded" . $body);
	    }
    }
}

function loyale_get_gainrate($user_id = 0) {
	$gainrate = array();
	if(is_user_logged_in() && $user_id) {
		$gainRate = intval(get_user_meta($user_id, 'loyale_gain_rate', true));
		$rounding = intval(get_user_meta($user_id, 'loyale_rounding', true));
		if(!$gainRate) {
			$gainRate = intval(get_option('loyale_gain_rate'));
		}
		if(!$rounding) {
			$rounding = intval(get_option('loyale_rounding'));
		}
		$gainrate['gainRate'] = $gainRate;
		$gainrate['rounding'] = $rounding;
	} else {
		$gainRate = intval(get_option('loyale_gain_rate'));
		$rounding = intval(get_option('loyale_rounding'));
		$gainrate['gainRate'] = $gainRate;
		$gainrate['rounding'] = $rounding;
	}
	return $gainrate;
}