<?php
get_header();

$prod_cat = get_queried_object_id();
?>

    <section class="deals">
        <div class="max__width">

            <?php
                $cat_deals_args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'deal_expiration_date',
                            'value' => array(0, current_time('timestamp')),
                            'compare' => 'NOT BETWEEN'
                        ),
                        array(
                            'key' => '_visibility',
                            'compare' => 'NOT EXISTS'
                        )
                    ),
                    'post__not_in' => $posts_not_in,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $prod_cat,
                            'operator' => 'IN'
                        )
                    ),
                    'cache_results' => false,
                    'no_found_rows' => true
                );

                if(isset($_GET['min_price']) && !empty($_GET['min_price']) && is_numeric($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['max_price']) && is_numeric($_GET['max_price'])) {
                    $cat_deals_args['meta_query'][] = array(
                        'key' => '_price',
                        'value' => array($_GET['min_price'], $_GET['max_price']),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    );
                    $cat_deals_args['meta_key'] = '_price';
                    $cat_deals_args['orderby'] = 'meta_value_num';
                    $cat_deals_args['order'] = 'ASC';
                }

                $cat_deals_query = new WP_Query($cat_deals_args);

            ?>

            <div class="deals__wrapper do__flex deals__cat deals__all">

                <?php
                    while($cat_deals_query->have_posts() ) : $cat_deals_query->the_post();

                    $_product = wc_get_product(get_the_ID());

                    $merchant_obj = get_field('deal_merchant');
                    $merchant_id = $merchant_obj->ID;

                    $attachment_id = get_post_thumbnail_id();
                    $prod_image = vt_resize( $attachment_id,'' , 700, 585, true);

                    $product_cats = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'all'));
                    $product_cat = $product_cats[0];
                    $product_cat_link = get_term_link( $product_cat, 'product_cat');
                ?>

                    <article class="card third">

                        <div class="card__inner">

                            <div class="deal__images">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-src="<?php echo $prod_image['url']; ?>" class="deal__image blazy"></a>
                            </div><!-- featured__deal images -->

                            <div class="deal__details">
                                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                <h4><i class="fal fa-map-marker-alt"></i><?php echo get_the_title($merchant_id); ?></h4>

                                <div class="deal__meta">
                                    <div class="deal__category">
                                        <a href="<?php echo $product_cat_link; ?>" title="View more on <?php echo $product_cat->name; ?>"><?php echo $product_cat->name; ?></a>
                                    </div><!-- deal__category -->

                                    <div class="deal__price">
                                        <div class="the__price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div><!-- the__price -->
                                    </div><!-- deal__price -->
                                </div><!-- deal__meta -->
                            </div><!-- deal__details -->

                        </div><!-- card__inner -->
                    </article><!-- card third -->

                <?php endwhile; wp_reset_postdata(); ?>

            </div><!-- deals__wrapper -->

        </div><!-- max__width -->
    </section><!-- deals -->

<?php get_footer(); ?>
