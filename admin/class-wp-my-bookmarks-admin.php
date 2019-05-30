<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/admin
 * @author     Lilumi <lilumi.odi@gmail.com>
 */
class Wp_My_Bookmarks_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-my-bookmarks-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-my-bookmarks-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_plugin_page()
    {
        add_menu_page(
            'My Bookmarks', 
            'My Bookmarks', 
            'read', 
            'my_bookmarks', 
			array( $this, 'view_and_manage_my_bookmarks' ),
			'dashicons-tag',
			33 //position in the left sidebar is right after "Comments"
        );
	}
	
	public function view_and_manage_my_bookmarks() {
		require_once('partials/wp-my-bookmarks-admin-display.php');
	}

}
