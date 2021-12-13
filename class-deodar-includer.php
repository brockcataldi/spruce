<?php
/**
 * Deodar Includer Class
 *
 * Handling javascript files and stylesheets, in various enqueuing strategies.
 *
 * @package Deodar
 * @subpackage Configuration
 * @since 1.0.0
 */

/**
 * Deodar Includer Class
 *
 * Handling javascript files and stylesheets, in various enqueuing strategies.
 *
 * @since 1.0.0
 */
class Deodar_Includer {

	/**
	 * Includes patterns.
	 */
	const INCLUDES = array(
		'bundles'        => array(
			'suffix'    => '_Bundle',
			'pattern'   => '/class-(.*)\.bundle\.php/',
			'extension' => '.bundle.php',
			'static'    => false,
			'register'  => true,
		),
		'customizations' => array(
			'suffix'    => '_Customization',
			'pattern'   => '/class-(.*)\.customization\.php/',
			'extension' => '.customization.php',
			'static'    => false,
			'register'  => true,
		),
		'post-types'     => array(
			'suffix'    => '_Post_Type',
			'extension' => '.post-type.php',
			'pattern'   => '/class-(.*)\.post-type\.php/',
			'static'    => true,
			'register'  => true,
		),
		'taxonomies'     => array(
			'suffix'    => '_Taxonomy',
			'pattern'   => '/class-(.*)\.taxonomy\.php/',
			'extension' => '.taxonomy.php',
			'static'    => true,
			'register'  => true,
		),
		'walkers'        => array(
			'suffix'    => '_Walker',
			'pattern'   => '/class-(.*)\.walker\.php/',
			'extension' => '.walker.php',
			'static'    => false,
			'register'  => false,
		),
	);

	/**
	 * Constructor for the Theme.
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
	 * Load one of the includes file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name class slug.
	 * @param string $type the type of include.
	 * @param array  $include the includes data object.
	 * @param any    $arguments WP_Customizer or any other arguments.
	 *
	 * @return void
	 */
	private function load( string $name, string $type, array $include, $arguments = null ) {
		$path       = DEODAR_INCLUDES_DIR . $type . '/class-' . strtolower( $name ) . $include['extension'];
		$class_name = classify( $name . $include['suffix'] );

		$loaded = include_file( $path );

		if ( false === $loaded || false === $include['register'] ) {
			return;
		}

		if ( true === $include['static'] ) {
			if ( is_null( $arguments ) ) {
				$class_name::register();
			} else {
				$class_name::register( $arguments );
			}
		} else {
			$temp = new $class_name();

			if ( is_null( $arguments ) ) {
				$temp->register();
			} else {
				$temp->register( $arguments );
			}
		}
	}

	/**
	 * Load all of the includes based on slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type the type of includes.
	 * @param any    $arguments WP_Customizer or any other arguments.
	 *
	 * @return void
	 */
	public function loads( string $type, $arguments = null ) {
		$loading_options = $this->configuration->get( $type );
		$include         = self::INCLUDES[ $type ];

		if ( 'array' === gettype( $loading_options ) ) {

			foreach ( $loading_options  as $loading_option ) {
				$this->load( $loading_option, $type, $include, $arguments );
			}
		} elseif ( Deodar_Configuration::AUTOMATIC === $loading_options ) {

			$entries = scan( DEODAR_INCLUDES_DIR . $type . '/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( 1 === preg_match( $include['pattern'], $entry, $matches ) ) {
						$this->load( $matches[1], $type, $include, $arguments );
					}
				}
			}
		}
	}
}
