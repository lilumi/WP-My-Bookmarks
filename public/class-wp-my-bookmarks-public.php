<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/public
 * @author     Lilumi <lilumi.odi@gmail.com>
 */
class Wp_My_Bookmarks_Public {

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
    
    private $add_text;
	private $remove_text;
	private $add_ok_text;
	private $remove_ok_text;
	private $wait_text;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
        $this->version = $version;
        
        $this->add_text = __('Add to bookmarks', 'wp-my-bookmarks');
		$this->remove_text =  __('Remove from bookmarks', 'wp-my-bookmarks');
		$this->add_ok_text = __('Added.', 'wp-my-bookmarks');
		$this->remove_ok_text =  __('Removed.', 'wp-my-bookmarks');
		$this->wait_text =  __('Please wait a second...', 'wp-my-bookmarks');

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_My_Bookmarks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_My_Bookmarks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-my-bookmarks-public.css', array('dashicons'), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_My_Bookmarks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_My_Bookmarks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-my-bookmarks-public.js', array( 'jquery' ), $this->version, true );
		$props = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('bookmarks_nonce'),
			'wait_text' => $this->wait_text,
		  );
		wp_localize_script( $this->plugin_name, 'ajax_props', $props );
	}

	/**
	 * Add "Add to Bookmarks" link after the content in the archive view
	 *
	 * @since    1.0.0
	 */
	private function bookmark_link($id = null) {


	}	

	/**
	 * Add "Add to Bookmarks" link after the content in the archive view
	 *
	 * @since    1.0.0
	 */
	public function add_bookmark_this($content = '') {
		global $id;
		if (is_user_logged_in() && is_archive() && !is_feed()) {
			$user_id = get_current_user_id();
			$bookmarks_array = json_decode( get_user_meta($user_id, 'lm_my_bookmarks', true) , true);
	
			if (isset($bookmarks_array[$id]) && $bookmarks_array[$id]['is_in_trash'] == false ) { 
				$bookmark_text = $this->remove_text;
			} else {
				$bookmark_text = $this->add_text;
			}
			$html_insert = '<div class="user_bookmark"><a href="#" data-id="'.$id.'" class="lm_add_to_bookmarks">'.$bookmark_text.'</a><span class="lm_wait"></span></div>';
	
			$content .= $html_insert;
		}
		return $content;

	}	

	private function toggle_bookmark($post_id = null) {
		$result = array('text' => '', 'ok_message' => '');
		$user_id = get_current_user_id();
		$bookmarks_array = json_decode( get_user_meta($user_id, 'lm_my_bookmarks', true) , true);

		if (isset($bookmarks_array[$post_id]) ) { // if exists then - remove from the list

			unset($bookmarks_array[$post_id]);
			update_user_meta($user_id, 'lm_my_bookmarks', json_encode($bookmarks_array, JSON_UNESCAPED_UNICODE));
			$result['text'] = $this->add_text;
			$result['debug'] = 'removed from list';
			$result['ok_message'] = $this->remove_ok_text;

		} else { // add bookmark to the list

			$bookmarks_array[$post_id] = array(
				'ID' => $post_id, 
				'permalink' => htmlentities(get_the_permalink($post_id)),
				'title' => htmlentities(get_the_title($post_id)),
				'excerpt' => htmlentities(get_the_excerpt($post_id)) , 
				'thumbnail' => htmlentities(get_the_post_thumbnail($post_id)), 
				'is_in_trash' => false,
			);
			update_user_meta($user_id, 'lm_my_bookmarks', json_encode($bookmarks_array, JSON_UNESCAPED_UNICODE));
			$result['text'] = $this->remove_text;
			$result['debug'] = json_encode($bookmarks_array, JSON_UNESCAPED_UNICODE);
			$result['ok_message'] = $this->add_ok_text;

		}

		return $result;
	}

	/**
	 * Function which fires on ajax action when user click on "Add to Bookmarks" link
	 *
	 * @since    1.0.0
	 */
	public function process_bookmark() {
        check_ajax_referer( 'bookmarks_nonce', 'security' );
        $response = '';
		$post_id = intval($_POST['post_id']);
		if ( $post_id > 0 ) {
			$results = $this->toggle_bookmark($post_id);
            $response = [
				'text' => $results['text'],
				'debug' => $results['debug'],
				'ok_message' => $results['ok_message'],
              ];
		}
		
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
  		exit;

	}	

	/**
	 * Function which allows to use shorcode to display user bookmarks.
	 *
	 * @since    1.0.0
	 */
	public static function list_bookmarks_shortcode( $atts, $content = "" ) {
		$result = '';
		$user_id = get_current_user_id();
		$bookmarks_array = json_decode( get_user_meta($user_id, 'lm_my_bookmarks', true) , true);
		if (!empty($bookmarks_array)) {
			foreach($bookmarks_array as $boo) {
				$result .= '<div class="lm_bookmark" id="bookmark_'.$boo['ID'].'">'.
				'<div class="lm_thumb">'.html_entity_decode($boo['thumbnail']).'</div>'.
				    '<h3><a href="'.$boo['permalink'].'">'.$boo['title'].'</a></h3>'.
					'<div class="lm_short_desc">'.html_entity_decode($boo['excerpt']).'</div>'. 
					'<div class="user_bookmark"><a href="#" data-id="'.$boo['ID'].'" class="lm_add_to_bookmarks">'.__('Remove from bookmarks', 'wp-my-bookmarks').'</a><span class="lm_wait"></span></div>'.
				'</div>';
			}
		}

		return $result;
	}

}

add_shortcode( 'bookmarks', array( 'Wp_My_Bookmarks_Public', 'list_bookmarks_shortcode' ) );
