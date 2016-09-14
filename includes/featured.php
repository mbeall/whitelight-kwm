<?php
/**
 * Homepage Slider
 */
global $wp_query, $post, $panel_error_message, $woo_options;

$settings = array(
  'featured_type' => 'Full',
  'featured_entries' => 3,
  'featured_height' => 380,
  'featured_tags' => '',
  'slider_video_title' => 'true',
  'slider_video_description' => 'true',
  'featured_order' => 'DESC',
  'featured_sliding_direction' => 'vertical',
  'featured_effect' => 'slide',
  'featured_speed' => '7',
  'featured_hover' => 'false',
  'featured_touchswipe' => 'true',
  'featured_animation_speed' => '0.6',
  'featured_pagination' => 'false',
  'featured_nextprev' => 'true',
  'featured_opacity' => '0.5',
  'featured_slide_group' => '0',
);

$settings = woo_get_dynamic_values( $settings );
$count = 0;
$featposts = $settings['featured_entries']; // Number of featured entries to be shown
$orderby = 'date';

if ( $settings['featured_order'] == 'rand' ) {
  $orderby = 'rand';
}

$query_args = array(
  'post_type' => 'slide',
  'numberposts' => $featposts,
  'order' => $settings['featured_order'],
  'orderby' => $orderby,
  'suppress_filters' => 0,
);

if ( 0 < intval( $settings['featured_slide_group'] ) ) {
  $query_args['tax_query'] = array(
    array( 'taxonomy' => 'slide-page', 'field' => 'id', 'terms' => intval( $settings['featured_slide_group'] ) ),
  );
}

$slides = get_posts( $query_args ); if ( count( $slides ) > 0 ) {
  $slidertype = $settings['featured_type'];
  if ( $slidertype != 'full' ) { ?>
  <div class="featured-wrap carousel faded"><?php
    } ?>
    <div class="controls-container <?php if ( $slidertype != 'full' ) { echo 'col-full'; } ?>">
      <section id="featured" <?php if ( $woo_options['woo_featured_effect'] == 'slide' ) { echo 'class="slide"'; } ?>>
        <ul class="slides fix"><?php
          foreach ( $slides as $post ) {
            setup_postdata( $post );
            $count++; ?>
            <li id="slide-<?php echo $count; ?>" class="slide slide-id-<?php the_ID(); ?>" <?php if ( $slidertype != 'full' ) { echo 'style="opacity: ' . $settings['featured_opacity'] . ';"'; } ?>>
              <?php $url = get_post_meta( $post->ID, 'url', true );
              if ( $slidertype == 'full' ) {
                $has_embed = woo_embed( 'width=800&height=400&class=slide-video' );
              } else {
                $has_embed = woo_embed( 'width=960&height=' . $settings['featured_height'] . '&class=slide-video-carousel' );
              }

              if ( $has_embed ) {
                echo $has_embed;
              } else {
                if ( isset( $url ) && $url != '' ) { ?>
                  <a href="<?php echo $url ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php
                }

                if ( $slidertype != 'full' ) {
                  woo_image( 'width=960&height=' . $settings['featured_height'] . '&class=slide-image&link=img&noheight=true' );
                } else {
                  woo_image( 'width=2560&height=' . $settings['featured_height'] . '&class=slide-image full&link=img&noheight=true' );
                }

                if ( isset( $url ) && $url != '' ) { ?>
                  </a><?php
                }
              }

              /**
               * if is a video post - hide title and description
               * else show the container
               * if hide title, add class and dont show title
               * if hide description, add class and dont show description
               */
              if ( ! $has_embed ) {
                if ( $settings['slider_video_title'] == 'true' || $settings['slider_video_description'] == 'true' ) { ?>
                  <div class="slide-content-container">
                    <article class="slide-content col-full <?php if ( ! $has_embed ) { echo 'not-video'; } ?>">
                      <header <?php if ( $settings['slider_video_title'] != 'true' || $settings['slider_video_description'] != 'true' ) { echo 'class="no-meta"'; } ?>><?php
                        if ( $settings['slider_video_title'] == 'true' ) { ?>
                          <h1><?php
                            if ( isset( $url ) && $url != '' ) { ?>
                              <a href="<?php echo $url ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php
                            }

                            $slide_title = get_the_title();
                            echo woo_text_trim( $slide_title, 25 );

                            if ( isset( $url ) && $url != '' ) {
                              ?></a><?php
                            } ?>
                          </h1><?php
                        }

                        if ( $settings['slider_video_description'] == 'true' ) { ?>
                          <div class="entry"><?php
                            $slide_excerpt = get_the_excerpt();
                            echo woo_text_trim( $slide_excerpt, 16 ); ?>
                          </div><?php
                        } ?>
                      </header>
                    </article>
                  </div><?php
                }
              } ?>
            </li><!--/.slide--><?php
          } ?>
        </ul><!-- /.slides --><?php if ( $slidertype == 'full' ) {
        ?><div class="col-full controls-inner"></div><?php
        } ?>
      </section><!-- /#featured -->
    </div><?php if ( $slidertype != 'full' ) {
    ?>
  </div><?php
  }

  // Slider Settings
  /**
   * $slideDirection = $settings['featured_sliding_direction'];
   */
  $animation = $settings['featured_effect'];

  if ( $settings['featured_speed'] == 'Off' ) {
    $slideshow = 'false';
  } else {
    $slideshow = 'true';
  }

  $pause_on_hover = $settings['featured_hover'];
  $touch_swipe = $settings['featured_touchswipe'];
  $slideshow_speed = $settings['featured_speed'] * 1000; // milliseconds
  $animation_duration = $settings['featured_animation_speed'] * 1000; // milliseconds
  $pagination = $settings['featured_pagination'];
  $nextprev = $settings['featured_nextprev']; ?>

  <script type="text/javascript">
  jQuery(window).load(function() {
  jQuery('#featured').flexslider({
  animation: "<?php
    if ( $slidertype != 'full' ) {
      echo 'slide';
    } else {
      echo $animation;
    } ?>",
  controlsContainer: <?php
    if ( $slidertype != 'full' ) {
      echo '".controls-container"';
    } else {
      echo '".controls-container .controls-inner"';
    } ?>,
  slideshow: <?php echo $slideshow; ?>,
  directionNav: <?php echo $nextprev; ?>,
  controlNav: <?php echo $pagination; ?>,
  pauseOnHover: <?php echo $pause_on_hover; ?>,
  slideshowSpeed: <?php echo $slideshow_speed; ?>,
  animationDuration: <?php
    echo $animation_duration;
    if ( $slidertype != 'full' ) {
      echo ',';
    }
    if ( $slidertype != 'full' ) { ?>
      start: function(slider) {
        jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').addClass('current-slide');
      },
      before: function(slider) {
        jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').removeClass('current-slide');
      },
      after: function(slider) {
        jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').addClass('current-slide');
      }<?php
    } ?>
  });
  jQuery('#slides').addClass('loaded');
  });
  </script><?php
} else { ?>
  <div class="col-full">
    <?php echo do_shortcode( '[box type="info"]Please add some slides in the WordPress admin backend to show in the Featured Slider.[/box]' ); ?>
  </div><?php
} ?>
