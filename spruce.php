<?php
/**
 * Plugin Name:  Spruce
 * Plugin URI:
 * Description:
 * Author:       Brock Cataldi
 * Version:      1.0
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

// Including the Spruce Base Class.
require_once SPRUCE_PLUGIN_DIR . 'class-spruce.php';

// Initial Creation Hook.
add_action( 'plugins_loaded', array( 'Spruce', 'plugins_loaded' ) );

// Plugin Activation and Deactivation Hooks.
register_activation_hook( __FILE__, array( 'Spruce', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Spruce', 'plugin_deactivation' ) );
