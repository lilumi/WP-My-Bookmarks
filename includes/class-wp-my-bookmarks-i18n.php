<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/includes
 * @author     Lilumi <lilumi.odi@gmail.com>
 */
class Wp_My_Bookmarks_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-my-bookmarks',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
