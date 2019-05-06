<?php
namespace Crocoblock_Wizard\Modules\Select_Skin;

use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define skins uploader class
 */
class Uploader {

	/**
	 * Skin file data arrray
	 *
	 * @var int
	 */
	private $file = null;

	private $zip_path = '';

	private $error = false;

	/**
	 * Constructor for the class
	 */
	public function __construct( $file = array() ) {
		$this->file = $file;
	}

	/**
	 * Process skin file uploading and unpacking
	 *
	 * @return [type] [description]
	 */
	public function upload() {

		if ( empty( $this->file['tmp_name'] ) ) {
			$this->error = __( 'Uploaded file not found in TMP dir', 'crocoblock-wizrad' );
			return false;
		}

		$path = Plugin::instance()->files_manager->base_path();
		$this->zip_path = $path . $this->file['name'];

		$copied = copy( $this->file['tmp_name'], $this->zip_path );

		if ( ! $copied ) {
			$this->error = __( 'Can`t move skin ZIP file to uploads dir', 'crocoblock-wizrad' );
			return false;
		}

		unlink( $this->file['tmp_name'] );

		$this->unzip_skin();

	}

	/**
	 * Extract skin data from ziip file
	 *
	 * @return [type] [description]
	 */
	public function unzip_skin() {

		include_once( ABSPATH . '/wp-admin/includes/class-pclzip.php' );

	}

}