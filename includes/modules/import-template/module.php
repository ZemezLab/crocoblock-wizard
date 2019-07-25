<?php
namespace Crocoblock_Wizard\Modules\Import_Template;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $templates_server = 'https://crocoblock.com/wp-json/croco-site-api/v1/free-templates';

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'import-template';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-import-template',
			CB_WIZARD_URL . 'assets/js/template.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$config['body']            = 'cbw-free-templates';
		$config['title']           = __( 'Import Template', 'crocoblock-wizard' );
		$config['wrapper_css']     = 'import-template-page';
		$config['templates']       = $this->get_templates();
		$config['template_button'] = __( 'Import Template', 'crocoblock-wizard' );
		$config['page_button']     = __( 'Create a Page', 'crocoblock-wizard' );
		$config['tabs']            = array(
			'home-pages'    => __( 'Home pages', 'crocoblock-wizard' ),
			'landing-pages' => __( 'Landing pages', 'crocoblock-wizard' ),
			'other-pages'   => __( 'Other pages', 'crocoblock-wizard' ),
		);

		return $config;

	}

	/**
	 * Returns tempaltes list
	 *
	 * @return [type] [description]
	 */
	public function get_templates() {

		$templates = Plugin::instance()->files_manager->get_json( 'templates.json', 3 * DAY_IN_SECONDS );

		if ( ! $templates ) {
			$response  = wp_remote_get( $this->templates_server, array( 'timeout' => 30 ) );
			$body      = wp_remote_retrieve_body( $response );
			$result    = json_decode( $body, true );
			$templates = array();

			if ( ! empty( $result['success'] ) ) {
				$templates = $result['items'];
				Plugin::instance()->files_manager->put_json( 'templates.json', $templates );
			}

		}

		return $templates;

	}

	/**
	 * Add welcome component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['free_templates']  = 'import-template/free-templates';
		$templates['template']        = 'import-template/template';
		$templates['import_template'] = 'import-template/import';
		return $templates;

	}

	/**
	 * AJAX callback for import template
	 *
	 * @return [type] [description]
	 */
	public function import_template() {

		$url     = ! empty( $_REQUEST['url'] ) ? esc_url( $_REQUEST['url'] ) : false;
		$title   = ! empty( $_REQUEST['title'] ) ? esc_attr( $_REQUEST['title'] ) : false;
		$post_id = $this->remote_import( $url, $title, 'elementor_library', 'publish' );

		if ( ! $post_id ) {
			wp_send_json_error( array(
				'message' => __( 'Can’t create template. Please try again.', 'crocoblock-wizard' ),
			) );
		}

		$edit_url = add_query_arg(
			array(
				'post'   => $post_id,
				'action' => 'elementor'
			),
			esc_url( admin_url( 'post.php' ) )
		);

		wp_send_json_success( array(
			'message'      => __( 'Congratulations! The template was successfully imported', 'crocoblock-wizard' ),
			'url'          => $edit_url,
			'button_label' => __( 'Open the Template', 'crocoblock-wizard' ),
		) );

	}

	/**
	 * AJAX callback for import page
	 *
	 * @return [type] [description]
	 */
	public function import_page() {

		$url     = ! empty( $_REQUEST['url'] ) ? esc_url( $_REQUEST['url'] ) : false;
		$title   = ! empty( $_REQUEST['title'] ) ? esc_attr( $_REQUEST['title'] ) : false;
		$post_id = $this->remote_import( $url, $title, 'page', 'draft' );

		if ( ! $post_id ) {
			wp_send_json_error( array(
				'message' => __( 'Can’t create page. Please try again.', 'crocoblock-wizard' ),
			) );
		}

		$edit_url = add_query_arg(
			array(
				'post'   => $post_id,
				'action' => 'elementor'
			),
			esc_url( admin_url( 'post.php' ) )
		);

		wp_send_json_success( array(
			'message'      => __( 'Congratulations! The page was successfully created', 'crocoblock-wizard' ),
			'url'          => $edit_url,
			'button_label' => __( 'Go to the Page', 'crocoblock-wizard' ),
		) );

	}

	/**
	 * Process remote import
	 *
	 * @param  [type] $url       [description]
	 * @param  string $post_type [description]
	 * @return [type]            [description]
	 */
	public function remote_import( $url, $title = false, $post_type = 'elementor_library', $status = 'publish' ) {

		if ( ! $url ) {
			wp_send_json_error( array(
				'message' => __( 'Template URL not found in request', 'crocoblock-wizard' ),
			) );
		}

		$response = wp_remote_get( $url, array( 'tiemout' => 30 ) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array(
				'message' => $response->get_error_message(),
			) );
		}

		$template = wp_remote_retrieve_body( $response );

		if ( empty( $template ) ) {
			wp_send_json_error( array(
				'message' => __( 'Empty reponse recieved from template server', 'crocoblock-wizard' ),
			) );
		}

		$data = json_decode( $template, true );

		if ( empty( $data ) ) {
			wp_send_json_error( array(
				'message' => __( 'Can’t parse template data from response', 'crocoblock-wizard' ),
			) );
		}

		$content = isset( $data['content'] ) ? $data['content'] : false;
		$type    = isset( $data['type'] ) ? $data['type'] : 'page';

		if ( ! $title ) {
			$title = isset( $data['title'] ) ? $data['title'] : false;
		}

		if ( ! $content ) {
			wp_send_json_error( array(
				'message' => __( 'Incorrect response format. Template content not found in response', 'crocoblock-wizard' ),
			) );
		}

		if ( class_exists( '\\Elementor\\Plugin' ) ) {

			$prepared_content = \Elementor\Plugin::$instance->db->iterate_data(
				$content, function( $element_data ) {

					$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance(
						$element_data
					);

					// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
					if ( ! $element ) {
						return null;
					}

					return $this->process_element_import_content( $element );
				}
			);

		} else {
			$prepared_content = $content;
		}

		return wp_insert_post( array(
			'post_type'   => $post_type,
			'post_title'  => $title,
			'post_status' => $status,
			'meta_input'  => array(
				'_elementor_data'          => $prepared_content,
				'_elementor_edit_mode'     => 'builder',
				'_elementor_template_type' => $type,
			),
		) );

	}

	/**
	 * Process element import content
	 *
	 * @return [type] [description]
	 */
	public function process_element_import_content( $element ) {

		$element_data = $element->get_data();

		if ( method_exists( $element, 'on_import' ) ) {
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
