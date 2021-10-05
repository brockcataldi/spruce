<?php
/**
 * Spruce Customization class
 *
 * An abstract class to add customzier options
 *
 * @package Spruce
 * @subpackage Customization
 * @since 1.0
 */

/**
 * Spruce Customization class
 *
 * An abstract class to add customzier options
 *
 * @package Spruce
 * @subpackage Customization
 * @since 1.0
 */
abstract class Spruce_Customization {
	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct(){}

	/**
	 * The actual registration for the customizer.
	 *
	 * @see https://developer.wordpress.org/themes/customize-api/customizer-objects/
	 *
	 * @param WP_Customize_Manager $wp_customize the WP_Customize_Manager.
	 *
	 * @since 1.0
	 */
	abstract public function register( WP_Customize_Manager $wp_customize );
}
