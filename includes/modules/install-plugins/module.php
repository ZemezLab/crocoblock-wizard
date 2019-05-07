<?php
namespace Crocoblock_Wizard\Modules\Install_Plugins;

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
		return 'install-plugins';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-plugins',
			CB_WIZARD_URL . 'assets/js/plugins.js',
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

		$skin        = isset( $_GET['skin'] ) ? $_GET['skin'] : false;
		$is_uploaded = isset( $_GET['is_uploaded'] ) ? $_GET['is_uploaded'] : false;

		$config['title']         = __( 'Configure plugins', 'crocoblock-wizard' );
		$config['cover']         = CB_WIZARD_URL . 'assets/img/cover-3.png';
		$config['body']          = 'cbw-plugins';
		$config['wrapper_css']   = 'vertical-flex';
		$config['is_uploaded']   = $is_uploaded;
		$config['skin']          = $skin;
		$config['rec_plugins']   = Plugin::instance()->skins->get_skin_plugins( $skin, $is_uploaded );
		$config['extra_plugins'] = Plugin::instance()->skins->get_skin_plugins( $skin, $is_uploaded );

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

		$templates['plugins']        = 'install-plugins/main';
		$templates['select_plugins'] = 'install-plugins/select';
		return $templates;

	}

}