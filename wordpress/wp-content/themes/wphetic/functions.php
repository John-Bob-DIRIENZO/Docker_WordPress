<?php

function wphetic_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    register_nav_menu('header', 'Navigation dans le header');
    register_nav_menu('footer', 'Navigation dans le footer');
}

function wphetic_bootstrap()
{
    wp_enqueue_style('bootstrap_css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css');
    wp_enqueue_script('boostrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js',
        [], false, true);
}

function wphetic_nav_menu_css_class($classes): array
{
    $classes[] = 'nav-item';
    return $classes;
}

function wphetic_nav_menu_link_attributes($atts)
{
    $atts['class'] = 'nav-link';
    return $atts;
}

function wphetic_paginate_links()
{
    $pages = paginate_links(['type' => 'array']);
    if (!$pages) {
        return;
    }

    ob_start();
    echo '<nav aria-label="Page navigation example">';
    echo '<ul class="pagination">';
    foreach ($pages as $page) {
        // Cherche 'current' dans $post
        $active = strpos($page, 'current');
        // Si il le trouve, ajoute "active" comme classe
        $class = $active ? 'page-item active' : 'page-item';
        echo '<li class="' . $class . '">';
        echo str_replace('page-numbers', 'page-link', $page);
        echo '</li>';
    }
    echo '</ul>';
    echo '</nav>';
    return ob_get_clean();
}

add_action('init', 'wphetic_register_style_taxonomy');
function wphetic_register_style_taxonomy()
{
    $labels = [
        'name' => 'Styles',
        'singular_name' => 'Style',
        'search_items' => 'Rechercher style',
        'all_items' => 'Tous les styles'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true
    ];

    register_taxonomy('style', ['event'], $args);
}

add_action('init', 'wphetic_register_event_cpt');
function wphetic_register_event_cpt()
{
    $labels = [
        'name' => 'Évènement',
        'singular_name' => 'Évènements',
        'search_items' => 'Rechercher évènement',
        'all_items' => 'Tous les évènements'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-tickets',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'has_archive' => true,
        'taxonomies' => ['style']
    ];

    register_post_type('event', $args);
}

require_once 'classes/SponsoBox.php';
$sponso = new SponsoBox('wphetic_sponso');

require_once 'classes/BannerMessage.php';
BannerMessage::init();

add_action('after_setup_theme', 'wphetic_theme_support');
add_action('wp_enqueue_scripts', 'wphetic_bootstrap');
add_filter('login_headerurl', 'wphetic_login_headerurl');
add_filter('admin_footer_text', 'wp_hetic_admin_footer_hearts');
add_filter('nav_menu_css_class', 'wphetic_nav_menu_css_class');
add_filter('nav_menu_link_attributes', 'wphetic_nav_menu_link_attributes');

add_filter('manage_event_posts_columns', function ($col) {
    return array(
        'cb' => $col['cb'],
        'title' => $col['title'],
        'image' => 'Image',
        'price' => 'Prix',
        'taxonomy-style' => $col['taxonomy-style'],
        'date' => $col['date']
    );
});

add_action('manage_event_posts_custom_column', function ($col, $post_id) {
    if ($col === 'image') {
        the_post_thumbnail('thumbnail', $post_id);
    } elseif ($col === 'price') {
        echo get_post_meta($post_id, 'event_prix', true) . " €";
    }

}, 10, 2);

add_filter('query_vars', function ($params) {
    $params[] = 'sponso';
    $params[] = 'price';
    return $params;
});

add_action('pre_get_posts', function (WP_Query $query) use ($sponso) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->get('post_type') !== 'event' && get_query_var('sponso') === '1') {
        $meta_query = $query->get('meta_query', []);
        $meta_query[] = array(
            'key' => $sponso->getMetakey(),
            'compare' => 'EXISTS'
        );
        $query->set('meta_query', $meta_query);
    }

    if ($query->get('post_type') === 'event' && !empty(get_query_var('price'))) {
        $meta_query = $query->get('meta_query', []);
        $meta_query[] = array(
            'key' => 'event_prix',
            'value' => get_query_var('price'),
            'compare' => '<=',
            'type' => 'NUMERIC'
        );
        $query->set('meta_query', $meta_query);
    }
});
