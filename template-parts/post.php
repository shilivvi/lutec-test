<?php
    the_post();
    $excerpt = get_the_excerpt();
?>

<article class="main__post post post-<?php the_ID(); ?>">
    <div class="post__img">
        <?php the_post_thumbnail(); ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post__link"></a>
    </div>
    <div class="post__content">
        <h2 class="post__title">
            <a href="<?php the_permalink(); ?>">
                <?php esc_html_e(get_the_title()); ?>
            </a>
        </h2>
        <p class="post__text">
            <?php if(empty($excerpt)): ?>
                <?php esc_html_e('', 'lutec'); ?>
            <?php else: ?>
                <?php echo wpautop($excerpt) ?>
            <?php endif ?>
        </p>
        <div class="post__meta">
            <div class="post__author">
                <span><?php esc_html_e('Автор: ', 'lutec'); ?></span>
                <?php echo get_the_author_link(); ?>
            </div>
            <?php do_shortcode('[likes id="' . get_the_ID() . '" class="post__likes"]'); ?>
        </div>
    </div>
</article>
