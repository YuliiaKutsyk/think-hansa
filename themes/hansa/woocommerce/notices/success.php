<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

?>

<?php foreach ( $notices as $notice ) : ?>
	<div class="woocommerce-message"<?php echo wc_get_notice_data_attr( $notice ); ?> role="alert">
		<?php 
            if(strpos($notice['notice'],' has been added to your cart') !== false) {
                $notice['notice'] = '<strong>' . str_replace(' has been added to your cart.', '', $notice['notice']) . '</strong> has been added to your cart.';
            }
            echo wc_kses_notice( $notice['notice'] ); 
        ?>
        <span class="woocommerce-message-delimiter"></span>
        <span class="woocommerce-message-close"></span>
	</div>
<?php endforeach; ?>
