<?php
/**
 * Deodar Enqueuer Class
 *
 * Handling javascript files and stylesheets, in various enqueuing strategies.
 *
 * @package Deodar
 * @subpackage Configuration
 * @since 1.0.0
 */

/**
 * Deodar Enqueuer Class
 *
 * Handling javascript files and stylesheets, in various enqueuing strategies.
 *
 * @since 1.0.0
 */
class Deodar_Enqueuer {

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
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @param string $prefix the block prefix.
	 * @param string $block the block name.
	 * @param bool   $editor if enqueued for the editor.
	 *
	 * @return void
	 */
	private function enqueue_block_styles( string $prefix, string $block, bool $editor = false ) {
		if ( true === $editor || true === has_block( sprintf( '%s/%s', $prefix, $block ) ) ) {
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
	 * Loading core block stylesheets, will be adapted for other block providers eventually.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $editor if enqueued for the editor.
	 *
	 * @return void
	 */
	public function enqueue_blocks_styles( bool $editor = false ) {
		$prefix               = 'core';
		$block_loading_option = $this->configuration->get( array( 'blocks', $prefix ) );

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->enqueue_block_styles( $prefix, $block );
			}
		} elseif ( Deodar_Configuration::AUTOMATIC === $block_loading_option ) {

			$entries = scan( get_stylesheet_directory() . '/blocks/' . $prefix );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) {
						$this->enqueue_block_styles( $prefix, $entry );
					}
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
	public function enqueue_scripts() {
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
	 * Loading stylesheets passed into the styles config
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
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
}
