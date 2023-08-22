<?php
/** Template Name: Delivery & Returns */
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
            <?php } 
                $sch_table_1 = get_field('dr_table');
                $schedule_headers = $sch_table_1['header'];
                $schedule_body = $sch_table_1['body'];
                $sch_table_2 = get_field('dr_table_2');
                $schedule_headers_2 = $sch_table_2['header'];
                $schedule_body_2 = $sch_table_2['body'];
            ?>

              <div class="accordeon-item">
                <h6 class="accordeon-title">Delivery Schedule By Area</h6>
                <div class="accordeon-description">
                  <table>
                    <tr>
                        <?php foreach($schedule_headers as $sh) { ?>
                            <th><?php echo $sh['c']; ?></th>
                        <?php } ?>
                    </tr>
                    <?php foreach($schedule_body as $sb) { ?>
                      <tr>
                        <?php foreach($sb as $sb_col) { ?>
                          <td><?php print_r($sb_col['c']); ?></td>
                        <?php } ?>
                      </tr>
                    <?php } ?>
                  </table>
                  <table>
                    <tr>
                        <?php foreach($schedule_headers_2 as $sh) { ?>
                            <th><?php echo $sh['c']; ?></th>
                        <?php } ?>
                    </tr>
                    <?php foreach($schedule_body_2 as $sb) { ?>
                      <tr>
                        <?php foreach($sb as $sb_col) { ?>
                          <td><?php print_r($sb_col['c']); ?></td>
                        <?php } ?>
                      </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div><!-- /about-page_section -->
<?php
get_footer();