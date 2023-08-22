<?php 
/** Template Name: Text page */
get_header();
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

	<section class="stock-page_section">
	  <div class="container">
	    <div class="row">
	      <div class="col-md-12">
	        <div class="text-center">
	        	<?php the_content(); ?>
	          <a href="/contact" class="blank-button">Contact Us</a>
	        </div>
	      </div>
	    </div>
	  </div>
	</section>
</div>
<?php
get_footer();