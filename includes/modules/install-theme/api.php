<?php
namespace Crocoblock_Wizard\Modules\Install_Theme;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define theme installation API class
 */
class API {

	/**
	 * Installed theme URL.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Installation result
	 *
	 * @var mixed
	 */
	private $result;

	/**
	 * Adjusted theme directory name
	 *
	 * @var string
	 */
	private $adjusted_dir;

	/**
	 * Constructor for the class
	 */
	function __construct( $url = null ) {
		$this->url    = $url;
	}

	/**
	 * Perform theme installation
	 *
	 * @return array
	 */
	public function do_theme_install() {

		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		add_filter( 'upgrader_source_selection', array( $this, 'adjust_theme_dir' ), 1, 3 );

		$theme_url = $this->url;
		$skin      = new \WP_Ajax_Upgrader_Skin();
		$upgrader  = new \Theme_Upgrader( $skin );
		$result    = $upgrader->install( $theme_url );

		remove_filter( 'upgrader_source_selection', array( $this, 'adjust_theme_dir' ), 1 );

		$data    = array();
		$success = true;
		$message = esc_html__( 'The theme is succesfully installed. Activating...', 'crocoblock-wizard' );

		if ( is_wp_error( $result ) ) {

			$message = $result->get_error_message();
			$success = false;

		} elseif ( is_wp_error( $skin->result ) ) {

			if ( ! isset( $skin->result->errors['folder_exists'] ) ) {
				$message = $skin->result->get_error_message();
				$success = false;
			} else {
				$message = esc_html__( 'The theme has been already installed. Activating...', 'crocoblock-wizard' );
			}

		} elseif ( $skin->get_errors()->get_error_code() ) {

			$message = $skin->get_error_messages();
			$success = false;

		} elseif ( is_null( $result ) ) {

			global $wp_filesystem;
			$message = esc_html__( 'Unable to connect to the filesystem. Please confirm your credentials.', 'crocoblock-wizard' );

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$message = esc_html( $wp_filesystem->errors->get_error_message() );
			}

			$success  = false;

		}

		return array(
			'success' => $success,
			'message' => $message,
		);
	}

	/**
	 * Adjust the theme directory name.
	 *
	 * @since  1.0.0
	 * @param  string       $source        Path to upgrade/zip-file-name.tmp/subdirectory/.
	 * @param  string       $remote_source Path to upgrade/zip-file-name.tmp.
	 * @param  \WP_Upgrader $upgrader      Instance of the upgrader which installs the theme.
	 * @return string $source
	 */
	public function adjust_theme_dir( $source, $remote_source, $upgrader ) {

		global $wp_filesystem;

		if ( ! is_object( $wp_filesystem ) ) {
			return $source;
		}

		// Ensure that is Wizard installation request
		if ( empty( $_REQUEST['action'] ) ) {
			return $source;
		}

		// Check for single file plugins.
		$source_files = array_keys( $wp_filesystem->dirlist( $remote_source ) );
		if ( 1 === count( $source_files ) && false === $wp_filesystem->is_dir( $source ) ) {
			return $source;
		}

		$css_key  = array_search( 'style.css', $source_files );

		if ( false === $css_key ) {
			return $source;
		}

		$css_path = $remote_source . '/' . $source_files[ $css_key ];

		if ( ! file_exists( $css_path ) ) {
			return $source;
		}

		$theme_data = get_file_data( $css_path, array(
			'TextDomain' => 'Text Domain',
			'ThemeName'  => 'Theme Name',
		), 'theme' );

		if ( ! $theme_data || ! isset( $theme_data['TextDomain'] ) ) {
			return $source;
		}

		$theme_name = $theme_data['TextDomain'];
		$from_path  = untrailingslashit( $source );
		$to_path    = untrailingslashit( str_replace( basename( $remote_source ), $theme_name, $remote_source ) );

		if ( true === $wp_filesystem->move( $from_path, $to_path ) ) {

			/**
			 * Fires after reanming before returns result.
			 */
			do_action( 'crocoblock-wizard/source-rename-done', $theme_data );

			return trailingslashit( $to_path );

		} else {

			return new WP_Error(
				'rename_failed',
				esc_html__( 'The remote plugin package does not contain a folder with the desired slug and renaming did not work.', 'crocoblock-wizard' ),
				array( 'found' => $subdir_name, 'expected' => $theme_name )
			);

		}

		return $source;

	}

}
