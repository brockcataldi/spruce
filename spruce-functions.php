<?php
/**
 * Spruce Utility functions
 *
 * Used as helper functions for various tasks.
 *
 * @package Spruce
 * @subpackage Spruce
 * @since 1.0.0
 */

/**
 * Determining classes based on conditionals
 *
 * @param array $classes the classes and the conditionals.
 * @param bool  $echo whether or not to echo or return.
 *
 * @return void|string the class list
 */
function classes( array $classes = array(), bool $echo = true ) {
	$valid = array();

	foreach ( $classes as $classname => $conditional ) {

		if ( true === $conditional ) {
			$valid[] = $classname;
		}
	}

	if ( true === $echo ) {
		echo esc_html( implode( ' ', $valid ) );
	} else {
		return esc_html( $valid );
	}
}

/**
 * Determining if the current screen is an editor screen,
 * this is really just a wrapper for is_admin() currently.
 *
 * @see https://developer.wordpress.org/reference/functions/is_admin/
 *
 * @return bool whether or not the screen is an admin screen
 */
function is_editor() {
	return is_admin();
}

/**
 * Echoing or Returning either an href with link or an empty string.
 * The whole point of this is to echo out an href, so that's why there are
 * phpcs:disables
 *
 * @param string $url the url to echo or return.
 * @param bool   $echo whether to echo or to return.
 *
 * @return string the string
 */
function href( string $url, bool $echo = true ) {

	$string = ( is_editor() ) ? '' : sprintf( 'href="%s"', $url );

	if ( true === $echo ) {
		// phpcs:disable
		echo $string;
		// phpcs:enable
	} else {
		// phpcs:disable
		return $string;
		// phpcs:enable
	}
}
