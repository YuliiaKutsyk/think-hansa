<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
global $wp_query;
global $wpdb;
$main_cat_id = get_queried_object_id();

//$prices = get_minmax_price();
//$min = $prices['min'];
//$max = $prices['max'];

$min = 0;
$max = 1;
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                /**
                 * Hook: woocommerce_before_main_content.
                 *
                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                 * @hooked woocommerce_breadcrumb - 20
                 * @hooked WC_Structured_Data::generate_website_data() - 30
                 */
                do_action( 'woocommerce_before_main_content' );
                ?>
            </div>
        </div>
    </div>
    </div>
    <script language="javascript" type="text/javascript">
        //Filter search
        function filter(element) {
            var value = jQuery(element).val().toLowerCase();

            jQuery(element).parents('.category-filter_dropdown').find('.filter-form_row label').each(function() {
                if (jQuery(this).text().toLowerCase().search(value) > -1) {
                    jQuery(this).show();
                }
                else {
                    jQuery(this).hide();
                }
            });
        }
    </script>
    <div class="archive-wrapper">
        <section class="category-filters">
            <div class="container">
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h1><?php woocommerce_page_title(); ?></h1>
                            <?php
                            /**
                             * Hook: woocommerce_archive_description.
                             *
                             * @hooked woocommerce_taxonomy_archive_description - 10
                             * @hooked woocommerce_product_archive_description - 10
                             */
                            do_action( 'woocommerce_archive_description' );
                            ?>
                        </div>
                    </div>
                <?php
                endif;
                $term_id = get_queried_object_id();
                $term_name = single_term_title('',false);
                $filter_taxes = array();
                if(get_option("related_tax_$term_id")) {
                    $filter_taxes = get_option("related_tax_$term_id");
                }
                $taxonomies = get_taxonomies(array('_builtin' => false),'objects');
                $exclude_tax = array('product_type','product_visibility','product_cat','product_tag','product_shipping_class','pa_size');
                ?>


                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="get">
                            <div class="mobile-filter_button">Filter Products</div>
                            <div class="category-filters_holder" id="category-filters_holder">

                                <!-- // Subcategories filter-->
                                <?php
                                $args = array(
                                    'taxonomy' => 'product_cat',
                                    'parent' => $term_id
                                );
                                $subcats = get_terms($args);
                                $i = 1;
                                $active_subcats = array();
                                if(isset($_GET['subcat']) && !empty($_GET['subcat'])) {
                                    $active_subcats = explode(',',$_GET['subcat']);
                                }
                                ?>
                                <div class="category-filter_item">
                                    <div class="current-filter_choose"><?php echo $term_name; ?> Categories</div>
                                    <div class="filter-choose_hidden hidden"><?php echo $term_name; ?> Categories</div>
                                    <a href="#" class="mobile-clear_filter">Clear</a>
                                    <div class="category-filter_dropdown">
                                        <div class="mobile-filter_title">
                                            <a href="#" class="close-filter_button"></a>
                                            <h4><?php echo $term_name; ?> Categories</h4>
                                        </div>
                                        <div class="filter-form_top">
                                            <button class="search-submit"></button>
                                            <input type="search" placeholder="Search" class="seach-input" onkeyup="filter(this)">
                                        </div>
                                        <div class="filter-form_items">
                                            <button class="clear-filter" data-tax="subcat">Clear</button>

                                            <?php
                                            foreach($subcats as $subcat) {
                                                $subcat_name = $subcat->name;
                                                $subcat_slug = $subcat->slug;
                                                $subcat_id = $subcat->term_id;
                                                $is_active = in_array($subcat_id, $active_subcats);
                                                $sql_subcat = "SELECT COUNT(wp_posts.ID) AS quantity FROM wp_posts LEFT JOIN wp_term_relationships ON ( wp_posts.ID = wp_term_relationships.object_id ) WHERE wp_posts.post_type IN ('product') AND wp_posts.post_status = 'publish' AND ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN ({$main_cat_id}) ) AND NOT ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN (6, 7) ) ) AND wp_term_relationships.term_taxonomy_id IN ({$subcat_id}) );";
                                                $subcat_q = $wpdb->get_row($sql_subcat);
                                                $count = $subcat_q->quantity;
                                                if($count) {
                                                    ?>
                                                    <div class="filter-form_row<?php echo $is_active ? ' active' : ''; ?>">
                                                        <label for="filter-form_input--<?php echo $i; ?>" <?php echo $is_active ? 'class="active"' : ''; ?>><?php echo $subcat_name; ?> <span>(<?php echo $count; ?>)</span></label>
                                                        <input type="checkbox" <?php echo $is_active ? 'checked' : ''; ?> name="subcat" id="filter-form_input--<?php echo $i; ?>" class="filter-form_checkbox" value="<?php echo $subcat_id; ?>">
                                                    </div>
                                                    <?php $i++; }}?>
                                        </div>
                                    </div>
                                </div><!-- category-filter_item -->

                                <?php
                                $orderby = 'relevance';
                                if(isset($_GET['orderby'])) {
                                    $orderby = $_GET['orderby'];
                                }
                                $sort_options = array(
                                    'relevance' => 'Relevance',
                                    'title' => 'Alphabetic',
                                    'rating' => 'Top Rated',
                                    'date' => 'Latest',
                                    'price-asc' => 'Price: Low to High',
                                    'price-desc' => 'Price: High to Low'
                                );
                                $i = 1;
                                ?>
                                <div class="category-filter_item">
                                    <div class="current-filter_choose">Sort</div>
                                    <div class="category-filter_dropdown">
                                        <div class="mobile-filter_title">
                                            <a href="#" class="close-filter_button"></a>
                                            <h4>Sort</h4>
                                        </div>
                                        <div class="filter-form_items">
                                            <?php foreach($sort_options as $key => $option) {
                                                $is_active = $orderby == $key;
                                                ?>
                                                <div class="filter-form_row filter-form_row--sort">
                                                    <label for="filter-form_search_input--<?php echo $i; ?>" <?php echo $is_active ? 'class="active"' : ''; ?>><?php echo $option; ?></label>
                                                    <input type="checkbox" id="filter-form_search_input--<?php echo $i; ?>" name="orderby" class="filter-form_checkbox" value="<?php echo $key; ?>">
                                                </div>
                                                <?php $i++; }?>
                                        </div>
                                    </div>
                                </div><!-- category-filter_item -->

                                <?php
                                $t5 = microtime(true);
                                foreach($taxonomies as $key=>$value) {
                                    if(in_array($value->name, $exclude_tax) || in_array($value->name,$filter_taxes)) {
                                        continue;
                                    }
                                    $filter_obj = $value;
                                    $filter = $value->name;
                                    $filter_label = $filter_obj->label;
                                    $filter_slug = $filter_obj->name;
                                    $filter_alias = $filter_slug;
                                    if($filter_alias == 'country') {
                                        $filter_alias = 'countries';
                                    }
                                    if($filter_alias == 'grape') {
                                        $filter_alias = 'grapes';
                                    }
                                    if($filter_alias == 'volume') {
                                        $filter_alias = 'volumes';
                                    }
                                    if($filter_alias == 'region') {
                                        $filter_alias = 'regions';
                                    }
                                    if($filter_alias == 'country') {
                                        $filter_alias = 'countries';
                                    }
                                    if($filter_alias == 'vintage') {
                                        $filter_alias = 'vintages';
                                    }
                                    if($filter_alias == 'abv') {
                                        $filter_alias = 'abvs';
                                    }
                                    if($filter_alias == 'producer') {
                                        $filter_alias = 'producers';
                                    }
                                    $args = array(
                                        'taxonomy' => $filter
                                    );
                                    $sql_terms = "SELECT `wp_terms`.`term_id`, `wp_terms`.`name`, `wp_terms`.`slug` FROM `wp_terms` LEFT JOIN `wp_term_taxonomy` ON ( `wp_terms`.`term_id` = `wp_term_taxonomy`.`term_id` ) WHERE `wp_term_taxonomy`.`taxonomy` = '{$filter}' AND `wp_term_taxonomy`.`count` > 0 ORDER BY `wp_terms`.`name` ASC;";
//                                    echo '<pre>';
//                                    print_r('test requests');
//                                    echo '</pre>';
//                                    echo '<pre>';
//                                    print_r($active_subcats);
//                                    echo '</pre>';
//                                    echo '<pre>';
//                                    print_r($sql_terms);
//                                    echo '</pre>';
                                    $filter_terms = $wpdb->get_results($sql_terms);
                                    $i = 1;
                                    $active_filters = array();
                                    if(isset($_GET[$filter_alias]) && !empty($_GET[$filter_alias])) {
                                        $active_filters = explode(',',$_GET[$filter_alias]);
                                    }
                                    ?>
                                    <div class="category-filter_item">
                                        <div class="current-filter_choose"><?php echo $filter_label; ?></div>
                                        <div class="filter-choose_hidden hidden"><?php echo $filter_label; ?></div>
                                        <div class="mobile-items_chosen"></div>
                                        <div class="category-filter_dropdown">
                                            <div class="mobile-filter_title">
                                                <a href="#" class="close-filter_button"></a>
                                                <h4><?php echo $filter_label; ?></h4>
                                            </div>
                                            <div class="filter-form_top">
                                                <button class="search-submit"></button>
                                                <input type="search" placeholder="Search" class="seach-input" onkeyup="filter(this)">
                                            </div>
                                            <div class="filter-form_items">
                                                <button class="clear-filter" data-tax="<?php echo $filter_alias; ?>">Clear</button>
                                                <?php
                                                foreach($filter_terms as $ft) {
                                                    $item_name = $ft->name;
                                                    $item_slug = $ft->slug;
                                                    $item_id = $ft->term_id;
                                                    $is_active = in_array($item_id, $active_filters);
                                                    $sql = "SELECT COUNT(wp_posts.ID) AS quantity FROM wp_posts LEFT JOIN wp_term_relationships ON ( wp_posts.ID = wp_term_relationships.object_id ) WHERE wp_posts.post_type IN ('product') AND wp_posts.post_status = 'publish' AND ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN ({$main_cat_id}) ) AND NOT ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN (6, 7) ) ) AND wp_term_relationships.term_taxonomy_id IN ({$item_id}) );";
                                                    $result = $wpdb->get_row($sql);
                                                    $count = $result->quantity;
                                                    if($count) {
                                                        ?>
                                                        <div class="filter-form_row<?php echo $is_active ? ' active' : ''; ?>">
                                                            <label for="filter-form_<?php echo $filter_slug; ?>_input--<?php echo $i; ?>" <?php echo $is_active ? 'class="active"' : ''; ?>><?php echo $item_name; ?> <span>(<?php echo $count; ?>)</span></label>
                                                            <input type="checkbox" <?php echo $is_active ? 'checked' : ''; ?> id="filter-form_<?php echo $filter_slug; ?>_input--<?php echo $i; ?>" class="filter-form_checkbox" name="<?php echo $filter_alias; ?>" value="<?php echo $item_id; ?>">
                                                        </div>
                                                        <?php $i++; }} ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }



                                // if(isset($_GET['price'])) {
                                // 	$url_prices = explode(',',$_GET['price']);
                                // 	if(isset($url_prices[0])) {
                                // 		$min = intval($url_prices[0]);
                                // 	}
                                // 	if(isset($url_prices[1])) {
                                // 		$max = intval($url_prices[1]);
                                // 	}
                                // }
                                ?>
                                <div class="category-filter_item">
                                    <input type="hidden" id="rng_start_min" style="display: none;" value="<?php echo $min; ?>">
                                    <input type="hidden" id="rng_start_max" style="display: none;" value="<?php echo $max; ?>">
                                    <div class="current-filter_choose">Price Range</div>
                                    <div class="mobile-items_chosen"></div>
                                    <div class="category-filter_dropdown range-dropdown">
                                        <div class="mobile-filter_title">
                                            <a href="#" class="close-filter_button"></a>
                                            <h4>Price Range</h4>
                                        </div>
                                        <div>
                                            <button class="clear-filter" data-tax="price">Clear</button>
                                        </div>
                                        <div class="top-title_wrap">
                                            <p>Price Range Selected</p>
                                        </div>
                                        <div class="filter-form_items range-form_wrap">
                                            <div class="rangeslider-holder">
                                                <div id="slider-range" class="price-filter-range rangeslider" name="rangeInput" data-role="rangeslider">
                                                    <div class="price-range-block">
                                                        <div class="range-inputs_wrap">
                                                            <div class="price-input_holder">
                                                                <p>€</p>
                                                                <input type="text" min="<?php echo $min; ?>" max="<?php echo $max; ?>" oninput="validity.valid||(value='<?php echo $min; ?>');" id="min_price" class="price-range-field" />
                                                            </div>
                                                            <div class="price-input_holder">
                                                                <p>€</p>
                                                                <input type="text" min="<?php echo $min; ?>" max="<?php echo $max; ?>" oninput="validity.valid||(value='<?php echo $max; ?>');" id="max_price" class="price-range-field" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- category-filter_item -->
                                <?php
                                $args = array(
                                    'taxonomy' => 'product_tag'
                                );
                                $tags = get_terms($args);
                                $i = 1;
                                $active_tags = array();
                                if(isset($_GET['tags']) && !empty($_GET['tags'])) {
                                    $active_tags = explode(',',$_GET['tags']);
                                }
                                ?>
                                <div class="category-filter_item">
                                    <div class="current-filter_choose">Lifestyle</div>
                                    <div class="filter-choose_hidden hidden">Lifestyle</div>
                                    <div class="mobile-items_chosen"></div>
                                    <div class="category-filter_dropdown">
                                        <div class="mobile-filter_title">
                                            <a href="#" class="close-filter_button"></a>
                                            <h4>Lifestyle</h4>
                                        </div>
                                        <div class="filter-form_top">
                                            <button class="search-submit"></button>
                                            <input type="search" placeholder="Search" class="seach-input" onkeyup="filter(this)">
                                        </div>
                                        <div class="filter-form_items">
                                            <button class="clear-filter" data-tax="tags">Clear</button>
                                            <?php foreach($tags as $tag) {
                                                $tag_name = $tag->name;
                                                $tag_slug = $tag->slug;
                                                $tag_id = $tag->term_id;
                                                $is_active = in_array($tag_id, $active_tags);

                                                $sql = "SELECT COUNT(wp_posts.ID) AS quantity FROM wp_posts LEFT JOIN wp_term_relationships ON ( wp_posts.ID = wp_term_relationships.object_id ) WHERE wp_posts.post_type IN ('product') AND wp_posts.post_status = 'publish' AND ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN ({$main_cat_id}) ) AND NOT ( wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN (6, 7) ) ) AND wp_term_relationships.term_taxonomy_id IN ({$tag_id}) );";
                                                $result = $wpdb->get_row($sql);
                                                $count = $result->quantity;
                                                if($count) {
                                                    ?>
                                                    <div class="filter-form_row<?php echo $is_active ? ' active' : ''; ?>">
                                                        <label for="filter-form_lifestyle_input--<?php echo $i; ?>" <?php echo $is_active ? 'class="active"' : ''; ?>><?php echo $tag_name; ?> <span>(<?php echo $count; ?>)</span></label>
                                                        <input type="checkbox" <?php echo $is_active ? 'checked' : ''; ?> id="filter-form_lifestyle_input--<?php echo $i; ?>" class="filter-form_checkbox" name="tags" value="<?php echo $tag_id; ?>">
                                                    </div>
                                                    <?php $i++; }} ?>
                                        </div>
                                    </div>
                                </div><!-- category-filter_item -->

                                <div class="category-reset-btn black-btn">Clear All</div>

                            </div><!-- category-filters_holder -->
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <?php
        $current_id = get_queried_object()->term_id;
        $found_posts = $wp_query->found_posts;
        ?>
        <section class="products-section" data-parent="<?php echo $current_id; ?>">
            <div class="container">
                <div class="row archive-top-block" <?php echo $found_posts == 0 ? 'style="display: none;"' : ''; ?>>
                    <div class="col-md-12">
                        <?php

                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        // do_action( 'woocommerce_before_shop_loop' );
                        ?>
                        <p class="items-found"><span><?php echo $found_posts; ?></span> Products Found</p>
                    </div>
                </div>
                <div class="row archive-products-row categories-row">
                    <?php
                    if ( woocommerce_product_loop() ) {
                        woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();
                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action( 'woocommerce_shop_loop' );
                                wc_get_template_part( 'content', 'product' );
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>
                        <?php
                    } else {
                        /**
                         * Hook: woocommerce_no_products_found.
                         *
                         * @hooked wc_no_products_found - 10
                         */
                        do_action( 'woocommerce_no_products_found' );
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action( 'woocommerce_after_shop_loop' );
                        $page = 1;
                        if(isset($_GET['page'])) {
                            if($_GET['page'] > 1) {
                                $page = $_GET['page'];
                            }
                        }
                        ?>
                        <div class="load-more-wrapper">
                            <button class="load-more-btn black-button" <?php if($found_posts <= $wp_query->query_vars['posts_per_page']) { ?>style="display:none;"<?php } ?> data-page="<?php echo $page; ?>">Load more</button>
                        </div>
                    </div>
                </div>
                <?php
                /**
                 * Hook: woocommerce_after_main_content.
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );

                /**
                 * Hook: woocommerce_sidebar.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action( 'woocommerce_sidebar' );
                ?>
            </div>
        </section>
    </div>
<?php
$is_bottom_visible = false;
if($found_posts > $wp_query->query_vars['posts_per_page']) {
    $is_bottom_visible = true;
}
?>
    <section class="bottom-banner_section" <?php echo $is_bottom_visible ? 'style="display: none"' : ''; ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bottom-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/End-of-List.png" alt="image" class="cover-image">
                        <h4>You’ve reached the end of the list</h4>
                        <p>Haven’t found what you were looking for? <br> Try refining your search or contact us for more information.</p>
                        <a href="<?php echo site_url(); ?>/contact" class="blank-button">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mobile-filter_bg"></div>
<?php
get_footer( 'shop' );
