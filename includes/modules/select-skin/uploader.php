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

	private $zip_path   = '';
	private $unzip_path = '';
	private $skin_dir   = '';

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

		// Ensure this is authorized attempt
		if ( ! current_user_can( 'manage_options' ) ) {
			$this->error = __( 'You don`t have permissions to do this', 'crocoblock-wizrad' );
			return false;
		}

		if ( empty( $this->file['tmp_name'] ) ) {
			$this->error = __( 'Uploaded file not found in TMP dir', 'crocoblock-wizrad' );
			return false;
		}

		$path             = Plugin::instance()->files_manager->base_path();
		$this->zip_path   = $path . $this->file['name'];
		$pathinfo         = pathinfo( $this->zip_path );
		$this->unzip_path = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'];
		$this->skin_dir   = $pathinfo['filename'];

		$copied = copy( $this->file['tmp_name'], $this->zip_path );

		if ( ! $copied ) {
			$this->error = __( 'Can`t move skin ZIP file to uploads dir', 'crocoblock-wizrad' );
			return false;
		}

		unlink( $this->file['tmp_name'] );

		$unzipped = $this->unzip_skin();

		if ( ! $unzipped ) {
			return false;
		}

		unlink( $this->zip_path );

		$this->adjust_skin_path();

		$required_files = array(
			'settings.json',
			'sample-data.xml',
		);

		$skin_data     = array();
		$files_missing = null;

		foreach ( $required_files as $file ) {

			if ( is_readable( $this->unzip_path . '/' . $file ) ) {
				$skin_data[ $file ] = $this->unzip_path . '/' . $file;
				continue;
			}

			if ( ! $files_missing ) {
				$files_missing = $file;
			} else {
				$files_missing .= ', ' . $file;
			}

		}

		if ( $files_missing ) {

			$this->error = sprintf(
				__( 'The skin is broken. Missing required files: %s', 'crocoblock-wizrad' ),
				$files_missing
			);

			$this->delete_skin();

			return false;

		}

		return $skin_data;

	}

	/**
	 * Ensure unzipped skin dir is equal to skin slug
	 *
	 * @return void
	 */
	public function adjust_skin_path() {

		Plugin::instance()->files_manager->fs_connect();

		global $wp_filesystem;

		if ( ! is_object( $wp_filesystem ) ) {
			return false;
		}

		$settings_file = $this->unzip_path . '/settings.json';

		if ( ! is_readable( $settings_file ) ) {
			return false;
		}

		ob_start();
		include  $settings_file;

		$settings = ob_get_clean();
		$settings = json_decode( $settings, true );
		$slug     = isset( $settings['slug'] ) ? esc_attr( $settings['slug'] ) : false;
		$to_path  = trailingslashit( dirname( $this->unzip_path ) ) . $slug;

		if ( $wp_filesystem->move( $this->unzip_path, $to_path ) ) {
			$this->unzip_path = $to_path;
		}

	}

	/**
	 * Delete skin
	 *
	 * @return void
	 */
	public function delete_skin() {

		global $wp_filesystem;

		if ( ! is_object( $wp_filesystem ) ) {
			return false;
		}

		$del = $wp_filesystem->rmdir( $this->unzip_path, true );

	}

	/**
	 * Return error message
	 *
	 * @return [type] [description]
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Extract skin data from ziip file
	 *
	 * @return [type] [description]
	 */
	public function unzip_skin() {

		include_once( ABSPATH . '/wp-admin/includes/class-pclzip.php' );

		$zip    = new \PclZip( $this->zip_path );
		$result = $zip->extract( PCLZIP_OPT_PATH, $this->unzip_path );

		if ( ! $result ) {
			$this->delete_skin();
			$this->error = $zip->errorInfo( true );
		}

		return $result;

	}

}