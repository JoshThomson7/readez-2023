<?php
/**
 * Blog Single - Header
 */

global $post;

$blog = new FL1_Blog($post->ID);
$blog_cat = $blog->main_category('id=>name');
$excerpt = $blog->excerpt(1000);
?>

<div class="blog__single-wrapper">
    <div class="blog__single max__width">

        <div class="blog__info">
            <h5><a href="<?php echo esc_url(get_permalink(FL1_BLOG_PAGE_ID)); ?>">&lsaquo; News</a> / <?php echo $blog_cat; ?></h5>
            <h1><?php echo get_the_title($post->ID); ?></h1>
            <date><?php echo $blog->date('M jS Y'); ?></date>

            <?php if($excerpt): ?>
                <p class="blog__excerpt"><?php echo $excerpt; ?></p>
            <?php endif; ?>
        </div>
    </div><!-- blog__single -->
</div><!-- max__width -->