<?php get_header(); ?>
<section class="empty-search_section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="empty-search_wrap">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Small.svg" alt="" class="nosearch-icon">
          <h1>Something’s Missing</h1>
          <p>The page you’re looking for doesn’t exist.</p>
          <a href="<?php echo site_url(); ?>" class="blank-button">Return Home</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>