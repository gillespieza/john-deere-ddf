<?php
/**
 * Genesass.
 *
 * This file adds functions to the Genesass Theme.
 *
 * @package Genesass
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://github.com/gillespieza/genesass-and-genuflex/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';


/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesass_localization_setup() {
	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'genesass_localization_setup' );

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}
add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );



/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesass_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		null
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}
add_action( 'wp_enqueue_scripts', 'genesass_enqueue_scripts_styles' );


/**
 * Add preconnect for Google Fonts.
 *
 * @since 3.4.1
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function genesass_resource_hints( $urls, $relation_type ) {

	if ( wp_style_is( genesis_get_theme_handle() . '-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'genesass_resource_hints', 10, 2 );


/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesass_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}
add_action( 'after_setup_theme', 'genesass_theme_support', 9 );


/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesass_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}
add_action( 'after_setup_theme', 'genesass_post_type_support', 9 );


// Don't load deprecated functions.
add_filter( 'genesis_load_deprecated', '__return_false' );

/* ----------------------------------------------------- */

// Adds debugging functions.
require_once get_stylesheet_directory() . '/lib/genesass/debugger.php';

// Adds theme customisations.
require_once get_stylesheet_directory() . '/lib/genesass/theme.php';

// Adds theme image customisations.
require_once get_stylesheet_directory() . '/lib/genesass/theme-images.php';

// Adds theme customisations for archive pages.
require_once get_stylesheet_directory() . '/lib/genesass/theme-archives.php';

// Adds theme customisations for single posts.
require_once get_stylesheet_directory() . '/lib/genesass/theme-single.php';

// Adds theme customisations for sidebars and widgets.
require_once get_stylesheet_directory() . '/lib/genesass/theme-sidebars.php';

// Adds theme customisations for menus.
require_once get_stylesheet_directory() . '/lib/genesass/theme-navigation.php';

// Clean up head and various bloat meta.
require_once get_stylesheet_directory() . '/lib/genesass/cleanup.php';

// Performance optimisations.
require_once get_stylesheet_directory() . '/lib/genesass/performance.php';

// Security optimisations.
require_once get_stylesheet_directory() . '/lib/genesass/security.php';

// Allow SVGs.
require_once get_stylesheet_directory() . '/lib/genesass/svg.php';

// WooCommerce.
require_once get_stylesheet_directory() . '/lib/genesass/woocommerce-custom.php';

