<?php
/** Template Name: FAQ */
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

    <section class="accordeon-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <?php $tabs = get_field('dr_tabs'); ?>
            <div class="accordeon-block">
            <?php foreach($tabs as $tab) { ?>
                <div class="accordeon-item">
                    <h6 class="accordeon-title"><?php echo $tab['title']; ?></h6>
                    <div class="accordeon-description"><?php echo $tab['text']; ?></div>
                </div>
            <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div><!-- /about-page_section -->
<?php
get_footer();