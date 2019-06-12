<?php
namespace Crocoblock_Wizard\Modules\Export_Skin;

use Crocoblock_Wizard\Tools\WXR_Exporter as WXR_Exporter;
use Crocoblock_Wizard\Tools\Files_Download as Downloader;
use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'export-skin';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-export-skin',
			CB_WIZARD_URL . 'assets/js/export-skin.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$config['title']       = __( 'Export Skin', 'crocoblock-wizard' );
		$config['body']        = 'cbw-export-skin';
		$config['wrapper_css'] = 'export-skin';
		$config['plugins']     = $this->get_plugins_config();

		return $config;

	}

	/**
	 * Returns default plugins config
	 *
	 * @return [type] [description]
	 */
	public function get_plugins_config() {

		$active_plugins = get_option( 'active_plugins' );
		$all_plugins    = get_plugins();
		$result         = array();

		foreach ( $active_plugins as $plugin_file ) {

			if ( ! isset( $all_plugins[ $plugin_file ] ) ) {
				continue;
			}

			$slug = dirname( $plugin_file );

			if ( 'crocoblock-wizard' === $slug ) {
				continue;
			}

			$plugin                = array();
			$data                  = $all_plugins[ $plugin_file ];
			$plugin['slug']        = $slug;
			$plugin['name']        = $data['Name'];
			$plugin['description'] = $data['Description'];
			$result[]              = $plugin;
		}

		return $result;

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['export_skin'] = 'export-skin/main';
		return $templates;

	}

	/**
	 * Returns path to exported skins directory
	 *
	 * @return [type] [description]
	 */
	public function get_base_path() {

		$base_path = Plugin::instance()->files_manager->base_path() . 'export-skins/';

		if ( ! is_dir( $base_path ) ) {
			mkdir( $base_path );
		}

		return $base_path;

	}

	/**
	 * Perform skin export
	 *
	 * @return [type] [description]
	 */
	public function export_skin() {

		$settings = isset( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : array();
		$plugins  = isset( $_REQUEST['plugins'] ) ? $_REQUEST['plugins'] : array();

		$base_path = $this->get_base_path();
		$skin_name = ! empty( $settings['skin_name'] ) ? $settings['skin_name'] : get_bloginfo( 'name' );
		$skin_slug = sanitize_file_name( strtolower( remove_accents( $skin_name ) ) );
		$skin_path = $base_path . $skin_slug . '/';

		if ( ! is_dir( $skin_path ) ) {
			mkdir( $skin_path );
		}

		$settings_file = $skin_path . 'settings.json';

		$only_xml = ! empty( $settings['only_xml'] ) ? $settings['only_xml'] : false;
		$only_xml = filter_var( $only_xml, FILTER_VALIDATE_BOOLEAN );

		$settings_content = array(
			'name'      => $skin_name,
			'slug'      => $skin_slug,
			'demo'      => ! empty( $settings['demo_url'] ) ? esc_url( $settings['demo_url'] ) : '',
			'thumbnail' => ! empty( $settings['thumb_url'] ) ? esc_url( $settings['thumb_url'] ) : '',
		);

		if ( ! $only_xml ) {
			$settings_content['plugins'] = $this->prepare_plugins_to_export( $plugins );
		}

		file_put_contents( $settings_file, json_encode( $settings_content ) );

		$options = isset( $settings['export_options'] ) ? $settings['export_options'] : false;
		$tables  = isset( $settings['export_tables'] ) ? $settings['export_tables'] : false;

		$exporter = new WXR_Exporter(
			$this->textarea_to_array( $options ),
			$this->textarea_to_array( $tables ),
			$skin_path
		);

		$content_file = $exporter->do_export();

		$zip_basename = $skin_slug . '.zip';
		$zip_file     = $base_path . $skin_slug . '.zip';
		$archive      = new \PclZip( $base_path . $skin_slug . '.zip' );

		$archive->create( $skin_path, PCLZIP_OPT_REMOVE_PATH, $skin_path );

		Plugin::instance()->files_manager->_delete_dir( $skin_path );

		wp_send_json_success( array(
			'redirect' => add_query_arg(
				array(
					'action'  => Plugin::instance()->dashboard->page_slug . '/' . $this->get_slug(),
					'handler' => 'download_skin',
					'zip'     => $zip_basename,
					'nonce'   => wp_create_nonce( Plugin::instance()->dashboard->page_slug )
				),
				esc_url( admin_url( 'admin-ajax.php' ) )
			),
		) );

	}

	/**
	 * Perform uploaded skin download
	 *
	 * @return [type] [description]
	 */
	public function download_skin() {

		$zip       = isset( $_GET['zip'] ) ? $_GET['zip'] : false;
		$base_path = $this->get_base_path();
		$zippath   = $base_path . $zip;

		if ( ! is_readable( $zippath ) ) {
			wp_die( __( 'Can`t find export ZIP, please return to previous page and try again', 'crocoblock-wizard' ) );
		}

		$downloader = new Downloader( $zip, $zippath, 'zip' );

		$downloader->set_headers();
		echo file_get_contents( $zippath );
		unlink( $zippath );
		die();

	}

	/**
	 * Explode testarea string into srray
	 *
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function textarea_to_array( $string ) {
		return explode( ',', str_replace( ' ', '', $string ) );
	}

	/**
	 * Prepare passed plugins to be exported
	 *
	 * @return [type] [description]
	 */
	public function prepare_plugins_to_export( $plugins = array() ) {

		$prepared = array();

		foreach ( $plugins as $plugin => $data ) {

			$include = ! empty( $data['include'] ) ? $data['include'] : false;
			$include = filter_var( $include, FILTER_VALIDATE_BOOLEAN );

			if ( $include ) {

				$source = ! empty( $data['source'] ) ? $data['source'] : 'wordpress';

				$prepared[ $plugin ] = array(
					'name'   => ! empty( $data['name'] ) ? $data['name'] : '',
					'source' => $source,
					'path'   => ( ! empty( $data['url'] ) && 'remote' === $source ) ? $data['url'] : false,
				);
			}

		}

		return $prepared;

	}

}
