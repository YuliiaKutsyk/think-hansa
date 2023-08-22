<?php
/** Template Name: Customize */
get_header();
?>
<section class="breadcrumbs-section">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumbs">
        <li><a href="<?php echo site_url(); ?>">Shop</a></li>
        <li class="separator">></li>
        <li><span><?php the_title(); ?></span></li>
      </ul>
      <a href="#" class="back-button">Go Back</a>
    </div>
  </div>
</div>
</section>

<section class="single-product_section">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="product-content_holder">
        <?php $images = get_field('customize_images'); ?>
        <div class="product-slider_wrap">
          <div class="product-slider" id="product-slider">
            <?php foreach($images as $image) { ?>
              <div class="product-slider_image" data-thumb="<?php echo $image['url']; ?>">
                <a href="<?php echo $image['url']; ?>" class="loop"></a>
                <img src="<?php echo $image['url']; ?>" alt="image" class="contain-image">
              </div>
            <?php } ?>
          </div>
        </div>
        <?php 
          $products = new WP_Query( array (
            'post_type' => 'product',
            'post_status' => array('publish','private'),
            'orderby' => 'title',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'meta_query' => array(
              array(
                  'key' => 'is_customizable',
                  'value' => 'yes',
                  'compare' => 'LIKE'
              )
            )
          ));
          $first_product = false;
          $i = 0;
        ?>
        <div class="product-single_content">
          <h1><?php the_title(); ?></h1> 
          <div class="product-details_content custom" <?php echo isset($_GET['id']) ? 'data-selected="' . $_GET['id'] . '"':''; ?>><?php the_content(); ?></div>
          <!-- Customize product -->
          <?php if($products->have_posts()) { ?>
          <div class="customize-product_wrap">
            <p class="top-label">Select Champagne</p>
            <div class="form-row date-dropdown_holder">
              <select name="customize_id" class="hansa-select" id="customize_product_select">
                  <?php while($products->have_posts()) { 
                    $products->the_post();
                    $product_id = get_the_ID();
                    $product = wc_get_product();
                    if($i == 0) {
                      $first_product = $product;
                    }
                    $maxlength = 11;
                    $volumes = wc_get_product_terms($product_id, 'volume');
                    $volume_name = '';
                    if($volumes) {
                      $volume_name = $volumes[0]->name;
                      $maxlength_data = array(
                        '20cl' => 13,
                        '75cl' => 11,
                        '1.5l' => 13,
                        '3l' => 11,
                        '6l' => 14,
                      );
                      if(isset($maxlength_data[$volume_name])) {
                        $maxlength = $maxlength_data[$volume_name];
                      }
                    }
                    if($i == 0) {
                      $first_maxl = $maxlength;
                    }
                  ?>
                    <option data-maxlength="<?php echo $maxlength; ?>" value="<?php the_id(); ?>" class="<?php echo $volume_name; ?>"><?php echo get_the_title() . ' ' . $volume_name . ' (' . wc_price($product->get_price()) . ')'; ?></option>
                  <?php $i++; } ?>
              </select>
            </div>
            <p class="top-label">Custom Label (case-sensitive)</p>
            <div class="customize-row">
              <div class="input-part">
                <label for="custom-input_1">Line 1</label>
                <input type="text" id="custom-input_1" class="customize-input" maxlength="<?php echo $first_maxl; ?>" />
              </div>
              <p class="charcount-reciever"><span class="carcount-number"><?php echo $first_maxl; ?></span> characters left</p>
            </div>
            <div class="customize-row">
              <div class="input-part">
                <label for="custom-input_2">Line 2</label>
                <input type="text" id="custom-input_2" class="customize-input" maxlength="<?php echo $first_maxl; ?>" />
              </div>
              <p class="charcount-reciever"><span class="carcount-number"><?php echo $first_maxl; ?></span> characters left</p>
            </div>
            <div class="custom-bottom_text">
              <p>Due to limitations of the equipment, some words or phrases may not fit properly. In this unlikely event, the Hansa team will contact you for adjustments.</p>
            </div>
          </div>
        <?php } ?>
          <!-- /Customize product -->

          <div class="price-wrap">
            <div class="left">
              <div class="current-price customize" data-price="<?php echo $first_product->get_price() + 10; ?>"><?php echo strip_tags(wc_price($first_product->get_price() + 10)); ?></div>
            </div>
            <?php if(!hansa_is_b2b_user()) { ?>
              <div data-points="<?php echo loyale_get_price_points($first_product->get_price() + 10); ?>" class="points-value customize">+ <?php echo loyale_get_price_points($first_product->get_price() + 10); ?>pts</div>
            <?php } ?>
          </div>
          <div class="qty-wrap">
            <div class="quantity-choser customize">
              <div class="quantity-less"></div>
              <input type="text" value="1" class="qty-number" min="1" name="qtyValue" readonly />
              <div class="quantity-more"></div>
            </div>
            <a href="#" class="add-to-cart customize">Add to Cart</a>
          </div>
          <div class="payment-titles">
            <p>Secure payment</p>
            <div class="payments-images">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/payments.png" alt="payments">
            </div>
          </div>
          <div class="product-options">
            <p class="options-row delivery-row">Free delivery for orders over €50</p>
            <p class="options-row support-row">Customer support - email & phone</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<section class="product-review_section">
