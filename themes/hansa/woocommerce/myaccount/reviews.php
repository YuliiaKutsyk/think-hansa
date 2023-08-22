<?php
    $user_id = get_current_user_id();
    $args = array(
        'status'   => 'approve',
        'type' => 'review',
        'author__in' => array($user_id)
    );
    $reviews_query = new WP_Comment_Query;
    $reviews = $reviews_query->query( $args );
    $is_reviews = count($reviews) ? true : false;
?>

<div class="profile-content_top">
    <div class="profile-content_icon">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/My-Reviews.svg" alt="center" class="contain-image">
    </div>
    <h6 class="ptofile-content_title">My Reviews</h6>
</div>

<?php if($is_reviews) { ?>
    <div class="profile-reviews">
        <?php foreach($reviews as $review) { 
            $review_id = $review->comment_ID;
            $product_id = $review->comment_post_ID;
            $product = wc_get_product($product_id);
            $title = get_comment_meta($review_id, "title", true);
            $rating = get_comment_meta($review_id, 'rating', true);
            $date = strtotime($review->comment_date);
        ?>
            <div class="profile-review_item">
              <div class="review-item_top">
                <div class="review-item_thumbnail">
                    <?php echo $product->get_image( 'medium'); ?>
                </div>
                <div class="review-item_titles">
                  <h6><?php echo $product->get_title(); ?></h6>
                  <div class="price"><?php echo strip_tags(wc_price($product->get_price())); ?></div>
                </div>
                <a data-remove-id="<?php echo $review_id ?>" href="#" class="remove-current_review remove-item_button"></a>
              </div>
              <div class="review-item_bottom">
                <div class="review-stars">
                    <?php for($i = 0; $i < 5; $i++){ ?>
                        <a href="#" <?php echo $i < $rating ? 'class="filled"' : ''; ?>></a>
                    <?php } ?>
                </div>
                <div class="review-item_content">
                  <h6><?php echo $title; ?></h6>
                  <p class="review-date">Review on <?php echo date('l d M Y',$date); ?></p>
                </div>
                <div class="review-item_message">
                  <p><?php echo $review->comment_content; ?></p>
                </div>
              </div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="account-empty_data" style="display: block;">
        <h4>You haven't reviewed any products yet</h4>
        <a href="<?php echo wc_get_account_endpoint_url('orders'); ?>" class="grey-button orders-button">My Orders</a>
    </div>
<?php } ?>

<div class="delete-confirm popup">
  <div class="delete-confirm__inner">
    <div class="delete-confirm__close"></div>
    <div class="delete-confirm__title">Remove Review</div>
    <div class="delete-confirm__text">Are you sure you want to remove this review?</div>
    <div class="delete-confirm__btns">
      <div class="delete-confirm__back">Go Back</div>
      <div class="delete-confirm__btn review">Remove Review</div>
    </div>
  </div>
</div>