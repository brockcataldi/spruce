<?php
/**
 * Spruce Post Type Class
 *
 * Used as a basic class to register post types.
 *
 * @package Spruce
 * @subpackage PostType
 * @since 1.0.0
 */

/**
 * Spruce Post Type Class
 *
 * Used as a basic class to register post types.
 *
 * @package Spruce
 * @subpackage PostType
 * @since 1.0.0
 */
abstract class Spruce_Post_Type {

	/**
	 * Post type slug.
	 *
	 * @var string $post_type
	 */
	public static string $post_type = '';

	/**
	 * Register post types.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @return WP_Post_Type|WP_Error the result of the post type registration.
	 */
	public static function register() {
		return register_post_type( static::$post_type, static::arguments() );
	}

	/**
	 * Creates all of the basic labels for the post type dynamically. Though it does not test for grammar.
	 *
	 * @param string $singular the singular word of the post type label.
	 * @param string $plural the plural word of the post type label.
	 *
	 * @return array the labels.
	 */
	public static function create_labels( $singular, $plural ) {
        //phpcs:disable
		return array(
			'name'                  => _x( ucfirst( $plural ), 'Post type general name', CHILD_THEME ),
			'singular_name'         => _x( ucfirst( $singular ) . '', 'Post type singular name', CHILD_THEME ),
			'menu_name'             => _x( ucfirst( $plural ), 'Admin Menu text', CHILD_THEME ),
			'name_admin_bar'        => _x( ucfirst( $singular ) . '', 'Add New on Toolbar', CHILD_THEME ),
			'add_new'               => __( 'Add New', CHILD_THEME ),
			'add_new_item'          => __( 'Add New ' . ucfirst( $singular ), CHILD_THEME ),
			'new_item'              => __( 'New ' . ucfirst( $singular ), CHILD_THEME ),
			'edit_item'             => __( 'Edit ' . ucfirst( $singular ), CHILD_THEME ),
			'view_item'             => __( 'View ' . ucfirst( $singular ), CHILD_THEME ),
			'all_items'             => __( 'All ' . ucfirst( $plural ), CHILD_THEME ),
			'search_items'          => __( 'Search ' . ucfirst( $plural ), CHILD_THEME ),
			'parent_item_colon'     => __( 'Parent ' . ucfirst( $plural ) . ':', CHILD_THEME ),
			'not_found'             => __( 'No ' . strtolower( $plural ) . ' found.', CHILD_THEME ),
			'not_found_in_trash'    => __( 'No ' . strtolower( $plural ) . ' found in Trash.', CHILD_THEME ),
			'featured_image'        => _x( ucfirst( $singular ) . ' Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', CHILD_THEME ),
			'set_featured_image'    => _x( 'Set image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', CHILD_THEME ),
			'remove_featured_image' => _x( 'Remove image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', CHILD_THEME ),
			'use_featured_image'    => _x( 'Use image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', CHILD_THEME ),
			'archives'              => _x( ucfirst( $singular ) . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', CHILD_THEME ),
			'insert_into_item'      => _x( 'Insert into ' . ucfirst( $singular ), 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', CHILD_THEME ),
			'uploaded_to_this_item' => _x( 'Uploaded to this ' . ucfirst( $singular ), 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', CHILD_THEME ),
			'filter_items_list'     => _x( 'Filter ' . strtolower( $plural ) . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', CHILD_THEME ),
			'items_list_navigation' => _x( ucfirst( $plural ) . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', CHILD_THEME ),
			'items_list'            => _x( ucfirst( $plural ) . ' list', 'Screen reader text for the items list head' ),
		);
        //phpcs:enable
	}

	/**
	 * All of the arguments for the post types.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @var array the post type arguments
	 */
	abstract public static function arguments();

}
