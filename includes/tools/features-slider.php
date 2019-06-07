<?php
namespace Crocoblock_Wizard\Tools;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Main exporter class
 */
class Features_Slider {

	private $source = 'https://raw.githubusercontent.com/CrocoBlock/wizard-slides/master/slides.json';

	public function __construct() {

		add_action( 'crocoblock-wizard/dashboard/before-enqueue-assets', array( $this, 'assets' ) );

		add_filter( 'crocoblock-wizard/dashboard/js-page-config', array( $this, 'page_config' ), 10, 2 );
		add_filter( 'crocoblock-wizard/dashboard/js-page-templates', array( $this, 'page_templates' ), 10, 2 );

	}

	/**
	 * Enqueue assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {

		wp_register_script(
			'siema',
			CB_WIZARD_URL . 'assets/js/vendor/siema.min.js',
			array(),
			CB_WIZARD_VERSION,
			true
		);

		wp_enqueue_script(
			'crocoblock-wizard-slides',
			CB_WIZARD_URL . 'assets/js/slides.js',
			array( 'cx-vue-ui', 'siema' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	public function page_config( $config ) {
		$config['slides_url'] = $this->source;
		return $config;
	}

	public function page_templates( $templates ) {
		$templates['slides'] = 'common/slides';
		return $templates;
	}

}
