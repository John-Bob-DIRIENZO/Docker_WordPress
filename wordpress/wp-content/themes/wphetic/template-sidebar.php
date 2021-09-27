<?php
/**
 * Template Name: Avec Sidebar
 * Template Post Type: page, post
 * Description: Un modèle avec sidebar
 */
get_header(); ?>

<div class="row">

    <?php while (have_posts()) :
        the_post(); ?>

        <div class="col-md-8 blog-main">
            <h2 class="pb-3 mb-4 font-italic border-bottom blog-post-title">
                <?php the_title(); ?>
            </h2>

            <div class="blog-post">
                <p class="blog-post-meta"><?php the_date(); ?> par <a
                            href="<?= get_author_posts_url(get_post_field('post_author', get_the_ID())); ?>"><?php the_author(); ?></a>
                </p>

                <p><?php the_content(); ?></p>
            </div>

        </div>

    <?php endwhile; ?>

    <aside class="col-md-4 blog-sidebar">
        <?php get_sidebar('wphetic_sidebar'); ?>
    </aside>

</div>


<?php get_footer(); ?>
