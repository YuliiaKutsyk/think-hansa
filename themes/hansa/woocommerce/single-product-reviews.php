<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>

<section class="product-review_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-xs-12 product-review_leave">
          <h4>Customer Reviews</h4>
			<?php 
				$reviews_count = $product->get_review_count(); 
				$rating = $product->get_average_rating();
			?>
			<div class="customer-review">
				<div class="review-stars">
					<?php for($i = 0; $i < 5; $i++) { ?>
				  		<a href="#" <?php echo $i < floor($rating) ? 'class="filled"' : ''; ?>></a>
				  	<?php } ?>
				</div>
				<div class="customer-review_options">
				  <p><?php echo number_format($rating,1); ?> <span>(<?php echo $reviews_count . ' ' . _n('Review','Reviews',$reviews_count); ?>)</span></p>
				</div>
			</div>
			<a href="#" class="blank-button review-button open-review_popup">Write a Review</a>
			</div>
			<?php if ( have_comments() ) : ?>

		        <div class="col-md-8 col-xs-12 product-review_exists">
					<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
		        </div>

				<?php
				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
					echo '<nav class="woocommerce-pagination">';
					paginate_comments_links(
						apply_filters(
							'woocommerce_comment_pagination_args',
							array(
								'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
								'next_text' => is_rtl() ? '&larr;' : '&rarr;',
								'type'      => 'list',
							)
						)
					);
					echo '</nav>';
				endif;
				?>
			<?php else : ?>
				<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
			<?php endif; ?>
      </div>
    </div>

    <div class="new-review_popup" id="review_form_wrapper">
      <div class="popup-inner" id="review_form">
        <div class="popup-content">
          <a href="#" class="close-popup_review"></a>
          <h4>Review <?php echo $product->get_title(); ?></h4>

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
					'class_submit'		  => 'submit-review',
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

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
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

<div id="reviews" class="woocommerce-Reviews">
	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper">
			<div id="review_form">
			</div>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
