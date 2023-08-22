<?php
/** Template Name: Explore Single*/
get_header();
?>
<section class="breadcrumbs-section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <ul class="breadcrumbs">
	        <li><a href="/explore">Explore</a></li>
	        <li class="separator">></li>
	        <li><span><?php the_title(); ?></span></li>
	      </ul>
	      <a href="#" class="back-button">Go Back</a>
	    </div>
	  </div>
	</div>
</section>

<section class="single-explore_section">
	<div class="container">
	  <div class="row">
	    <div class="col-md-12">
	      <div class="single-explore_wrap">
	        <div class="single-explore_titles">
	          <h1><?php the_title(); ?></h1>
	          <?php the_content(); ?>
	        </div>
	        <?php 
	        	$items = get_field('page_items');
	        	if($items){
	        		foreach($items as $item){
	        			$image = $item['image'];
	        ?>
		        <div class="single-explore_item">
		          <div class="explore-item_thumb">
		            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="cover-image">
		          </div>
		          <div class="explore-item_description">
		            <h4><?php echo $item['title']; ?></h4>
		            <?php echo $item['text']; ?>
		          </div>
		        </div>
		    <?php }} ?>
	      </div>
	    </div>
	  </div>
	</div>
</section>
<?php
get_footer();