<?php
get_header();
$posted_date = get_the_date('d F, Y');
$author_id = get_post_field( 'post_author' );
?>
<section class="breadcrumbs-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <ul class="breadcrumbs">
	        <li><a href="/events">Events</a></li>
	        <li class="separator">&gt;</li>
	        <li><span><?php the_title(); ?></span></li>
	      </ul>
	      <a href="#" class="back-button">Go Back</a>
	    </div>
	  </div>
	</div>
</section>


  <section class="single-explore_section single-country-holder single-explore_section--countries">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="single-explore_wrap country-explore_wrap">
            <div class="single-country_holder">
              <div class="country-content_wrap">
                <div class="single-explore_titles">
                  <h1><?php the_title(); ?></h1>
                  <p class="event-post_date">Posted on <?php echo $posted_date; ?></p>
                </div>

                <div class="contry-content event-content">
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

                <div class="author-social_wrap">
                  <div class="author_wrap">
                    <div class="author-image">
                    	<?php echo get_avatar( $author_id, '80'); ?> 
                      <img src="img/Hansa-Mark.png" alt="center" class="cover-image">
                    </div>
                    <div class="author-descr">
                      <h4>By <?php echo get_the_author_meta('nickname',$author_id); ?></h4>
                      <p>Posted on <?php echo $posted_date; ?></p>
                    </div>
                  </div>

                  <div class="share-post">
                    <div class="share-title">Share</div>
                    <div class="heateor_sss_sharing_container heateor_sss_horizontal_sharing" ss-offset="0" heateor-sss-data-href="https://businessnow.mt/apple-announces-safety-update-to-airtags-in-wake-of-stalking-allegations/">
                      <ul class="heateor_sss_sharing_ul">
                        <li class="heateorSssSharingRound">
                          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                          	<i alt="Facebook" title="Facebook" class="heateorSssSharing heateorSssFacebookBackground">
                            <ss  class="heateorSssSharingSvg heateorSssFacebookSvg"></ss>
                          </i>
                          </a>
                        </li>
                      </ul>
                      <div class="heateorSssClear"></div>
                    </div>
                    <a href="#" class="copy-link" data-clipboard-text="<?php the_permalink(); ?>"></a>
                  </div>
                </div>
              </div><!-- /country-content_wrap -->

              <div class="country-content_navigation">
                <div class="country-nav_wrap">
                  <h4>Enquire for event</h4>
                  <?php echo do_shortcode('[contact-form-7 id="5" title="Event Enquire" html_class="contact-form"]'); ?>
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