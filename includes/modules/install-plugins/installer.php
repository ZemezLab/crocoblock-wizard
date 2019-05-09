<?php
namespace Crocoblock_Wizard\Modules\Install_Plugins;

use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Installer {

	private $plugin;
	private $slug;
	private $log;
	private $installer;

	public function __construct( $slug ) {

		$plugins = Plugin::instance()->settings->get_all_plugins();

		if ( ! isset( $plugins[ $slug ] ) ) {

			$this->plugin = array(
				'source' => 'wordpress',
			);

		} else {
			$this->plugin = $plugins[ $slug ];
		}

		$this->slug   = $slug;

	}

	/**
	 * Process plugin installation.
	 *
	 * @param  array $plugin   Plugin data.
	 * @param  bool  $activate Perform plugin activation or not.
	 * @return bool
	 */
	public function do_plugin_install( $activate = true ) {

		if ( ! $this->plugin || ! $this->slug ) {
			$this->log = __( 'Plugin is not registered', 'crocoblock-wizard' );
			return false;
		}

		$plugin         = $this->plugin;
		$plugin['slug'] = $this->slug;

		/**
		 * Hook fires before plugin installation.
		 *
		 * @param array $plugin Plugin data array.
		 */
		do_action( 'crocoblock-wizard/before-plugin-install', $plugin );

		$this->log = null;
		ob_start();

		$this->dependencies();

		$source = $this->locate_source( $plugin );

		if ( ! $source ) {
			return false;
		}

		$this->installer = new Plugin_Upgrader(
			new Plugin_Upgrader_Skin(
				array(
					'url'    => false,
					'plugin' => $plugin['slug'],
					'source' => $plugin['source'],
					'title'  => $plugin['name'],
				)
			)
		);

		$installed       = $this->installer->install( $source );
		$this->log       = ob_get_clean();
		$plugin_activate = $this->installer->plugin_info();

		/**
		 * Hook fires after plugin installation but before activation.
		 *
		 * @param array $plugin Plugin data array.
		 */
		do_action( 'crocoblock-wizard/after-plugin-install', $plugin );


		if ( false !== $activate ) {
			$this->activate_plugin( $plugin_activate, $plugin['slug'] );
		}

		/**
		 * Hook fires after plugin activation.
		 *
		 * @param array $plugin Plugin data array.
		 */
		do_action( 'crocoblock-wizard/after-plugin-activation', $plugin );

		return $installed;

	}

	/**
	 * Returns instacllation log
	 *
	 * @return [type] [description]
	 */
	public function get_log() {
		return $this->log;
	}

	/**
	 * Activate plugin.
	 *
	 * @param  string $activation_data Activation data.
	 * @param  string $slug            Plugin slug.
	 * @return WP_Error|null
	 */
	public function activate_plugin( $activation_data, $slug ) {

		if ( ! empty( $activation_data ) ) {
			$activate = activate_plugin( $activation_data );
			return $activate;
		}

		$all_plugins = get_plugins();

		if ( empty( $all_plugins ) ) {
			return null;
		}

		$all_plugins = array_keys( $all_plugins );

		foreach ( $all_plugins as $plugin ) {

			if ( false === strpos( $plugin, $slug ) ) {
				continue;
			}

			if ( ! is_plugin_active( $plugin ) ) {
				$activate = activate_plugin( $plugin );
				return $activate;
			}
		}

		return null;
	}

	/**
	 * Returns plugin installation source URL.
	 *
	 * @param  array  $plugin Plugin data.
	 * @return string
	 */
	public function locate_source( $plugin = array() ) {

		$source = isset( $plugin['source'] ) ? $plugin['source'] : 'wordpress';
		$result = false;

		switch ( $source ) {
			case 'wordpress':

				require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api

				$api = plugins_api(
					'plugin_information',
					array( 'slug' => $plugin['slug'], 'fields' => array( 'sections' => false ) )
				);

				if ( is_wp_error( $api ) ) {
					$this->log = __( 'Plugins API error', 'crocoblock-wizard' ) . ': ' . $api->get_error_message();
					return false;
				}

				if ( isset( $api->download_link ) ) {
					$result = $api->download_link;
				}

				break;

			case 'local':
				$result = ! empty( $plugin['path'] ) ? $plugin['path'] : false;
				break;

			case 'remote':
				$result = ! empty( $plugin['path'] ) ? esc_url( $plugin['path'] ) : false;
				break;

			case 'crocoblock':

				$license = Plugin::instance()->modules->load_module( 'license' );

				if ( $license ) {
					$api_url = $license->get_license_api_host();
					$result  = add_query_arg(
						array(
							'ct_api_action' => 'get_plugin',
							'license'       => $license->get_license(),
							'url'           => urlencode( home_url( '/' ) ),
							'slug'          => $plugin['slug'],
						),
						$api_url
					);
				}

				break;
		}

		return $result;
	}

	/**
	 * Include dependencies.
	 *
	 * @return void
	 */
	public function dependencies() {

		if ( ! class_exists( '\\Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

	}

}
