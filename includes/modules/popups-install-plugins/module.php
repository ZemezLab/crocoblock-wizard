<?php
namespace Crocoblock_Wizard\Modules\Popups_Install_Plugins;

use Crocoblock_Wizard\Plugin;
use Crocoblock_Wizard\Modules\Install_Plugins\Installer;
use Crocoblock_Wizard\Base\Module as Module_Base;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $plugins = false;
	private $api     = null;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'popups-install-plugins';
	}

	/**
	 * Init plugin actions
	 *
	 * @return [type] [description]
	 */
	public function init() {
		if ( $this->is_module_page() ) {
			$this->check_missed_plugins();
		}
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'croco-popups-plugins',
			CB_WIZARD_URL . 'assets/js/popups-plugins.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * Check missed plugins
	 *
	 * @return [type] [description]
	 */
	public function check_missed_plugins() {

		$plugins        = $this->get_plugins();
		$missed_plugins = array();

		foreach ( $plugins as $slug => $plugin ) {

			if ( empty( $plugin['function_name'] ) ) {
				continue;
			}

			if ( ! function_exists( $plugin['function_name'] ) ) {

				$missed_plugins[] = array(
					'value' => $slug,
					'label' => $plugin['name'],
				);

			}

		}

		if ( empty( $missed_plugins ) ) {
			wp_redirect( Plugin::instance()->dashboard->page_url( 'popups-import-popup' ) );
			die();
		}
	}

	/**
	 * Get plugins
	 *
	 * @return [type] [description]
	 */
	public function get_plugins() {

		if ( false === $this->plugins ) {
			$this->api     = new API();
			$this->plugins = $this->api->get_plugins();
		}

		return $this->plugins;
	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		if ( ! $this->api ) {
			$this->api = new API();
		}

		$plugins        = $this->get_plugins();
		$missed_plugins = array();
		$plugin_names   = array();

		foreach ( $plugins as $slug => $plugin ) {

			if ( empty( $plugin['function_name'] ) ) {
				continue;
			}

			if ( ! function_exists( $plugin['function_name'] ) ) {

				$missed_plugins[] = array(
					'value' => $slug,
					'label' => $plugin['name'],
				);

				$plugin_names[ $slug ] = $plugin['name'];

			}

		}

		// For all inlusive memebers plugins is alwyas included
		$plugins_included = true;

		if ( $plugins_included ) {
			$body = 'cbw-plugins';
		} else {
			$body = 'cbw-plugins-info';
		}

		$config['body']         = $body;
		$config['wrapper_css']  = 'plugins-page';
		$config['plugin_names'] = $plugin_names;
		$config['plugins']      = $missed_plugins;
		$config['prev_step']    = Plugin::instance()->dashboard->page_url( 'popups-license' );
		$config['next_step']    = Plugin::instance()->dashboard->page_url( 'popups-import-popup' );

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

		$templates['plugins']         = 'popups-install-plugins/main';
		$templates['select_plugins']  = 'popups-install-plugins/select';
		$templates['install_plugins'] = 'popups-install-plugins/install';
		$templates['plugins_info']    = 'popups-install-plugins/plugins-info';

		return $templates;

	}

	/**
	 * Install plugin
	 *
	 * @return void
	 */
	public function install_plugin() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'You don\'t have permissions to do this', 'croco-ik' ) )
			);
		}

		$plugin      = isset( $_REQUEST['plugin'] ) ? esc_attr( $_REQUEST['plugin'] ) : false;
		$skin        = isset( $_REQUEST['skin'] ) ? esc_attr( $_REQUEST['skin'] ) : false;
		$is_uploaded = isset( $_REQUEST['is_uploaded'] ) ? esc_attr( $_REQUEST['is_uploaded'] ) : false;
		$installer   = new Installer( $plugin );
		$installed   = $installer->do_plugin_install();

		if ( ! $installed ) {
			wp_send_json_error( array( 'message' => $installer->get_log() ) );
		} else {
			wp_send_json_success( array( 'message' => $installer->get_log() ) );
		}

	}

}