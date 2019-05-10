<?php
namespace Crocoblock_Wizard\Tools;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Files_Manager class
 */
class Files_Manager {

	private $base_path = false;
	private $base_url  = false;
	private $base_slug = 'crocoblock-wizard/';

	/**
	 * Returns base path
	 *
	 * @return string
	 */
	public function base_path() {

		if ( ! $this->base_path ) {

			$upload_dir      = wp_upload_dir();
			$upload_base_dir = $upload_dir['basedir'];
			$this->base_path = trailingslashit( $upload_base_dir ) . $this->base_slug;

			if ( ! is_dir( $this->base_path ) ) {
				mkdir( $this->base_path );
			}

		}

		return $this->base_path;

	}

	/**
	 * Returns base path
	 *
	 * @return string
	 */
	public function base_url() {

		if ( ! $this->base_url ) {

			// Ensure folder is created
			if ( ! $this->base_path ) {
				$this->base_path();
			}

			$upload_dir      = wp_upload_dir();
			$upload_base_url = $upload_dir['baseurl'];
			$this->base_url  = trailingslashit( $upload_base_url ) . $this->base_slug;
		}

		return $this->base_url;

	}


	/**
	 * Returns base path
	 *
	 * @return string
	 */
	public function put_json( $relative_path = null, $data = '' ) {
		file_put_contents( $this->base_path() . $relative_path, json_encode( $data ) );
	}

	/**
	 * Returns base path
	 *
	 * @return string|bool
	 */
	public function get_json( $relative_path = null ) {

		$file = $this->base_path() . $relative_path;

		if ( ! is_file( $file ) ) {
			return false;
		}

		ob_start();
		include $file;
		$content = ob_get_clean();

		return json_decode( $content, true );

	}

	/**
	 * Remove directory with all fils inside it
	 *
	 * @param  [type] $dirname [description]
	 * @return [type]          [description]
	 */
	public function delete_dir( $dirname ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$target = trailingslashit( $this->base_path() . $dirname );
		$this->_delete_dir( $target );

	}

	/**
	 * Remove directory with all fils inside it
	 *
	 * @param  [type] $dirname [description]
	 * @return [type]          [description]
	 */
	public function _delete_dir( $target ) {

		if ( is_dir( $target ) ) {

			$files = glob( $target . '*', GLOB_MARK );

			foreach( $files as $file ){
				$this->_delete_dir( $file );
			}

			rmdir( $target );

		} elseif( is_file( $target ) ) {
			unlink( $target );
		}

	}

	/**
	 * Delete file if exists
	 *
	 * @param  [type] $relative_path [description]
	 * @return [type]                [description]
	 */
	public function delete( $relative_path = null ) {

		$file = $this->base_path() . $relative_path;

		if ( is_file( $file ) ) {
			unlink( $file );
		}

	}

}
