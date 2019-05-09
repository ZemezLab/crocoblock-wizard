<?php
namespace Crocoblock_Wizard\Modules\Import_Content;

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
		$config['prev_step']        = Plugin::instance()->dashboard->page_url( 'install-plugins' );
		$config['next_step']        = '#';
		$config['choices']          = array(
			'append' => array(
				'label'       => __( 'Append demo content to my existing content', 'crocoblock-wizard' ),
				'description' => __( 'Skip child theme installation and continute with parent theme.', 'crocoblock-wizard' ),
			),
			'replace' => array(
				'label'       => __( 'Replace my existing content with demo content', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
			'skip' => array(
				'label'       => __( 'Skip demo content installation', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
		);

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

		$templates['content']     = 'import-content/main';
		$templates['select_type'] = 'import-content/select-type';
		return $templates;

	}

}