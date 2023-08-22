<?php
/** Template Name: Countries & Grapes */
get_header();
global $post;
?>
<section class="breadcrumbs-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <ul class="breadcrumbs">
	        <li><a href="<?php echo get_permalink( $post->post_parent ); ?>">Explore</a></li>
	        <li class="separator">></li>
	        <li><span><?php the_title(); ?></span></li>
	      </ul>
	      <a href="#" class="back-button">Go Back</a>
	    </div>
	  </div>
	</div>
</section>
<section class="single-explore_section single-explore_section--countries">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <div class="single-explore_wrap">
	        <div class="single-explore_titles">
	          <h1><?php the_title(); ?></h1>
	          <?php the_content(); ?>
	        </div>
	        <?php 
	        	$image = get_field('cg_image');
	        ?>
	        <div class="explore-country_image">
	           <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="cover-image">
	        </div>
	        <div class="explore-navigation_holder">
		        <?php 
		        	$args = array(
		        		'post_type' => 'country',
		        		'posts_per_page' => -1
		        	);
		        	$query = new WP_Query($args);
		        ?>
				<div class="explore-navigation explore-navigation_1">
					<h6>Countries</h6>
					<div class="column">
					  <ul>
					  	<?php while($query->have_posts()) { 
					  		$query->the_post();
				  		?>
						    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php } ?>
					  </ul>
					</div>
				</div>
	          	<?php
		        	$grapes_count = wp_count_posts('grape');
		        	$columns = round($grapes_count->publish/9);
	        	?>
				<div class="explore-navigation explore-navigation_2">
					<h6>Grapes</h6>
					<div class="explore-navigation_columns">
						<?php for($i = 1; $i <= $columns; $i++) {
				        	$args = array(
				        		'post_type' => 'grape',
				        		'posts_per_page' => 9,
				        		'paged' => $i
				        	);
				        	$query = new WP_Query($args);
		        		?>
						  <div class="column">
						    <ul>
							  	<?php while($query->have_posts()) { 
							  		$query->the_post();
						  		?>
						    		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
								<?php } ?>
						    </ul>
						  </div>
						<?php } ?>
					</div>
				</div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
</section>
<?php
get_footer();