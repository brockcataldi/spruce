<?php
/**
 * Spruce Bundle Class
 *
 * A series of hooks and filters to handle a goal, replaces certain plugins
 *
 * @package Spruce
 * @subpackage Bundle
 *
 * @since 1.0
 */

/**
 * Spruce Bundle Class
 *
 * A series of hooks and filters to handle a goal, replaces certain plugins
 *
 * @package Spruce
 * @subpackage Bundle
 *
 * @since 1.0
 */
abstract class Spruce_Bundle {

	/**
	 * Bundle Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {}

	/**
	 * Registering all the bundle hooks, ideally add your actions/filters/hooks here
	 */
	abstract public function register();
}
