<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?php the_post_thumbnail_url(); ?>" class="img-fluid rounded-start" alt="Event image">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php the_title(); ?></h5>
                    <p class="card-text"><?php the_content(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-secondary">Acheter :
                        <?= get_post_meta(get_the_ID(), 'event_prix', true); ?>€</a>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>
<?php

/*
$terms_slug = [];
$terms = get_the_terms(get_the_ID(), 'style');
foreach ($terms as $term) {
    $terms_slug[] = $term->name;
}
*/
// Les deux fonctionnent
$terms_slug = array_map(function ($term) {
    return $term->name;
}, get_the_terms(get_the_ID(), 'style'));

$query = new WP_Query([
    'post_type' => 'event',
    'post__not_in' => [get_the_ID()],
    'tax_query' => array(
        array(
            'taxonomy' => 'style',
            'field' => 'slug',
            'terms' => $terms_slug
        )
    ),
    'meta_query' => array(
        array(
            'key' => 'event_prix',
            'value' => get_post_meta(get_the_ID(), 'event_prix', true),
            'compare' => '<=',
            'type' => 'NUMERIC'
        )
    ),
    'posts_per_page' => 3,
    'orderby' => 'rand'
]);

if ($query->have_posts()) : ?>
    <h2 class="display-4 mt-5">Vous aimerez aussi ces évènements moins chers</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        while ($query->have_posts()) : $query->the_post();
            get_template_part('partials/card', 'event');
        endwhile; ?>
    </div>
<?php endif;
wp_reset_postdata();
?>

<?php get_footer(); ?>