<?php
/**
 * Deodar Class
 *
 * Used to be the bridge between deodar operations and the WordPress hooks and filters.
 *
 * @package Deodar
 * @subpackage Deodar
 * @since 1.0.0
 */

/**
 * Deodar Class
 *
 * Used to be the bridge between deodar operations and the WordPress hooks and filters.
 *
 * @since 1.0.0
 */
class Deodar {

	/**
	 * A string value in the config file for Automatic.
	 */
	const AUTOMATIC = 'auto';

	/**
	 * Includes patterns.
	 */
	const INCLUDES = array(
		'post-types' => array(
			'suffix'    => '_Post_Type',
			'extension' => '.post-type.php',
			'pattern'   => '/class-(.*)\.post-type\.php/',
			'static'    => true,
			'register'  => true,
		),
		'taxonomies' => array(
			'suffix'    => '_Taxonomy',
			'pattern'   => '/class-(.*)\.taxonomy\.php/',
			'extension' => '.taxonomy.php',
			'static'    => true,
			'register'  => true,
		),
		'bundles'    => array(
			'suffix'    => '_Bundle',
			'pattern'   => '/class-(.*)\.bundle\.php/',
			'extension' => '.bundle.php',
			'static'    => false,
			'register'  => true,
		),
		'walkers'    => array(
			'suffix'    => '_Walker',
			'pattern'   => '/class-(.*)\.walker\.php/',
			'extension' => '.walker.php',
			'static'    => false,
			'register'  => false,
		),
	);

	/**
	 * Configuration Manager.
	 *
	 * @var Deodar_Configuration $configuration
	 */
	public Deodar_Configuration $configuration;

	/**
	 * Deodar Constructor
	 *
	 * Binding the init hook
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->configuration = new Deodar_Configuration();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'acf/init', array( $this, 'acf_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	/**
	 * Load acf blocks via the config options, either automatically or manually
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_acf_blocks() {
		$block_loading_option = $this->configuration->get( array( 'blocks', 'acf' ) );

		if ( false === $block_loading_option ) {
			return;
		}

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->load_acf_block( $block );
			}
		} elseif ( self::AUTOMATIC === $block_loading_option ) {

			$entries = scan( get_stylesheet_directory() . '/blocks/acf/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) { // exclude files at a root level.
						$this->load_acf_block( $entry );
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
	private function load_acf_block( $block ) {
		$loaded = include_file( sprintf( '%s/%s/class-%s.block.php', get_stylesheet_directory() . '/blocks/acf/', $block, $block ) );
		if ( true === $loaded ) {
			call_user_func( classify( $block ) . '_Block::register' );
		}
	}

	/**
	 * Loading stylesheets passed into the styles config
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_styles() {
		$styles = $this->configuration->get( 'styles' );

		if ( false === $styles ) {
			return;
		}

		foreach ( $styles as $style ) {
			if ( true === array_key_exists( 'name', $style ) ) {
				if ( true === array_key_exists( 'file', $style ) ) {
					wp_enqueue_style(
						$style['name'],
						sprintf( '%s/%s', get_stylesheet_directory_uri(), $style['file'] ),
						( false === array_key_exists( 'dependencies', $style ) ) ? array() : $style['dependencies'],
						( false === array_key_exists( 'version', $style ) ) ? null : $style['version'],
						( false === array_key_exists( 'media', $style ) ) ? 'all' : $style['media']
					);
				} elseif ( true === array_key_exists( 'uri', $style ) ) {
					wp_enqueue_style(
						$style['name'],
						$style['uri'],
						( false === array_key_exists( 'dependencies', $style ) ) ? array() : $style['dependencies'],
						( false === array_key_exists( 'version', $style ) ) ? null : $style['version'],
						( false === array_key_exists( 'media', $style ) ) ? 'all' : $style['media']
					);
				}
			}
		}
	}

	/**
	 * Loading javascripts passed into the scripts config
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_scripts() {
		$scripts = $this->configuration->get( 'scripts' );

		if ( false === $scripts ) {
			return;
		}
		foreach ( $scripts as $script ) {
			if ( true === array_key_exists( 'name', $script ) ) {
				if ( true === array_key_exists( 'file', $script ) ) {
					wp_enqueue_script(
						$script['name'],
						sprintf( '%s/%s', get_stylesheet_directory_uri(), $script['file'] ),
						( false === array_key_exists( 'dependencies', $script ) ) ? array() : $script['dependencies'],
						( false === array_key_exists( 'version', $script ) ) ? null : $script['version'],
						( false === array_key_exists( 'footer', $script ) ) ? false : $script['footer']
					);
				} elseif ( true === array_key_exists( 'uri', $script ) ) {
					wp_enqueue_script(
						$script['name'],
						$script['uri'],
						( false === array_key_exists( 'dependencies', $script ) ) ? array() : $script['dependencies'],
						( false === array_key_exists( 'version', $script ) ) ? null : $script['version'],
						( false === array_key_exists( 'footer', $script ) ) ? false : $script['footer']
					);
				}
			}
		}
	}

	/**
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_blocks_styles() {

		$prefix               = 'core';
		$block_loading_option = $this->configuration->get( array( 'blocks', $prefix ) );

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->load_block_styles( $prefix, $block );
			}
		} elseif ( self::AUTOMATIC === $block_loading_option ) {

			$entries = scan( get_stylesheet_directory() . '/blocks/' . $prefix );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) {
						$this->load_block_styles( $prefix, $entry );
					}
				}
			}
		}
	}

	/**
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @param string $prefix the block prefix.
	 * @param string $block the block name.
	 *
	 * @return void
	 */
	private function load_block_styles( $prefix, $block ) {
		if ( true === has_block( sprintf( '%s/%s', $prefix, $block ) ) ) {

			// phpcs:disable
			wp_enqueue_style(
				sprintf( '%s-%s', $prefix, $block ),
				sprintf( '%s/blocks/%s/%s/%s.build.css', get_stylesheet_directory_uri(), $prefix, $block, $block),
				array(),
				null,
				'all'
			);
			// phpcs:enable
		}
	}

