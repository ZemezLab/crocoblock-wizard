<?php
namespace Crocoblock_Wizard\Modules\Popups_Import_Popup;

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
	public $transient_key = 'cpb_popups';
	public $api = 'https://account.crocoblock.com/wp-content/uploads/static/pb-wizard-popups.json';

	private $all_popups = null;

	/**
	 * Error message holder
	 */
	private $error = null;

	public function __construct() {
		if ( ! empty( $_GET['clear_cache'] ) ) {
			delete_transient( $this->transient_key );
		}
	}

	/**
	 * Get all popups list
	 *
	 * @return [type] [description]
	 */
	public function get_popups() {
		$data = $this->get_data();
		return isset( $data['popups'] ) ? $data['popups'] : array();
	}

	/**
	 * Returns filters info
	 *
	 * @return [type] [description]
	 */
	public function get_filters() {
		$data = $this->get_data();
		return isset( $data['filters'] ) ? $data['filters'] : array();
	}

	public function get_data() {

		if ( null !== $this->all_popups ) {
			return $this->all_popups;
		}

		$this->all_popups = get_transient( $this->transient_key );
		//$this->all_popups = false;

		if ( ! $this->all_popups ) {

			$this->all_popups = $this->remote_get_data();

			if ( true !== $this->connection_status ) {
				return false;
			}

			set_transient( $this->transient_key, $this->all_popups, WEEK_IN_SECONDS );

		}

		return $this->all_popups;
	}

	/**
	 * Perform a remote request with passed action for passed license key
	 *
	 * @param  string $action  EDD action to perform (activate_license, check_license etc)
	 * @param  string $license License key
	 * @return WP_Error|array
	 */
	public function remote_get_data() {

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
