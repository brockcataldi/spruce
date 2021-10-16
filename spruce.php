<?php
/**
 * Plugin Name:  Spruce
 * Plugin URI:
 * Description:  Modular Theme Building Framework
 * Author:       Brock Cataldi
 * Version:      1.0.0
 * Text Domain:  spruce
 * Requires PHP: 7.4
 * License:
 *
 * @package Spruce
 */

// Ensure there is no direct execution.
if ( ! function_exists( 'add_action' ) ) {
	echo 'Nice Try';
	exit;
}

// Defining basic constants.
define( 'SPRUCE_VERSION', '1.0' );
define( 'SPRUCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPRUCE_ACF_BLOCKS_DIR', get_stylesheet_directory() . '/blocks/acf/' );
define( 'SPRUCE_INCLUDES_DIR', get_stylesheet_directory() . '/includes/' );

// Default Configuration Options.
define(
	'SPRUCE_DEFAULT_CONFIGURATION',
	array(
		'vanity'         => false,
		'blocks'         => array(
			'acf'  => 'auto',
			'core' => 'auto',
		),
		'styles'         => array(),
		'scripts'        => array(),
		'supports'       => array(),
		'menus'          => array(),
		'sidebars'       => array(),
		'post-types'     => 'auto',
		'taxonomies'     => 'auto',
		'customizations' => 'auto',
		'bundles'        => 'auto',
		'walkers'        => 'auto',
	)
);


// Requiring the Base class for ACF Blocks.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-block.php';

// Requiring the abstract class for Post Types.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-post-type.php';

// Requiring the abstract class for Taxonomies.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-taxonomy.php';

// Requiring the abstract class for Customizations.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-customization.php';

// Requiring the abstrct class for Bundles.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-bundle.php';

// Including the Spruce Base Class.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce.php';

// Initial Creation Hook.
add_action( 'plugins_loaded', array( 'Spruce', 'plugins_loaded' ) );

// Plugin Activation and Deactivation Hooks.
register_activation_hook( __FILE__, array( 'Spruce', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Spruce', 'plugin_deactivation' ) );
