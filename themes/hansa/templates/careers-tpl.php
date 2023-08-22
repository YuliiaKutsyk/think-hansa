<?php
/** Template Name: Careers */
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
<section class="careers-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="careers-wrap">
            <div class="careers-description">
              <h4 class="column-title"><?php the_field('ls_title'); ?></h4>
              <p><?php the_field('ls_text'); ?></p>
              <?php 
              	$image = get_field('ls_image');
              ?>
              <img src="<?php echo $image['url'];?>" alt="<?php echo $image['alt'];?>" class="contain-image">
            </div>

            <div class="careers-form">
              <h4 class="column-title"><?php the_field('rs_title');?></h4>
              <?php echo do_shortcode('[contact-form-7 id="325180" title="Application Form" html_class="contact-form"]'); ?>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
get_footer();