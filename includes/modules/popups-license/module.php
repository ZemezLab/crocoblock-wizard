<?php
namespace Crocoblock_Wizard\Modules\Popups_License;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;
use Crocoblock_Wizard\Modules\License\API as License_API;
use Crocoblock_Wizard\Tools\Files_Download as Files_Download;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $api = false;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'popups-license';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'croco-popups-license',
			CB_WIZARD_URL . 'assets/js/popups-license.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * Returns licensing API instance
	 *
	 * @return [type] [description]
	 */
	public function get_api() {

		if ( ! $this->api ) {
			$this->api = new License_API();
		}

		return $this->api;
	}

	/**
	 * Return URL to licensing API server
	 *
	 * @return [type] [description]
	 */
	public function get_license_api_host() {
		$license_api = $this->get_api();
		return $license_api->api;
	}

	/**
	 * Returns stored license key
	 *
	 * @return [type] [description]
	 */
	public function get_license() {
		$license_api = $this->get_api();
		return $license_api->get_license();
	}

	/**
	 * Returns stored license key
	 *
	 * @return [type] [description]
	 */
	public function get_item_id() {
		$license_api = $this->get_api();
		return $license_api->item_id;
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
				'message' => esc_html__( 'Please fill in License field and try again', 'croco-ik' ),
			) );
		}

		$license_api  = $this->get_api();
		$install_data = $license_api->activate_license( $license );

		if ( ! $install_data ) {
			wp_send_json_error( array(
				'message' => $license_api->get_error(),
			) );
		} else {
			if ( ! $license_api->has_template_access() ) {
				wp_send_json_error( array(
					'access_error' => true,
					'message'      => esc_html__( 'Templates is not included into your license', 'croco-ik' ),
				) );
			} else {
				wp_send_json_success( array(
					'message' => esc_html__( 'Your license is activated. Redirecting...', 'croco-ik' ),
				) );
			}
		}

	}

	/**
	 * Deactivate currently stored license key
	 *
	 * @return [type] [description]
	 */
	public function deactivate_license() {

		$api     = $this->get_api();
		$license = $this->get_license();

		if ( $license ) {
			$api->license_request( 'deactivate_license', $license );
			$api->delete_license();
		}

		wp_safe_redirect( Plugin::instance()->dashboard->page_url( 'popups-license' ) );
		die();

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$license_api = $this->get_api();
		$is_active   = $license_api->is_active();

		if ( true !== $license_api->connection_status ) {

			$config['body']        = 'cbw-connection-error';
			$config['wrapper_css'] = 'connection-error-panel';
			$config['report_url']  = add_query_arg(
				array(
					'action'  => Plugin::instance()->dashboard->page_slug . '/' . $this->get_slug(),
					'handler' => 'download_report',
					'nonce'   => $config['nonce'],
				),
				admin_url( 'admin-ajax.php' )
			);

			return $config;

		}

		if ( $is_active ) {
			$page_title = __( 'Interactive Popup Library', 'croco-ik' );
		} else {
			$page_title = __( 'Please, enter your license key to start', 'croco-ik' );
		}

		$config['title']               = __( 'Installation wizard', 'croco-ik' );
		$config['body']                = 'cbw-popups-license';
		$config['deactivate_link']     = $this->get_deactivate_url( $config );
		$config['wrapper_css']         = 'license-panel';
		$config['button_label']        = __( 'Enter license key', 'croco-ik' );
		$config['ready_button_label']  = __( 'Start Installation', 'croco-ik' );
		$config['has_template_access'] = $license_api->has_template_access();
		$config['license_is_active']   = $is_active;
		$config['page_title']          = $page_title;
		$config['next_step']           = Plugin::instance()->dashboard->page_url( 'popups-install-plugins' );

		return $config;

	}

	/**
	 * Returns deactivate license URL
	 *
	 * @return [type] [description]
	 */
	public function get_deactivate_url( $config ) {

		return add_query_arg(
			array(
				'action'  => str_replace( '%module%', $this->get_slug(), $config['action_mask'] ),
				'handler' => 'deactivate_license',
				'nonce'   => $config['nonce'],
			),
			esc_url( admin_url( 'admin-ajax.php' ) )
		);

	}

	/**
	 * Download report handler
	 *
	 * @return [type] [description]
	 */
	public function download_report() {

		$license_api = $this->get_api();
		$connection  = $license_api->check_connection_status();

		if ( ! is_wp_error( $connection ) ) {
			wp_redirect( Plugin::instance()->dashboard->page_url( 'license' ) );
			die();
		}

		ob_start();

		echo '####################' . PHP_EOL . PHP_EOL;
		echo 'Error Message:' . PHP_EOL;
		echo  $connection->get_error_message() . PHP_EOL . PHP_EOL;
		echo '####################' . PHP_EOL . PHP_EOL;
		echo 'Server Info:' . PHP_EOL . PHP_EOL;
		echo 'Operating System: ' . PHP_OS . PHP_EOL;
		echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;
		echo 'Software: ' . $_SERVER['SERVER_SOFTWARE'] . PHP_EOL;

		if ( function_exists( 'curl_version' ) ) {
			echo 'cURL Info:' . PHP_EOL;

			foreach ( curl_version() as $key => $value ) {

				if ( is_array( $value ) ) {
					$value = implode( ', ', $value );
				}

				echo '    ' . $key . ': ' . $value . PHP_EOL;
			}

		}

		$content = ob_get_clean();
		$fd      = new Files_Download( 'error-report.txt', false, 'txt', $content );

		$fd->download();

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['popups_license']   = 'popups-license/main';
		$templates['connection_error'] = 'popups-license/connection-error';

		return $templates;

	}

}
