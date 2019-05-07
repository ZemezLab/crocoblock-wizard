<?php
namespace Crocoblock_Wizard\Modules\License;

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
		return 'license';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-license',
			CB_WIZARD_URL . 'assets/js/license.js',
			array( 'cx-vue-ui', 'crocoblock-wizard-mixins' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * Verify license key
	 *
	 * @return [type] [description]
	 */
	public function verify_license() {

		$license = isset( $_REQUEST['license_key'] ) ? esc_attr( $_REQUEST['license_key'] ) : false;

		if ( ! $license ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Please fill in License field and try again', 'crocoblock-wizard' ),
			) );
		}

		$license_api  = new API();
		$install_data = $license_api->activate_license( $license );

		if ( ! $install_data ) {
			wp_send_json_error( array(
				'message' => $license_api->get_error(),
			) );
		} else {

			Plugin::instance()->storage->store( 'theme_data', $install_data );

			wp_send_json_success( array(
				'message'     => esc_html__( 'Your license is activated. Downloading and installing theme...', 'crocoblock-wizard' ),
				'doNext'      => true,
				'nextRequest' => array(
					'action'  => Plugin::instance()->dashboard->page_slug . '/install-theme',
					'handler' => 'install_parent',
				),
			) );
		}

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$config['title']       = __( 'Please, enter your license key to start installation', 'crocoblock-wizard' );
		$config['cover']       = CB_WIZARD_URL . 'assets/img/cover-1.png';
		$config['body']        = 'cbw-license';
		$config['wrapper_css'] = 'vertical-flex';

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

		$templates['license'] = 'license/main';
		return $templates;

	}

}