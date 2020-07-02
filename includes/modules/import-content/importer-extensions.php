<?php
namespace Crocoblock_Wizard\Modules\Import_Content;

use Crocoblock_Wizard\Tools;

use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Jet_Data_Importer_Extensions class
 */
class Importer_Extensions {

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		// Prevent from errors triggering while MotoPress Booking posts importing (loving it)
		add_filter( 'crocoblock-wizard/import/skip-post', array( $this, 'prevent_import_errors' ), 10, 2 );

		// After import actions
		add_action( 'crocoblock-wizard/import/finish', array( $this, 'clear_fonts_cache' ) );
		add_action( 'crocoblock-wizard/import/finish', array( $this, 'reindex_filters' ) );
		add_action( 'crocoblock-wizard/import/finish', array( $this, 'update_booking_table' ) );
		add_action( 'crocoblock-wizard/import/finish', array( $this, 'update_appointment_table' ) );

		add_action( 'crocoblock-wizard/tools/db-tables/content-cleared', array( $this, 'drop_bookings_table' ) );
		add_action( 'crocoblock-wizard/tools/db-tables/content-cleared', array( $this, 'drop_appointments_table' ) );

		add_action( 'crocoblock-wizard/import/before-options-processing', array( $this, 'set_container_width' ) );
		add_action( 'crocoblock-wizard/import/after-options-processing', array( $this, 'set_required_options' ) );

		add_action( 'crocoblock-wizard/import/after-import-tables', array( $this, 'clear_woo_transients' ) );
		add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

		add_filter( 'crocoblock-wizard/import/create-missing-table/jet_apartment_bookings', array( $this, 'create_bookings_table' ) );
		add_filter( 'crocoblock-wizard/import/create-missing-table/jet_appointments', array( $this, 'create_appointments_table' ) );

	}

	/**
	 * Reindex Smrt Filters after import
	 *
	 * @return [type] [description]
	 */
	public function reindex_filters() {

		if ( ! function_exists( 'jet_smart_filters' ) ) {
			return;
		}

		jet_smart_filters()->indexer->index_filters();

	}

	/**
	 * Create appointments table
	 * @return [type] [description]
	 */
	public function create_bookings_table( $result = false ) {

		if ( ! function_exists( 'jet_abaf' ) ) {
			return $result;
		}

		jet_abaf()->db->install_table();

		$result = true;

		return $result;

	}

	public function create_appointments_table( $result ) {

		if ( ! function_exists( 'jet_apb' ) ) {
			return $result;
		}

		jet_apb()->db->appointments->install_table();
		jet_apb()->db->excluded_dates->install_table();

		$result = true;

		return $result;

	}

	public function update_booking_table() {

		if ( ! function_exists( 'jet_abaf' ) ) {
			return;
		}

		if ( ! jet_abaf()->db->is_bookings_table_exists() ) {
			return;
		}

		$db_columns = jet_abaf()->settings->get( 'additional_columns' );

		if ( empty( $db_columns ) ) {
			return;
		}

		foreach ( $db_columns as $column ) {

			$column_name = jet_abaf()->settings->sanitize_column_name( $column['column'] );

			if ( ! jet_abaf()->db->column_exists( $column_name ) ) {
				jet_abaf()->db->insert_table_columns( array( $column_name ) );
			}
		}

	}

	public function update_appointment_table() {

		if ( ! function_exists( 'jet_apb' ) ) {
			return;
		}

		if ( ! jet_apb()->db->appointments->is_table_exists() ) {
			return;
		}

		$db_columns = jet_apb()->settings->get( 'db_columns' );

		if ( empty( $db_columns ) ) {
			return;
		}

		foreach ( $db_columns as $column ) {
			$column = jet_apb()->settings->sanitize_column( $column );
			if ( ! jet_apb()->db->appointments->column_exists( $column ) ) {
				jet_apb()->db->appointments->insert_table_columns( array( $column ) );
			}
		}

	}

	public function drop_bookings_table() {

		if ( ! function_exists( 'jet_abaf' ) ) {
			return;
		}

		if ( jet_abaf()->db->is_bookings_table_exists() ) {
			$table = jet_abaf()->db::bookings_table();
			jet_abaf()->db::wpdb()->query( "DROP TABLE $table;" );
		}

	}

	public function drop_appointments_table() {

		if ( ! function_exists( 'jet_apb' ) ) {
			return;
		}

		if ( jet_apb()->db->appointments->is_table_exists() ) {
			$table = jet_apb()->db->appointments->table();
			jet_apb()->db->appointments->wpdb()->query( "DROP TABLE $table;" );
		}

	}

	/**
	 * Delete WooCommerce-related transients after new tables are imported
	 *
	 * @return void
	 */
	public function clear_woo_transients() {
		delete_transient( 'wc_attribute_taxonomies' );
	}

	/**
	 * Preset elemntor container width if it was not passed in XML
	 */
	public function set_container_width( $data ) {

		if ( ! isset( $data['elementor_container_width'] ) ) {
			update_option( 'elementor_container_width', 1200 );
		}

	}

	/**
	 * Set required Kava Extra and Jet Elements options
	 */
	public function set_required_options() {

		if ( class_exists( 'Kava_Extra' ) ) {

			$options = get_option( 'kava-extra-settings' );

			if ( ! $options ) {
				update_option( 'kava-extra-settings', array(
					'nucleo-mini-package' => 'true',
				) );
			}

			unset( $options );

		}

		if ( class_exists( 'Jet_Elements' ) ) {

			$options = get_option( 'jet-elements-settings' );

			if ( empty( $options ) ) {
				$options = array();
			}

			if ( empty( $options['api_key'] ) ) {
				$options['api_key'] = 'AIzaSyDlhgz2x94h0UZb7kZXOBjwAtszoCRtDLM';
			}

			update_option( 'jet-elements-settings', $options );

		}

	}

	/**
	 * Ckear Google fonts cache.
	 *
	 * @return void
	 */
	public function clear_fonts_cache() {
		delete_transient( 'cherry_google_fonts_url' );
		delete_transient( 'cx_google_fonts_url_kava' );
	}

	/**
	 * Prevent PHP errors on import.
	 *
	 * @param  bool   $skip Default skip value.
	 * @param  array  $data Plugin data.
	 * @return bool
	 */
	public function prevent_import_errors( $skip, $data ) {

		if ( isset( $data['post_type'] ) && 'mphb_booking' === $data['post_type'] ) {
			return true;
		}

		return $skip;
	}

}
