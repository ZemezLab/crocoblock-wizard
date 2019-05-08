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

		$skin         = isset( $_GET['skin'] ) ? $_GET['skin'] : false;
		$is_uploaded  = isset( $_GET['is_uploaded'] ) ? $_GET['is_uploaded'] : false;
		$skin_plugins = Plugin::instance()->skins->get_skin_plugins( $skin, $is_uploaded );
		$all_plugins  = Plugin::instance()->settings->get_all_plugins();

		$config['title']         = __( 'Configure plugins', 'crocoblock-wizard' );
		$config['install_title'] = __( 'Install Plugins', 'crocoblock-wizard' );
		$config['cover']         = CB_WIZARD_URL . 'assets/img/cover-3.png';
		$config['body']          = 'cbw-plugins';
		$config['wrapper_css']   = 'vertical-flex';
		$config['is_uploaded']   = $is_uploaded;
		$config['skin']          = $skin;
		$config['rec_plugins']   = $skin_plugins;
		$config['extra_plugins'] = $this->get_rest_of_plugins( $skin_plugins, $all_plugins );
		$config['all_plugins']   = $all_plugins;
		$config['prev_step']     = Plugin::instance()->dashboard->page_url( 'select-skin' );

		return $config;

	}

	/**
	 * Returns rest of registered plugins
	 *
	 * @return [type] [description]
	 */
	public function get_rest_of_plugins( $skin_plugins, $all_plugins ) {

		array_walk( $all_plugins, function( &$plugin, $slug ) use ( $skin_plugins ) {
			if ( in_array( $slug, $skin_plugins ) ) {
				$plugin = false;
			}
		} );

		return array_keys( array_filter( $all_plugins ) );

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['plugins']         = 'install-plugins/main';
		$templates['select_plugins']  = 'install-plugins/select';
		$templates['install_plugins'] = 'install-plugins/install';
		return $templates;

	}

}