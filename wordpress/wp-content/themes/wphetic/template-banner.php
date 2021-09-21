<?php
/**
 * Template Name: Modèle Jumbotron
 * Template Post Type: page, post
 * Description: Un modèle de page énorme
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold"><?php the_title(); ?></h1>
            <p class="col-md-8 fs-4"><?php the_content(); ?></p>
        </div>
    </div>


<?php endwhile; ?>

<?php get_footer(); ?>
