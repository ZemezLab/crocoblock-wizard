<?php
namespace Crocoblock_Wizard\Modules\Import_Template;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $templates_server = 'http://192.168.9.40/_2019/04_April/travengo/wp-json/croco-site-api/v1/free-templates';

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

		$config['body']        = 'cbw-free-templates';
		$config['wrapper_css'] = 'import-template-page';
		$config['templates']   = $this->get_templates();
		$config['tabs']        = array(
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

		$templates = Plugin::instance()->files_manager->get_json( 'templates.json', DAY_IN_SECONDS );

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

		$templates['free_templates'] = 'import-template/free-templates';
		$templates['template']       = 'import-template/template';
		return $templates;

	}

}
