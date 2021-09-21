<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>

<?php if (!is_front_page()) : ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><?php bloginfo('name'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <?php wp_nav_menu([
                    'theme_location' => 'header',
                    'menu_class' => 'navbar-nav me-auto mb-2 mb-lg-0',
                    'container' => false
                ]); ?>

                <?php get_search_form(); ?>

            </div>
        </div>
    </nav>

<?php endif; ?>

<div class="container mt-5 mb-5">

