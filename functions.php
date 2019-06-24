<?php
/**
 * Theme functions and definitions
 *
 * @package Steep
 */

/**
 * Setup stuffs.
 *
 * @since 1.0.0
 */
function steep_theme_setup() {
	// Remove custom header support.
	remove_theme_support( 'custom-header' );

	// Register image size.
	add_image_size( 'steep-thumb', 400, 300 );
}

add_action( 'after_setup_theme', 'steep_theme_setup', 20 );

/**
 * Register sidebars.
 *
 * @since 1.0.0
 */
function steep_register_sidebars() {
	hybrid_register_sidebar(
		array(
			'id'          => 'header-right',
			'name'        => esc_html_x( 'Header Right', 'sidebar', 'steep' ),
			'description' => esc_html__( 'Sidebar for header right area.', 'steep' ),
		)
	);

	hybrid_register_sidebar(
		array(
			'id'   => 'home-widget-area',
			'name' => esc_html_x( 'Home Widget Area', 'sidebar', 'steep' ),
		)
	);
}

add_action( 'widgets_init', 'steep_register_sidebars', 15 );

/**
 * Register nav menus.
 *
 * @since 1.0.0
 */
function steep_register_menus() {
	register_nav_menu( 'footer', esc_html_x( 'Footer', 'nav menu location', 'steep' ) );
}

add_action( 'init', 'steep_register_menus', 11 );

/**
 * Enqueue styles and scripts.
 *
 * @since 1.0.0
 */
function steep_enqueue_styles() {
	wp_enqueue_style( 'steep-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'steep-style', get_stylesheet_uri(), array( 'steep-parent-style' ) );
}

add_action( 'wp_enqueue_scripts', 'steep_enqueue_styles', 11 );

/**
 * Adds support for the WordPress 'custom-background' theme feature.
 *
 * @since 1.0.0
 */
function steep_custom_background_setup() {
	add_theme_support(
		'custom-background',
		array(
			'default-color'      => '2d2d2d',
			'default-attachment' => 'fixed',
			'default-image'      => get_stylesheet_directory_uri() . '/images/background.jpg',
			'wp-head-callback'   => 'stargazer_custom_background_callback',
		)
	);
}

add_action( 'after_setup_theme', 'steep_custom_background_setup', 15 );

/**
 * Load helpers.
 */
require get_stylesheet_directory() . '/inc/helpers.php';

/**
 * Load widgets.
 */
require get_stylesheet_directory() . '/inc/widgets.php';
