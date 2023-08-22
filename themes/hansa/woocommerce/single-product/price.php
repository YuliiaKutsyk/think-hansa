<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$product_id = $product->get_id();
$regular_price = $product->get_price();
$retail_price = (float)get_post_meta($product_id,'b2b_price_3', true);
$is_customizable = get_post_meta($product_id,'is_customizable',true);
?>

<div class="price-wrap">
    <?php if($product->get_price() > 0){?>
    <div class="left">
        <?php if($retail_price > $regular_price) { ?>
            <div class="current-price"><?php echo strip_tags(wc_price($regular_price)); ?></div>
            <div class="sale-price"><?php echo strip_tags(wc_price($retail_price)); ?></div>
        <?php } else { ?>
            <div class="current-price"><?php echo strip_tags(wc_price($regular_price)); ?></div>
        <?php } ?>
    </div>
    <?php if(!hansa_is_b2b_user()) { ?>
        <div class="points-value">+ <?php echo loyale_get_product_points($product_id); ?>pts</div>
    <?php } ?>
    <?php
    } else {?>
        <div class="left">
            <div class="current-price">Price On Request</div>
        </div>
    <?php } ?>
</div>
<?php 
    $addons = get_post_meta($product_id,'product_addons',true);
    if($addons) {
        $addons_list = explode(',',$addons);
?>
<div class="single-product__addons">
    <?php 
        foreach($addons_list as $addon_sku) {
            $addon_sku = trim($addon_sku);
            $addon_product_id = wc_get_product_id_by_sku(trim($addon_sku));
            $addon_product = wc_get_product($addon_product_id);
            $addon_title = '';
            if($addon_product) {
                $addon_title = $addon_product->get_title();
            }
            if($addon_title) {
    ?>
        <div class="single-product__addon">+ <?php echo $addon_title . ' (' . strip_tags(wc_price($addon_product->get_price())) . ')'; ?></div>
    <?php }} ?>
</div>
<?php } ?>
<?php if($is_customizable) { ?>
    <a href="/customise?id=<?php echo $product_id; ?>" class="single-customize-btn">Customise Moët</a>
<?php } ?>
