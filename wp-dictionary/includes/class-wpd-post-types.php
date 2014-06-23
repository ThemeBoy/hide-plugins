<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class 		WPD_Post_types
 * @version		0.1
 * @package		WP_Dictionary/Classes/
 * @category	Class
 * @author 		ThemeBoy
 */
class WPD_Post_types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	/**
	 * Register WP Dictionary taxonomies.
	 */
	public static function register_taxonomies() {
		do_action( 'wp_dictionary_register_taxonomy' );

		$labels = array(
			'name' => __( 'Parts of Speech', 'wp-dictionary' ),
			'singular_name' => __( 'Part of Speech', 'wp-dictionary' ),
			'all_items' => __( 'All', 'wp-dictionary' ),
			'edit_item' => __( 'Edit Part of Speech', 'wp-dictionary' ),
			'view_item' => __( 'View', 'wp-dictionary' ),
			'update_item' => __( 'Update', 'wp-dictionary' ),
			'add_new_item' => __( 'Add New', 'wp-dictionary' ),
			'new_item_name' => __( 'Name', 'wp-dictionary' ),
			'parent_item' => __( 'Parent', 'wp-dictionary' ),
			'parent_item_colon' => __( 'Parent:', 'wp-dictionary' ),
			'search_items' =>  __( 'Search', 'wp-dictionary' ),
			'not_found' => __( 'No results found.', 'wp-dictionary' ),
		);
		$args = array(
			'label' => __( 'Parts', 'wp-dictionary' ),
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => get_option( 'wp_dictionary_part_slug', 'part' ) ),
		);
		$object_types = apply_filters( 'wp_dictionary_object_types', array( 'dictionary_word' ) );
		register_taxonomy( 'dictionary_part', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'dictionary_part', $object_type );
		endforeach;
	}

	/**
	 * Register core post types
	 */
	public static function register_post_types() {
		do_action( 'wp_dictionary_register_post_type' );

		register_post_type( 'dictionary_word',
			apply_filters( 'wp_dictionary_register_post_type_word',
				array(
					'labels' => array(
						'name' 					=> __( 'Words', 'wp-dictionary' ),
						'singular_name' 		=> __( 'Word', 'wp-dictionary' ),
						'menu_name' 			=> __( 'Dictionary', 'wp-dictionary' ),
						'all_items' 		=> __( 'Words', 'wp-dictionary' ),
						'add_new_item' 			=> __( 'Add New Word', 'wp-dictionary' ),
						'edit_item' 			=> __( 'Edit Word', 'wp-dictionary' ),
						'new_item' 				=> __( 'New', 'wp-dictionary' ),
						'view_item' 			=> __( 'View', 'wp-dictionary' ),
						'search_items' 			=> __( 'Search', 'wp-dictionary' ),
						'not_found' 			=> __( 'No results found.', 'wp-dictionary' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'wp-dictionary' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'wp_dictionary_word_slug', 'dictionary' ) ),
					'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-book-alt',
				)
			)
		);
	}
}

new WPD_Post_types();
