<?php
/**
 * Plugin Name: Générateur de formulaire
 * Description: Un plugin qui va afficher un formulaire avec le shortcode [wphetic_form]
 * Version: 1.0.0
 * Author: Jean-François DI RIENZO
 * Author URI: https://www.totastudio.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Accès interdit' );
}

require_once 'classes/Wphetic_FormGenerator.php';

add_shortcode( 'wphetic_form', function () {
	return Wphetic_FormGenerator::createForm();
} );

add_action( 'admin_post_wphetic_form', function () {
	Wphetic_FormGenerator::handleForm();
} );

add_shortcode( 'wphetic_login_form', function () {
	return Wphetic_FormGenerator::displayLoginForm();
} );