<?php get_header(); ?>

<h1>Tous nos évènements</h1>

<div class="mt-3 mb-3">
    <ul class="list-group list-group-horizontal text-center">
        <?php
        $terms = get_terms(['taxonomy' => 'style']);
        foreach ($terms as $term) {
            $active = get_query_var('style') === $term->slug ? active : '';
            echo '<a class="list-group-item list-group-item-action ' . $active . '" 
            href="' . get_term_link($term) . '">' . $term->name . '</a>';
        }; ?>
    </ul>
</div>

<?php if (have_posts()) : ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php while (have_posts()) : the_post();
            get_template_part('partials/card', 'event');
        endwhile; ?>

        <?= wphetic_paginate_links(); ?>
    </div>
<?php else : ?>
    <h2>Pas de posts</h2>
<?php endif; ?>

<?php get_footer(); ?>

