<?php
namespace Crocoblock_Wizard\Tools;

use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Main exporter class
 */
class WXR_Exporter {

	/**
	 * Options array to export
	 *
	 * @var array
	 */
	public $export_options = null;

	/**
	 * Instance specific options to export
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Instance specific custom tables to export
	 *
	 * @var array
	 */
	private $tables = array();

	/**
	 * Instance specific path to export file into
	 *
	 * @var string
	 */
	private $to_dir = array();

	/**
	 * Constructor for the class
	 */
	function __construct( $options = array(), $tables = array(), $to_dir = null ) {

		if ( ! class_exists( '\\PclZip' ) ) {
			include_once( ABSPATH . '/wp-admin/includes/class-pclzip.php' );
		}

		if ( ! function_exists( 'export_wp' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/export.php' );
		}

		$this->options = $options;
		$this->tables  = $tables;
		$this->to_dir  = $to_dir;

	}

	/**
	 * Get array of options to export with content
	 *
	 * @return void
	 */
	public function get_options_to_export() {

		if ( null === $this->export_options ) {

			$theme = get_option( 'stylesheet' );

			$default_options = apply_filters( 'crocoblock-wizard/export/options-to-export', array(
				'blogname',
				'blogdescription',
				'users_can_register',
				'posts_per_page',
				'date_format',
				'time_format',
				'thumbnail_size_w',
				'thumbnail_size_h',
				'thumbnail_crop',
				'medium_size_w',
				'medium_size_h',
				'large_size_w',
				'large_size_h',
				'theme_mods_' . $theme,
				'show_on_front',
				'page_on_front',
				'page_for_posts',
				'permalink_structure',
				$theme . '_sidebars',
				$theme . '_sidbars',
				'jet_site_conditions',
				'elementor_container_width',
				'jet-elements-settings',
			) );

			$user_options = Plugin::instance()->settings->get( array( 'export', 'options' ) );

			if ( ! $user_options || ! is_array( $user_options ) ) {
				$user_options = array();
			}

			$this->export_options = array_unique( array_merge( $default_options, $user_options, $this->options ) );

		}

		return $this->export_options;

	}

	/**
	 * Process XML export
	 *
	 * @return string
	 */
	public function do_export( $into_file = true ) {

		ob_start();

		ini_set( 'max_execution_time', -1 );
		set_time_limit( 0 );

		$use_custom_export = apply_filters( 'crocoblock-wizard/export/use-custom-export', false );

		if ( $use_custom_export && function_exists( $use_custom_export ) ) {
			call_user_func( $use_custom_export );
		} else {
			export_wp();
		}

		$xml = ob_get_clean();
		$xml = $this->add_extra_data( $xml );

		if ( true === $into_file ) {

			$filename = $this->get_filename();

			if ( $this->to_dir ) {
				$xml_dir = trailingslashit( $this->to_dir ) . $filename;
			} else {
				$upload_dir      = wp_upload_dir();
				$upload_base_dir = $upload_dir['basedir'];
				$xml_dir         = $upload_base_dir . '/' . $filename;
			}

			file_put_contents( $xml_dir, $xml );

			return $xml_dir;

		} else {
			return $xml;
		}

	}

	/**
	 * Returns filename for exported sample data
	 *
	 * @return void
	 */
	public function get_filename() {

		return apply_filters(
			'crocoblock-wizard/export/filename',
			'sample-data.xml'
		);

	}

	/**
	 * Add options and widgets to XML
	 *
	 * @param  string $xml Exported XML.
	 * @return string
	 */
	private function add_extra_data( $xml ) {

		ini_set( 'max_execution_time', -1 );
		ini_set( 'memory_limit', -1 );
		set_time_limit( 0 );

		$xml = str_replace(
			"</wp:base_blog_url>",
			"</wp:base_blog_url>\r\n" . $this->get_options() . $this->get_widgets() . $this->get_tables(),
			$xml
		);
		return $xml;
	}

	/**
	 * Get options list in XML format.
	 *
	 * @return string
	 */
	public function get_options() {

		$options        = '';
		$format         = "\t\t<wp:%1\$s>%2\$s</wp:%1\$s>\r\n";
		$export_options = $this->get_options_to_export();

		foreach ( $export_options as $option ) {

			$value = get_option( $option );

			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}

			if ( ! empty( $option ) ) {
				$value   = wxr_cdata( $value );
				$options .= "\t\t<wp:{$option}>{$value}</wp:{$option}>\r\n";
			}

		}

		return "\t<wp:options>\r\n" . $options . "\t</wp:options>\r\n";

	}

