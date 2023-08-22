<?php
  $is_wishlist = is_page( apply_filters( 'wpml_object_id', tinv_get_option( 'page', 'wishlist' ), 'page', true ));
  if(!is_archive() && !is_search() && !$is_wishlist && !is_cart() && !is_404() && !is_checkout() && !is_account_page() && !is_page('login') && !is_page('delivery-returns')) { 
  $subscribe_img = get_field('subscribe_form_bg','option'); 
?>
<section class="subscribe-section subscribe-section_inner">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="subscribe-banner">
          <?php if($subscribe_img) { ?>
            <img src="<?php echo $subscribe_img['url']; ?>" alt="subscribe-form" class="cover-image">
          <?php } ?>
          <div class="subscribe-banner_titles">
            <div class="titles-top">
              <h4><?php the_field('subscribe_form_title','option'); ?></h4>
              <p><?php the_field('subscribe_form_desc','option'); ?></p>
            </div>
            <?php echo do_shortcode('[contact-form-7 id="337958" title="Subscribe"]'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if(!is_singular('product')) { ?>
<section class="instagram-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h4 class="section-title center-text">Find us on Instagram</h4>
        <a href="//www.instagram.com/hansa_malta" class="section-undertitle center-text">@hansa_malta</a>
        <div class="instagram-feed_wrap">
          <?php echo do_shortcode('[ff id="1"]'); ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php }} ?>
</div><!-- /wrapper -->


  <div class="adult-popup">
    <div class="adult-popup_inner">
      <div class="adult-popup_content">
        <h4 class="age-popup_title">Welcome to Hansa</h4>
        <div class="age-form_wrap">
          <form action="#" class="age-form">
            <div class="form-row_popup">

              <label class="checkbox-label_adult" for="adult-check">
               I am over the age 18
              </label>
               <input type="checkbox" id="adult-check" />

              <!-- <div class="form-row_inner">
                <div class="form-row_part--third date-dropdown_holder">
                  <input type="text" readonly value="DD" class="age-input day-input" required />
                  <div class="date-dropdown">
                    <?php for($i = 1; $i <= 31; $i++) { ?>
                      <div class="day-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-row_part--third date-dropdown_holder">
                  <input type="text" readonly value="MM" class="age-input month-input" required />
                  <div class="date-dropdown">
                    <?php for($i = 1; $i <= 12; $i++) { ?>
                      <div class="month-picker"><?php echo $i < 10 ? '0' . $i : $i; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-row_part--third date-dropdown_holder">
                  <input type="text" readonly value="YY" class="age-input year-input" required />
                  <div class="date-dropdown">
                    <?php for($i = 1870; $i <= intval(date('Y')); $i++) { ?>
                      <div class="year-picker"><?php echo $i; ?></div>
                    <?php } ?>
                  </div>
                </div>
              </div> -->
            </div>
            <div class="submit-form_wrap">
              <input type="submit" class="submit-form disabled" value="Enter" disabled />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<footer>
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="footer-row_inner">
          <?php 
            $footer_logo = get_field('footer_logo','option');
          ?>
          <div class="footer-logo col-md-2">
            <img src="<?php echo $footer_logo['url']; ?>" alt="<?php echo $footer_logo['alt']; ?>" class="contain-image">
          </div>
          <?php 
            $menus = get_field('footer_menus','option');
          ?>
          <div class="footer-navigation col-md-9 col-lg-8 col-xs-12">
            <?php foreach($menus as $menu) { ?>
              <div class="footer-nav_column">
                <h6><?php echo $menu['menu_title']; ?></h6>
                <nav>
                  <?php 
                    wp_nav_menu(array(
                      'menu' => $menu['menu'],
                      'container' => '',
                      'menu_class' => ''
                    )); 
                  ?>
                </nav>
              </div>
            <?php } ?>
            <?php 
              $socials = get_field('footer_socials','option');
              if($socials) {
            ?>
              <div class="footer-nav_column">
                <h6>Follow Us</h6>
                <div class="footer-social">
                  <?php foreach($socials as $s) {
                    $image = $s['icon'];
                  ?>
                    <a href="<?php echo $s['link']; ?>" target=_blank class="facebook" style="background: transparent url('<?php echo $image['url']; ?>') 50% no-repeat"></a>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          </div>
        </div><!-- /.col-md-12 -->
      </div><!-- /.row -->
    </div><!-- /.container -->
  </div>

  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
          <p class="copyright"><?php echo date('Y'); ?> Copyright © Hansa Wines and Spirits Ltd</p>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 developed-link">
          <p>Designed and Developed by <a href="//think.mt" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Think-Logo.svg" alt="think-logo" class="contain-image">
          </a></p>
        </div>
      </div>
    </div>
  </div>

  
</footer><!-- /footer -->

<!-- Messenger Chat Plugin Code -->
    <!-- <div id="fb-root"></div> -->

    <!-- Your Chat Plugin code -->
<!--     <div id="fb-customer-chat" class="fb-customerchat">
    </div>
 -->
<!--     <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "3252");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script> -->

    <!-- Your SDK code -->
<!--     <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'API-VERSION'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script> -->

<?php wp_footer(); ?>
<script>
    let ajax = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    jQuery(document).ready(function($) {
        $('body').on('click', '.add-delivery_button', function(e) {
            e.preventDefault();
            $('.add-new_adress_form').show();
            $('.add-new_adress_form').removeClass('hidden');
            $('.add-new_adress_form').addClass('visible-100');
            $('#current-billing_adress').prop('checked',false);
            $('#ship-to-different-address-checkbox').prop('checked',true);
            $(this).hide();
        });

        $('body').on('click', '.cancel-adress_button', function(e) {
            e.preventDefault();
            $('.add-new_adress_form').hide();
            $('.add-delivery_button').show();
            $('.add-new_adress_form').removeClass('visible-100');
        });

        $('body').on('updated_checkout', function(){
            if($('.woocommerce-shipping-methods li input[value*=local_pickup]').is(':checked')) {
                $('#ship-to-different-address-checkbox').prop('checked',false);
                $('.checkout-addr-rows,.woocommerce-shipping-fields').hide();
            } else {
                if($('#current-billing_adress').is(':checked')) {
                    $('#ship-to-different-address-checkbox').prop('checked',false);
                    $('.add-delivery_button').show();
                    $('.add-new_adress_form').hide();
                } else {
                    if(!$('.checkout-row.saved-addr').length){
                        $('.add-delivery_button').hide();
                    }
                    $('#ship-to-different-address-checkbox').prop('checked',true);
                    $('.woocommerce-shipping-fields').show();
                }
                $('.checkout-addr-rows').show();
            }

            let data = {
                action: "hansa_get_delivery_addr"
            };
            $.ajax({
                url: ajax,
                data: data,
                method: "POST",
                success: function(response) {
                    $('input[name="shipping_type[]"][value="' + response + ']"').trigger('click');
                },
                error: function(response) {
                    if(!$('input[name="shipping_type[]"]:checked').val()){
                        if($('input[name="shipping_type[]"]').length > 1){
                            $('input[name="shipping_type[]"]').eq(1).trigger('click');
                        } else {
                            $('input[name="shipping_type[]"]').eq(0).trigger('click');
                        }
                    }
                }
            });
        });

        $('body').on('click', '#current-billing_adress', function(e) {
          $('#ship-to-different-address-checkbox').prop('checked',false);
          $('.add-delivery_button').show();
          $('.add-new_adress_form').hide();
        });

        $('body').on('click', 'input[name="shipping_type[]"]', function(e){
            let parent = $(this).closest('.saved-addr');
            let addrId = parent.attr('data-id');
            if(parent.attr('data-addr-data')) {
              let addrData = JSON.parse(parent.attr('data-addr-data'));
              for (const key in addrData){
                  $('input[name='+key+']').val(addrData[key]);
              }
              let data = {
                  action: "hansa_session_delivery_addr",
                  addr: addrId
              };
              $.ajax({
                  url: ajax,
                  data: data,
                  method: "POST",
                  success: function(response) {
                      console.log("saved");
                  },
                  error: function(response) {
                      console.log("not saved");
                  }
              });
            }
        });

    });
</script>
</body>
</html>