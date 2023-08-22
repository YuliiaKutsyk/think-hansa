<?php
/** Template Name: Mobile Menu */
get_header();
?>
  <section class="profile-main profile-main_mobile">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="profile-title">Profile</h1>

          <div class="account-page_wrap">

            <div class="account-sidebar">
              <div class="account-sidebar_top">
                <p class="top">Hi,</p>
                <h6 class="profile-name"><?php echo esc_html( $current_user->display_name ); ?></h6>
              </div>
              <ul class="account-sidbar_pages">
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                  <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div><!-- account-sidebar -->
        </div>
      </div>
    </div>
  </section>
  
<?php
get_footer();