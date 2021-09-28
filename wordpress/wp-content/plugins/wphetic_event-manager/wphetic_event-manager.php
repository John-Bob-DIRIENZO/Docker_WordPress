<?php
/**
 * Plugin Name: Event Manager
 * Description: Rajoute un post type "évènement", une taxonomie "style", ajoute les permissions nécessaires à l'administrateur et crée un rôle "Event Manager" qui ne pourra manipuler que les évènement
 * Version: 1.0.0
 * Author: Jean-François DI RIENZO
 * Author URI: https://www.totastudio.com
 */

if (!defined('ABSPATH')) {
    die('Accès interdit');
}

register_activation_hook(__FILE__, function () {
    // Add capability to the admin
    $admin = get_role('administrator');
    $admin->add_cap('manage_events');

    // Creates Event Manager role
    add_role('event_manager', 'Event Manager', [
        'read' => true,
        'manage_events' => true
    ]);
});

register_deactivation_hook(__FILE__, function () {
    // Clean-up
    $admin = get_role('administrator');
    $admin->remove_cap('manage_events');
    remove_role('event_manager');
});

/**
 * Enregistrement des taxonomies
 */
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

/**
 * Enregistrement du CPT
 */
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
        'taxonomies' => ['style'],
        'capabilities' => array(
            'edit_post' => 'manage_events',
            'read_post' => 'manage_events',
            'delete_post' => 'manage_events',
        ),
    ];

    register_post_type('event', $args);
}



add_shortcode('display_events', function ($attr) {

    $values = shortcode_atts([
        'nbre_posts' => 3
    ], $attr);

    require_once 'classes/Wphetic_EventManagerQuery.php';
    $query = new Wphetic_EventManagerQuery(intval($values['nbre_posts']));
    return $query->render();
});
