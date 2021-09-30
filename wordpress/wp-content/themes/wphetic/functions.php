<?php

/**
 * Déclaration des support du thème
 */
add_action( 'after_setup_theme', 'wphetic_theme_support' );
function wphetic_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );
	register_nav_menu( 'header', 'Navigation dans le header' );
	register_nav_menu( 'footer', 'Navigation dans le footer' );
}

/**
 * Déclaration des feuilles CSS et JS de Bootstrap
 */
add_action( 'wp_enqueue_scripts', 'wphetic_bootstrap' );
function wphetic_bootstrap() {
	wp_enqueue_style( 'bootstrap_css',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css' );
	wp_enqueue_script( 'boostrap_js',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js',
		[], false, true );
	wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
		[], '3.6.0', true );
}

/**
 * Style de la navigation
 */
add_filter( 'nav_menu_css_class', 'wphetic_nav_menu_css_class' );
add_filter( 'nav_menu_link_attributes', 'wphetic_nav_menu_link_attributes' );
function wphetic_nav_menu_css_class( $classes ): array {
	$classes[] = 'nav-item';

	return $classes;
}

function wphetic_nav_menu_link_attributes( $atts ) {
	$atts['class'] = 'nav-link';

	return $atts;
}

/**
 * Pagination custom
 */
function wphetic_paginate_links() {
	$pages = paginate_links( [ 'type' => 'array' ] );
	if ( ! $pages ) {
		return;
	}

	ob_start();
	echo '<nav aria-label="Page navigation example">';
	echo '<ul class="pagination">';
	foreach ( $pages as $page ) {
		// Cherche 'current' dans $post
		$active = strpos( $page, 'current' );
		// Si il le trouve, ajoute "active" comme classe
		$class = $active ? 'page-item active' : 'page-item';
		echo '<li class="' . $class . '">';
		echo str_replace( 'page-numbers', 'page-link', $page );
		echo '</li>';
	}
	echo '</ul>';
	echo '</nav>';

	return ob_get_clean();
}

///**
// * Enregistrement des taxonomies
// */
//add_action('init', 'wphetic_register_style_taxonomy');
//function wphetic_register_style_taxonomy()
//{
//    $labels = [
//        'name' => 'Styles',
//        'singular_name' => 'Style',
//        'search_items' => 'Rechercher style',
//        'all_items' => 'Tous les styles'
//    ];
//
//    $args = [
//        'labels' => $labels,
//        'public' => true,
//        'hierarchical' => true,
//        'show_in_rest' => true,
//        'show_admin_column' => true
//    ];
//
//    register_taxonomy('style', ['event'], $args);
//}
//
///**
// * Enregistrement du CPT
// */
//add_action('init', 'wphetic_register_event_cpt');
//function wphetic_register_event_cpt()
//{
//    $labels = [
//        'name' => 'Évènement',
//        'singular_name' => 'Évènements',
//        'search_items' => 'Rechercher évènement',
//        'all_items' => 'Tous les évènements'
//    ];
//
//    $args = [
//        'labels' => $labels,
//        'public' => true,
//        'show_in_rest' => true,
//        'menu_icon' => 'dashicons-tickets',
//        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
//        'has_archive' => true,
//        'taxonomies' => ['style'],
//        'capabilities' => array(
//            'edit_post' => 'manage_events',
//            'read_post' => 'manage_events',
//            'delete_post' => 'manage_events',
//        ),
//    ];
//
//    register_post_type('event', $args);
//}
//
///**
// * Modifier les rôles de l'admin
// * quand on active le thème
// */
//add_action('after_switch_theme', function () {
//    $admin = get_role('administrator');
//    $admin->add_cap('manage_events');
//});
//
///**
// * Ajout d'un rôle Event Manager
// * quand on active le thème
// */
//add_action('after_switch_theme', function () {
//    add_role('event_manager', 'Event Manager', [
//        'read' => true,
//        'manage_events' => true
//    ]);
//});
//
///**
// * Nettoyage à la désactivation du thème
// */
//add_action('switch_theme', function () {
//    $admin = get_role('administrator');
//    $admin->remove_cap('manage_events');
//    remove_role('event_manager');
//});

/**
 * Meta-Box
 */
require_once 'classes/SponsoBox.php';
$sponso = new SponsoBox( 'wphetic_sponso' );

/**
 * Message en bannière
 */
require_once 'classes/BannerMessage.php';
BannerMessage::init();

/**
 * Anciens hooks pour le cours
 */
add_filter( 'login_headerurl', 'wphetic_login_headerurl' );
add_filter( 'admin_footer_text', 'wp_hetic_admin_footer_hearts' );

