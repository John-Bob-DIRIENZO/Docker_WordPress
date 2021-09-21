<?php get_header(); ?>

<h1>Hello World !</h1>

<?php if (have_posts()) : ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php while (have_posts()) : the_post();
            get_template_part('partials/card', 'post');
        endwhile; ?>

        <?= wphetic_paginate_links(); ?>
    </div>
<?php else : ?>
    <h2>Pas de posts</h2>
<?php endif; ?>

<?php get_footer(); ?>

