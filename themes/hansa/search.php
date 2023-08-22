<?php 
	get_header();
  global $wp_query;


  $s_args = array(
    'post_type' => 'product',
    's' => get_search_query(),
    'extend_where' => hansa_get_extended_search_params(get_search_query()),
    'post_status' => get_query_statuses(),
    'tax_query' => array(
      'relation' => 'AND',
      array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array('champagne-labels','empties'),
            'operator'=> 'NOT IN'
      ),
      array(
        'taxonomy'  => 'product_visibility',
        'terms'     => array('exclude-from-search','exclude-from-catalog'),
        'field'     => 'slug',
        'operator'  => 'NOT IN'
      )
    ),
    'meta_query' => array(
      array(
        'key' => 'is_gift',
        'compare' => 'NOT EXISTS'
      )
    )
  );

  $posts_per_page = intval(get_option('posts_per_page'));
  if(isset($_GET['page_n'])) {
    if($_GET['page_n'] > 1) {
      $s_args['posts_per_page'] = $_GET['page_n'] * $posts_per_page;
    }
  } else {
    $s_args['posts_per_page'] = $posts_per_page;
  }

  if(isset($_GET['orderby']) && !empty($_GET['orderby'])) {
    switch ($_GET['orderby']) {
      case 'title':
        $s_args['orderby'] = 'title';
        $s_args['order'] = 'ASC';
        break;
      case 'rating':
        $s_args['orderby'] = 'meta_value_num';
        $s_args['meta_key'] = '_wc_average_rating';
        break;
      case 'date':
        $s_args['orderby'] = 'date';
        break;
      case 'price-asc':
        $s_args['orderby'] = 'meta_value_num';
        $s_args['meta_key'] = '_price';
        $s_args['order'] = 'ASC';
        break;
      case 'price-desc':
        $s_args['orderby'] = 'meta_value_num';
        $s_args['meta_key'] = '_price';
        $s_args['order'] = 'DESC';
        break;
      default:
        $s_args['orderby'] = 'relevance';
        break;
    }
  }

  $s_query = new WP_Query($s_args);
  $temp_query = $wp_query;
  $wp_query = $s_query;

	$found_posts = $wp_query->found_posts;
  if ( have_posts() ) : ?>
<section class="category-filters">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Search Results For: "<?php the_search_query(); ?>"</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="mobile-filter_button">Filter Products</div>
        <div class="category-filters_holder" id="category-filters_holder">
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
                <?php $i++; } ?>
              </div>
            </div>
          </div><!-- category-filter_item -->

        </div><!-- category-filters_holder -->
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="products-section archive-wrapper" data-s="<?php the_search_query(); ?>">
    <div class="container">
      <div class="row archive-top-block" <?php echo $found_posts == 0 ? 'style="display: none;"' : ''; ?>>
    	    <div class="col-md-12">
    			<p class="items-found"><span><?php echo $found_posts; ?></span> Products Found</p>
    		</div>
    	</div>
      <div class="row archive-products-row categories-row">
      	<?php
		    if ( have_posts() ) :
		        /* Start the Loop */
		        while ( have_posts() ) :
		            the_post();
                wc_get_template_part( 'content', 'product' );
		        endwhile;
		    else :
				  do_action( 'woocommerce_no_products_found' );
		    endif;
	    ?>
      </div>
      <?php 
        $page = 1;
        if(isset($_GET['page'])) {
          if($_GET['page'] > 1) {
            $page = $_GET['page'];
          }
        }
      ?>
        <div class="row">
          <div class="col-md-12">
          <div class="load-more-wrapper">
            <button <?php if($found_posts <= $wp_query->query_vars['posts_per_page']) { ?>style="display: none; "<?php } ?> class="load-more-btn black-button" data-page="<?php echo $page; ?>">Load more</button>
          </div>
        </div>
        </div>
    </div>
  </section>
<?php 
$is_bottom_visible = false;
if($found_posts > $wp_query->query_vars['posts_per_page']) { 
  $is_bottom_visible = true;
}
?>
<?php if ( have_posts() ) : ?>
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
<?php endif; ?>
  <div class="mobile-filter_bg"></div>
<?php 
	get_footer();
?>