<?php 
get_header();
?>
<section class="breadcrumbs-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <ul class="breadcrumbs">
	        <li><a href="/explore">Explore</a></li>
	        <li class="separator">></li>
	        <li><a href="/countries-grapes">Countries & Grapes</a></li>
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
			  <div class="single-explore_wrap country-explore_wrap">
			    <div class="single-country_holder">
			      <div class="country-content_wrap">
			        <div class="single-explore_titles">
			          <h1><?php the_title(); ?></h1>
			        </div>

			        <div class="contry-content">
			        <?php if(has_post_thumbnail()) { ?>
			          <div class="wp-block-image">
			            <figure>
			              <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="cover-image">
			              <figcaption><?php echo get_the_post_thumbnail_caption(); ?></figcaption>
			            </figure>
			          </div>
		          	<?php } ?>
			         <?php the_content(); ?>
			        </div>
			      </div><!-- /country-content_wrap -->

			      <div class="country-content_navigation">
			      	<div class="country-nav_wrap">
				        <h4>Explore More</h4>
				        <?php 
				        	wp_nav_menu(array(
				        		'menu' => 'Country Page Menu',
				        		'menu_class' => 'country-side_navigation',
				        		'container' => '',
				        		'add_link_class' => 'country-sidenav_item'
				        	)); 
				        ?>
			        </div>
			      </div><!-- /country-content_navigation -->
			    </div>
			  </div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();