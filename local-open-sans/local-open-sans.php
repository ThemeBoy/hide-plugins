<?php
/**
 * @package Local_Open_Sans
 * @version 1.0
 */
/*
Plugin Name: Local Open Sans
Plugin URI: http://wordpress.org/plugins/local-open-sans/
Description: Replace Open Sans with a local copy to speed up admin testing and development.
Author: ThemeBoy
Version: 1.0
Author URI: http://themeboy.com/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin setup
 *
 * @since 1.0
*/
class Local_Open_Sans {

	/**
	 * Constructor.
	 * @access public
	 */
	public function __construct() {

		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'replace_open_sans' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'LOCAL_OPEN_SANS_VERSION' ) )
			define( 'LOCAL_OPEN_SANS_VERSION', '1.0' );

		if ( !defined( 'LOCAL_OPEN_SANS_URL' ) )
			define( 'LOCAL_OPEN_SANS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'LOCAL_OPEN_SANS_DIR' ) )
			define( 'LOCAL_OPEN_SANS_DIR', plugin_dir_path( __FILE__ ) );
	}

	public function replace_open_sans() {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', trailingslashit( plugins_url( '/', __FILE__ ) ) . 'open-sans.css' );
		wp_register_style( 'open-sans', false );
	}
}

new Local_Open_Sans();
