<?php
/**
 * WP Dictionary Meta Boxes
 *
 * Sets up the write panels used by custom post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	WP_Dictionary/Admin/Meta Boxes
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPD_Admin_Meta_Boxes
 */
class WPD_Admin_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
	}

	/**
	 * Add SP Meta boxes
	 */
	public function add_meta_boxes() {
		// Word
		add_meta_box( 'wpd_editordiv', __( 'Definitions', 'wp-dictionary' ), 'wp_editor', 'dictionary_word', 'normal', 'high' );
	}

	/**
	 * Check if we're saving, then trigger an action based on the post type
	 *
	 * @param  int $post_id
	 * @param  object $post
	 */
	public function save_meta_boxes( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( empty( $_POST['wp_dictionary_meta_nonce'] ) || ! wp_verify_nonce( $_POST['wp_dictionary_meta_nonce'], 'wp_dictionary_save_data' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id  )) return;
		if ( ! in_array( $post->post_type, array( 'dictionary_word' ) ) ) return;

		do_action( 'wp_dictionary_process_' . $post->post_type . '_meta', $post_id, $post );
	}

}

new WPD_Admin_Meta_Boxes();
