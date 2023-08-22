let ajax_url = backend_vars.ajax_url;
let addPreloader,removePreloader;

(function($) {

	// Add preloader to element
	addPreloader = function(el) {
		el.append('<div class="h-preloader-inner"><div class="h-preloader"><div></div><div></div><div></div><div></div></div></div>');
		el.find('.h-preloader-inner').addClass('visible');
	}

	// Remove preloader
	removePreloader = function(el) {
		el.find('.h-preloader-inner').removeClass('visible');
		el.find('.h-preloader-inner').remove();
	}

	$('body').on('change','.product-content_holder .qty-number',function(){
		let value = $(this).val();
		$('.single_add_to_cart_button').attr('data-quantity',value);
	});


	$('body').on('click','.quantity-less,.quantity-more',function(){
		let btn = $(this);
		setTimeout(function(){
			let parent = btn.closest('.qty-wrap');
			let value = parent.find('.qty-number').val();
			parent.find('.add-to-cart').attr('data-quantity', value);
		},200);
	});

	$('.remove-current_review').on('click',function(e){
		e.preventDefault();
		let reviewId = parseInt($(this).attr('data-remove-id'));
		$('.delete-confirm.popup').fadeIn();
		$('.delete-confirm__btn').attr('data-id',reviewId);
	});

	$('.delete-confirm__btn.review').on('click',function(){
		if(!$(this).hasClass('clicked')) {
			let btn = $(this);
			let reviewId = btn.attr('data-id');
			let data = {
				action: 'hansa_remove_product_review',
				review_id: reviewId
			};
			$.ajax({
				url: ajax_url,
				data: data,
				method: 'POST',
				success: function(response) {
					if(response) {
						location.reload();
					} else {
						btn.removeClass('clicked');
					}
				},
				error: function(response) {
					btn.removeClass('clicked');
				}
			});
		}
	});

	$('.add-new_adress').on('click',function(e){
		e.preventDefault();
		$('.add-new_adress_form').show();
	});

	$('body').on('click','.profile-save-addr-btn,.profile-edit-addr-btn',function(e){
		e.preventDefault();
		let btn = $(this);
		if(!btn.hasClass('clicked')) {
			let isUpdate = btn.hasClass('profile-edit-addr-btn');
			$('.profile-addr-err').remove();
			$('input.error').removeClass('error');
			let is_errors = false;
			let parent = $('.add-new_adress_form');
			if(isUpdate) {
				parent = btn.closest('.edit-addr-form');
			}
			parent.find('input.required').each(function(){
				if(btn.val().trim() == '') {
					btn.addClass('error');
					let rowClass = '';
					if(btn.closest('.form-row_part--half').length) {
						rowClass = '.form-row_part--half';
					} else {
						rowClass = '.form-row';
					}
					btn.closest(rowClass).append('<span class="profile-addr-err">This field can\'t be empty.</span>');
					is_errors = true;
					return;
				}
			});
			let actionName = 'hansa_add_profile_address';
			if(isUpdate) {
				actionName = 'hansa_update_profile_address';
			}
			if(!is_errors) {
				let data = {
		            action: actionName,
		            shipping_first_name: parent.find("input[name=billing_first_name]").val(),
		            shipping_last_name: parent.find("input[name=billing_last_name]").val(),
		            shipping_company: parent.find('input[name=billing_company]').val(),
		            shipping_vat: parent.find('input[name=billing_vat]').val(),
		            shipping_address_1: parent.find('input[name=billing_address_1]').val(),
		            shipping_address_2: parent.find('input[name=billing_address_2]').val(),
		            shipping_city: parent.find('input[name=billing_city]').val(),
		            shipping_postcode: parent.find('input[name=billing_postcode]').val()
		        };

		        if(isUpdate) {
		        	data.address_id = parseInt(btn.attr('data-id'));
		        }

		        btn.addClass('clicked');

				$.ajax({
					url: ajax_url,
					data: data,
					method: 'POST',
					success: function(response) {
						if(response) {
							location.reload();
						} else {
							btn.removeClass('clicked');
						}
					},
					error: function(response) {
						btn.removeClass('clicked');
					}
				});
			}
		}
	});

	$('.remove-current_adress').on('click',function(e){
		e.preventDefault();
		let addrId = parseInt($(this).closest('.form-row').attr('data-id'));
		$('.delete-confirm.popup').fadeIn();
		$('.delete-confirm__btn').attr('data-id',addrId);
	});

	$('.delete-confirm__btn.addr').on('click',function(){
		if(!$(this).hasClass('clicked')) {
			let btn = $(this);
			let addrId = btn.attr('data-id');
			let data = {
	            action: 'hansa_remove_profile_address',
	            addr_id: addrId
	        };
			$.ajax({
				url: ajax_url,
				data: data,
				method: 'POST',
				success: function(response) {
					if(response) {
						location.reload();
					} else {
						btn.removeClass('clicked');
					}
				},
				error: function(response) {
					btn.removeClass('clicked');
				}
			});
		}
	});

	$('.edit-current_address').on('click',function(e) {
		e.preventDefault();
		$(this).closest('.form-row').next().slideToggle();
		let title = $(this).text();
		$(this).text(title == 'Edit' ? 'Hide' : 'Edit');
	});

	$('.cancel-edit-form_btn').on('click',function(e){
		e.preventDefault();
		$(this).closest('.edit-addr-form').slideUp();
	});

	$('.delete-confirm__close,.delete-confirm__back').on('click',function(){
		$('.delete-confirm.popup').fadeOut();
	});

	if(($('.single-product').length || $('.page-template-customize-tpl').length) && $('.comment-form').length) {
		let length = $('.comment-form .review-stars a').length;
		$('.form-row.stars').insertBefore('.comment-form-rating');
		$('.comment-form .review-stars a').on('click',function(e){
			e.preventDefault();
			$('.comment-form .review-stars a').removeClass('selected');
			$(this).addClass('selected');
			let index = $('.comment-form .review-stars a').length - $(this).index();
			$('select[name="rating"] option[value="' + index + '"]').prop('selected',true);
		});
	}

	let searchTimer;
	$('body').on('input','.header-search__input',function(){
		clearTimeout(searchTimer);
		let value = $(this).val();
		if(value != '') {
			searchTimer = setTimeout(function(){
				addPreloader($('.header-search'));
				let data = {
		            action: 'hansa_search_products',
		            s: value
		        };
				$.ajax({
					url: ajax_url,
					data: data,
					method: 'POST',
					success: function(response) {
						$('.search-product_dropdown .search-product').remove();
						$('.header-search__not-found').remove();
						if(response) {
							$('.search-product_dropdown').prepend(response);
							$('.product-dropdown_bottom .search-all-link').attr('href','/?s=' + value);
							$('.product-dropdown_bottom .search-all-link').show();
						} else {
							$('.product-dropdown_bottom .search-all-link').hide();
							$('.product-dropdown_bottom').prepend('<div class="header-search__not-found">No products found</div>');
						}
						$('.search-product_dropdown').show();
						removePreloader($('.header-search'));
					},
					error: function(response) {
						removePreloader($('.header-search'));
					}
				});
			},1000);
		} else {
			$('.search-product_dropdown').hide();
		}
	});

	// $('.header-search__input').on('blur',function(){
	// 	$('.search-product_dropdown').hide();
	// });

	$('body').on('click',function(e){
		if($('.search-product_dropdown').is(':visible')) {
			if(e.target != '.search-product_dropdown') {
				$('.search-product_dropdown').hide();
			}
		}
	});

	// Repeat order button
	$('.repeat-order_button').on('click',function(e){
		e.preventDefault();
		$(this).addClass('clicked');
		let orderBlock = $(this).closest('.woocommerce-orders-table__row');
		let orderId = parseInt(orderBlock.attr('data-order-id'));
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'hansa_repeat_order',
				'id': orderId
			},
			success: function( data ) {
				//alert(data);
				window.location.href = '/cart';
			}
		});
	});

	// Update cart on quantity change
	let updateCartTimer;
	$('body').on('change','.cart-item .qty-number',function(){
		clearTimeout(updateCartTimer);
		setTimeout(function(){
			$("[name='update_cart']").removeAttr('disabled');
	        $("[name='update_cart']").trigger("click"); 
		},1500);
	});

	// Category page filtering
	$('.filter-form_row').on('click',function(){
		let isSearch = false;
		let isSort = $(this).hasClass('filter-form_row--sort');
		if($('.archive-wrapper').attr('data-s') !== undefined) {
			isSearch = true;
		}
		addPreloader($('.archive-wrapper'));
		let isActive = $(this).find('label').hasClass('active');
		const url = new URL(window.location.href);
		let value = $(this).find('input').val();
		let filterName = $(this).find('input').attr('name').replace('[]', '');
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		let filterValue = urlParams.get(filterName);
		let isIncluded = false;
		if(filterValue !== null && !isSort) {
			let filterArr = filterValue.split(',');
			if(!filterArr.includes(value)) {
				if(!isActive) {
					filterValue += ',' + value;
				}
			} else {
				if(isActive) {
					for( var i = 0; i < filterArr.length; i++){
				        if ( filterArr[i] === value) { 
				            filterArr.splice(i, 1);
				            break;
				        }
				    }
				    filterValue = filterArr.join(',');
				}
			}
		} else {
			filterValue = value;
		}
		if(filterValue != '') {
			url.searchParams.set(filterName, filterValue);
		} else {
			url.searchParams.delete(filterName);
		}
		if(!isSort) {
			url.searchParams.delete('page_n');
		}
		window.history.replaceState(null, null, url);
		queryString = window.location.search;
		urlParams = new URLSearchParams(queryString);
		let filterParams = {};
		for (let pair of urlParams.entries()) {
			filterParams[pair[0]] = pair[1];
		}
		let data = {
			'action': 'hansa_ajax_filter_products',
			'filters': filterParams
		};
		let parentCat = parseInt($('.products-section').attr('data-parent'));
		if(parentCat) {
			data.parent_cat = parseInt($('.products-section').attr('data-parent'))
		}
		if(isSearch) {
			data.s = $('.archive-wrapper').attr('data-s');
		}
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			success: function( response ) {
				removePreloader($('.archive-wrapper'));
				let resp = JSON.parse(response);
				console.log(resp['max_price']);
				console.log(resp['args']);
				let productsList = resp['html'];
				if(resp['n'] == 0) {
					$('.archive-top-block').hide();
				} else {
					$('.items-found span').text(resp['n']);
					$('.archive-top-block').show();
				}
				if(productsList != '') {
					$('.archive-products-row > *').remove();
					$('.archive-products-row').append(productsList);
					if(resp['max_price'] > 0) {
						updatePriceRangeSlider(resp['max_price']);
					}
				}
				if(!isSort) {
					$('.load-more-btn').attr('data-page',1);
				}
				let page = parseInt($('.load-more-btn').attr('data-page'));
				if(resp['max_p'] <= page) {
					$('.load-more-btn').hide();
					$('.bottom-banner_section').show();
				} else {
					$('.load-more-btn').show();
					$('.bottom-banner_section').hide();
				}
			},
			error: function( response ) {
				removePreloader($('.archive-wrapper'));
			}
		});
	});

	$('#slider-range').on('slidechange',function(event, ui){
		let min = ui.values[0];
		let max = ui.values[1];
		addPreloader($('.archive-wrapper'));
		const url = new URL(window.location.href);
		let value = min + ',' + max;
		let filterName = 'price';
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		let filterValue = urlParams.get(filterName);
		filterValue = value;
		if(filterValue != '') {
			url.searchParams.set(filterName, filterValue);
		} else {
			url.searchParams.delete(filterName);
		}
		url.searchParams.delete('page_n');
		window.history.replaceState(null, null, url);
		queryString = window.location.search;
		urlParams = new URLSearchParams(queryString);
		let filterParams = {};
		for (let pair of urlParams.entries()) {
			filterParams[pair[0]] = pair[1];
		}
		let data = {
			'action': 'hansa_ajax_filter_products',
			'filters': filterParams
		};
		let parentCat = parseInt($('.products-section').attr('data-parent'));
		if(parentCat) {
			data.parent_cat = parentCat;
		}
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			success: function( response ) {
				removePreloader($('.archive-wrapper'));
				let resp = JSON.parse(response);
				let productsList = resp['html'];
				if(resp['n'] == 0) {
					$('.archive-top-block').hide();
				} else {
					$('.items-found span').text(resp['n']);
					$('.archive-top-block').show();
				}
				if(productsList != '') {
					$('.archive-products-row > *').remove();
					$('.archive-products-row').append(productsList);
				}
				$('.load-more-btn').attr('data-page',1);
				let page = parseInt($('.load-more-btn').attr('data-page'));
				if(resp['max_p'] <= page) {
					$('.load-more-btn').hide();
					$('.bottom-banner_section').show();
				} else {
					$('.load-more-btn').show();
					$('.bottom-banner_section').hide();
				}
			},
			error: function( response ) {
				removePreloader($('.archive-wrapper'));
			}
		});
	});

	// Clear filter
	$('.clear-filter').on('click',function(e){
		e.preventDefault();
		addPreloader($('.archive-wrapper'));
		const url = new URL(window.location.href);
		let tax = $(this).attr('data-tax');
		if(tax != '') {
			url.searchParams.delete(tax);
		}
		url.searchParams.delete('page_n');
		let isPriceClear = $(this).closest('.range-dropdown').length;
		if(!isPriceClear) {
			$(this).closest('.category-filter_dropdown').find('.filter-form_row').removeClass('active');
			$(this).closest('.category-filter_dropdown').find('label').removeClass('active');
			$(this).closest('.category-filter_dropdown').find('input').prop('checked',false);
		}
		window.history.replaceState(null, null, url);
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		let filterParams = {};
		for (let pair of urlParams.entries()) {
			filterParams[pair[0]] = pair[1];
		}
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'hansa_ajax_filter_products',
				'filters': filterParams,
				'parent_cat': parseInt($('.products-section').attr('data-parent'))
			},
			success: function( response ) {
				removePreloader($('.archive-wrapper'));
				let resp = JSON.parse(response);
				let productsList = resp['html'];
				if(resp['n'] == 0) {
					$('.archive-top-block').hide();
				} else {
					$('.items-found span').text(resp['n']);
					$('.archive-top-block').show();
				}
				if(productsList != '') {
					$('.archive-products-row > *').remove();
					$('.archive-products-row').append(productsList);
					if(resp['max_price'] > 0) {
						updatePriceRangeSlider(resp['max_price']);
					}
				}
				$('.load-more-btn').attr('data-page',1);
				let page = parseInt($('.load-more-btn').attr('data-page'));
				if(resp['max_p'] <= page) {
					$('.load-more-btn').hide();
					$('.bottom-banner_section').show();
				} else {
					$('.load-more-btn').show();
					$('.bottom-banner_section').hide();
				}
			},
			error: function( response ) {
				removePreloader($('.archive-wrapper'));
			}
		});
	});

	$('.category-reset-btn').on('click',function(e){
		e.preventDefault();
		addPreloader($('.archive-wrapper'));
		const url = location.pathname;
		window.history.replaceState(null, null, url);
		$('.filter-form_row label').removeClass('active');
		$('.category-filter_item').removeClass('chosen');
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'hansa_ajax_filter_products',
				'filters': '',
				'parent_cat': parseInt($('.products-section').attr('data-parent'))
			},
			success: function( response ) {
				removePreloader($('.archive-wrapper'));
				let resp = JSON.parse(response);
				let productsList = resp['html'];
				if(resp['n'] == 0) {
					$('.archive-top-block').hide();
				} else {
					$('.items-found span').text(resp['n']);
					$('.archive-top-block').show();
				}
				if(productsList != '') {
					$('.archive-products-row > *').remove();
					$('.archive-products-row').append(productsList);
					if(resp['max_price'] > 0) {
						updatePriceRangeSlider(resp['max_price']);
					}
				}
				$('.load-more-btn').attr('data-page',1);
				let page = parseInt($('.load-more-btn').attr('data-page'));
				if(resp['max_p'] <= page) {
					$('.load-more-btn').hide();
					$('.bottom-banner_section').show();
				} else {
					$('.load-more-btn').show();
					$('.bottom-banner_section').hide();
				}
			},
			error: function( response ) {
				removePreloader($('.archive-wrapper'));
			}
		});
	});

	$('.category-filter_item').each(function(){
		if($(this).find('.filter-form_row.active').length) {
			$(this).addClass('chosen');
		}
	});

	$('.load-more-btn').on('click',function(e){
		e.preventDefault();
		let isSearch = false;
		if($('.archive-wrapper').attr('data-s') !== undefined) {
			isSearch = true;
		}
		const url = new URL(window.location.href);

		let page = parseInt($(this).attr('data-page'));
		$(this).attr('data-page',++page);
		addPreloader($('.archive-wrapper'));
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		let filterParams = {};
		for (let pair of urlParams.entries()) {
			filterParams[pair[0]] = pair[1];
		}
		url.searchParams.set('page_n', page);
		window.history.replaceState(null, null, url);
		let data = {
			'action': 'hansa_ajax_filter_products',
			'filters': filterParams,
			'page': page
		};
		let parentCat = parseInt($('.products-section').attr('data-parent'));
		if(parentCat) {
			data.parent_cat = parentCat;
		}
		if(isSearch) {
			data.s = $('.archive-wrapper').attr('data-s');
		}
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			success: function( response ) {
				removePreloader($('.archive-wrapper'));
				let resp = JSON.parse(response);
				let productsList = resp['html'];
				if(resp['n'] == 0) {
					$('.load-more-btn').hide();
					$('.bottom-banner_section').show();
				} else {
					if(productsList != '') {
						$('.archive-products-row').append(productsList);
					}
					if(resp['max_p'] <= page) {
						$('.load-more-btn').hide();
						$('.bottom-banner_section').show();
					}
				}
			},
			error: function( response ) {
				removePreloader($('.archive-wrapper'));
			}
		});
	});
	
	// Customize product change
	$('#customize_product_select').on('change',function(){
		let value = parseInt($(this).val());
		$('#comment_post_ID').val(value);
		let maxlength = $('#customize_product_select option:selected').attr('data-maxlength');
		$('.input-part .customize-input').attr('maxlength',maxlength);
		$('.carcount-number').text(maxlength);
		$('#custom-input_1,#custom-input_2').val('');
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'hansa_custom_product_change',
				'product_id': value
			},
			success: function( response ) {
				if(response) {
					let parsed = JSON.parse(response);
					$('.current-price.customize').html(parsed['curr_symbol']+parsed['price'].toFixed(2));
					$('.current-price.customize').attr('data-price',parseFloat(parsed['price']).toFixed(2));
					$('.points-value.customize').attr('data-points',parsed['points']);
					$('.points-value.customize').text('+' + parsed['points'] + 'pts');
					$('.customer-review_options p').html(parsed['rating'] + ' <span>('+parsed['reviews_count']+' Reviews)</span>');
					$('.product-review_exists > *').remove();
					$('.product-review_exists').append(parsed['reviews']);
					$('.add-to-cart.customize').text('Add To Cart');
				}
			},
			error: function( response ) {
			}
		});
	});

	$('body').on('click','.quantity-choser.customize .quantity-less,.quantity-choser.customize .quantity-more',function(){
		setTimeout(function(){
			let q = $('.quantity-choser.customize .qty-number').val();
			let price = $('.current-price.customize').attr('data-price');
			let points = $('.points-value.customize').attr('data-points');
			let total = price * q;
			let points_total = points * q;
			$('.current-price.customize').text('€' + total.toFixed(2));
			$('.points-value.customize').text('+' + points_total + 'pts');
		},300);
	});

	$('.add-to-cart.customize').on('click',function(e){
		e.preventDefault();
		let btn = $(this);
		btn.addClass('loading');
		let q = $('.quantity-choser.customize .qty-number').val();
		let label1 = $('#custom-input_1').val();
		let label2 = $('#custom-input_2').val();
		let productId = $('#customize_product_select').val();
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'hansa_add_custom_product_to_cart',
				'label_1': label1,
				'label_2': label2,
				'product_id': productId,
				'quantity': q
			},
			success: function( response ) {
				btn.removeClass('loading').text('Added');
				$(document.body).trigger( 'wc_fragments_refreshed' );
			},
			error: function( response ) {
				btn.removeClass('loading');
			}
		});
	});

	if($('.copy-link').length) {
		var clipboard = new ClipboardJS('.copy-link');
		clipboard.on('success', function(e) {
			$('.copy-link').addClass('copied');
			setTimeout(function(){
				$('.copy-link').removeClass('copied');
			},1500);
		});
	}
	if($('div[data-loyale-sso]').length) {
		loyalesso({
			schemeId: backend_vars.scheme_id, 
			successUrl: backend_vars.success_url, 
			errorUrl: backend_vars.error_url, 
			fields: 0,
			environment: backend_vars.env
		});
	}

	$('.category-dropdown_list li,.subcategory-dropdown_list li').on('mouseover',function(){
		let image = $(this).attr('data-thumb');
		if(image == '' || image == undefined) {
			if($(this).closest('.subcategory-dropdown_list').length) {
				image = $('.category-dropdown_list .current').attr('data-thumb');
			}
		}
		if(image == '' || image == undefined) {
			$('.header-category_thumb img').attr('src','');
			$('.header-category_thumb img').hide();
			$('.header-category_thumb').hide();
		} else {
			$('.header-category_thumb img').attr('src',image);
			$('.header-category_thumb img').show();
			$('.header-category_thumb').show();
		}
	});

	$('#min_price,#max_price').on('input',function(){
		let value = $(this).val();
		$(this).val(value.replace(/[a-zA-Z]/g, ""));
	});

	$('.filter-form_items:not(.range-form_wrap)').each(function(){
		if(!$(this).find('.filter-form_row').length) {
			$(this).closest('.category-filter_item').hide();
		}
	});

	$(document.body).on('added_to_cart removed_from_cart', function(){
		setTimeout(function(){
			$('.add-to-cart.added').text('Added');
		},500);
	});

	$('.product-single_content .quantity-choser').on('change',function(){
		let value = $(this).find('input').val();
		$(this).next().attr('href','?add-to-cart=' + $(this).next().attr('data-product-id') + '&quantity=' + value);
	});

	function updatePriceRangeSlider(maxPrice = 0) {
	    let currentSliderValues = $("#slider-range").slider( "values" );
	    if(currentSliderValues[1] > maxPrice) {
	    	currentSliderValues[1] = maxPrice;
	    }
	    $('#max_price').val(maxPrice);
	    $("#slider-range").slider( "destroy" );
	    $("#slider-range").slider({
	        range: true,
	        orientation: "horizontal",
	        min: 0,
	        max: maxPrice,
	        values: [currentSliderValues[0], maxPrice],
	        step: 1,

	        slide: function (event, ui) {
	          if (ui.values[0] == ui.values[1]) {
	              return false;
	          }

	          $("#min_price").attr('value', ui.values[0]);
	          $("#max_price").attr('value', ui.values[1]);
	          
	          $("#min_price").val(ui.values[0]);
	          $("#max_price").val(ui.values[1]);
	        }
	    });
	}

	$('.woocommerce-message-close').on('click',function(){
		if($('.woocommerce-message:visible').length == 1) {
			$('.woocommerce-notices-wrapper').slideUp();
		} else {
			$(this).closest('.woocommerce-message').slideUp();
		}
	});

	$('.single_add_to_cart_button').on('click',function(e){
		e.preventDefault();
		let btn = $(this);
		let productId = parseInt(btn.attr('data-product-id'));
		let q = parseInt(btn.attr('data-quantity'));
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				'action': 'woocommerce_ajax_add_to_cart',
				'product_id': productId,
				'quantity': q,
				'variation_id': 0
			},
			beforeSend: function(){
				btn.addClass('loading');
			},
			complete: function(response) {
				btn.removeClass('loading').addClass('added').text('Added');
			},
			success: function( response ) {
				btn.removeClass('loading').text('Added');
				$(document.body).trigger( 'wc_fragments_refreshed' );
			},
			error: function( response ) {
				btn.removeClass('loading');
			}
		});
	});

	if($('.product-details_content.custom').length) {
		if($('.product-details_content.custom').attr('data-selected')) {
			let selectedID = $('.product-details_content.custom').attr('data-selected');
			if($('#customize_product_select option[value="' + selectedID + '"]').length) {
				$('#customize_product_select').val(selectedID).trigger('change');
			}
		}
	}

	// $('.add_to_cart_button,.single_add_to_cart_button').on('click',function(){
	// 	let productId = $(this).attr('data-product_id');
	// 	if($(this).hasClass('.single_add_to_cart_button')) {
	// 		productId = $(this).attr('data-product-id');
	// 	}
	// 	$.ajax({
	// 		url: ajax_url,
	// 		data: {
	// 			product_id: productId,
	// 			action: 'hansa_generate_gtag_by_id'
	// 		},
	// 		method: 'POST',
	// 		success: function(response) {
	// 			let data = JSON.parse(response);
	// 			if(data) {
	// 				gtag('event', 'add_to_cart', {
	// 				   'currency': 'EUR',
	// 				   'value': data['price'],
	// 				   'items': {
	// 				       'item_id': data['sku'],
	// 				       'item_name': data['name'],
	// 				       'price': data['price']
	// 				   },
	// 				});
	// 			}
	// 		},
	// 		error: function(response) {
	// 		}
	// 	});
	// });
	// $('.delete-cart_item').on('click',function(){
	// 	let productId = $(this).attr('data-product_id');
	// 	$.ajax({
	// 		url: ajax_url,
	// 		data: {
	// 			product_id: productId,
	// 			action: 'hansa_generate_gtag_by_id'
	// 		},
	// 		method: 'POST',
	// 		success: function(response) {
	// 			let data = JSON.parse(response);
	// 			if(data) {
	// 				gtag('event', 'remove_from_cart', {
	// 				   'currency': 'EUR',
	// 				   'value': data['price'],
	// 				   'items': {
	// 				       'item_id': data['sku'],
	// 				       'item_name': data['name'],
	// 				       'price': data['price']
	// 				   },
	// 				});
	// 			}
	// 		},
	// 		error: function(response) {
	// 		}
	// 	});
	// });
})( jQuery );
	
// window.dataLayer = window.dataLayer || [];
// function gtag(){dataLayer.push(arguments);}