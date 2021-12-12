<?php
/**
 * Deodar Utility functions
 *
 * Used as helper functions for various tasks.
 *
 * @package Deodar
 * @subpackage Functions
 * @since 1.0.0
 */


/**
 * Scans directory based on path, removes the linux entries for up a directory and a current directory.
 *
 * @since 1.0.0
 *
 * @param string $path the path to scan.
 *
 * @return array|boolean $entries returns false when the path doesn't exist
 */
function scan( $path ) {
	if ( false === file_exists( $path ) ) {
		return false;
	}

	$scan = scandir( $path );
	return ( false === $scan ) ? false : array_diff( $scan, array( '..', '.' ) );
}

/**
 * Get value from array, recursively. Returns null if nothing is found
 *
 * @param array $key multidimensional key from the config.
 * @param array $array array to search.
 *
 * @return any the value within the config file.
 */
function search_array( $key, $array ) {
	if ( 1 === count( $key ) ) {
		return $array[ $key[0] ];
	} else {
		$shifted = array_shift( $key );

		if ( true === array_key_exists( $shifted, $array ) ) {
			return search_array( $key, $array[ $shifted ] );
		} else {
			return null;
		}
	}
}

/**
 * Load specified file via the $path
 *
 * @since 1.0.0
 *
 * @param string $path the file path.
 *
 * @return bool whether or not the file was loaded successfully
 */
function include_file( $path ) {

	if ( true === file_exists( $path ) ) {
		include_once $path;
		return true;
	}

	return false;
}

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

/**
 * This uppercases each word and makes the separator an underscore.
 *
 * @since 1.0.0
 *
 * @param string $string the word to be classified.
 *
 * @return string the classified name
 */
function classify( $string ) {
	return implode(
		'_',
		array_map(
			function( $word ) {
				return ucfirst( $word );
			},
			explode(
				'_',
				str_replace( '-', '_', $string )
			)
		)
	);
}