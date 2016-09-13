<?php
/**
 * Homepage Shop Panel
 */

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
global $woocommerce;

$settings = array(
  'thumb_w' => 100,
  'thumb_h' => 100,
  'thumb_align' => 'alignleft',
  'shop_area' => 'false',
  'shop_area_entries' => 3,
  'shop_area_title' => '',
  'shop_area_message' => '',
  'shop_area_link_text' => 'View all our products',
);

$settings = woo_get_dynamic_values( $settings ); ?>
<section id="shop-home" class="home-section fix">
  <header class="block">
    <h1><?php echo stripslashes( $settings['shop_area_title'] ); ?></h1>
    <p><?php echo stripslashes( $settings['shop_area_message'] ); ?></p>
    <a class="more" href="<?php echo get_post_type_archive_link( 'product' ); ?>" title="<?php _e( 'View all our products', 'woothemes' ); ?>"><?php echo $settings['shop_area_link_text']; ?></a>
  </header>

  <ul class="recent products"><?php
    $number_of_products = $settings['shop_area_entries'];
    $args = array( 'post_type' => 'product', 'posts_per_page' => $number_of_products, 'meta_query' => array( array( 'key' => '_visibility', 'value' => array( 'catalog', 'visible' ), 'compare' => 'IN' ) ) );
    $first_or_last = 'first';
    $loop = new WP_Query( $args );
    $count = 0;
    global $post;
    while ( $loop->have_posts() ) {
      $loop->the_post();
      $count++;

      if ( function_exists( 'get_product' ) ) {
        $_product = get_product( $loop->post->ID );
      } else {
        $_product = new WC_Product( $loop->post->ID );
      } ?>

      <li class="product <?php if ( $count % 3 == 0 ) { echo 'last'; } ?>">
        <a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr( $loop->post->post_title ? $loop->post->post_title : $loop->post->ID ); ?>">
          <div class="img-wrap"><?php
            woocommerce_show_product_sale_flash( $post, $_product );
            if ( has_post_thumbnail( $loop->post->ID ) ) {
              echo get_the_post_thumbnail( $loop->post->ID, 'shop_thumbnail' );
            } else {
              echo '<img src="' . $woocommerce->plugin_url() . '/assets/images/placeholder.png" alt="Placeholder" width="' . $woocommerce->get_image_size( 'shop_catalog_image_width' ) . 'px" height="' . $woocommerce->get_image_size( 'shop_catalog_image_height' ) . 'px" />';
            } ?>
          </div>
        </a>

        <h3><a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr( $loop->post->post_title ? $loop->post->post_title : $loop->post->ID ); ?>"><?php the_title(); ?></a></h3>
        <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
        <span class="price"><?php echo $_product->get_price_html(); ?></span>
        <?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>
        <div class="fix"></div>
      </li>

      <?php if ( $count % 3 == 0 ) { echo '<li class="fix clear"></li>'; } ?>
    <?php } ?>
  </ul><!--/ul.recent-->
</section>

<?php wp_reset_query(); ?>
