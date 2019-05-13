<?php
namespace Crocoblock_Wizard\Modules\Export_Skin;

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
		return 'export-skin';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-export-skin',
			CB_WIZARD_URL . 'assets/js/export-skin.js',
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

		$config['title']       = __( 'Configure Skin For Export', 'crocoblock-wizard' );
		$config['body']        = 'cbw-export-skin';
		$config['wrapper_css'] = 'vertical-flex';
		$config['plugins']     = $this->get_plugins_config();

		return $config;

	}

	public function get_plugins_config() {

		$active_plugins = get_option( 'active_plugins' );
		$all_plugins    = get_plugins();
		$result         = array();

		foreach ( $active_plugins as $plugin_file ) {

			if ( ! isset( $all_plugins[ $plugin_file ] ) ) {
				continue;
			}

			$plugin         = array();
			$data           = $all_plugins[ $plugin_file ];
			$plugin['slug'] = pathinfo( $plugin_file, PATHINFO_FILENAME);
			$plugin['name'] = $data['Name'];
			$result[]       = $plugin;
		}

		return $result;
	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['export_skin'] = 'export-skin/main';
		return $templates;

	}

}