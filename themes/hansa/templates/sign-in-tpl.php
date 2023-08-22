<?php
/** Template Name: Sign In/Sign Up */
if(is_user_logged_in()) {
    wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ));
}
get_header();
$loyale_scheme_id = get_field('loyale_scheme_id','option');
?>
  <section class="sign-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="sign-form_wraper">
            <div class="sign-tabs">
              <h6 class="current">Sign In</h6>
              <h6>Create an Account</h6>
            </div>
            <div class="sign-tabs_content--wrap">
              <h4 class="sign-title login">Sign In to Hansa</h4>
              <h4 class="sign-title register">Sign Up to Hansa</h4>
              <?php 
                do_action( 'woocommerce_before_customer_login_form' );
                if(isset($_GET['login_error'])) {
                  if($_GET['login_error'] == 'b2b') { ?>
                  <div class="sign-in-b2b-error">The Hansa loyalty scheme is only available to non-business clients</div>
              <?php }} ?>
              <div class="sign-in-undertitle">
                <div data-loyale-sso data-loyale-other="<?php echo site_url(); ?>"></div>
                <div class=sign-divider><span>or with your email</span></div>
              </div>
              <div class="sign-tab_content login">
                <?php woocommerce_login_form(); ?>
              </div><!-- /sign-tab_content -->
              <div class="sign-tab_content register">
                <?php get_template_part('template-parts/signup-form'); ?>
                <p class="privacy-text">By creating your account, you agree to our <a href="#">Terms & Conditions</a> & <a href="#">Privacy Policy</a></p>
              </div><!-- /sign-tab_content -->
            </div><!-- /sign-tabs_content--wrap -->

            <div class="restore-password_block">
              <div class="restore-password_top">
                <a href="#" class="back-to_sign">Back to Sign In</a>
              </div>
              <div class="restore-password_titles">
                <h4>Forgot Your Password?</h4>
                <p>No worries, enter your email address below and we’ll send you reset instructions.</p>
              </div>
              <form action="#" class="restore-password_form">
                <div class="form-row">
                  <label for="enq-email_restore">Email</label>
                  <input type="text" id="enq-email_restore" name="reset_email" placeholder="Email" />
                </div>
                <button class="black-button reset-pass-btn">Reset Password</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
get_footer();