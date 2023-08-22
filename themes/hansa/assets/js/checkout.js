let ajax_url_ = backend_vars.ajax_url;

(function($) {
	// Inputting gift custom message
	let isInputting = false;
	$('.gift-card_text').on('blur',function(){
		let value = $(this).val();
		let parent = $(this).closest('.checkout-product.gift');
		if(parent.hasClass('in-cart') && value != '') {
			let productId = parseInt(parent.attr('data-id'));
			let cartId = parent.attr('data-cart-id');
			let data = {
				action: 'hansa_add_gift_message',
				product_id: productId,
				message: value,
				cart_id: cartId
			};
			isInputting = true;
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					console.log('saved gift message');
				},
				error: function(response) {
					console.log('not saved gift message');
				}
			});
		}
	});

	// Adding gift to cart on checkout
	let isGiftAdding = false;
	$('.checkout-product.gift .quantity-less, .checkout-product.gift .quantity-more').on('click',function(){
		clearTimeout(isGiftAdding);
		let giftsBlock = $('.checkout-right_card.gifts-block');
		let parent = $(this).closest('.checkout-product.gift');
		let value = parseInt(parent.find('.gift-card_quantity').val());
		let addingValue = 0;
		if($(this).hasClass('quantity-less')) {
			addingValue = value > 0 ? --addingValue : 0;
		} else {
			addingValue++;
		}
		value += addingValue;
		let productId = parseInt(parent.attr('data-id'));
		let cartId = parent.attr('data-cart-id');
		let message = '';
		if(parent.find('textarea').length) {
			message = parent.find('textarea').val();
		}
		let data = {
			action: 'hansa_add_gift_to_cart',
			product_id: productId,
			message: message,
			cart_id: cartId,
			quantity: value
		};
		isGiftAdding = setTimeout(function(){
			addPreloader(giftsBlock);
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					if(response.length > 1) {
						parent.attr('data-cart-id',response);
						parent.addClass('in-cart');
					}
					if(value == 0) {
						parent.attr('data-cart-id','');
					}
					removePreloader(giftsBlock);
					if(parent.hasClass('first')) {
						if(value > 0) {
							giftsBlock.find('.gift-card_text').show();
						} else {
							giftsBlock.find('.gift-card_text').hide();
						}
					}
					$('body').trigger('update_checkout', { update_shipping_method: true });
				},
				error: function(response) {
					removePreloader(giftsBlock);
				}
			});
		},1500);
	});

	// Apply redeem
	$('body').on('click','#redeem-button',function(e){
		e.preventDefault();
		let redeemValue = $('#redeem_input').val();
		redeemValue = parseFloat(redeemValue.substring(1));
		console.log(redeemValue);
		if(redeemValue > 0) {
			let data = {
				action: 'hansa_apply_redeem',
				redeem_value: redeemValue
			};
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					let redeemData = JSON.parse(response);
					if(redeemData['is_applied']) {
						$('body').trigger('update_checkout', { update_shipping_method: true });
						console.log(redeemData);
					}
				},
				error: function(response) {
				}
			});
		}
	});

	//Remove redeem
	$('body').on('click','.remove-redeem',function(e){
		e.preventDefault();
		let redeemValue = parseFloat($('#redeem_input').val());
		if(redeemValue > 0 && $('.promocode-message.redeem').length) {
			let data = {
				action: 'hansa_remove_redeem'
			};
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					$('body').trigger('update_checkout', { update_shipping_method: true });
				},
				error: function(response) {
				}
			});
		}
	});

	// Apply coupon
	$('body').on('click','#coupon-button',function(e){
		e.preventDefault();
		let couponValue = $('#coupon_code_ph').val();
		if(couponValue != '') {
			let data = {
				action: 'hansa_apply_coupon',
				coupon_code: couponValue
			};
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					let couponData = JSON.parse(response);
					$('body').trigger('update_checkout', { update_shipping_method: true });
					console.log(couponData);
				},
				error: function(response) {
				}
			});
		}
	});

	//Remove coupon
	$('body').on('click','.remove-coupon',function(e){
		e.preventDefault();
		let couponValue = $(this).attr('data-code');
		if(couponValue != '') {
			let data = {
				action: 'hansa_remove_coupon',
				coupon_code: couponValue
			};
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					if(response) {
						$('body').trigger('update_checkout', { update_shipping_method: true });
					}
				},
				error: function(response) {
				}
			});
		}
	});

	// Changing of shipping
	$('body').on('click','.current_addr_checkbox',function(){
		$('#ship-to-different-address-checkbox').prop('checked',false);
		$('.add-new_adress_form').removeClass('visible-100');
		$('.add-delivery_button').show();
	});


	// Add/edit address
	$('body').on('click','.save-adress_input,.edit-adress_input',function(e){
		e.preventDefault();
		let btn = $(this);
		if(!btn.hasClass('clicked')) {
			let isUpdate = btn.hasClass('edit-adress_input');
			$('.woocommerce-shipping-fields .profile-addr-err').remove();
			$('input.error').removeClass('error');
			let block = $('.checkout-delivery-block');
			let is_errors = false;
			let parent = $('.woocommerce-shipping-fields');
			let isSave = $('.enq-save_adress').is(':checked');
			addPreloader(block);
			parent.find('.validate-required input').each(function(){
				if($(this).val().trim() == '') {
					$(this).closest('.form-row').addClass('woocommerce-invalid');
					is_errors = true;
					removePreloader(block);
					return;
				}
			});
			let actionName = 'hansa_add_profile_address';
			if(isUpdate) {
				actionName = 'hansa_update_profile_address';
			} else {
				// if(!isSave) {
				// 	actionName = 'hansa_add_address_to_session';
				// }
			}
			if(!is_errors) {
				let data = {
					action: actionName,
					shipping_first_name: parent.find("input[name=shipping_first_name]").val(),
					shipping_last_name: parent.find("input[name=shipping_last_name]").val(),
					shipping_company: parent.find('input[name=shipping_company]').val(),
					shipping_vat: parent.find('input[name=shipping_vat]').val(),
					shipping_address_1: parent.find('input[name=shipping_address_1]').val(),
					shipping_address_2: parent.find('input[name=shipping_address_2]').val(),
					shipping_city: parent.find('input[name=shipping_city]').val(),
					shipping_postcode: parent.find('input[name=shipping_postcode]').val()
				};

				if(isUpdate) {
					data.address_id = parseInt(btn.attr('data-id'));
				}

				btn.addClass('clicked');
				$('.add-new_adress_form').slideUp();
				$.ajax({
					url: ajax_url_,
					data: data,
					method: 'POST',
					success: function(response) {
						if(response) {
							if(isUpdate) {
								$('.saved-addr[data-id='+data.address_id+'] label').text(data.shipping_address_1 + ', ' + data.shipping_address_2 + ', ' + data.shipping_city).trigger('click');
								$('.saved-addr[data-id='+data.address_id+'] input').trigger('click');
								$('#ship-to-different-address-checkbox').prop('checked',true);
								$('.saved-addr[data-id='+data.address_id+']').attr('data-addr-data', JSON.stringify(data));


								for (const key1 in data){
									$('input[name='+key1+']').val(data[key1]);
								}

							} else {
								$('.add-delivery_button').before('<div class="checkout-row saved-addr" data-id="'+response+'" data-addr-data=\''+JSON.stringify(data) +'\'><input type="radio" name="shipping_type[]" value="'+response+'" id="saved_addr_'+response+'"><label for="saved_addr_'+response+'" class="">'+ data.shipping_address_1 + ', ' + data.shipping_address_2 + ', ' + data.shipping_city +'</label><div class="checkout-row_actions"><a href="#" class="edit-current_row  edit-saved-addr">Edit</a><a href="#" class="delete-current_addr delete-current_row"></a></div></div>');

								$('.saved-addr[data-id='+response+'] label').text(data.shipping_address_1 + ', ' + data.shipping_address_2 + ', ' + data.shipping_city).trigger('click');
								$('.saved-addr[data-id='+response+'] input').trigger('click');
								$('#ship-to-different-address-checkbox').prop('checked',true);
							}
						}
						$('input[name=shipping_country]').val('MT');
						$('input.shipping_country_ph').val('Malta');
						// $('#ship-to-different-address-checkbox').prop('checked',false);
						$('.add-delivery_button').show();
						$('.add-new_adress_form').hide();
						$('.add-new_adress_form').removeClass('visible-100');
						btn.removeClass('clicked');
						removePreloader(block);
					},
					error: function(response) {
						btn.removeClass('clicked');
						removePreloader(block);
					}
				});
			}
		}
	});

	//Delete current address
	$('body').on('click','.delete-current_addr',function(e){
		e.preventDefault();
		if(!$(this).hasClass('clicked')) {
			let block = $('.checkout-delivery-block');
			let btn = $(this);
			let parent = $(this).closest('.saved-addr');
			let addrId = parent.attr('data-id');
			let data = {
				action: 'hansa_remove_profile_address',
				addr_id: addrId
			};
			addPreloader(block);
			$.ajax({
				url: ajax_url_,
				data: data,
				method: 'POST',
				success: function(response) {
					if(response) {
						parent.slideUp();
						$('.current_addr_checkbox').trigger('click');
					}
					btn.removeClass('clicked');
					removePreloader(block);
				},
				error: function(response) {
					btn.removeClass('clicked');
					removePreloader(block);
				}
			});
		}
	});

	if($('.checkout-row[class*=shipping-local_pickup] input:checked').length) {
		$('.checkout-addr-rows,.woocommerce-shipping-fields').hide();
	}

	//Change shipping method input
	$('body').on('change','.woocommerce-shipping-methods input[type="radio"]',function(){
		let value = $(this).val();
		if($(this).is(':checked')) {
			$('.woocommerce-shipping-methods label').removeClass('active');
			$(this).next('label').addClass('active');
			if(value.indexOf('local_pickup') !== -1) {
				$('.checkout-addr-rows,.woocommerce-shipping-fields').hide();
				$('input[name="ship_to_different_address"]').prop('checked',false);
			} else {
				if($('.woocommerce-shipping-fields').attr('data-shipping-zone') == 'Malta') {
					$('input[name="ship_to_different_address"]').prop('checked',false);
				} else {
					$('input[name="ship_to_different_address"]').prop('checked',true);
				}
				$('.checkout-addr-rows,.woocommerce-shipping-fields').show();
			}
		}
	});

	$('body').on('click','.saved-addr label',function(){
		$('#ship-to-different-address-checkbox').prop('checked',true);
	});

	// Edit address
	$('body').on('click','.edit-saved-addr',function(e){
		e.preventDefault();
		let parent = $(this).closest('.saved-addr');
		let addrId = parent.attr('data-id');
		let addrData = JSON.parse(parent.attr('data-addr-data'));
		$('input[name="shipping_type[]"][value="' + addrId + '"]').trigger('click');
		for (const key in addrData){
			$('input[name='+key+']').val(addrData[key]);
		}
		$('input[name=shipping_country]').val('MT');
		$('input.shipping_country_ph').val('Malta');
		$('.edit-adress_input').attr('data-id',addrId).show();
		$('.save-addr-row').hide();
		$('.add-new_adress_form').show().removeClass('hidden');
		$('.save-adress_input').hide();
		$('.add-new_adress_form h6 span').text('Edit');
	});

	$('body').on('click','.add-delivery_button',function(){
		$('.add-new_adress_form h6 span').text('Add New');
		$('.woocommerce-shipping-fields input[name*=shipping]').val('');
		$('input[name=shipping_country]').val('MT');
		$('input.shipping_country_ph').val('Malta');
		$('.save-adress_input').show();
		$('.edit-adress_input').hide();
	});

	// $('#place_order.not-accepted').on('click',function(e){
	// 	e.preventDefault();
	// });

	$('#terms-checkbox_input').on('change',function(){
		if(($(this)).is(':checked')) {
			$('#place_order').removeClass('not-accepted');
		} else {
			$('#place_order').addClass('not-accepted');
		}
	});

	let curr = backend_vars.currency;

	$('body').on('keyup','#redeem_input',function(){
		let value = parseInt($(this).val().replace(/\D/g,''));
		if(isNaN(value)) {
			value = 0;
		}
		let max = parseFloat($(this).attr('max'));
		let formatted = 0
		if(value > 0) {
			value /= 100;
		}
		if(value > max) {
			value = max.toFixed(2);
		}
		formatted = formatNumberToCurr(value);
		$(this).val(curr + formatted);
	});


	setInterval(function(){
		if(!$('.shipping_country_note').length) {
			$('#shipping_country_field').append('<p style="color: #000;font-size: 14px;opacity: 1;font-weight: 500;" class="shipping_country_note">We currently do not deliver to Gozo.</p>');
		}
		if(!$('.shipping_country_ph').length) {
			$('#shipping_country_field #shipping_country').hide();
			$('#shipping_country_field label').after('<input type="text" class="shipping_country_ph" readonly value="Malta">');
		}
		if($('input[type="tel"]').length) {
			$('input[type="tel"],#shipping_phone').each(function(){
				if(!$(this).attr('data-intl-tel-input-id')) {
					var input = document.getElementById($(this).attr('id'));
					window.intlTelInput(input, {
						initialCountry: 'MT',
						preferredCountries: ['mt']
					});
				}
			});
		}
	},1000);

	$('body').on('click','.ce-field',function(){
		if($(this).find('input').prop('checked')) {
			$(this).find('label').addClass('active');
		} else {
			$(this).find('label').removeClass('active');
		}
	});

    jQuery('body').on('update_checkout', function() {
        let updatedJWT = '';
        if (st !== undefined) {
            jQuery.ajax({
                url: ajax_url_,
                type: 'POST',
                data: {
                    'action': 'hansa_get_update_jwt'
                },
                success: function (data) {
                    updatedJWT = data;
                    st.updateJWT(updatedJWT);
                    tpWCGatewayEnableForm( 'updated_checkout' );
                },
                error: function (data) {
                }
            });
        }
    });

    $(window).on('load',function(){
        setTimeout(function() {
            tpWCGatewayEnableForm( 'updated_checkout' );
        }, 500);
    });

    // setInterval(function(){
	// 	$('label[for="payment_method_tp_gateway"]').text('Credit Card - Online Transaction');
    // },100);

})( jQuery );

function formatNumberToCurr(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}