	/**
	 * Loading core block stylesheets for the edtior side, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_editor_blocks_styles() {
		$prefix               = 'core';
		$block_loading_option = $this->configuration->get( array( 'blocks', $prefix ) );

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->load_editor_block_styles( $prefix, $block );
			}
		} elseif ( self::AUTOMATIC === $block_loading_option ) {

			$entries = scan( get_stylesheet_directory() . '/blocks/' . $prefix );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) {
						$this->load_editor_block_styles( $prefix, $entry );
					}
				}
			}
		}
	}

	/**
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @param string $prefix the block prefix.
	 * @param string $block the block name.
	 *
	 * @return void
	 */
	private function load_editor_block_styles( $prefix, $block ) {
		// phpcs:disable
		wp_enqueue_style(
			sprintf( '%s-%s', $prefix, $block ),
			sprintf( '%s/blocks/%s/%s/%s.build.css', get_stylesheet_directory_uri(), $prefix, $block, $block),
			array(),
			null,
			'all'
		);
		// phpcs:enable
	}

	/**
	 * Load all of the includes based on slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type the type of includes.
	 *
	 * @return void
	 */
	private function load_includes( $type ) {
		$loading_options = $this->configuration->get( $type );
		$include         = self::INCLUDES[ $type ];

		if ( 'array' === gettype( $loading_options ) ) {

			foreach ( $loading_options  as $loading_option ) {
				$this->load_include( $loading_option, $type, $include );
			}
		} elseif ( self::AUTOMATIC === $loading_options ) {

			$entries = scan( DEODAR_INCLUDES_DIR . $type . '/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( 1 === preg_match( $include['pattern'], $entry, $matches ) ) {
						$this->load_include( $matches[1], $type, $include );
					}
				}
			}
		}
	}

	/**
	 * Load one of the includes file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name class slug.
	 * @param string $type the type of include.
	 * @param array  $include the includes data object.
	 *
	 * @return void
	 */
	private function load_include( $name, $type, $include ) {
		$path       = DEODAR_INCLUDES_DIR . $type . '/class-' . strtolower( $name ) . $include['extension'];
		$class_name = classify( $name . $include['suffix'] );

		if ( true === file_exists( $path ) ) {
			include_once $path;

			if ( true === $include['register'] ) {
				if ( true === $include['static'] ) {
					$class_name::register();
				} else {
					$temp = new $class_name();
					$temp->register();
				}
			}
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
	private function load_supports() {
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
	private function load_menus() {
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
	private function load_sidebars() {
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
	 * Load and registers customization
	 *
	 * @since 1.0.0
	 *
	 * @param string               $name The name of the customization.
	 * @param WP_Customize_Manager $wp_customize the WP_Customize_Manager.
	 *
	 * @return void
	 */
	private function load_customization( $name, $wp_customize ) {
		$path       = DEODAR_INCLUDES_DIR . 'customizations/class-' . strtolower( $name ) . '.customization.php';
		$class_name = classify( $name . '_Customization' );

		if ( true === file_exists( $path ) ) {
			include_once $path;

			$temp = new $class_name();
			$temp->register( $wp_customize );
		}
	}
	/**
	 * Load and registers all customizations
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize the WP_Customize_Manager.
	 *
	 * @return void
	 */
	private function load_customizations( $wp_customize ) {
		$customizations_options = $this->configuration->get( 'customizations' );

		if ( 'array' === gettype( $customizations_options ) ) {

			foreach ( $customizations_options  as $customization_option ) {
				$this->load_customization( $customization_option, $wp_customize );
			}
		} elseif ( self::AUTOMATIC === $customizations_options ) {

			$entries = scan( DEODAR_INCLUDES_DIR . 'customizations/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( 1 === preg_match( '/class-(.*)\.customization\.php/', $entry, $matches ) ) {
						$this->load_customization( $matches[1], $wp_customize );
					}
				}
			}
		}
	}

	/**
	 * Admin_init hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_init() {
		add_editor_style( 'editor-style.css' );
	}

	/**
	 * Enqueue_block_editor_assets hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		$this->load_editor_blocks_styles();
	}

	/**
	 * Init hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->load_includes( 'post-types' );
		$this->load_includes( 'taxonomies' );
	}

	/**
	 * Customize_register hook.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize the WP_Customize_Manager.
	 *
	 * @return void
	 */
	public function customize_register( $wp_customize ) {
		$this->load_customizations( $wp_customize );
	}

	/**
	 * Wp_head hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wp_head() {}

	/**
	 * Widgets_init hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function widgets_init() {
		$this->load_sidebars();
	}

	/**
	 * After_setup_theme hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function after_setup_theme() {
		$this->load_supports();
		$this->load_menus();
		$this->load_includes( 'bundles' );
		$this->load_includes( 'walkers' );
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
		$this->load_acf_blocks();
	}

	/**
	 * Enqueue Scripts and Styles Hooks.
	 *
	 * Loading styles and specific styles based on core blocks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts() {
		$this->load_styles();
		$this->load_blocks_styles();
		$this->load_scripts();
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
	 * Meant to ensure the deodar global object is created.
	 *
	 * @since 1.0.0
	 *
	 * @global Deodar $deodar Deodar Global Object.
	 *
	 * @return void
	 */
	public static function plugins_loaded() {
		global $deodar;

		if ( ! array_key_exists( 'deodar', $GLOBALS ) || ! is_a( $GLOBALS['deodar'], 'Deodar' ) ) {
				$deodar = new Deodar();
		}
	}
}
