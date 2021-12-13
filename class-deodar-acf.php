<?php
/**
 * Deodar ACF Class
 *
 * Used to manage the ACF registration.
 *
 * @package Deodar
 * @subpackage Deodar
 * @since 1.0.0
 */

/**
 * Deodar ACF Class
 *
 * Used to manage the ACF registration.
 *
 * @since 1.0.0
 */
class Deodar_ACF {
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
	 * Loading a specific block as long as it's in the right folder.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block the block key to include.
	 *
	 * @return void
	 */
	private function load_block( $block ) {
		$loaded = include_file( sprintf( '%s/%s/class-%s.block.php', get_stylesheet_directory() . '/blocks/acf/', $block, $block ) );
		if ( true === $loaded ) {
			call_user_func( classify( $block ) . '_Block::register' );
		}
	}

	/**
	 * Load acf blocks via the config options, either automatically or manually
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_blocks() {
		$block_loading_option = $this->configuration->get( array( 'blocks', 'acf' ) );

		if ( false === $block_loading_option ) {
			return;
		}

		if ( 'array' === gettype( $block_loading_option ) ) {

			foreach ( $block_loading_option as $block ) {
				$this->load_block( $block );
			}
		} elseif ( Deodar_Configuration::AUTOMATIC === $block_loading_option ) {

			$entries = scan( get_stylesheet_directory() . '/blocks/acf/' );

			if ( false !== $entries ) {
				foreach ( $entries as $entry ) {
					if ( false === strpos( $entry, '.' ) ) { // exclude files at a root level.
						$this->load_block( $entry );
					}
				}
			}
		}
	}
}
