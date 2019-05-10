<?php
namespace Crocoblock_Wizard\Modules\Import_Content;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $import_file = null;
	private $importer    = null;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'import-content';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-content',
			CB_WIZARD_URL . 'assets/js/content.js',
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

		$skin         = isset( $_GET['skin'] ) ? $_GET['skin'] : false;
		$is_uploaded  = isset( $_GET['is_uploaded'] ) ? $_GET['is_uploaded'] : false;

		$config['title']            = __( 'We’re almost there!', 'crocoblock-wizard' );
		$config['import_title']     = __( 'Importing sample data', 'crocoblock-wizard' );
		$config['regenerate_title'] = __( 'Regenerating thumbnails', 'crocoblock-wizard' );
		$config['cover']            = CB_WIZARD_URL . 'assets/img/cover-4.png';
		$config['cover_import']     = CB_WIZARD_URL . 'assets/img/cover-5.png';
		$config['body']             = 'cbw-content';
		$config['wrapper_css']      = 'vertical-flex';
		$config['is_uploaded']      = $is_uploaded;
		$config['skin']             = $skin;
		$config['prev_step']        = add_query_arg(
			array(
				'skin'        => $skin,
				'is_uploaded' => $is_uploaded,
			),
			Plugin::instance()->dashboard->page_url( 'install-plugins' )
		);
		$config['next_step']        = '#';
		$config['import_types']     = array(
			array(
				'value'       => 'append',
				'label'       => __( 'Append demo content to my existing content', 'crocoblock-wizard' ),
				'description' => __( 'Skip child theme installation and continute with parent theme.', 'crocoblock-wizard' ),
			),
			array(
				'value'       => 'replace',
				'label'       => __( 'Replace my existing content with demo content', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
			array(
				'value'       => 'skip',
				'label'       => __( 'Skip demo content installation', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
		);

		$this->get_import_file( $skin, $is_uploaded );

		return $config;

	}

	/**
	 * Returns rest of registered plugins
	 *
	 * @return [type] [description]
	 */
	public function get_rest_of_plugins( $skin_plugins, $all_plugins ) {

		array_walk( $all_plugins, function( &$plugin, $slug ) use ( $skin_plugins ) {
			if ( in_array( $slug, $skin_plugins ) ) {
				$plugin = false;
			}
		} );

		return array_keys( array_filter( $all_plugins ) );

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['content']        = 'import-content/main';
		$templates['select_type']    = 'import-content/select-type';
		$templates['import_content'] = 'import-content/import-content';
		return $templates;

	}

	/**
	 * Returns information about current import session
	 *
	 * @return [type] [description]
	 */
	public function get_import_info() {

		$importer = $this->get_importer();
		$importer->prepare_import();

		$total   = $importer->cache->get( 'total_count' );
		$summary = $importer->cache->get( 'import_summary' );

		var_dump( $total );
		var_dump( $summary );

	}

	/**
	 * Get path to imported XML file
	 *
	 * @return [type] [description]
	 */
	public function get_import_file( $skin = null, $is_uploaded = null ) {

		if ( null !== $this->import_file ) {
			return $this->import_file;
		}

		$file = null;

		if ( ! $skin ) {
			$skin = ! empty( $_REQUEST['skin'] ) ? esc_attr( $_REQUEST['skin'] ) : false;
		}

		if ( ! $skin ) {
			return false;
		}

		if ( null === $is_uploaded ) {
			$is_uploaded =  ! empty( $_REQUEST['is_uploaded'] ) ? esc_attr( $_REQUEST['is_uploaded'] ) : false;
		}

		if ( ! empty( $is_uploaded ) ) {
			$file = $this->get_uploaded_file( $skin );
		} else {
			$file = $this->get_remote_file( $skin );
		}

		if ( ! $file || ! file_exists( $file ) ) {
			return false;
		} else {
			$this->import_file = $file;
			return $this->import_file;
		}

	}

	/**
	 * Copy file into root of base dir and return file path
	 *
	 * @return [type] [description]
	 */
	public function get_uploaded_file( $skin = null ) {

		$filename  = 'sample-data.xml';
		$from_path = Plugin::instance()->files_manager->base_path() . $skin . '/' . $filename;
		$to_path   = Plugin::instance()->files_manager->base_path() . $skin . '.xml';

		if ( file_exists( $to_path ) ) {
			return $to_path;
		}

		if ( ! file_exists( $from_path ) ) {
			return false;
		}

		$copied = copy( $from_path, $to_path );

		if ( $copied ) {
			return $to_path;
		} else {
			return false;
		}

	}

	/**
	 * Returns remote file
	 *
	 * @param  [type] $skin [description]
	 * @return [type]       [description]
	 */
	public  function get_remote_file( $skin = null ) {

		$file_url = Plugin::instance()->skins->get_skin_data( 'full_xml', $skin );

		if ( ! $file_url ) {
			return false;
		}

		$filename  = $skin . '.xml';
		$base_path = Plugin::instance()->files_manager->base_path();
		$to_path   = $base_path . $filename;

		if ( file_exists( $to_path ) ) {
			return $to_path;
		}

		$tmpath = download_url( esc_url( $file_url ) );

		if ( ! $tmpath ) {
			return false;
		}

		if ( ! copy( $tmpath, $to_path ) ) {
			return false;
		}

		unlink( $tmpath );

		return $to_path;

	}

	/**
	 * Returns importer instance
	 *
	 * @return WXR_Importer
	 */
	public function get_importer() {

		if ( null !== $this->importer ) {
			return $this->importer;
		}

		$options = array();
		$file    = $this->get_import_file();

		if ( ! $file ) {
			return false;
		}

		return $this->importer = new WXR_Importer( $options, $file );

	}

}
