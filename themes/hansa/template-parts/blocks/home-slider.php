<?php 
  $slides = get_field('slides');
  if($slides) {
?>
<section class="home-banner">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="home-banner_owl owl-carousel" id="home-banner_owl">
          <?php foreach($slides as $slide) { ?>
            <div class="slider-item">
              <img src="<?php echo $slide['image']['url']; ?>" alt="<?php echo $slide['image']['alt']; ?>" class="cover-image">
              <div class="home-banner_titles">
                <h1><?php echo $slide['heading']; ?></h1>
                <p><?php echo $slide['text']; ?></p>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php } ?>

<script>
  (function($) {
  
  setTimeout(function(){
    $("#home-banner_owl").owlCarousel({
      items: 1,
      nav: true,
      margin: 5,
    });
  },1000);
})( jQuery );
</script>