	/**
	 * Get tables to export
	 *
	 * @return string
	 */
	public function get_tables() {

		$user_tables     = Plugin::instance()->settings->get( array( 'export', 'tables' ) );
		$instance_tables = array();

		if ( ! is_array( $user_tables ) ) {
			$user_tables = array();
		}

		if ( class_exists( 'WooCommerce' ) && ! in_array( 'woocommerce_attribute_taxonomies', $user_tables ) ) {
			$user_tables[] = 'woocommerce_attribute_taxonomies';
		}

		global $wpdb;

		if ( ! empty( $this->tables ) ) {
			$instance_tables = array_map( function( $table_name ) use ( $wpdb ) {

				if ( false === strpos( $table_name, $wpdb->prefix ) ) {
					$table_name = $wpdb->prefix . $table_name;
				}

				return $table_name;

			}, $this->tables );
		}

		if ( ! empty( $instance_tables ) ) {
			$user_tables = array_unique( array_merge( $user_tables, $instance_tables ) );
		}

		$user_tables = apply_filters( 'crocoblock-wizard/export/tables-to-export', $user_tables );

		if ( empty( $user_tables ) ) {
			return;
		}

		$result = '';

		foreach ( $user_tables as $table ) {

			if ( ! DB_Tables::is_db_table_exists( $table ) ) {
				continue;
			}

			$name = esc_attr( $wpdb->prefix . $table );
			$data = $wpdb->get_results( "SELECT * FROM $name WHERE 1", ARRAY_A );

			if ( empty( $data ) ) {
				continue;
			}

			$data = maybe_serialize( $data );

			$result .= "\t\t<" . $table . ">" . wxr_cdata( $data ) . "</" . $table . ">\r\n";
		}

		if ( empty( $result ) ) {
			return;
		}

		return "\t<wp:user_tables>\r\n" . $result . "\r\n\t</wp:user_tables>\r\n";
	}

	/**
	 * Get widgets data to export
	 *
	 * @return string
	 */
	private function get_widgets() {

		// Get all available widgets site supports
		$available_widgets = Widgets::available_widgets();

		// Get all widget instances for each widget
		$widget_instances = array();

		foreach ( $available_widgets as $widget_data ) {

			// Get all instances for this ID base
			$instances = get_option( 'widget_' . $widget_data['id_base'] );

			// Have instances
			if ( ! empty( $instances ) ) {

				// Loop instances
				foreach ( $instances as $instance_id => $instance_data ) {

					// Key is ID (not _multiwidget)
					if ( is_numeric( $instance_id ) ) {
						$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
						$widget_instances[ $unique_instance_id ] = $instance_data;
					}

				}

			}

		}

		// Gather sidebars with their widget instances
		$sidebars_widgets = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
		$sidebars_widget_instances = array();
		foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

			// Skip inactive widgets
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Skip if no data or not an array (array_version)
			if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
				continue;
			}

			// Loop widget IDs for this sidebar
			foreach ( $widget_ids as $widget_id ) {

				// Is there an instance for this widget ID?
				if ( isset( $widget_instances[ $widget_id ] ) ) {

					// Add to array
					$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];

				}

			}

		}

		// Filter pre-encoded data
		$data = apply_filters( 'crocoblock-wizard/export/pre-get-widgets', $sidebars_widget_instances );

		// Encode the data for file contents
		$encoded_data = json_encode( $data );
		$encoded_data = apply_filters( 'crocoblock-wizard/export/get-widgets', $encoded_data );

		// Return contents
		return "\t<wp:widgets_data>" . wxr_cdata( $encoded_data ) . "</wp:widgets_data>\r\n";

	}

}
