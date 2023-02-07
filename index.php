<?php get_header(); ?>

    <main class="main">
        <div class="container container__main container__main--sidebar">
            <article class="main__content">
                <h1 class="main__title">Статьи</h1>

                <?php if (have_posts()): ?>

                    <?php while (have_posts()): ?>
                        <?php get_template_part( 'template-parts/post' ); ?>
                    <?php endwhile; ?>

                <?php endif; ?>

                <?php the_posts_pagination([
                    'show_all' => false,
                    'end_size' => 3,
                    'mid_size' => 1,
                    'class' => 'pagination'
                ]); ?>
            </article>
            <aside class="main__sibebar sidebar">
                <div class="sidebar__container">Sidebar</div>
            </aside>
        </div>
    </main>

<?php
get_footer();
