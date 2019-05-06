<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Files_Manager class
 */
class Files_Manager {


	/**
	 * Data inmporter file manager base path
	 * @var [type]
	 */
	private $base_path = false;

	/**
	 * Returns base path
	 *
	 * @return string
	 */
	public function base_path() {

		if ( ! $this->base_path ) {

			$upload_dir      = wp_upload_dir();
			$upload_base_dir = $upload_dir['basedir'];
			$this->base_path = trailingslashit( $upload_base_dir ) . 'crocoblock-wizard/';

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
