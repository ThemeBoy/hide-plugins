<?php
/**
 * @package Hide_Plugins
 * @version 1.0.4
 */
/*
Plugin Name: Hide Plugins
Plugin URI: http://wordpress.org/plugins/hide-plugins/
Description: Hide installed plugins from all other users.
Author: brianmiyaji
Version: 1.0.4
Author URI: http://themeboy.com/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin setup
 *
 * @since 1.0
*/
class Hide_Plugins {

	/**
	 * @var string
	 */
	public $filename = 'hide-plugins/hide-plugins.php';

	/**
	 * Hide Plugins Constructor.
	 * @access public
	 */
	public function __construct() {

		// Activate plugin
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'option_hide_plugins', array( $this, 'hide_self' ) );
		add_filter( 'all_plugins', array( $this, 'prepare_items' ) );
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 4 );
		add_action( 'admin_action_hide_plugin', array( $this, 'hide_action' ) );
		add_action( 'admin_action_show_plugin', array( $this, 'show_action' ) );
		
		// Multisite
		add_filter( 'network_admin_plugin_action_links', array( $this, 'action_links' ), 10, 4 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'HIDE_PLUGINS_VERSION' ) )
			define( 'HIDE_PLUGINS_VERSION', '1.0.4' );

		if ( !defined( 'HIDE_PLUGINS_URL' ) )
			define( 'HIDE_PLUGINS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'HIDE_PLUGINS_DIR' ) )
			define( 'HIDE_PLUGINS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Install
	 */
	public function install() {
		$current_user = wp_get_current_user();
		update_option( 'hide_plugins_user', $current_user->ID );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'hide-plugins' );
		
		// Global + Frontend Locale
		load_plugin_textdomain( 'hide-plugins', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/**
	 * Hide this plugin from other users.
	 */
	public function hide_self( $option ) {
		if ( ! is_array( $option ) ) {
			$option = array();
		}
		if ( ! in_array( $this->filename, $option ) ) {
			$option[] = $this->filename;
		}
		return $option;
	}

	/**
	 * Prepare plugins.
	 */
	public function prepare_items( $plugins ) {
		$user = get_option( 'hide_plugins_user' );
		$current_user = wp_get_current_user();

		if ( ! isset( $user ) || ! get_userdata( $user ) || ! isset( $current_user ) ) return $plugins;

		$hidden = (array) array_unique( get_option( 'hide_plugins', array( $this->filename ) ) );
		foreach ( $hidden as $filename ) {
			if ( array_key_exists( $filename, $plugins ) ) {
				if ( $user == $current_user->ID ) {
					$plugins[ $filename ]['Name'] = '<em>' . $plugins[ $filename ]['Name'] . '</em>';
					$plugins[ $filename ]['Description'] = '<em>' . $plugins[ $filename ]['Description'] . '</em>';
				} else {
					unset( $plugins[ $filename ] );
				}
			}
		}
		return $plugins;
	}

	/**
	 * Add the action link.
	 */
	public function action_links( $actions, $plugin_file, $plugin_data, $context ) {
		global $page, $s;
		$user = get_option( 'hide_plugins_user' );
		$current_user = wp_get_current_user();

		if ( $user != $current_user->ID ) return $actions;

		$hidden = (array) get_option( 'hide_plugins', array( $this->filename ) );
		if ( $this->filename != $plugin_file ) {
			if ( in_array( $plugin_file, $hidden ) ) {
				$actions['show'] = '<a href="' . wp_nonce_url('plugins.php?action=show_plugin&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'show-plugin_' . $plugin_file) . '" title="' . esc_attr__('Show this plugin', 'show-plugins') . '" class="edit">' . __('Show', 'hide-plugins') . '</a>';
			} else {
				$actions['hide'] = '<a href="' . wp_nonce_url('plugins.php?action=hide_plugin&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'hide-plugin_' . $plugin_file) . '" title="' . esc_attr__('Hide this plugin', 'hide-plugins') . '" class="edit">' . __('Hide', 'hide-plugins') . '</a>';
			}
		}
		return $actions;
	}

	/**
	 * Hide a plugin action.
	 */
	public function hide_action() {
		if ( empty( $_REQUEST['plugin'] ) ) {
			wp_die(__( 'Plugin file does not exist.', 'hide-plugins' ));
		}

		// Get the filename
		$filename = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		// Hide the plugin
		if ( ! empty( $filename ) ) {
			$hidden = get_option( 'hide_plugins', array() );
			if ( ! in_array( $filename, $hidden ) ) {
				$hidden[] = $filename;
			}
			update_option( 'hide_plugins', $hidden );
		} else {
			wp_die(__( 'Plugin file does not exist.', 'hide-plugins' ) );
		}
	}

	/**
	 * Show a plugin action.
	 */
	public function show_action() {
		if ( empty( $_REQUEST['plugin'] ) ) {
			wp_die(__( 'Plugin file does not exist.', 'hide-plugins' ));
		}

		// Get the filename
		$filename = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		// Show the plugin
		if ( ! empty( $filename ) ) {
			$hidden = get_option( 'hide_plugins', array() );
			if ( ( $key = array_search( $filename, $hidden ) ) !== false ) {
				unset( $hidden[ $key ] );
			}
			update_option( 'hide_plugins', $hidden );
		} else {
			wp_die(__( 'Plugin file does not exist.', 'hide-plugins' ) );
		}
	}
}

new Hide_Plugins();