add_filter( 'manage_event_posts_columns', function ( $col ) {
	return array(
		'cb'             => $col['cb'],
		'title'          => $col['title'],
		'image'          => 'Image',
		'price'          => 'Prix',
		'taxonomy-style' => $col['taxonomy-style'],
		'date'           => $col['date']
	);
} );

/**
 * Ajout de colonnes custom dans le back-office
 */
add_action( 'manage_event_posts_custom_column', function ( $col, $post_id ) {
	if ( $col === 'image' ) {
		the_post_thumbnail( 'thumbnail', $post_id );
	} elseif ( $col === 'price' ) {
		echo get_post_meta( $post_id, 'event_prix', true ) . " €";
	}

}, 10, 2 );

/**
 * Ajout de params dans l'URL
 */
add_filter( 'query_vars', function ( $params ) {
	$params[] = 'sponso';
	$params[] = 'price';

	return $params;
} );

/**
 * Modif de la query via les params en URL
 */
add_action( 'pre_get_posts', function ( WP_Query $query ) use ( $sponso ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->get( 'post_type' ) !== 'event' && get_query_var( 'sponso' ) === '1' ) {
		$meta_query   = $query->get( 'meta_query', [] );
		$meta_query[] = array(
			'key'     => $sponso->getMetakey(),
			'compare' => 'EXISTS'
		);
		$query->set( 'meta_query', $meta_query );
	}

	if ( $query->get( 'post_type' ) === 'event' && ! empty( get_query_var( 'price' ) ) ) {
		$meta_query   = $query->get( 'meta_query', [] );
		$meta_query[] = array(
			'key'     => 'event_prix',
			'value'   => get_query_var( 'price' ),
			'compare' => '<=',
			'type'    => 'NUMERIC'
		);
		$query->set( 'meta_query', $meta_query );
	}
} );

/**
 * Enregistrement d'une sidebar
 */
add_action( 'widgets_init', function () {
	register_sidebar( [
		'name'          => 'SideBar Perso',
		'id'            => 'wphetic_sidebar',
		'before_widget' => '<div class="p-3 %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="font-italic">',
		'after_title'   => '</h4>'
	] );
} );

/**
 * Création d'un widget
 */
require_once 'widgets/Wphetic_InstagramWidget.php';
require_once 'widgets/Wphetic_SocialLinks.php';
add_action( 'widgets_init', function () {
	register_widget( Wphetic_InstagramWidget::class );
	register_widget( Wphetic_SocialLinks::class );
} );

/**
 * Des termes au changement de thème
 */
add_action( 'after_switch_theme', function () {
	wp_insert_term( 'Dubstep', 'style' );
	flush_rewrite_rules();
} );

/**
 * L'onglet de personnalisation
 */
add_action( 'customize_register', function ( WP_Customize_Manager $manager ) {

	$manager->add_section( 'wphetic_nav_color', [
		'title' => 'Couleur du header'
	] );

	$manager->add_setting( 'wphetic_nav_bg_color', [
		'default'  => '#000000',
		'sanitize' => 'sanitize_hex_color'
	] );

	$manager->add_control( new WP_Customize_Color_Control( $manager, 'wphetic_nav_bg_color', [
		'section' => 'wphetic_nav_color',
		'label'   => 'Couleur de la NavBar'
	] ) );
} );

///**
// * @var wpdb $wpdb
// */
//global $wpdb;
//
//$table_name = $wpdb->prefix . "ma_table_perso";
//$charset_collate = $wpdb->get_charset_collate();
//
//$sql = "CREATE TABLE {$table_name} (
//	id mediumint(9) NOT NULL AUTO_INCREMENT,
//	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
//	name tinytext NOT NULL,
//	text text NOT NULL,
//	url varchar(55) DEFAULT '' NOT NULL,
//	PRIMARY KEY  (id)
//) {$charset_collate}";
//
//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//dbDelta( $sql );

add_action( 'rest_api_init', function () {
	register_rest_route( 'wphetic/v1',
		'/demo/(?P<message>[a-zA-Z-]+)',
		array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'callback_de_lecture',
				'permission_callback' => '__return_true'
			),
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => 'callback_edition',
				'permission_callback' => '__return_true'
			),
		)
	);
} );

function callback_de_lecture( WP_REST_Request $request ) {
	$res = new WP_REST_Response( [ 'message' => 'bonne lecture' ] );
	$res->set_headers( [ 'Access-Control-Allow-Origin' => '*' ] );

	return $res;

}

function callback_edition( WP_REST_Request $request ) {
	$res = new WP_REST_Response( [ 'message' => 'bon upload' ] );
	$res->set_headers( [ 'Access-Control-Allow-Origin' => '*' ] );

	return $res;
}


