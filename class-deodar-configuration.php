<?php
/**
 * Deodar Configuration Class
 *
 * The configuration manager, via the basic and custom configuration files.
 *
 * @package Deodar
 * @subpackage Configuration
 * @since 1.0.0
 */

/**
 * Deodar Configuration Class
 *
 * The configuration manager, via the basic and custom configuration files.
 *
 * @since 1.0.0
 */
class Deodar_Configuration {

	/**
	 * A string value in the config file for Automatic.
	 */
	const AUTOMATIC = 'auto';

	/**
	 * Whether or not the configuration file was successfully loaded.
	 *
	 * @var bool $configuration_loaded
	 */
	public bool $custom_configuration_loaded = false;

	/**
	 * Constructor for the Configuration. Initalizes the loading of the custom configuration.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->custom_configuration_loaded = $this->load_configuration();
	}

	/**
	 * Get the configuration value from either the default or custom config file
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $key the key or multidimensional key from the config.
	 *
	 * @return any the value within the config file.
	 */
	public function get( $key ) {
		$key_type = gettype( $key );

		if ( 'string' === $key_type ) {
			return $this->get_string( $key, $this->custom_configuration_loaded );
		} elseif ( 'array' === $key_type ) {
			return $this->get_array( $key, $this->custom_configuration_loaded );
		} else {
			return null;
		}
	}

	/**
	 * Get the configuration value from either the default or custom config file recursively.
	 * Returns null if nothing is found.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key key from the config.
	 * @param bool   $custom whether or not to even attempt to load it from the custom config file.
	 *
	 * @return any $value the value within the config file.
	 */
	private function get_string( string $key, bool $custom = false ) {

		if ( true === $custom ) {
			if ( true === array_key_exists( $key, DEODAR_CONFIGURATION ) ) {
				return DEODAR_CONFIGURATION[ $key ];
			}
		}

		if ( true === array_key_exists( $key, DEODAR_DEFAULT_CONFIGURATION ) ) {
			return DEODAR_DEFAULT_CONFIGURATION[ $key ];
		}

		return null;
	}

	/**
	 * Get the configuration value from either the default or custom config file, specifically an array based key.
	 * Returns null if nothing is found.
	 *
	 * @since 1.0.0
	 *
	 * @param array $key multidimensional key from the config.
	 * @param bool  $custom whether or not to even attempt to load it from the custom config file.
	 *
	 * @return any $value the value within the config file.
	 */
	private function get_array( array $key, bool $custom = false ) {

		if ( true === $custom ) {
			$result = search_array( $key, DEODAR_CONFIGURATION );

			if ( false === is_null( $result ) ) {
				return $result;
			}
		}

		$result = search_array( $key, DEODAR_DEFAULT_CONFIGURATION );

		if ( false === is_null( $result ) ) {
			return $result;
		}

		return null;
	}

	/**
	 * Load the deodar.config.php file from the child theme
	 *
	 * @since 1.0.0
	 *
	 * @return bool whether or not the file was loaded successfully
	 */
	public function load_configuration() {
		if ( true === include_file( get_stylesheet_directory() . '/deodar.config.php' ) ) {
			return defined( 'DEODAR_CONFIGURATION' );
		}
		return false;
	}
}
