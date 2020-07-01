<?php
namespace Crocoblock_Wizard\Modules\Popups_Import_Popup;

/**
 * Main importer class
 */
class JSON_Importer {

	private $url;
	private $data;
	private $status;
	private $log = array();

	/**
	 * Importer constructor
	 *
	 * @param [type] $url [description]
	 */
	public function __construct( $url ) {

		$this->url = $url;
		$this->get_remote_data();

	}

	/**
	 * Retrieve data from remote url
	 * @return [type] [description]
	 */
	public function get_remote_data() {

		$response = wp_remote_get( $this->url, array(
			'timeout'   => 60,
			'sslverify' => false
		) );

		if ( is_wp_error( $response ) ) {
			$this->status = $response;
			return;
		}

		$this->status = true;
		$body         = wp_remote_retrieve_body( $response );
		$this->data   = json_decode( $body, true );

	}

	/**
	 * Process import
	 *
	 * @return [type] [description]
	 */
	public function import() {

		if ( empty( $this->data ) || ! is_array( $this->data ) ) {
			return false;
		}

		$templates = ! empty( $this->data['templates'] ) ? $this->data['templates'] : array();
		$popups    = ! empty( $this->data['popups'] ) ? $this->data['popups'] : array();

		$this->import_items( $templates, 'templates' );
		$this->import_items( $popups, 'popups', function( $content ) {

			$settings = implode( '|', array(
				'front_side_template_id',
				'back_side_template_id',
			) );

			$regex = "/(" . $settings . ")[\'\"]:[\'\"](\d+)/";

			$content = preg_replace_callback(
				$regex, function( $matches ) {

					if ( empty( $matches[2] ) ) {
						return $matches[0];
					}

					$log    = ! empty( $this->log['templates'] ) ? $this->log['templates'] : array();
					$new_id = ! empty( $log[ $matches[2] ] ) ? $log[ $matches[2] ] : false;

					if ( ! $new_id ) {
						return $matches[0];
					} else {
						return str_replace( $matches[2], $new_id, $matches[0] );
					}

				},
				$content
			);

			return $content;

		} );

		return true;

	}

	/**
	 * Returns fill log or log group
	 *
	 * @param  [type] $group [description]
	 * @return [type]        [description]
	 */
	public function get_log( $group = null ) {

		if ( ! $group ) {
			return $this->log;
		} else {
			return isset( $this->log[ $group ] ) ? $this->log[ $group ] : false;
		}

	}

	/**
	 * Import items
	 *
	 * @param  array  $items [description]
	 * @param  string $group [description]
	 * @return [type]        [description]
	 */
	public function import_items( $items = array(), $group = 'global', $remap = false ) {

		if ( empty( $items ) ) {
			return;
		}

		if ( empty( $this->log[ $group ] ) ) {
			$this->log[ $group ] = array();
		}

		foreach ( $items as $item ) {

			$item['post_status'] = 'publish';
			$content = ! empty( $item['meta_input']['_elementor_data'] ) ? $item['meta_input']['_elementor_data'] : '';
			$old_post_id = isset( $item['ID'] ) ? $item['ID'] : false;

			if ( $old_post_id ) {
				unset( $item['ID'] );
			}

			if ( ! empty( $item['meta_input']['_elementor_page_settings']['jet_popup_use_ajax'] ) ) {
				$item['meta_input']['_elementor_page_settings']['jet_popup_use_ajax'] = '';
			}

			$new_post_id = wp_insert_post( $item );

			if ( ! $new_post_id ) {
				continue;
			}

			$this->log[ $group ][ $old_post_id ] = $new_post_id;

			if ( $content && class_exists( '\Elementor\Plugin' ) ) {

				if ( is_callable( $remap ) ) {
					$content = call_user_func( $remap, $content );
				}

				$content  = json_decode( $content, true );
				$content  = $this->process_import_content( $content );
				$post_id  = $new_post_id;
				$document = \Elementor\Plugin::$instance->documents->get( $post_id );

				if ( $document ) {
					$content = $document->get_elements_raw_data( $content, true );
				}

				update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $content ) ) );

			}

		}

	}

	/**
	 * Process content for export/import.
	 *
	 * @param array  $content A set of elements.
	 *
	 * @return mixed Processed content data.
	 */
	protected function process_import_content( $content ) {
		return \Elementor\Plugin::$instance->db->iterate_data(
			$content,
			function( $element_data ) {

				$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

				// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
				if ( ! $element ) {
					return null;
				}

				return $this->process_element_import_content( $element );
			}
		);
	}

	/**
	 * Process single element content for export/import.
	 *
	 * @param Controls_Stack $element
	 *
	 * @return array Processed element data.
	 */
	protected function process_element_import_content( $element ) {

		$element_data = $element->get_data();

		if ( method_exists( $element, 'on_import' ) ) {
			// TODO: Use the internal element data without parameters.
			$element_data = $element->on_import( $element_data );
		}

		foreach ( $element->get_controls() as $control ) {

			$control_class = \Elementor\Plugin::$instance->controls_manager->get_control( $control['type'] );

			// If the control isn't exist, like a plugin that creates the control but deactivated.
			if ( ! $control_class ) {
				return $element_data;
			}

			if ( method_exists( $control_class, 'on_import' ) ) {
				$element_data['settings'][ $control['name'] ] = $control_class->on_import(
					$element->get_settings( $control['name'] ),
					$control
				);
			}

		}

		return $element_data;

	}

}
