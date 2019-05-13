<?php
namespace Crocoblock_Wizard\Modules\Select_Skin;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'select-skin';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-skins',
			CB_WIZARD_URL . 'assets/js/skins.js',
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

		$config['title']          = __( 'Select skin and start install', 'crocoblock-wizard' );
		$config['cover']          = false;
		$config['body']           = 'cbw-skins';
		$config['wrapper_css']    = 'panel-wide';
		$config['default_back']   = Plugin::instance()->dashboard->page_url( 'install-theme' );
		$config['skins_by_types'] = Plugin::instance()->skins->get_skins_by_types();
		$config['allowed_types']  = Plugin::instance()->skins->get_types();
		$config['upload_hook']    = array(
			'action'  => Plugin::instance()->dashboard->page_slug . '/' . $this->get_slug(),
			'handler' => 'upload_user_skin',
		);
		$config['upload_errors']  = array(
			'limit' => __( 'Only 1 file allowed to upload', 'crocoblock-wizard' ),
			'type'  => __( 'Only .zip files are allowed', 'crocoblock-wizard' ),
		);

		return $config;

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['skins']         = 'skins/main';
		$templates['skin']          = 'skins/skin';
		$templates['skin_uploader'] = 'skins/skin-uploader';

		return $templates;

	}

	public function upload_user_skin() {

		$skin = isset( $_FILES['_skin'] ) ? $_FILES['_skin'] : false;

		if ( ! $skin ) {
			wp_send_json_error( array(
				'message' => __( 'Skin file not found in request', 'crocoblock-wizard' ),
			) );
		}

		$uploader = new Uploader( $skin );

		$result = $uploader->upload();

		if ( ! $result ) {
			wp_send_json_error( array(
				'message' => $uploader->get_error(),
			) );
		}

		$info = array();

		ob_start();
		include $result['settings.json'];
		$settings = ob_get_clean();
		$settings = json_decode( $settings, true );

		if ( empty( $settings ) || ! isset( $settings['name'] ) || ! isset( $settings['slug'] )  || ! isset( $settings['settings'] ) ) {

			$uploader->delete_skin();

			wp_send_json_error( array(
				'message' => __( 'Incorrect settings file format', 'crocoblock-wizard' ),
			) );
		}

		wp_send_json_success( array(
			'name'      => $settings['name'],
			'slug'      => $settings['slug'],
			'demo'      => isset( $settings['demo'] ) ? $settings['demo'] : false,
			'thumbnail' => isset( $settings['thumbnail'] ) ? $settings['thumbnail'] : false,
		) );

	}

	/**
	 * Delete uploaded skin
	 *
	 * @return [type] [description]
	 */
	public function delete_uploaded_skin() {
		$slug = isset( $_REQUEST['slug'] ) ? esc_attr( $_REQUEST['slug'] ) : '';
		Plugin::instance()->files_manager->delete_dir( $slug );
	}

	/**
	 * Prepare passed skin for installation
	 *
	 * @return [type] [description]
	 */
	public function prepare_skin_installation() {

		$is_uploaded = isset( $_REQUEST['is_uploaded'] ) ? $_REQUEST['is_uploaded'] : false;
		$is_uploaded = filter_var( $is_uploaded, FILTER_VALIDATE_BOOLEAN );
		$skin        = ! empty( $_REQUEST['slug'] ) ? esc_attr( $_REQUEST['slug'] ) : false;

		if ( ! $skin ) {
			wp_send_json_error( array(
				'message' => __( 'Skin slug not found in request', 'crocoblock-wizard' ),
			) );
		}

		Plugin::instance()->storage->store( 'install_skin', $skin );

		wp_send_json_success( array(
			'redirect' => add_query_arg(
				array(
					'skin'        => $skin,
					'is_uploaded' => $is_uploaded
				),
				Plugin::instance()->dashboard->page_url( 'install-plugins' )
			)
		) );

	}

}
