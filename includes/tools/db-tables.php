<?php
namespace Crocoblock_Wizard\Tools;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define DB tables class
 */
class DB_Tables {

	/**
	 * Check if passed table is exists in database
	 *
	 * @param  string  $table Table name.
	 * @return boolean
	 */
	public static function is_db_table_exists( $table = '' ) {

		global $wpdb;

		$table_name = $wpdb->prefix . $table;

		return ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name );
	}

	/**
	 * Check if passed table is exists in database
	 *
	 * @param  string  $table Table name.
	 * @return boolean
	 */
	public static function clear_content( $table = '' ) {

		if ( ! current_user_can( 'delete_users' ) ) {
			return;
		}

		$attachments = get_posts( array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
		) );

		if ( ! empty( $attachments ) ) {
			foreach ( $attachments as $attachment ) {
				wp_delete_attachment( $attachment->ID, true );
			}
		}

		global $wpdb;

		$tables_to_clear = array(
			$wpdb->commentmeta,
			$wpdb->comments,
			$wpdb->links,
			$wpdb->postmeta,
			$wpdb->posts,
			$wpdb->termmeta,
			$wpdb->terms,
			$wpdb->term_relationships,
			$wpdb->term_taxonomy,
		);

		foreach ( $tables_to_clear as $table ) {
			$wpdb->query( "TRUNCATE {$table};" );
		}

		$options = apply_filters( 'crocoblock-wizard/tools/db-tables/clear-options-on-remove', array(
			'sidebars_widgets',
		) );

		foreach ( $options as $option ) {
			delete_option( $option );
		}

		/**
		 * Clear widgets data
		 */
		$widgets = $wpdb->get_results(
			"SELECT * FROM $wpdb->options WHERE `option_name` LIKE 'widget_%'"
		);

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				delete_option( $widget->option_name );
			}
		}
	}

}