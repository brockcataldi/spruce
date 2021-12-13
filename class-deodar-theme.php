<?php
/**
 * Deodar Theme Class
 *
 * Handling theme modifications, via supports, menus and sidebars.
 *
 * @package Deodar
 * @subpackage Configuration
 * @since 1.0.0
 */

/**
 * Deodar Enqueuer Class
 *
 * Handling theme modifications, via supports, menus and sidebars.
 *
 * @since 1.0.0
 */
class Deodar_Theme {

	/**
	 * Constructor for the Enqueuer.
	 *
	 * @var Deodar_Configuration $configuration the system configuration.
	 *
	 * @since 1.0.0
	 */
	public Deodar_Configuration $configuration;

	/**
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @param Deodar_Configuration $configuration the system configuration.
	 *
	 * @return void
	 */
	public function __construct( Deodar_Configuration $configuration ) {
		$this->configuration = $configuration;
	}

	/**
	 * Load and registers all passed navigation menus
	 *
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @throws Exception If the supports config option isn't an array.
	 *
	 * @return void
	 */
	public function load_menus() {
		$menus_options = $this->configuration->get( 'menus' );

		if ( 'array' !== gettype( $menus_options ) ) {
			throw new Exception( 'menus in the deodar.config.php must be an array' );
		}

		if ( count( $menus_options ) === 0 ) {
			return;
		}

		register_nav_menus( $menus_options );
	}

	/**
	 * Load and registers all passed navigation menus
	 *
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_sidebar/
	 *
	 * @throws Exception If the supports config option isn't an array.
	 *
	 * @return void
	 */
	public function load_sidebars() {
		$sidebars_options = $this->configuration->get( 'sidebars' );

		if ( 'array' !== gettype( $sidebars_options ) ) {
			throw new Exception( 'sidebars in the deodar.config.php must be an array' );
		}

		if ( count( $sidebars_options ) === 0 ) {
			return;
		}

		foreach ( $sidebars_options as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

	/**
	 * Load and apply all of the add_theme_support variables
	 *
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @throws Exception If the supports config option isn't an array.
	 *
	 * @return void
	 */
	public function load_supports() {
		$supports_options = $this->configuration->get( 'supports' );

		if ( 'array' !== gettype( $supports_options ) ) {
			throw new Exception( 'supports in the deodar.config.php must be an array' );
		}

		if ( count( $supports_options ) === 0 ) {
			return;
		}

		foreach ( $supports_options as $key => $value ) {
			$key_type = gettype( $key );

			switch ( $key_type ) {
				case 'integer':
					add_theme_support( $value );
					break;

				case 'string':
					add_theme_support( $key, $value );
					break;
			}
		}
	}
}
