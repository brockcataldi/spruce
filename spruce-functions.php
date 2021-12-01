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
