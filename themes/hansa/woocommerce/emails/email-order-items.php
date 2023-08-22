<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';

$order_id = $order->get_id();
$points = (int)get_post_meta($order_id,'order_total_points',true);

foreach ( $items as $item_id => $item ) :
	$addons_total = 0;
	$product       = $item->get_product();
	$product_id       = $item->get_product_id();
	$sku           = '';
	$purchase_note = '';
	$image         = '';
	$is_addon = has_term('empties','product_cat',$product_id) || has_term('empties-miscellaneous','product_cat',$product_id);
	$total = $item->get_total();

	$regular_price = $product->get_price();
	$retail_price = (float)get_post_meta($product_id,'b2b_price_3', true);

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) || $is_addon) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( 'large' );
	}

	?>
	<tr style="border: 1px solid #E9E9E9;" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td style="width: 80px; height: 80px; text-align: center; margin-right: 16px; border: none; border-bottom: 1px solid #E9E9E9;">
			<?php 
				// Show title/image etc.
				echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
			?>
		</td>	
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; font-weight: 500; color: #000; vertical-align: middle; font-family: Montserrat, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word; border: none; border-bottom: 1px solid #E9E9E9;">
		<?php
		$qty          = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

		if ( $refunded_qty ) {
			$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
		} else {
			$qty_display = esc_html( $qty );
		}


		// Product name.
		echo '<span style="color:#FF7758;">x' . $qty_display . '</span> ' . $item->get_name() . ' (' . wc_price($regular_price) . ')';

		echo '<br>';

    	$product_addons = get_post_meta($product_id,'product_addons',true);
		if($product_addons) {
			$addons = explode(',',$product_addons);
			foreach($addons as $addon) {
				$addon = trim($addon);
				$addon_id = wc_get_product_id_by_sku($addon);
				if($addon_id) {
					if(is_product_in_order($order_id,$addon_id)) {
						$addon_product = wc_get_product($addon_id);
						$addon_price = $addon_product->get_price();
						echo '<p style="padding: 0;font-size: 14px;margin-top: 10px;">';
						echo '<span style="color:#FF7758;">x' . $qty . '</span>';
						echo ' +' . $addon_product->get_title() . ' (' . strip_tags(wc_price($addon_price)) . ')';
						echo '</p>';
                        $addons_total += $addon_price;
					}
				}
			}
		}

		echo '<p style="padding: 0; margin: 0; margin-top: 5px;">';
		if($retail_price > $regular_price) { ?>
			<span style="font-size: 14px; color: #4C4C4C"><?php echo strip_tags(wc_price(($regular_price + $addons_total) * $qty)); ?></span>
			<span style="font-size: 14px; color:#FF7758; text-decoration: line-through;"><?php echo strip_tags(wc_price(($retail_price + $addons_total) * $qty)); ?></span>
		<?php } else { ?>
			<span style="font-size: 14px; color: #4C4C4C"><?php echo strip_tags(wc_price(($regular_price + $addons_total) * $qty)); ?></span>
		<?php }
		if($points > 0 && !hansa_is_b2b_user()) {
			echo '<span  style="font-size: 14px; color: #4C4C4C"> | + ' . loyale_get_price_points(($regular_price + $addons_total) * $qty) . 'pts</span>';
		}
		echo '</p>';

		// SKU.
		if ( $show_sku && $sku ) {
			echo wp_kses_post( ' (#' . $sku . ')' );
		}

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

		wc_display_item_meta(
			$item,
			array(
				'label_before' => '<strong class="wc-item-meta-label" style="float: ' . esc_attr( $text_align ) . '; margin-' . esc_attr( $margin_side ) . ': .25em; clear: both">',
			)
		);

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

		?>
		</td>
		<td style="border: none; border-bottom: 1px solid #E9E9E9;"></td>
	</tr>
	<?php

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: Montserrat, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}
	?>

<?php endforeach; ?>
