<?php
/**
 * Deodar Class
 *
 * Used to be the bridge between deodar operations and the WordPress hooks and filters.
 *
 * @package Deodar
 * @subpackage Deodar
 *
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
	 * Configuration Manager for default and custom configurations.
	 *
	 * @var Deodar_Configuration $configuration
	 */
	public Deodar_Configuration $configuration;

	/**
	 * Manager for ACF registrations.
	 *
	 * @var Deodar_ACF $acf
	 */
	public Deodar_ACF $acf;

	/**
	 * Enqueuer for Scripts and Styles.
	 *
	 * @var Deodar_Enqueuer $enqueuer
	 */
	public Deodar_Enqueuer $enqueuer;

	/**
	 * Loader for includes.
	 *
	 * @var Deodar_Includer $includer
	 */
	public Deodar_Includer $includer;

	/**
	 * Loader for includes.
	 *
	 * @var Deodar_Theme $theme
	 */
	public Deodar_Theme $theme;

	/**
	 * Deodar Constructor
	 *
	 * Binding the init hook
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->configuration = new Deodar_Configuration();
		$this->acf           = new Deodar_ACF( $this->configuration );
		$this->enqueuer      = new Deodar_Enqueuer( $this->configuration );
		$this->includer      = new Deodar_Includer( $this->configuration );
		$this->theme         = new Deodar_Theme( $this->configuration );

		add_action( 'acf/init', array( $this, 'acf_init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
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
		$this->acf->load_blocks();
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
	 * After_setup_theme hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function after_setup_theme() {
		$this->theme->load_supports();
		$this->theme->load_menus();
		$this->includer->loads( 'bundles' );
		$this->includer->loads( 'walkers' );
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
		$this->includer->loads( 'customizations', $wp_customize );
	}

	/**
	 * Enqueue_block_editor_assets hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		$this->enqueuer->enqueue_blocks_assets( true );
	}

	/**
	 * Init hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->includer->loads( 'post-types' );
		$this->includer->loads( 'taxonomies' );
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
		$this->theme->load_sidebars();
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
		$this->enqueuer->enqueue_styles();
		$this->enqueuer->enqueue_scripts();
		$this->enqueuer->enqueue_blocks_assets( false );
	}

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
