let _ajax_url = back_vars.ajax_url;
jQuery(document).ready(function($) {

	$('.register-user-btn').on('click',function(e){
		e.preventDefault();
		$('.register-err').remove();
		$('input.error').removeClass('error');
		let is_errors = false;
		let is_owner = $('input[name=register_is_owner]').is(':checked');
		$('input.required').each(function(){
			if($(this).val().trim() == '') {
				$(this).addClass('error');
				$(this).closest('.form-row').append('<span class="register-err">This field can\'t be empty.</span>');
				is_errors = true;
				return;
			}
		});
		if($('input[name=register_day]').val() == 'Day') {
			$('input[name=register_day]').addClass('error');
			$('input[name=register_day]').closest('.form-row_part--third').append('<span class="register-err">This field can\'t be empty.</span>');
			is_errors = true;
		}
		if($('input[name=register_month]').val() == 'Month') {
			$('input[name=register_month]').addClass('error');
			$('input[name=register_month]').closest('.form-row_part--third').append('<span class="register-err">This field can\'t be empty.</span>');
			is_errors = true;
		}
		if($('input[name=register_year]').val() == 'Year') {
			$('input[name=register_year]').addClass('error');
			$('input[name=register_year]').closest('.form-row_part--third').append('<span class="register-err">This field can\'t be empty.</span>');
			is_errors = true;
		}
		if(is_owner) {
			$('input.required-on-check').each(function(){
				if($(this).val().trim() == '') {
					$(this).addClass('error');
					let rowClass = '';
					if($(this).closest('.form-row_part--half').length) {
						rowClass = '.form-row_part--half';
					} else {
						rowClass = '.form-row';
					}
					$(this).closest(rowClass).append('<span class="register-err">This field can\'t be empty.</span>');
					is_errors = true;
					return;
				}
			});
		}

        if(!is_errors) {
			let data = {
	            action: 'hansa_register_customer',
	            email: $("input[name=register_email]").val(),
	            first_name: $("input[name=register_name]").val(),
	            last_name: $("input[name=register_surname]").val(),
	            phone: $("input[name=register_phone]").val(),
	            dd: $('input[name=register_day]').val(),
	            mm: $('input[name=register_month]').val(),
	            yyyy: $('input[name=register_year]').val(),
	            password1: $('input[name=register_pass1]').val(),
	            password2: $('input[name=register_pass2]').val(),
	            company_name: $('input[name=register_cname]').val(),
	            company_vat: $('input[name=register_vat]').val(),
	            company_reg: $('input[name=register_cnumber]').val(),
	            company_addr1: $('input[name=register_addr1]').val(),
	            company_addr2: $('input[name=register_addr2]').val(),
	            company_city: $('input[name=register_city]').val(),
	            company_postcode: $('input[name=register_postcode]').val(),
	            company_country: $('select[name=register_country]').val(),
	            is_subscribe: $('input[name=register_subscribe]').is(':checked')
	        };

	        if(is_owner) {
	        	data.is_owner = true;
	        }
			$.ajax({
				url: _ajax_url,
				data: data,
				method: 'POST',
				success: function(response) {
                    let errors = JSON.parse(response);
                    let is_errors = false;
					if(errors['status'] == 0) {
                        if(errors['email'].length) {
                            $('input[name=register_email]').addClass('error');
                            $('input[name=register_email]').after('<span class="register-err">' + errors['email']+'</span');
                            is_errors = true;
                        }

                        if(errors['first_name'].length) {
                            $("input[name=register_name]").addClass('error');
                            $("input[name=register_name]").after('<span class="register-err">' + errors['first_name']+'</span');
                            is_errors = true;
                        }

                        if(errors['last_name'].length) {
                            $("input[name=register_surname]").addClass('error');
                            $("input[name=register_surname]").after('<span class="register-err">' + errors['last_name']+'</span');
                            is_errors = true;
                        }

                        if(errors['birthdate'].length) {
                            $('input[name=register_day]').addClass('error');
                            $('input[name=register_month]').addClass('error');
                            $('input[name=register_year]').addClass('error');
                            $('input[name=register_day]').closest('.form-row').append('<span class="register-err">' + errors['birthdate']+'</span');
                            is_errors = true;
                        }

                         if(errors['password'].length) {
                            $('input[name=register_pass1]').addClass('error');
                            $('input[name=register_pass2]').addClass('error');
                            $('input[name=register_pass2]').after('<span class="register-err">' + errors['password']+'</span');
                            is_errors = true;
                        }
                    }
                    if(!is_errors) {
                    	const queryString = window.location.search;
                    	const urlParams = new URLSearchParams(queryString);
                    	const referrer = urlParams.get('referrer');
                    	console.log(referrer);
                    	if(referrer) {
                    		location.href = referrer;
                    	} else {
                    		location.href = '/my-account';
                    	}
                    }
				}
			});
        }
	});

	$('#check-profile').on('change',function(){
		if($(this).prop('checked')) {
			$('.required-on-check').prop('required',true);
		} else {
			$('.required-on-check').prop('required',false);
		}
	});

	$('body').on('click','.reset-pass-btn',function(e){
		e.preventDefault();
		$('input[name=reset_email]').removeClass('error');
		$('.restore-password_form .register-err').remove();
		let email = $('input[name=reset_email]').val();
		if(email.trim() == '') {
			$('input[name=reset_email]').addClass('error');
			$('input[name=reset_email]').after('<span class="register-err">This field can\'t be empty.</span');
		} else {
			let data = {
	            action: 'hansa_reset_password',
	            email: $("input[name=reset_email]").val(),
	        }
			$.ajax({
				url: _ajax_url,
				data: data,
				method: 'POST',
				success: function(response) {
					console.log(response);
                    let errors = JSON.parse(response);
                    let is_errors = false;
					if(errors['status'] == 0) {
                        if(errors['email'].length) {
                            $('input[name=reset_email]').addClass('error');
                            $('input[name=reset_email]').after('<span class="register-err">' + errors['email']+'</span>');
                            is_errors = true;
                        }
                    } else {
						if(errors == '1') {
							$('span.register-success').remove();
							$('span.register-timeout').remove();
							$('.reset-pass-btn').addClass('disabled').attr('disabled','disabled');
							$('.reset-pass-btn').after('<span class="register-success">Reset password has been sent to your email.</span>');
							$('.register-success').after('<span class="register-timeout">You can resend the email after <span>1</span> minute.</span>');
							setTimeout(function(){
								$('.reset-pass-btn').removeClass('disabled').removeAttr('disabled');
								$('.register-timeout').remove();
							},60000);
						}
                    }
				}
			});
		}
	});

	$('.forgot-pass_link').on('click',function(){
		$('.sign-divider').hide();
	});

	$('.back-to_sign').on('click',function(){
		$('.sign-divider').show();
	});


	if($('.sign-section').length) {
		let href = location.hash;
		if(href == '#register') {
			$('.sign-tab_content.login').hide();
			$('.sign-tab_content.register').show();
			$('.sign-tabs h6').removeClass('current');
			$('.sign-tabs h6:last-child').addClass('current');
			$('.sign-title.register').show();
			$('.sign-title.login').hide();
		}
	}

	$('.sign-tabs h6').on('click',function(){
		let index = $(this).index();
		$('.sign-title').hide();
		$('.sign-title').eq(index).show();
	});

	if($('input[type="tel"]').length) {
		$('input[type="tel"]').each(function(){
			if(!$(this).attr('data-intl-tel-input-id')) {
				var input = document.getElementById($(this).attr('id'));
				window.intlTelInput(input, {
					excludeCountries: ['gb','us'],
					initialCountry: 'MT',
					preferredCountries: ['mt']
				});
			}
		});
	}
});