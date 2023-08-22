<?php
   /**
   * The main template file
   *
   * This is the most generic template file in a WordPress theme
   * and one of the two required files for a theme (the other being style.css).
   * It is used to display a page when nothing more specific matches a query.
   * E.g., it puts together the home page when no home.php file exists.
   *
   * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
   *
   * @package hansa
   */
   
   get_header();
   $slides = get_field('home_slider_slides');
   if($slides) {
   ?>
<section class="home-banner">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="home-banner_owl owl-carousel" id="home-banner_owl">
               <?php foreach($slides as $slide) { ?>
               <div class="slider-item">
                  <a href="<?php echo $slide['url']; ?>">
                     <img src="<?php echo $slide['image']['url']; ?>" alt="<?php echo $slide['image']['alt']; ?>" class="cover-image">
                     <div class="home-banner_titles">
                        <h1><?php echo $slide['heading']; ?></h1>
                        <p><?php echo $slide['text']; ?></p>
                     </div>
                  </a>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</section>
<?php } ?>
<section class="category-section">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="section-title"><?php the_field('home_cat_title'); ?></h4>
         </div>
      </div>
      <div class="row category-sliders_holder">
         <?php 
            $big_cat = get_field('home_left_cat');
            $big_cat_id = $big_cat->term_id;
            $big_cat_thumbnail_id = get_term_meta( $big_cat_id, 'thumbnail_id', true );
            $big_cat_image = wp_get_attachment_image_src( $big_cat_thumbnail_id, 'large' );
            $big_cat_gallery = get_field('product_cat_gallery','term_' . $big_cat_id);
            ?>
         <div class="col-md-6 full-slider_col">
            <div class="category-slider_full category-slider">
               <div class="owl-carousel" id="category-slider_1">
                  <div class="slider-item">
                     <a href="<?php echo get_term_link( $big_cat_id, 'product_cat' ); ?>" class="slider-thumb">
                     <img src="<?php echo $big_cat_image[0]; ?>" alt="image" class="contain-image">
                     </a>
                  </div>
                  <?php if($big_cat_gallery) { 
                     foreach($big_cat_gallery as $cat_image) {
                     ?>
                  <div class="slider-item">
                     <a href="#" class="slider-thumb">
                     <img src="<?php echo $cat_image['url']; ?>" alt="<?php echo $cat_image['alt']; ?>" class="contain-image">
                     </a>
                  </div>
                  <?php }} ?>
               </div>
               <div class="slider-titles">
                  <h4>
                     <a href="<?php echo get_term_link( $big_cat_id, 'product_cat' ); ?>"><?php echo $big_cat->name; ?></a>
                  </h4>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <?php 
               $right_cats = get_field('home_right_cats');
               $i = 1;
               foreach($right_cats as $rc) {
               $rc_id = $rc->term_id;
               $rc_thumbnail_id = get_term_meta( $rc_id, 'thumbnail_id', true );
               $rc_image = wp_get_attachment_image_src( $rc_thumbnail_id, 'large' );
               $rc_image_alt = get_post_meta($rc_thumbnail_id, '_wp_attachment_image_alt', TRUE);
               $rc_gallery = get_field('product_cat_gallery','term_' . $rc_id);
               	echo $i%2 != 0 ? '<div class="category-sliders_row">' : ''; 
               ?>
            <div class="category-slider_half category-slider">
               <div class="owl-carousel" id="category-slider_<?php echo $i+1; ?>">
                  <div class="slider-item">
                     <a href="<?php echo get_term_link( $rc_id, 'product_cat' ); ?>" class="slider-thumb">
                     <img src="<?php echo $rc_image[0]; ?>" alt="<?php echo $rc_image_alt; ?>">
                     </a>
                  </div>
                  <?php if($rc_gallery) { 
                     foreach($rc_gallery as $rc_gal_image) {
                     ?>
                  <div class="slider-item">
                     <a href="<?php echo get_term_link( $rc_id, 'product_cat' ); ?>" class="slider-thumb">
                     <img src="<?php echo $rc_gal_image['url']; ?>" alt="<?php echo $rc_gal_image['alt']; ?>">
                     </a>
                  </div>
                  <?php }} ?>
               </div>
               <div class="slider-titles">
                  <h6>
                     <a href="<?php echo get_term_link( $rc_id, 'product_cat' ); ?>"><?php echo $rc->name; ?></a>
                  </h6>
               </div>
            </div>
            <?php 
               echo (($i%2 == 0) || ($i == count($right_cats))) ? '</div>' : '';
               $i++;
               }  
               ?>
         </div>
         <!-- col-md-6 -->
      </div>
      <!-- category-sliders_holder -->
      <div class="row static-category_row">
         <?php 
            $bottom_cats = get_field('home_bottom_cats');
            foreach($bottom_cats as $bc) {
            $bc_id = $bc->term_id;
            $bc_thumbnail_id = get_term_meta( $bc_id, 'thumbnail_id', true );
            $bc_image = wp_get_attachment_image_src( $bc_thumbnail_id, 'large' );
            $bc_image_alt = get_post_meta($bc_thumbnail_id, '_wp_attachment_image_alt', TRUE);
            $count = $bc->count;
            ?>
         <div class="col-md-6 col-xs-6">
            <div class="static-category">
               <a href="<?php echo get_term_link( $bc_id, 'product_cat' ); ?>" class="thumb-wrap">
               <img src="<?php echo $bc_image[0]; ?>" alt="<?php echo $bc_image_alt; ?>" class="contain-iamge">
               </a>
               <div class="category-titles">
                  <h6>
                     <a href="<?php echo get_term_link( $bc_id, 'product_cat' ); ?>"><?php echo $bc->name; ?></a>
                  </h6>
               </div>
            </div>
         </div>
         <?php } ?>
      </div>
      <?php
         $adv_list = get_field('adv_list');
         ?>
      <div class="row desktop-advantages_wrap">
         <?php foreach($adv_list as $adv_item) { ?>
         <div class="col-md-4 col-xs-4 category-advantages">
            <div class="thumb-wrap">
               <img src="<?php echo $adv_item['image']['url']; ?>" alt="<?php echo $adv_item['image']['alt']; ?>" class="contain-image">
            </div>
            <div class="description">
               <h6><?php echo $adv_item['title']; ?></h6>
               <p><?php echo $adv_item['description']; ?></p>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
   <div class="category-mobile_owl owl-carousel" id="category-mobile_owl">
      <?php foreach($adv_list as $adv_item) { ?>
      <div class="slider-item category-advantages">
         <div class="thumb-wrap">
            <img src="<?php echo $adv_item['image']['url']; ?>" alt="<?php echo $adv_item['image']['alt']; ?>" class="contain-image">
         </div>
         <div class="description">
            <h6><?php echo $adv_item['title']; ?></h6>
            <p><?php echo $adv_item['description']; ?></p>
         </div>
      </div>
      <?php } ?>
   </div>
</section>
<!-- category-section -->
<!-- Customize -->
<section class="customizse-banner">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="customise-banner_holder">
               <div class="banner-card">
                  <h4><?php the_field('home_custom_title', false, false); ?></h4>
                  <p><?php the_field('home_custom_text'); ?></p>
                  <a href="<?php the_field('home_custom_link'); ?>" class="blank-button"><?php the_field('home_custom_btn'); ?></a>
               </div>
               <div class="right">
                  <?php $custom_img = get_field('home_custom_image'); ?>
                  <img src="<?php echo $custom_img['url']; ?>" alt="<?php echo $custom_img['alt']; ?>" class="contain-image">
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- !Customize -->
<?php 
   $new_products_n = get_field('home_new_count'); 
   	$args = array(
   	'post_type' => 'product',
   	'posts_per_page' => $new_products_n,
   	'tax_query' => array(
          array(
              'taxonomy' => 'product_cat',
              'field' => 'slug',
              'terms' => array('empties','champagne-labels'),
              'operator' => 'NOT IN'
          )
      ),
      'meta_query' => array(
         array(
            'key' => 'is_gift',
            'compare' => 'NOT EXISTS',
            'value' => ''
         )
      )
   );
   $extra_args = array(
      'relation' => 'AND',
      array(
         'key' => 'is_gift',
         'compare' => 'NOT EXISTS',
         'value' => ''
      ),
      array(
         'key' => 'is_new',
         'compare' => '=',
         'value' => 'yes'
      )
   );
   $temp_args = $args;
   $temp_args['meta_query'] = $extra_args;
   $new_query = new WP_Query( $temp_args );
   if(!$new_query->have_posts()) {
      $args['orderby'] = 'date';
      $args['order'] = 'DESC';
      $new_query = new WP_Query( $args );
   }
   if($new_query->have_posts()) {
   ?>
<section class="arrival-section">
   <div class="container arrivals-titles">
      <div class="row">
         <div class="col-md-6 col-sm-9">
            <h4 class="section-title"><?php the_field('home_new_title'); ?></h4>
            <p><?php the_field('home_new_desc'); ?></p>
         </div>
      </div>
   </div>
   <div class="arrivals-owl owl-carousel" id="arrivals-owl">
      <?php while($new_query->have_posts()) { 
         $new_query->the_post();
         wc_get_template_part('content', 'product');
         } 
         ?>
   </div>
</section>
<?php } 
   wp_reset_postdata();
   ?>
<!-- Our journal -->
<?php
   $posts_n = get_field('home_journal_n');
   $args = array(
   	'post_type' => 'post',
   	'post_status' => 'publish',
   	'posts_per_page' => $posts_n,
   	'orderby' => 'date',
   	'order' => 'DESC'
   );
   $journal_q = new WP_Query($args);
   ?>
<section class="blog-section home-blog">
   <div class="container">
      <div class="row">
         <div class="col-md-12 blog-section_titles">
            <h4 class="section-title"><?php the_field('journal_title'); ?></h4>
            <a href="<?php echo site_url(); ?>/journal" class="link-more">Discover More</a>
         </div>
      </div>
      <div class="row blog-row_home">
         <?php while($journal_q->have_posts()) { 
            $journal_q->the_post();
            ?>
         <div class="col-md-4 col-sm-6">
            <a href="<?php the_permalink(); ?>" class="blog-thumb">
            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="cover-image">
            </a>
            <div class="blog-description">
               <div class="date"><?php echo get_the_date('d F, Y'); ?></div>
               <h4>
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               </h4>
               <p><?php the_excerpt(); ?></p>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
   <div class="blog-owl owl-carousel" id="blog-owl">
      <?php while($journal_q->have_posts()) { 
         $journal_q->the_post();
         ?>
      <div class="blog-item">
         <a href="<?php the_permalink(); ?>" class="blog-thumb">
         <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="cover-image">
         </a>
         <div class="blog-description">
            <div class="date"><?php echo get_the_date('d F, Y'); ?></div>
            <h4>
               <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
            <p><?php the_excerpt(); ?></p>
         </div>
      </div>
      <?php }
         wp_reset_postdata(); ?>
   </div>
</section>
<!-- !Our journal -->
<?php
get_footer();