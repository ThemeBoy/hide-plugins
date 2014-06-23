<?php
/**
 * Post Types Admin
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	WP_Dictionary/Admin
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPD_Admin_Post_Types' ) ) :

/**
 * WPD_Admin_Post_Types Class
 */
class WPD_Admin_Post_Types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'include_post_type_handlers' ) );
	}

	/**
	 * Conditonally load classes and functions only needed when viewing a post type.
	 */
	public function include_post_type_handlers() {
		include( 'post-types/class-wpd-admin-cpt-word.php' );
		do_action( 'wp_dictionary_include_post_type_handlers' );
	}
}

endif;

return new WPD_Admin_Post_Types();