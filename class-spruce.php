<?php
/**
 * Spruce Class
 *
 * Used to be the bridge between spruce operations and the WordPress hooks and filters.
 *
 * @package Spruce
 * @subpackage Spruce
 * @since 1.0.0
 */

/**
 * Spruce Class
 *
 * Used to be the bridge between spruce operations and the WordPress hooks and filters.
 *
 * @since 1.0.0
 */
class Spruce {

	/**
	 * Spruce Constructor
	 *
	 * Binding the init hook
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init hook
	 *
	 * Used to bind most of the actions.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		
	}

	/**
	 * Plugin_activation hook
	 *
	 * Called when the plugin is activated. Currently bound but unused.
	 *
	 * @since 1.0.0
	 */
	public static function plugin_activation() {}

	/**
	 * Plugin_deactivation hook
	 *
	 * Called when the plugin is deactivated. Currently bound but unused.
	 *
	 * @since 1.0.0
	 */
	public static function plugin_deactivation() {}

	/**
	 * Plugins_loaded hook
	 *
	 * Meant to ensure the spruce global object is created.
	 *
	 * @since 1.0.0
	 *
	 * @global Spruce $spruce Spruce Global Object.
	 */
	public static function plugins_loaded() {

		global $spruce;

		if ( ! array_key_exists( 'spruce', $GLOBALS ) || ! is_a( $GLOBALS['spruce'], 'Spruce' ) ) {
				$spruce = new Spruce();
		}

	}
}
