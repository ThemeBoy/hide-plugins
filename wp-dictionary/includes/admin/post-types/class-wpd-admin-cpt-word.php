<?php
/**
 * Admin functions for the dictionary post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	WP_Dictionary/Admin/Post Types
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPD_Admin_CPT_Word' ) ) :

/**
 * WPD_Admin_CPT_Word Class
 */
class WPD_Admin_CPT_Word {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'dictionary_word';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin Columns
		add_filter( 'manage_edit-dictionary_word_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_dictionary_word_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
	}

	/**
	 * Change title boxes in admin.
	 * @param  string $text
	 * @param  object $post
	 * @return string
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'dictionary_word' )
			return __( 'Word', 'wp-dictionary' );

		return $text;
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name', 'wp-dictionary' ),
			'dictionary_part' => __( 'Parts of Speech', 'wp-dictionary' ),
		);
		return apply_filters( 'wp_dictionary_word_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'dictionary_part':
				echo get_the_terms ( $post_id, 'dictionary_part' ) ? the_terms( $post_id, 'dictionary_part' ) : '&mdash;';
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'dictionary_word' )
	    	return;

		$selected = isset( $_REQUEST['dictionary_part'] ) ? $_REQUEST['dictionary_part'] : null;
		$args = array(
			'show_option_all' =>  __( 'All parts of speech', 'wp-dictionary' ),
			'taxonomy' => 'dictionary_part',
			'name' => 'dictionary_part',
			'selected' => $selected
		);
		$defaults = array(
			'show_option_all' => false,
			'show_option_none' => false,
			'taxonomy' => null,
			'name' => null,
			'id' => null,
			'selected' => null,
			'hide_empty' => false,
			'values' => 'slug',
		    'class' => null,
		    'property' => null,
		    'placeholder' => null,
		    'chosen' => false,
		);
		$args = array_merge( $defaults, $args ); 
		$terms = get_terms( $args['taxonomy'], $args );
		$name = ( $args['name'] ) ? $args['name'] : $args['taxonomy'];
		$id = ( $args['id'] ) ? $args['id'] : $name;

		unset( $args['name'] );
		unset( $args['id'] );

		$class = $args['class'];
		unset( $args['class'] );

		$property = $args['property'];
		unset( $args['property'] );

		$placeholder = $args['placeholder'];
		unset( $args['placeholder'] );

		$selected = $args['selected'];
		unset( $args['selected'] );

		$chosen = $args['chosen'];
		unset( $args['chosen'] );

		if ( $terms ):
			printf( '<select name="%s" class="postform %s" %s>', $name, $class . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . $placeholder . '" ' : '' ) . $property );

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['show_option_all'] ):
					printf( '<option value="0">%s</option>', $args['show_option_all'] );
				endif;
				if ( $args['show_option_none'] ):
					printf( '<option value="-1">%s</option>', $args['show_option_none'] );
				endif;
			endif;

			foreach ( $terms as $term ):

				if ( $args['values'] == 'term_id' ):
					$this_value = $term->term_id;
				else:
					$this_value = $term->slug;
				endif;

				if ( strpos( $property, 'multiple' ) !== false ):
					$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
				else:
					$selected_prop = selected( $this_value, $selected, false );
				endif;

				printf( '<option value="%s" %s>%s</option>', $this_value, $selected_prop, $term->name );
			endforeach;
			print( '</select>' );
			return true;
		else:
			return false;
		endif;
	}
}

endif;

return new WPD_Admin_CPT_Word();
