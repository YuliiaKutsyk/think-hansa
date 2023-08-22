<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$comment_id = $comment->comment_ID;
$user = get_user_by('id',$comment->user_id);
$full_name = $user->first_name . ' ' . $user->last_name;
$date = strtotime($comment->comment_date);
$title = get_comment_meta($comment_id, "title", true);
$content = $comment->comment_content;
$rating = get_comment_meta($comment_id, 'rating', true);
?>
<div class="product-review_item">
	<div class="review-stars">
		<?php for($i = 0; $i < 5; $i++) { ?>
	  		<a href="#" <?php echo $i < round($rating) ? 'class="filled"' : ''; ?>></a>
	  	<?php } ?>
	</div>
	<h6><?php echo $title; ?></h6>
	<p class="review-author">Review by <b><?php echo $full_name; ?></b>, <?php echo date('l d M Y',$date); ?></p>
	<div class="review-comment">
	  <p><?php echo $content; ?></p>
	</div>
</div>
