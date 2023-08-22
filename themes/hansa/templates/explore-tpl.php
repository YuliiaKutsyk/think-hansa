<?php
/** Template Name: Explore */
get_header();
?>
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
	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'post_parent'    => get_the_ID(),
		'order'          => 'DESC'
	);
	$query = new WP_Query($args);
?>
<section class="explore-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	    <?php while($query->have_posts()) { 
	    	$query->the_post();
    	?>
			<div class="explore-row">
				<div class="explore-image">
				  <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="cover-image">
				</div>
				<div class="explore-description">
				  <h4><?php the_title(); ?></h4>
				  <div class="explore-text"><?php the_content(); ?></div>
				  <a href="<?php the_permalink(); ?>" class="blank-button">Learn More</a>
				</div>
			</div>
      	<?php } ?>
	    </div>
	  </div>
	</div>
</section>
<?php
get_footer();