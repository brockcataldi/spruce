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
	 * Whether or not the configuration file was successfully loaded.
	 *
	 * @var bool $configuration_loaded
	 */
	public bool $configuration_loaded = false;

	/**
	 * Spruce Constructor
	 *
	 * Binding the init hook
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array($this, 'after_setup_theme' ));
		add_action( 'acf/init', array( $this, 'acf_init' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Load the spruce.config.php file from the child theme
	 *
	 * @return bool $file_load_result whether or not the file was loaded successfully
	 */
	public function load_configuration() {

		if ( file_exists( get_stylesheet_directory() . '/spruce.config.php' ) ) {

			try {
				require_once get_stylesheet_directory() . '/spruce.config.php';

				if ( false === defined( 'SPRUCE_CONFIGURATION' ) ) {
					return false;
				} else {
					return true;
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		return false;
	}

	/**
	 * Init hook
	 *
	 * Used to bind most of the actions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {

	}

	/**
	 * Acf_init hook.
	 *
	 * To register field groups and blocks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function acf_init() {

	}

	/**
	 * After_setup_theme hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function after_setup_theme() {

		// Attempting to load the configuration constant.
		$this->configuration_loaded = $this->load_configuration();

		error_log(var_export($this->configuration_loaded, true), 0);

		if(TRUE === $this->configuration_loaded ){
			error_log(var_export(SPRUCE_CONFIGURATION, true), 0);
		}
	}

	/**
	 * Plugin_activation hook
	 *
	 * Called when the plugin is activated. Currently bound but unused.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function plugin_activation() {}

	/**
	 * Plugin_deactivation hook
	 *
	 * Called when the plugin is deactivated. Currently bound but unused.
	 *
	 * @since 1.0.0
	 *
	 * @return void
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
	 *
	 * @return void
	 */
	public static function plugins_loaded() {
		global $spruce;

		if ( ! array_key_exists( 'spruce', $GLOBALS ) || ! is_a( $GLOBALS['spruce'], 'Spruce' ) ) {
				$spruce = new Spruce();
		}
	}
}
