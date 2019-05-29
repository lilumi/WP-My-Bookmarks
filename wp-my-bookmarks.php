<?php

/**
 *
 * @link              #
 * @since             1.0.0
 * @package           Wp_My_Bookmarks
 *
 * @wordpress-plugin
 * Plugin Name:       My bookmarks
 * Plugin URI:        #
 * Description:       Plugin allows you to add posts to your bookmarks after user’s login.
 * Version:           1.0.0
 * Author:            Lilumi
 * Author URI:        #
 * License:           
 * License URI:       
 * Text Domain:       wp-my-bookmarks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WP_MY_BOOKMARKS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-my-bookmarks-activator.php
 */
function activate_wp_my_bookmarks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-my-bookmarks-activator.php';
	Wp_My_Bookmarks_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-my-bookmarks-deactivator.php
 */
function deactivate_wp_my_bookmarks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-my-bookmarks-deactivator.php';
	Wp_My_Bookmarks_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_my_bookmarks' );
register_deactivation_hook( __FILE__, 'deactivate_wp_my_bookmarks' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-my-bookmarks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_my_bookmarks() {

	$plugin = new Wp_My_Bookmarks();
	$plugin->run();

}
run_wp_my_bookmarks();
