<?php
/** Template Name: Contact Us */
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
<section class="contact-banner">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	    	<?php the_field('contact_top'); ?>
	    </div>
	  </div>
	</div>
</section>
<section class="careers-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <div class="careers-wrap">
	        <div class="careers-description">
	          <h4 class="column-title">Hansa Information</h4>
	          <?php the_field('contact_info'); ?>
	          <div class="map" id="#map">
	            <?php the_field('google_maps_iframe'); ?>
	          </div>
	        </div>

	        <div class="careers-form">
	          <h4 class="column-title">Let’s Talk</h4>
	          <?php echo do_shortcode('[contact-form-7 id="325172" title="Contact Us" html_class="contact-form"]'); ?>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
</section>
<?php
get_footer();