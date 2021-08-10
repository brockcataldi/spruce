<?php
/**
 * Spruce Block Class
 *
 * Used as a base static class to create ACF blocks simply and effectively.
 *
 * @package Spruce
 * @subpackage Block
 * @since 1.0.0
 */

/**
 * Spruce Block Class
 *
 * Used as a base static class to create ACF blocks simply and effectively.
 *
 * @package Spruce
 * @subpackage Block
 * @since 1.0.0
 */
class Spruce_Block {

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
			if ( ! array_key_exists( 'enqueue_style', $options ) ) {
				$options['enqueue_style'] = sprintf( '%s/build/acf/%s.css', get_stylesheet_directory_uri(), static::$key );
			}

			// TODO: Add a scripts option.
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
