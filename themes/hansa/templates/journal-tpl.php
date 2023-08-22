<?php
/** Template Name: Journal */
get_header();
global $wp_query;
$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
?>
<div class="about-page_section">

    <section class="inner-title_section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="inner-title"><?php the_title(); ?></h1>
          </div>
        </div>
      </div>
    </section>
    <?php 
    	$posts_per_page = get_field('posts_per_page');
    	$posts_per_page = $posts_per_page > 0 ? $posts_per_page : 6;
    	$args = array(
    		'post_type' => 'post',
    		'post_status' => 'publish',
    		'posts_per_page' => $posts_per_page,
    		'paged' => $page
    	);
    	$query = new WP_Query($args);
    	$temp_q = $wp_query;
    	$wp_query = $query;
    ?>
    <section class="journal-section">
      <div class="container">
        <div class="row">
        <?php while($query->have_posts()) { 
        	$query->the_post();
    	?>
          <div class="col-md-4 col-sm-6 journal-item">
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
    </section>
    <?php if($wp_query->max_num_pages > 1) { ?>
	    <div class="pagination">
		    <?php 
		    	echo paginate_links(array(
		    		'prev_text' => '',
		    		'next_text' => ''
		    	));
		    	$wp_query = $temp_q;
		    ?>
	    </div>
	<?php } ?>
  </div><!-- /about-page_section -->
<?php
get_footer();