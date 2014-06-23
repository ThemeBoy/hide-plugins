<?php
/**
 * Plugin Name: WP Dictionary
 * Plugin URI: http://themeboy.com/
 * Description: Create your own online dictionary.
 * Version: 0.1
 * Author: ThemeBoy
 * Author URI: http://themeboy.com/
 * Requires at least: 3.8
 * Tested up to: 3.9.1
 *
 * Text Domain: wp-dictionary
 * Domain Path: /languages/
 *
 * @package WP_Dictionary
 * @category Core
 * @author ThemeBoy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_Dictionary' ) ) :

/**
 * Main WP_Dictionary Class
 *
 * @class WP_Dictionary
 * @version	0.1
 */
final class WP_Dictionary {

	/**
	 * @var string
	 */
	public $version = '0.1';

	/**
	 * @var WP_Dictionary The single instance of the class
	 * @since 0.1
	 */
	protected static $_instance = null;

	/**
	 * Main WP_Dictionary Instance
	 *
	 * Ensures only one instance of WP_Dictionary is loaded or can be loaded.
	 *
	 * @since 0.1
	 * @static
	 * @see WPD()
	 * @return WP_Dictionary - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 0.1
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-dictionary' ), '0.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 0.1
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-dictionary' ), '0.1' );
	}

	/**
	 * WP_Dictionary Constructor.
	 * @access public
	 * @return WP_Dictionary
	 */
	public function __construct() {
		// Auto-load classes on demand
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'init', array( $this, 'include_template_functions' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );

		// Loaded action
		do_action( 'wp_dictionary_loaded' );
	}

	/**
	 * Auto-load WPD classes on demand to reduce memory consumption.
	 *
	 * @param mixed $class
	 * @return void
	 */
	public function autoload( $class ) {
		$path  = null;
		$class = strtolower( $class );
		$file = 'class-' . str_replace( '_', '-', $class ) . '.php';

		if ( strpos( $class, 'wpd_shortcode_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/shortcodes/';
		} elseif ( strpos( $class, 'wpd_meta_box' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/admin/post-types/meta-boxes/';
		} elseif ( strpos( $class, 'wpd_admin' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/admin/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			include_once( $path . $file );
			return;
		}

		// Fallback
		if ( strpos( $class, 'wpd_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			include_once( $path . $file );
			return;
		}
	}

	/**
	 * Define WPD Constants
	 */
	private function define_constants() {
		define( 'WP_DICTIONARY_PLUGIN_FILE', __FILE__ );
		define( 'WP_DICTIONARY_VERSION', $this->version );

		if ( ! defined( 'WP_DICTIONARY_TEMPLATE_PATH' ) ) {
			define( 'WP_DICTIONARY_TEMPLATE_PATH', $this->template_path() );
		}

		if ( ! defined( 'WP_DICTIONARY_DELIMITER' ) ) {
			define( 'WP_DICTIONARY_DELIMITER', '|' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		//include_once( 'includes/wpd-core-functions.php' );
		//include_once( 'includes/class-wpd-install.php' );

		if ( is_admin() ) {
			include_once( 'includes/admin/class-wpd-admin.php' );
		}

		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}

		// Post types
		include_once( 'includes/class-wpd-post-types.php' );						// Registers post types

		// Include abstract classes
		//include_once( 'includes/abstracts/abstract-wpd-custom-post.php' );		// Custom posts

		// Include template hooks in time for themes to remove/modify them
		//include_once( 'includes/wpd-template-hooks.php' );
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		//include_once( 'includes/class-wpd-template-loader.php' );		// Template Loader
		//include_once( 'includes/class-wpd-frontend-scripts.php' );		// Frontend Scripts
		//include_once( 'includes/class-wpd-shortcodes.php' );			// Shortcodes class
	}

	/**
	 * Function used to Init SportsPress Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		//include_once( 'includes/wpd-template-functions.php' );
	}

	/**
	 * Include core widgets
	 */
	public function include_widgets() {
		do_action( 'wp_dictionary_widgets' );
	}

	/**
	 * Init WP Dictionary when WordPress Initialises.
	 */
	public function init() {
		// Before init action
		do_action( 'before_wp_dictionary_init' );

		// Set up localisation
		$this->load_plugin_textdomain();

		// Init action
		do_action( 'wp_dictionary_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-dictionary' );
		
		// Global + Frontend Locale
		load_textdomain( 'wp-dictionary', WP_LANG_DIR . "/wp-dictionary/wp-dictionary-$locale.mo" );
		load_plugin_textdomain( 'wp-dictionary', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/**
	 * Ensure theme and server variable compatibility and setup image sizes.
	 */
	public function setup_environment() {
		add_theme_support( 'post-thumbnails' );
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'WP_DICTIONARY_TEMPLATE_PATH', 'wp-dictionary/' );
	}
}

endif;

/**
 * Returns the main instance of WPD to prevent the need to use globals.
 *
 * @since  0.1
 * @return WP_Dictionary
 */
function WPD() {
	return WP_Dictionary::instance();
}

WPD();
