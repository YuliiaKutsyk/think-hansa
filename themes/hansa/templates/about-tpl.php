<?php
/** Template Name: About Us */
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
    <?php 
      $about_blocks = get_field('about_blocks');
      if($about_blocks) {
    ?>
      <section class="about-section">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <?php foreach($about_blocks as $block) { ?>
                <div class="about-section_row">
                  <?php 
                    $image = $block['image'];
                  ?>
                  <div class="about-image_holder">
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="cover-image">
                  </div>
                  <div class="about-description_holder">
                    <h4><?php echo $block['title']; ?></h4>
                    <?php echo $block['text']; ?>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

    <?php 
      $about_people = get_field('about_team');
      if($about_people) {
    ?>
      <section class="about-people">
        <div class="container">
          <div class="row">
            <?php foreach($about_people as $member) { ?>
              <div class="col-md-6 col-sm-12">
                <?php 
                  $photo = $member['photo'];
                ?>
                <div class="about-people_image">
                  <img src="<?php echo $photo['url']; ?>" alt="<?php echo $photo['alt']; ?>" class="cover-image">
                </div>
                <div class="about-people_description">
                  <h4><?php echo $member['name']; ?></h4>
                  <p class="position"><?php echo $member['position']; ?></p>
                  <div class="about-people_text"><?php echo $member['text']; ?></div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </section>
    <?php } ?>
  </div><!-- /about-page_section -->
<?php
get_footer();