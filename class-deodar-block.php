<?php
/**
 * Deodar Block Class
 *
 * Used as a base static class to create ACF blocks simply and effectively.
 *
 * @package Deodar
 * @subpackage Block
 * @since 1.0.0
 */

/**
 * Deodar Block Class
 *
 * Used as a base static class to create ACF blocks simply and effectively.
 *
 * @package Deodar
 * @subpackage Block
 * @since 1.0.0
 */
class Deodar_Block {

	/**
	 * Required unique key, creating the field group and block.
	 *
	 * @var string $key
	 */
	public static string $key = '';

	/**
	 * Options to create the block. The title option is required.
	 *
	 * @see https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 *
	 * @var array $options
	 */
	public static array $options = array();

	/**
	 * Options to create the field group.
	 *
	 * @see https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 *
	 * @var array $fields
	 */
	public static array $fields = array();

	/**
	 * Deodar Parameters to create the block. Mainly used for enqueue the javascript files.
	 *
	 * @var array $params
	 */
	public static array $params = array();

	/**
	 * Format the options property on the static class to create the block.
	 *
	 * @since 1.0.0
	 *
	 * @return array $options the properly formatted options array
	 */
	public static function get_options() {

		$options = array_merge(
			static::$options,
			array(
				'name'        => static::$key,
                // phpcs:disable
				'title'       => __( static::$options['title'] ),
				'description' => __( static::$options['description'] ),
                // phpcs:enable
			)
		);

		if ( ! array_key_exists( 'render_callback', $options )
		&& ! array_key_exists( 'render_template', $options ) ) {
			$options['render_template'] = sprintf( 'blocks/acf/%s/%s.php', static::$key, static::$key );
		}

		if ( ! array_key_exists( 'enqueue_assests', $options ) ) {

			$key                       = static::$key;
			$params                    = static::$params;
			$options['enqueue_assets'] = function() use ( $key, $params ) {

				// phpcs:disable
				wp_enqueue_style(
					'block-acf-' . $key,
					sprintf( '%s/blocks/acf/%s/%s.build.css', get_stylesheet_directory_uri(), $key, $key )
				);
				// phpcs:enable

				if ( array_key_exists( 'script', $params )
				&& true === $params['script'] ) {

					wp_enqueue_script(
						'block-acf-' . $key,
						sprintf( '%s/blocks/acf/%s/%s.build.js', get_stylesheet_directory_uri(), $key, $key ),
						( array_key_exists( 'dependencies', $params ) ) ? $params['dependencies'] : array(),
						( array_key_exists( 'version', $params ) ) ? $params['version'] : array(),
						( array_key_exists( 'footer', $params ) ) ? $params['footer'] : true,
					);
				}
			};

		}

		return $options;
	}

	/**
	 * Format the fields property on the static class to create a local field group, for only the block.
	 *
	 * @since 1.0.0
	 *
	 * @return array $fields the properly formatted fields array
	 */
	public static function get_fields() {
		return array(
			'key'      => static::$key,
            // phpcs:disable
			'title'    => __( static::$options['title'] ),
            // phpcs:enable
			'fields'   => static::$fields,
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/' . static::$key,
					),
				),
			),
		);
	}

	/**
	 * Register the block and field group.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register() {
		if ( function_exists( 'acf_register_block' ) ) {
			acf_register_block( static::get_options() );
		}

		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group( static::get_fields() );
		}
	}
}
