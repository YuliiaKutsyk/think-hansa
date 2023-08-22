jQuery(document).ready(function($) {

    //Sticky header
    $(window).scroll(function(){
      var sticky = $('header'),
          scroll = $(window).scrollTop();

      if (scroll >= 250) {
        sticky.addClass('fixed');
      }
      else sticky.removeClass('fixed');
    });

    //Owl carousel
    $("#home-banner_owl").owlCarousel({
        items: 1,
        nav: true,
        margin: 5,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplaySpeed: 1500,
        loop: true,
      });
    $("#category-slider_1").owlCarousel({
        items: 1,
        nav: false,
        margin: 5,
      });
    $("#category-slider_2").owlCarousel({
        items: 1,
        nav: false,
        margin: 5,
      });
    $("#category-slider_3").owlCarousel({
        items: 1,
        nav: false,
        margin: 5,
      });
    $("#category-slider_4").owlCarousel({
        items: 1,
        nav: false,
        margin: 5,
      });
    $("#category-slider_5").owlCarousel({
        items: 1,
        nav: false,
        margin: 5,
      });
    $("#arrivals-owl").owlCarousel({
        items: 2,
        nav: true,
        margin: 15,
        stagePadding: 0,
        responsive:{
            500:{
                items: 2,
                nav: true,
                margin: 15,
                stagePadding: 10,
            },
            600:{
                items: 2,
                nav: true,
                margin: 30,
                stagePadding: 80,
            },
            900:{
                items: 3,
                nav: true,
                margin: 30,
                stagePadding: 50,
            },
            1200:{
                items: 4,
                nav: true,
                margin: 30,
                stagePadding: 50,
            }
        }

      });

    $("#category-mobile_owl").owlCarousel({
        items: 1,
        nav: true,
        margin: 20,
        stagePadding: 60,
        loop: true,
    });
    
     $("#blog-owl").owlCarousel({
        items: 1,
        nav: true,
        margin: 20,
        stagePadding: 50,
    });

     if($('#product-slider').length) {
        var productSlider =  $("#product-slider").lightSlider({
            loop:true,
            keyPress:true,
            item: 1,
            cssEasing: 'ease',
            easing: 'linear',
            controls: false,
            vertical: false,
            verticalHeight: 485,
            vThumbWidth: 70,
            thumbItem: 6,
            thumbMargin: 16,
            enableTouch:true,
            enableDrag:true,
            gallery: true,
            adaptiveHeight: false,
            responsive : [
            {
                breakpoint:500,
                settings: {
                    loop:true,
                    keyPress:true,
                    item: 1,
                    cssEasing: 'ease',
                    easing: 'linear',
                    controls: false,
                    vertical: false,
                    verticalHeight: 320,
                    vThumbWidth: 70,
                    thumbItem: 4,
                    thumbMargin: 15,
                    enableTouch:true,
                    enableDrag:true,
                    gallery: true,
                    adaptiveHeight: false,
                  }
            },
            ],
         });
     }

     var maxLengthChar = 11;

     $('#customize_product_select').on('change',function(){
        if($('#customize_product_select option:selected').hasClass('20cl')) {
            productSlider.goToSlide(2);
            $('.customize-input').addClass('more-length');
            maxLengthChar = 13;
        }
        else {
            productSlider.goToSlide(1);
            $('.customize-input').removeClass('more-length');
            maxLengthChar = 11;
        }
    });

     //Form age
     if($.cookie('popupCookie') != 1 ){
        $('.adult-popup').fadeIn();
     }

     $('.age-form_wrap .submit-form').on('click', function() {
        /*var day = parseInt($('.day-input').val());
        var month = parseInt($('.month-input').val());
        var year = parseInt($('.year-input').val());
        var age = 18;
        var setDate = new Date(year + age, month - 1, day);
        var setMiliseconds = Date.parse(setDate);
        var currdate = new Date();
        var setMilisecondsCurrent = Date.parse(currdate);
        if (setMilisecondsCurrent >= setMiliseconds) { */
            // you are above 18
            $.cookie("popupCookie", 1, { expires : 7 });
            $('.adult-popup').fadeOut();
        /*} else {
          $('.age-form').addClass('age-error');
          $('.age-form .age-input').addClass('age-error_input');
          $('.age-form_title').html('You must be 18 or older to view this website.');
          $('.age-form_title').css({
           'color' : '#ff7758',
           'opacity' : '1',
        });
        }*/
     });

    $('body').on('click', '.checkbox-label', function() {
       $(this).toggleClass('active');
       if($(this).hasClass('checkbox-label_associate')) {
           $('.associate-form_part').toggle();
       }
       else if($(this).hasClass('gift-checkbox')) {
           $('.checkout-gifts_addons').toggle();
       }
     });

    $('body').on('click', '.checkbox-label_adult', function(e) {
        e.preventDefault();
        e.stopPropagation();
       $(this).toggleClass('active');
       $('.submit-form_wrap .submit-form').toggleClass('disabled');
       if($(this).hasClass('active')) {
        $('.submit-form_wrap .submit-form').prop('disabled', false);
       }
       else {
        $('.submit-form_wrap .submit-form').prop('disabled', true);
       }
     });

    $('.date-dropdown_holder').on('click', function() {
        $('.date-dropdown', this).toggle();
    });

    $('.date-dropdown div').on('click', function() {
        var dateValue = $(this).html();
        $(this).parent().parent().find('input').attr('value', dateValue);
    });

    $('.hamburger').on('click', function() {
        $(this).toggleClass('active');
        $('.mobile-menu').toggle();
        $('body').toggleClass('overflow');
    });

    $('.menu-item-has-children').on('click', function() {
        $('.sub-menu', this).toggle();
        $(this).toggleClass('active');
    });

    $('.header-categories_title').on('click', function() {
        $(this).toggleClass('active');
        $('.category-dropdown_holder').toggle();
    });

    $('.submit-form').on('click', function() {
        if($('.age-form').hasClass('error')) {
            $('.age-form_title').html('You must be 18 or older to view this website.');
        }
    });

    $('.header-account_options').on('mouseover', function() {
        $('.header-account_links').show();
    });

    $('.header-right_holder').on('mouseleave', function() {
        $('.header-account_links').hide();
    });

    $('.forgot-pass_link').on('click', function(e) {
        e.preventDefault();
        $('.restore-password_block').fadeIn();
    });

    $('.back-to_sign').on('click', function(e) {
        e.preventDefault();
        $('.restore-password_block').fadeOut();
    });

    //Checkout scripts
    $('.checkout-row label').on('click', function() {
        $(this).parents('.checkout-rows_holder').find('.checkout-row label').removeClass('active');
        $(this).addClass('active');
        if($(this).hasClass('no-delivery_choice')) {
            $(this).parents('.checkout-form_wrap').find('.checkout-options_rows').hide();
        }
        else if($(this).hasClass('no-card_payment--choice')) {
            $(this).parents('.checkout-form_wrap').find('.checkout-options_rows').hide();
        }
        else {
            $(this).parents('.checkout-form_wrap').find('.checkout-options_rows').show();
        }
    });

    $('body').on('click', '.add-delivery_button', function(e) {
        e.preventDefault();
        $('.add-new_adress_form').show();
    });

    $('body').on('click', '.cancel-adress_button', function(e) {
        e.preventDefault();
        $('.add-new_adress_form').hide();
    });

    $('body').on('click', '.add-card_button', function(e) {
        e.preventDefault();
        $('.add-newcard_form').show();
    });

    $('body').on('click', '.cancel-card_button', function(e) {
        e.preventDefault();
        $('.add-newcard_form').hide();
    });

    //TABLET/DESKTOP CONDITION
    if(innerWidth >=500) {
        $('.category-filter_item').on('click', function(e) {
            e.stopPropagation();
            $('.category-filter_dropdown').not(this).hide();
            $('.category-filter_dropdown', this).toggle();
        });

        $('.filter-form_row').on('click', function(e) {
            e.stopPropagation();
            //const filterTitle = $(this).parents('.category-filter_item').find('.filter-choose_hidden').html();
            $('.filter-form_row--sort label').removeClass('active');
            $('label', this).toggleClass('active');
            var activeElemetns = $(this).parent().find('label.active').length;
            if(activeElemetns >= 1) {
                $(this).parents('.category-filter_item').addClass('chosen');
            } else {
                $(this).parents('.category-filter_item').removeClass('chosen');
            }
        });

        $('.filter-form_row label').on('click', function(e) {
            e.stopPropagation();
        });

        $('.filter-form_top').on('click', function(e) {
            e.stopPropagation();
        });
    }

    //MOBILE CONDITION
    if(innerWidth <=500) {
         $('.category-filter_item').on('click', function(e) {
             e.stopPropagation();
            $('.category-filter_dropdown', this).toggle();
            $('.mobile-filter_bg').show();
        });

        $('.mobile-filter_button').on('click', function(e) {
             e.stopPropagation();
             $(this).toggleClass('active');
            $('.category-filters_holder').toggle();
        });

        $('.filter-form_row label').on('click', function(e) {
            e.stopPropagation();
        });

        $('.seach-input').on('click', function(e) {
            e.stopPropagation();
        });

        $('.filter-form_row').on('click', function(e) {
            e.stopPropagation();
            $('.filter-form_row--sort label').removeClass('active');
            $(this).toggleClass('active');
            $('label', this).toggleClass('active');
        });

        $('.quantity-choser').on('click', function() {
            $('.quantity-choser').not(this).removeClass('active');
            $(this).toggleClass('active');
            if($(this).parents().find('.owl-carousel')) {
                $(this).parents().find('.owl-carousel').toggleClass('active-quantity');
            }
        });

        $('.mobile-quantity_input input').on('click', function() {
            var currentValue = $(this).val();
            $(this).parent().parent().find('.qty-number').attr('value', currentValue);
            $(this).parents('.qty-wrap').find('.add-to-cart').attr('data-quantity', currentValue);
        });

        $('.product-item .quantity-more').on('click', function(e) {
            return false;
        });
        
        $('.product-item .quantity-less').on('click', function(e) {
            return false;
        });
    }

    //Desktop Condition
    if(innerWidth >= 900) {
        if($('.about-people .col-md-6').length) {
            var maxHeight = Math.max.apply(null, $(".about-people .col-md-6").map(function () {
                return $(this).height();
            }).get());
            $(".about-people .col-md-6").css('height', maxHeight);
         }
    }

    //Body click
    $('body').on('click', function() {
        $('.new-review_popup').fadeOut();
        $('.category-filter_dropdown').hide();
        $('.mobile-filter_bg').hide();
    });

    $('.filter-form_items').on('click', function(e) {
        e.stopPropagation();
    });

    $('.category-dropdown_holder').on('click', function(e) {
        e.stopPropagation();
    });

    $('.open-review_popup').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.new-review_popup').fadeIn();
    });

    $('.new-review_popup').on('click', function(e) {
        e.stopPropagation();
    });

    $('.close-popup_review').on('click', function(e) {
        e.preventDefault();
        $('.new-review_popup').fadeOut();
    });

    //Copy link
    $('.copy-link').on('click', function(e) {
        e.preventDefault();
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(this).attr('href')).select();
        document.execCommand("copy");
        $temp.remove();
    });

    //Accordeon items
    $('.accordeon-title').on('click', function() {
        $(this).parent().toggleClass('active');
    });

    //Input file label names/format/size file
    $('.file-input').bind('change',function() {
        var filenameUncropped = $(this).val().replace(/C:\\fakepath\\/i, '');
        var filename = filenameUncropped.substring(0, filenameUncropped.indexOf("."));
        var labelText = $(this).parent().find('label').html();
        var filesize = this.files[0].size;
        var fileformat = $(this).val().split('.').pop();
        if(filename.length > 1) {
            $(this).parent().parent().find('.file-label').addClass('active');
            $(this).parent().parent().find('.filename-reciever').html(filename);
            $(this).parent().parent().find('.filesize-reciever').html(fileformat + ' (' + filesize + 'kb)');
        }
        else {
            $(this).parent().parent().find('.file-label').removeClass('active');
            $(this).parent().parent().find('.filename-reciever').html(labelText);
            $(this).parent().parent().find('.filesize-reciever').html('Upload File');
        }
    });

    //Pack counter
    $('.pack-purchase_item').on('click', function() {
        var itemsInPack = $(this).val();
        $('.pack-purchase_item').removeClass('active');
        $(this).addClass('active');
        $('.pack-count_reciever').html(itemsInPack);
        $('.qty-number').attr('value', itemsInPack);
    });

    $('.clear-filter').on('click', function() {
        $(this).parent().parent().hide();
        $(this).parent().find('label').removeClass('active');
        $(this).parents('.category-filter_item').removeClass('chosen');
    });

    $('.close-filter_button').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.category-filter_dropdown').hide();
        $('.mobile-filter_bg').hide();
        var itemsSelected = $(this).parents('.category-filter_item').find('.filter-form_row.active').length;
        if(itemsSelected >0) {
            $(this).parents('.category-filter_item').find('.mobile-items_chosen').html(itemsSelected + ' Selected');
            $(this).parents('.category-filter_item').addClass('chosen');
        }
        else {
            $(this).parents('.category-filter_item').removeClass('chosen');
        }
    });

    //Tabs
    $(".subcategory-dropdown_list").not(":first").hide();
        $(".category-dropdown_list li").click( function() {
        $(".category-dropdown_list li").removeClass("current").eq($(this).index()).addClass("current");
        $(".subcategory-dropdown_list").hide().eq($(this).index()).show();
    }).eq(0).addClass("current");

    $(".header-category_thumb").not(":first").hide();
        $(".subcategory-dropdown_list li").hover( function() {
        $(".subcategory-dropdown_list li").removeClass("current").eq($(this).index()).addClass("current");
        $(".header-category_thumb").hide().eq($(this).index()).show();
    }).eq(0).addClass("current");

    $(".sign-tab_content").not(":first").hide();
        $(".sign-tabs h6").click( function() {
        $(".sign-tabs h6").removeClass("current").eq($(this).index()).addClass("current");
        $(".sign-tab_content").hide().eq($(this).index()).show();
    }).eq(0).addClass("current");

    //Quantity
    $('body').on('click', '.quantity-less', function() {
       var qtyVal = $(this).parent().find('.qty-number').val();
       qtyVal--;
       $(this).parent().find('.qty-number').attr('value', qtyVal);
       $(this).parents('.qty-wrap').find('.add-to-cart').attr('data-quantity', qtyVal);
       let minValue = $(this).closest('.checkout-product.gift').length ? 0 : 1;
       if(qtyVal <= minValue) {
            $(this).addClass('disabled');
            $(this).parent().find('.qty-number').attr('value', 1);
            if($(this).parents('.checkout-product').find('.gift-card_quantity')) {
               $(this).parents('.checkout-product').find('.gift-card_text').hide();
               $(this).parent().find('.qty-number').attr('value', 0);
            }    
       }
     });

     $('body').on('click', '.quantity-more', function() {
       var qtyVal = $(this).parent().find('.qty-number').val();
       qtyVal++;
       $(this).parent().find('.qty-number').attr('value', qtyVal);
       $('.quantity-less').removeClass('disabled');
       $(this).parents('.qty-wrap').find('.add-to-cart').attr('data-quantity', qtyVal);
       if($(this).parents('.checkout-product').find('.gift-card_quantity')) {
           $(this).parents('.checkout-product').find('.gift-card_text').show();
       }
     });

     //Keypress characters length
     $('.customize-input').on('keydown', function(objEvent) {
        var currentCharacters = $(this).val().length + 1;
        var valueOfChars = maxLengthChar - currentCharacters;
        var key = event.keyCode || event.charCode;
        if (event.ctrlKey) {
            event.preventDefault();
        }  
        if(key === 8) {
            var currentCharacters = $(this).val().length;
            var valueOfChars = maxLengthChar - (currentCharacters - 1) ;
            console.log(valueOfChars);
            if(valueOfChars <= maxLengthChar) {
                $(this).parents('.customize-row').find('.carcount-number').html(valueOfChars);
            }
        }
        if(valueOfChars >= 0 && valueOfChars <= maxLengthChar) {
            $(this).parents('.customize-row').find('.carcount-number').html(valueOfChars);
        }
     });

   //Range slider
    $('#price-range-submit').hide();

    $("#min_price,#max_price").on('change', function () {

      $('#price-range-submit').show();

      var min_price_range = parseInt($("#min_price").val());

      var max_price_range = parseInt($("#max_price").val());

      if (min_price_range > max_price_range) {
        $('#max_price').val(min_price_range);
      }

      $("#slider-range").slider({
        values: [min_price_range, max_price_range]
      });
      
    });


    $("#min_price,#max_price").on("paste keyup", function () {                                        

      $('#price-range-submit').show();

      let sliderMin = $("#slider-range").slider( "option","min" );
      let sliderMax = $("#slider-range").slider( "option","max" );

      let min_price_range = parseInt($("#min_price").val());

      let max_price_range = parseInt($("#max_price").val());

      console.log(min_price_range,max_price_range);

      if(min_price_range < sliderMin) {
        $(this).val(sliderMin);
        min_price_range = sliderMin;
      }
      if(min_price_range > sliderMax) {
        $(this).val(sliderMax);
        min_price_range = sliderMax;
      }

      if(max_price_range > sliderMax) {
        $(this).val(sliderMax);
        max_price_range = sliderMax;
      }
      if(max_price_range < sliderMin) {
        $(this).val(sliderMin);
        max_price_range = sliderMin;
      }

      $("#slider-range").slider({
        values: [min_price_range, max_price_range]
      });

    });


    $(function () {
        let maxPrice = 0;
        $('.current-price').each(function(){
            let price = Math.round(parseFloat($(this).text().replace(/[^0-9\.]+/g, '')));
            if(price > maxPrice) {
                maxPrice = price;
            }
        });
        $("#slider-range").slider({
            range: true,
            orientation: "horizontal",
            min: 0,
            max: maxPrice,
            values: [0, maxPrice],
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

        $("#min_price").val($("#slider-range").slider("values", 0));
        $("#max_price").val($("#slider-range").slider("values", 1));

    });

    $("#slider-range,#price-range-submit").on('click', function () {
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
    });

});
