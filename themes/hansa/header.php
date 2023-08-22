<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <?php wp_head(); ?>
  <!-- Hotjar Tracking Code for https://www.hansa.com.mt/ -->
<!--   <script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3070540,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
  </script> -->
    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '526248066089495');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=526248066089495&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->
</head>
<body <?php body_class(); ?>>
<!-- wrapper -->
<div class="wrapper <?php echo is_checkout() ? 'checkout-page' : ''; ?>">
  <header role="banner">
    <div class="header-top_row">
      <div class="container">
        <div class="row">
          <div class="col-md-12 header-row_wrap">
            <a href="<?php echo site_url(); ?>" class="header-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Hansa-Full-Logo-White.svg" alt="logotype" class="contain-image">
            </a><!-- /header--logo -->

            <a href="<?php echo site_url(); ?>" class="header-logo_inner">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Hansa-Logo-White.svg" alt="logotype" class="contain-image">
            </a><!-- /header--logo -->

            <nav class="header-nav" role="navigation">
              <?php
                wp_nav_menu(array(
                  'menu' => 'Top menu',
                  'container' => '',
                  'menu_class' => 'headnav'
                ));
              ?>
            </nav><!-- /header--nav -->

            <div class="mobile-header_holder header-right_holder">
              <a href="<?php echo wc_get_cart_url(); ?>" class="header-cart mobile-header-cart">
                <span class="minicart-holder"></span>
                <span class="minicart-itemscount"><?php echo WC()->cart->get_cart_contents_count() - hansa_count_cart_cat_items('empties'); ?></span>
              </a>
              <a href="<?php echo site_url(); ?>/wishlist" class="header-wishlist"></a>
              <a href="<?php echo site_url();?>/my-account" class="header-account_options"></a>
              <div class="hamburger"></div>
            </div>

          </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
      </div><!-- /.container -->
    </div><!-- header-top_row -->

    <div class="header-bottom_row">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="header-row_wrap--second">
              <div class="header-categories_wrap">
                <div class="header-categories_title">Categories</div>
                <div class="header-categories_dropdown"></div>
              </div>
              <div class="header-search">
                <form mathod="get" action="/" class="search-form">
                  <button class="search-submit"></button>
                  <input type="search" name="s" class="header-search__input" placeholder="Search product …" />
                </form>
                <div class="search-product_dropdown">
                  <div class="product-dropdown_bottom">
                    <a href="#" class="search-all-link">View All</a>
                  </div>
                </div><!-- /search-product_dropdown -->
              </div>
              <div class="header-right_holder <?php echo !is_user_logged_in() ? 'guest': ''; ?>">
                <a href="<?php echo wc_get_cart_url(); ?>" class="header-cart desktop-header-cart">
                  <span class="minicart-holder"></span>
                  <span class="minicart-itemscount"><?php echo WC()->cart->get_cart_contents_count() - hansa_count_cart_cat_items('empties'); ?></span>
                </a>
                <a href="<?php echo site_url(); ?>/wishlist" class="header-wishlist"></a>
                <a href="#" class="header-account_options">
                <?php if(is_user_logged_in()) {
                  $user = wp_get_current_user();
                  $user_name = $user->first_name;
                ?>
                  <div class="header-account_options--holder">
                    <div class="header-account_title">Welcome back,</div>
                    <div class="header-account_name"><?php echo $user_name; ?></div>
                  </div>
                <?php } else { ?>
                  <div class="header-account_options--holder">
                  </div>
                <?php } ?>
                </a>
                <?php if(is_user_logged_in()) { ?>
                  <div class="header-account_links">
                    <a href="<?php echo site_url();?>/my-account" class="create-account_link">Account</a>
                    <a href="<?php echo wp_logout_url(); ?>" class="sign-out_button">Logout</a>
                  </div>
                <?php } else { ?>
                  <div class="header-account_links">
                    <a href="<?php echo site_url();?>/login" class="sign-in_button">Sign In</a>
                    <a href="<?php echo site_url();?>/login#register" class="create-account_link">Create an Account</a>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mobile-menu">
      <div class="mobile-search">
        <form action="/" method="get">
          <button class="search-submit"></button>
          <input type="search" name="s" placeholder="Search product …" />
        </form>
      </div>
      <?php
        $parent_ids = array();
        $orderby = 'meta_value_num';
        $order = 'asc';
        $hide_empty = false;
        $cat_args = array(
            'meta_key' => 'top_menu_order',
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty
        );
        $product_cats = get_terms( 'product_cat', $cat_args );
      ?>
      <nav class="mobile-navigation">
        <ul class="mobilenav">
          <?php foreach($product_cats as $cat) {
            $cat_id = $cat->term_id;
            $is_display = get_field('is_in_cat_menu','term_'.$cat_id);
            $cat_children = get_term_children($cat_id, 'product_cat');
            if(!$cat->parent && $is_display) {
              array_push($parent_ids, $cat_id);
          ?>
            <li <?php echo $cat_children ? 'class="menu-item-has-children"':''; ?>>
              <a href="<?php echo get_term_link( $cat_id, 'product_cat' ); ?>"><?php echo $cat->name; ?></a>
              <?php if($cat_children) { ?>
                <ul class="sub-menu">
                  <?php foreach($cat_children as $subcat_id) {
                    $subcat = get_term($subcat_id);
                    $is_display = get_field('is_in_cat_menu','term_'. $subcat_id);
                    if($is_display) {
                  ?>
                    <li><a href="<?php echo get_term_link($subcat->slug, 'product_cat'); ?>"><?php echo $subcat->name; ?></a></li>
                  <?php }} ?>
                </ul>
              <?php } ?>
            </li>
          <?php }} ?>
        </ul>
      </nav>
      <nav class="mobile-navigation_second">
        <?php
          wp_nav_menu(array(
            'menu' => 'Top menu',
            'container' => '',
            'menu_class' => 'mobilenav'
          ));
        ?>
      </nav>
      <?php
        $socials = get_field('footer_socials','option');
        if($socials) {
      ?>
        <div class="mobile-social">
            <?php foreach($socials as $s) {
              $image = $s['icon'];
            ?>
              <a href="<?php echo $s['link']; ?>" target=_blank class="facebook" style="background: transparent url('<?php echo $image['url']; ?>') 50% no-repeat"></a>
            <?php } ?>
        </div>
      <?php } ?>
      </div>

    <div class="category-dropdown_holder">
      <div class="container">
        <div class="row">
          <div class="col-md-12 category-dropdown_wrap">
            <ul class="category-dropdown category-dropdown_list">
              <?php foreach($product_cats as $cat) {
                $cat_id = $cat->term_id;
                $is_display = get_field('is_in_cat_menu','term_'.$cat_id);
                if(!$cat->parent && $is_display) {
                  if(!in_array($cat_id, $parent_ids))
                  array_push($parent_ids, $cat_id);
                  $thumbnail_id = get_term_meta( $cat_id, 'thumbnail_id', true );
                  $image_url    = wp_get_attachment_url( $thumbnail_id );
              ?>
                <li data-thumb="<?php echo $image_url; ?>"><a href="#"><?php echo $cat->name; ?></a></li>
              <?php }} ?>
                <li><a href="<?php echo site_url(); ?>/customise">Customise Moët</a></li>
            </ul>
            <div class="category-dropdown subcategory-dropdown_wrap">
              <?php foreach($parent_ids as $parent_id) { ?>
                <ul class="subcategory-dropdown_list">
                  <li><a href="<?php echo get_term_link( $parent_id, 'product_cat' ); ?>">View All</a></li>
                  <?php
                    $orderby = 'name';
                    $order = 'asc';
                    $hide_empty = false ;
                    $cat_args = array(
                        'orderby'    => $orderby,
                        'order'      => $order,
                        'hide_empty' => $hide_empty,
                        'child_of' => $parent_id,
                    );
                    $product_cats = get_terms( 'product_cat', $cat_args );
                    foreach($product_cats as $product_cat) {
                      if(count(get_ancestors( $product_cat->term_id, 'product_cat' )) == 1) {
                        $thumbnail_id = get_term_meta( $product_cat->term_id, 'thumbnail_id', true );
                        $image_url    = wp_get_attachment_url( $thumbnail_id );
                        $is_display = get_field('is_in_cat_menu','term_'. $product_cat->term_id);
                        if($is_display) {
                  ?>
                    <li data-thumb="<?php echo $image_url; ?>"><a href="<?php echo get_term_link( $product_cat->term_id, 'product_cat' ); ?>"><?php echo $product_cat->name; ?></a></li>
                  <?php }}} ?>
                </ul>
              <?php } ?>
            </div>
            <div class="category-thumbnail_wrap">
              <div class="header-category_thumb">
                <img src="" alt="image" style="display: none;" class="contain-image">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header><!-- /header -->

  <?php
  print_r('user points');
  var_dump(loyale_get_customer_points());
  ?>