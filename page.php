<?php
/**
 * Default template
 */

get_header();

global $post;
?>

<?php if(is_account_page() || is_cart() || is_checkout()): ?>
    <div class="wc__wrapper">
        <div class="max__width">
            <?php while(have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div><!-- wc__wrapper -->
<?php else: ?>
    <?php FC_Helpers::flexible_content(); ?>
<?php endif; ?>

<?php get_footer(); ?>
