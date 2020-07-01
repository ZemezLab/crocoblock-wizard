<?php
namespace Crocoblock_Wizard\Modules\Popups_Install_Plugins;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define license API class
 */
class API {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Config properties
	 */
	public $plugins_option = 'cpb_included_plugins';
	public $api = 'https://account.crocoblock.com/wp-content/uploads/static/pb-wizard-plugins.json';

	/**
	 * Error message holder
	 */
	private $error = null;

	public function __construct() {
		if ( ! empty( $_GET['clear_cache'] ) ) {
			delete_transient( $this->plugins_option );
		}
	}

	public function get_plugins() {

		$plugins = get_transient( $this->plugins_option );

		if ( ! $plugins ) {

			$plugins = $this->remote_get_plugins();

			if ( true !== $this->connection_status ) {
				return false;
			}

			set_transient( $this->plugins_option, $plugins, 2 * DAY_IN_SECONDS );

		}

		return $plugins;

	}

	/**
	 * Perform a remote request with passed action for passed license key
	 *
	 * @param  string $action  EDD action to perform (activate_license, check_license etc)
	 * @param  string $license License key
	 * @return WP_Error|array
	 */
	public function remote_get_plugins() {

		$response = wp_remote_get( $this->api, array(
			'timeout'   => 60,
			'sslverify' => false
		) );

		if ( is_wp_error( $response ) ) {
			$this->connection_status = $response;
		} else {
			$this->connection_status = true;
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );

	}

}
