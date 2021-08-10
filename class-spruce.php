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
 * Requiring the Base class for ACF Blocks.
 */
require_once SPRUCE_PLUGIN_DIR . 'class-spruce-block.php';

/**
 * Spruce Class
 *
 * Used to be the bridge between spruce operations and the WordPress hooks and filters.
 *
 * @since 1.0.0
 */
class Spruce {

	/**
	 * A string value in the config file for Automatic
	 */
	const AUTOMATIC = 'auto';

	/**
	 * Whether or not the configuration file was successfully loaded.
	 *
	 * @var bool $configuration_loaded
	 */
	public bool $custom_configuration_loaded = false;

	/**
	 * Spruce Constructor
	 *
	 * Binding the init hook
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'acf/init', array( $this, 'acf_init' ) );
	}

	/**
	 * Get the configuration value from either the default or custom config file
	 *
	 * @param string|array $key the key or multidimensional key from the config.
	 *
	 * @return any $value the value within the config file.
	 */
	public function get( $key ) {
		$key_type = gettype( $key );
		$custom   = false === $this->custom_configuration_loaded;

		if ( 'string' === $key_type ) {
			return $this->get_string( $key, $custom );
		} elseif ( 'array' === $key_type ) {
			return $this->get_array( $key, $custom );
		} else {
			return null;
		}
	}

	/**
	 * Get the configuration value from either the default or custom config file recursively.
	 * Returns null if nothing is found.
	 *
	 * @param string $key key from the config.
	 * @param bool   $custom whether or not to even attempt to load it from the custom config file.
	 *
	 * @return any $value the value within the config file.
	 */
	private function get_string( $key, $custom = false ) {

		if ( true === $custom ) {
			if ( true === array_key_exists( $key, SPRUCE_CONFIGURATION ) ) {
				return SPRUCE_CONFIGURATION[ $key ];
			}
		}

		if ( true === array_key_exists( $key, SPRUCE_DEFAULT_CONFIGURATION ) ) {
			return SPRUCE_DEFAULT_CONFIGURATION[ $key ];
		}

		return null;
	}

	/**
	 * Get the configuration value from either the default or custom config file, specifically an array based key.
	 * Returns null if nothing is found.
	 *
	 * @param array $key multidimensional key from the config.
	 * @param bool  $custom whether or not to even attempt to load it from the custom config file.
	 *
	 * @return any $value the value within the config file.
	 */
	private function get_array( $key, $custom = false ) {

		if ( true === $custom ) {
			$result = $this->search_array( $key, SPRUCE_CONFIGURATION );

			if ( false === is_null( $result ) ) {
				return $result;
			}
		}

		$result = $this->search_array( $key, SPRUCE_DEFAULT_CONFIGURATION );

		if ( false === is_null( $result ) ) {
			return $result;
		}

		return null;
	}

	/**
	 * Get value from array, recursively. Returns null if nothing is found
	 *
	 * @param array $key multidimensional key from the config.
	 * @param array $array whether or not to even attempt to load it from the custom config file.
	 *
	 * @return any $value the value within the config file.
	 */
	private function search_array( $key, $array ) {
		if ( 1 === count( $key ) ) {
			return $array[ $key[0] ];
		} else {
			$shifted = array_shift( $key );

			if ( true === array_key_exists( $shifted, $array ) ) {
				return $this->search_array( $key, $array[ $shifted ] );
			} else {
				return null;
			}
		}
	}

	/**
	 * Load the spruce.config.php file from the child theme
	 *
	 * @since 1.0.0
	 *
	 * @return bool $file_load_result whether or not the file was loaded successfully
	 */
	public function load_configuration() {

		if ( true === $this->load_file( get_stylesheet_directory() . '/spruce.config.php' ) ) {
			return defined( 'SPRUCE_CONFIGURATION' );
		}

		return false;
	}

	/**
	 * Load specified file via the $path
	 *
	 * @since 1.0.0
	 *
	 * @param string $path the file path.
	 *
	 * @return bool $file_load_result whether or not the file was loaded successfully
	 */
	public function load_file( $path ) {

		if ( true === file_exists( $path ) ) {
			require_once $path;
			return true;
		}

		return false;
	}

	/**
	 * Load blocks via the config options, either automatically or manually
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_blocks() {
		$block_loading_option = $this->get( array( 'blocks', 'acf' ) );

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->load_block( $block );
			}
		} elseif ( self::AUTOMATIC === $block_loading_option ) {

			$entries = $this->scan( get_stylesheet_directory() . '/blocks/acf/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) {
						$this->load_block( $entry );
					}
				}
			}
		}
	}

	/**
	 * Loading a specific block as long as it's in the right folder.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block the block key to include.
	 *
	 * @return void
	 */
	private function load_block( $block ) {
		$loaded = $this->load_file( sprintf( '%s/%s/class-%s.block.php', get_stylesheet_directory() . '/blocks/acf/', $block, $block ) );
		if ( true === $loaded ) {
			call_user_func( $this->classify( $block ) . '_Block::register' );
		}
	}

	/**
	 * This uppercases each word and makes the separator an underscore.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string the word to be classified.
	 *
	 * @return string the classified name
	 */
	private function classify( $string ) {
		return implode(
			'_',
			array_map(
				function( $word ) {
					return ucfirst( $word );
				},
				explode( '_', $string )
			)
		);

	}

	/**
	 * Scans directory based on path, removes the linux entries for up a directory and a current directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path the path to scan.
	 *
	 * @return array|boolean $entries returns false when the path doesn't exist
	 */
	private function scan( $path ) {

		$scan = scandir( $path );

		return ( false === $scan ) ? false : array_diff( $scan, array( '..', '.' ) );
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
		$this->custom_configuration_loaded = $this->load_configuration();
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
		$this->load_blocks();
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
