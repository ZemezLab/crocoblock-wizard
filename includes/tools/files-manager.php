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
	 * Connect to the filesystem.
	 *
	 * @since 1.0.0
	 *
	 * @param array $directories                  Optional. A list of directories. If any of these do
	 *                                            not exist, a {@see WP_Error} object will be returned.
	 *                                            Default empty array.
	 * @param bool  $allow_relaxed_file_ownership Whether to allow relaxed file ownership.
	 *                                            Default false.
	 * @return bool|WP_Error True if able to connect, false or a {@see WP_Error} otherwise.
	 */
	public function fs_connect( $directories = array(), $allow_relaxed_file_ownership = false ) {

		global $wp_filesystem;

		$url = admin_url( 'tools.php' );

		if ( false === ( $credentials = request_filesystem_credentials( $url, '', false, false, array(), $allow_relaxed_file_ownership ) ) ) {
			return false;
		}

		if ( ! empty( $directories[0] ) ) {
			$dirs = $directories[0];
		} else {
			$dirs = array();
		}

		if ( ! WP_Filesystem( $credentials, $dirs, $allow_relaxed_file_ownership ) ) {
			$error = true;
			if ( is_object($wp_filesystem) && $wp_filesystem->errors->get_error_code() ) {
				$error = $wp_filesystem->errors;
			}
			return false;
		}

		if ( ! is_object($wp_filesystem) )
			return new WP_Error('fs_unavailable', $this->strings['fs_unavailable'] );

		if ( is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->get_error_code() )
			return new WP_Error('fs_error', $this->strings['fs_error'], $wp_filesystem->errors);

		foreach ( (array)$directories as $dir ) {
			switch ( $dir ) {
				case ABSPATH:
					if ( ! $wp_filesystem->abspath() )
						return new WP_Error('fs_no_root_dir', $this->strings['fs_no_root_dir']);
					break;
				case WP_CONTENT_DIR:
					if ( ! $wp_filesystem->wp_content_dir() )
						return new WP_Error('fs_no_content_dir', $this->strings['fs_no_content_dir']);
					break;
				case WP_PLUGIN_DIR:
					if ( ! $wp_filesystem->wp_plugins_dir() )
						return new WP_Error('fs_no_plugins_dir', $this->strings['fs_no_plugins_dir']);
					break;
				case get_theme_root():
					if ( ! $wp_filesystem->wp_themes_dir() )
						return new WP_Error('fs_no_themes_dir', $this->strings['fs_no_themes_dir']);
					break;
				default:
					if ( ! $wp_filesystem->find_folder($dir) )
						return new WP_Error( 'fs_no_folder', sprintf( $this->strings['fs_no_folder'], esc_html( basename( $dir ) ) ) );
					break;
			}
		}

		return true;

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

	/**
	 * Try automatically set filesystem credentials if required
	 */
	public function add_creds() {
		add_filter( 'request_filesystem_credentials', array( $this, 'maybe_set_cred' ), 10, 7 );
	}

	/**
	 * Maybe rewrite filesystem credentials
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed  $credentials  Form output to return instead. Default empty.
	 * @param  string $form_post    URL to POST the form to.
	 * @param  string $type         Chosen type of filesystem.
	 * @param  bool   $error        Whether the current request has failed to connect.
	 *                             Default false.
	 * @param  string $context      Full path to the directory that is tested for
	 *                             being writable.
	 * @param  bool   $allow_relaxed_file_ownership Whether to allow Group/World writable.
	 * @param  array  $extra_fields Extra POST fields.
	 * @return mixed
	 */
	public function maybe_set_cred( $credentials, $form_post, $type, $error, $context, $extra_fields, $allow_relaxed_file_ownership ) {

		$method = $this->check_filesystem_method();

		if ( true === $method ) {
			return $credentials;
		}

		$credentials = get_option( 'ftp_credentials', array( 'hostname' => '', 'username' => '' ) );

		// If defined, set it to that, Else, If POST'd, set it to that, If not, Set it to whatever it previously was(saved details in option)
		$credentials['hostname'] = defined('FTP_HOST') ? FTP_HOST : (!empty($_POST['hostname']) ? wp_unslash( $_POST['hostname'] ) : $credentials['hostname']);
		$credentials['username'] = defined('FTP_USER') ? FTP_USER : (!empty($_POST['username']) ? wp_unslash( $_POST['username'] ) : $credentials['username']);
		$credentials['password'] = defined('FTP_PASS') ? FTP_PASS : (!empty($_POST['password']) ? wp_unslash( $_POST['password'] ) : '');

		// Check to see if we are setting the public/private keys for ssh
		$credentials['public_key'] = defined('FTP_PUBKEY') ? FTP_PUBKEY : (!empty($_POST['public_key']) ? wp_unslash( $_POST['public_key'] ) : '');
		$credentials['private_key'] = defined('FTP_PRIKEY') ? FTP_PRIKEY : (!empty($_POST['private_key']) ? wp_unslash( $_POST['private_key'] ) : '');

		// Sanitize the hostname, Some people might pass in odd-data:
		$credentials['hostname'] = preg_replace('|\w+://|', '', $credentials['hostname']); //Strip any schemes off

		if ( strpos($credentials['hostname'], ':') ) {
			list( $credentials['hostname'], $credentials['port'] ) = explode(':', $credentials['hostname'], 2);
			if ( ! is_numeric($credentials['port']) )
				unset($credentials['port']);
		} else {
			unset($credentials['port']);
		}

		if ( ( defined( 'FTP_SSH' ) && FTP_SSH ) || ( defined( 'FS_METHOD' ) && 'ssh2' == FS_METHOD ) ) {
			$credentials['connection_type'] = 'ssh';
		} elseif ( ( defined( 'FTP_SSL' ) && FTP_SSL ) && 'ftpext' == $type ) {
			//Only the FTP Extension understands SSL
			$credentials['connection_type'] = 'ftps';
		} elseif ( ! empty( $_POST['connection_type'] ) ) {
			$credentials['connection_type'] = wp_unslash( $_POST['connection_type'] );
		} elseif ( ! isset( $credentials['connection_type'] ) ) {
			//All else fails (And it's not defaulted to something else saved), Default to FTP
			$credentials['connection_type'] = 'ftp';
		}

		return $credentials;

	}

	/**
	 * Check avaliable filesystem method
	 *
	 * @since  1.0.0
	 * @return bool true - if avaliable direct access, else - access method
	 */
	public function check_filesystem_method() {

		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$method = get_filesystem_method( array(), false, false );

		if ( 'direct' == $method ) {
			return true;
		}

		return $this->check_creds( $method );

	}

	/**
	 * Check, if user provide credentials via constants
	 *
	 * @since  1.0.0
	 * @param  string  $method  filesystem method
	 * @return bool|string
	 */
	public function check_creds( $method ) {

		if ( in_array( $method, array( 'ftpext', 'ftpsockets' ) ) && defined( 'FTP_HOST' ) && defined( 'FTP_USER' ) && defined( 'FTP_PASS' ) ) {
			return true;
		}

		if ( 'ssh2' == $method && defined( 'FTP_PUBKEY' ) && defined( 'FTP_PRIKEY' ) ) {
			return true;
		}

		return $method;

	}

}
