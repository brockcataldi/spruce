<?php
/**
 * Spruce Taxonomy Class
 *
 * Used as a basic class to register taxonomies.
 *
 * @package Spruce
 * @subpackage Taxonomy
 * @since 1.0.0
 */

/**
 * Spruce Taxonomy Class
 *
 * Used as a basic class to register taxonomies.
 *
 * @package Spruce
 * @subpackage Taxonomy
 * @since 1.0.0
 */
abstract class Spruce_Taxonomy {

	/**
	 * Taxonomy Slug.
	 *
	 * @var string $taxon
	 */
	public static string $taxon = '';

	/**
	 *  Post type slug or array of post type slugs
	 *
	 *  @since 3.0
	 *
	 *  @var string|array $post_types
	 */
	public static $post_types = array();

	/**
	 * Register taxonomy.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @return WP_Post_Type|WP_Error the result of the post type registration.
	 */
	public static function register() {
		return register_taxonomy( static::$taxon, static::$post_types, static::arguments() );
	}

	/**
	 * Creates all of the basic labels for the taxonomy dynamically. Though it does not test for grammar.
	 *
	 * @param string $singular the singular word of the taxonomy label.
	 * @param string $plural the plural word of the taxonomy label.
	 *
	 * @return array the labels.
	 */
	public static function create_labels( $singular, $plural ) {
        //phpcs:disable
		return array(
			'name'                       => _x( ucfirst($plural), 'taxonomy general name' ),
			'singular'                   => _x( ucfirst($singular), 'taxonomy singular name' ),
			'search_items'               => __( 'Search ' . ucfirst($plural) ),
			'popular_items'              => __( 'Popular ' . ucfirst($plural) ),
			'all_items'                  => __( 'All ' . ucfirst($plural) ),
			'parent_item'                => __( 'Parent ' . ucfirst($singular) ),
			'parent_item_colon'          => __( 'Parent ' . ucfirst($singular) . ':' ),
			'edit_item'                  => __( 'Edit ' . ucfirst($singular) ),
			'view_item'                  => __( 'View ' . ucfirst($singular) ),
			'update_item'                => __( 'Update ' . ucfirst($singular) ),
			'add_new_item'               => __( 'Add New ' . ucfirst($singular) ),
			'new_item'                   => __( 'New ' . ucfirst($singular) . ' Name' ),
			'separate_items_with_commas' => __( 'Separate ' . ucfirst($plural) . ' with commas' ),
			'add_or_remove_items'        => __( 'Add or remove ' . ucfirst($plural) ),
			'choose_from_most_used'      => __( 'Choose from the most used ' . ucfirst($plural) ),
		);
        //phpcs:enable
	}

	/**
	 * All of the arguments for the taxonomy.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @var array the taxonomy arguments
	 */
	abstract public static function arguments();

}