<div class="container">
  <div class="row">
    <div class="col-md-4 col-xs-12 product-review_leave">
      <h4>Customer Reviews</h4>
      <div class="customer-review">
        <div class="review-stars">
          <a href="#"></a>
          <a href="#"></a>
          <a href="#"></a>
          <a href="#"></a>
          <a href="#"></a>
        </div>
        <div class="customer-review_options">
          <p>4.5 <span>(2 Review)</span></p>
        </div>
      </div>
      <a href="#" class="blank-button review-button open-review_popup">Write a Review</a>
    </div>
    <div class="col-md-8 col-xs-12 product-review_exists">
      <?php 
        $args = array ('post_type' => 'product', 'post_id' => $first_product->get_id());
        $comments = get_comments( $args );
        wp_list_comments( array( 'callback' => 'woocommerce_comments'), $comments);
      ?>
    </div>
  </div>
</div>

<div class="new-review_popup" id="review_form_wrapper">
      <div class="popup-inner" id="review_form">
        <div class="popup-content">
          <a href="#" class="close-popup_review"></a>
          <h4>Review <?php echo $first_product->get_title(); ?></h4>

        <?php
        $commenter    = wp_get_current_commenter();
        $comment_form = array(
          /* translators: %s is product title */
          'title_reply'         => '',
          /* translators: %s is product title */
          'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
          'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
          'title_reply_after'   => '</span>',
          'comment_notes_after' => '',
          'label_submit'        => esc_html__( 'Add Review', 'woocommerce' ),
          'logged_in_as'        => '',
          'comment_field'       => '',
          'class_submit'      => 'submit-review',
        );

        $name_email_required = (bool) get_option( 'require_name_email', 1 );
        $fields              = array(
          'author' => array(
            'label'    => __( 'Name', 'woocommerce' ),
            'type'     => 'text',
            'value'    => $commenter['comment_author'],
            'required' => $name_email_required,
          ),
          'email'  => array(
            'label'    => __( 'Email', 'woocommerce' ),
            'type'     => 'email',
            'value'    => $commenter['comment_author_email'],
            'required' => $name_email_required,
          ),
        );

        $comment_form['fields'] = array();

        foreach ( $fields as $key => $field ) {
          $field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
          $field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

          if ( $field['required'] ) {
            $field_html .= '&nbsp;<span class="required">*</span>';
          }

          $field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

          $comment_form['fields'][ $key ] = $field_html;
        }

        $account_page_url = wc_get_page_permalink( 'myaccount' );
        if ( $account_page_url ) {
          /* translators: %s opening and closing link tags respectively */
          $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
        }

        if ( wc_review_ratings_enabled() ) {
          $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
            <option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
            <option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
            <option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
            <option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
            <option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
            <option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
          </select></div>';
        }

        $comment_form['comment_field'] .= '<div class="form-row">
              <span class="wpcf7-form-control-wrap">
                <label for="enq-review_message">Your Review</label>
                <textarea placeholder="Your Review" id="enq-review_message" required name="comment"></textarea>
              </span>
            </div>';

        comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ), $first_product->get_id() );
        ?>
        <div class="form-row stars">
                <label for="">Your Rating</label>
                <div class="review-stars">
                  <a href="#"></a>
                  <a href="#"></a>
                  <a href="#"></a>
                  <a href="#"></a>
                  <a href="#"></a>
                </div>
              </div>
    </div>
      </div>
    </div>
</section>
<?php
get_footer();