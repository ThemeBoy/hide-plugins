<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WP Dictionary Admin.
 *
 * @class 		WPD_Admin 
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	WP_Dictionary/Admin
 * @version     0.1
 */
class WPD_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// Classes
		include_once( 'class-wpd-admin-post-types.php' );
	}
}

return new WPD_Admin();