<?php
namespace Crocoblock_Wizard\Modules\Onboarding;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $api = false;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'onboarding';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-onboarding',
			CB_WIZARD_URL . 'assets/js/onboarding.js',
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

		$config['body']        = 'cbw-onboarding';
		$config['wrapper_css'] = 'onboarding-page';
		$config['has_header']  = false;
		$config['panels']      = $this->get_panels();

		return $config;

	}

	/**
	 * Returns panels list
	 *
	 * @return [type] [description]
	 */
	public function get_panels() {
		return apply_filters( 'crocoblock-wizard/onboarding/panels', array(
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H20.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H41.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/><rect x="38" y="49.4141" width="2" height="6.14227" transform="rotate(-45 38 49.4141)" fill="white"/><path d="M42.589 55.433C41.8037 54.6477 41.8037 53.3744 42.589 52.589C43.3744 51.8037 44.6477 51.8037 45.433 52.589L48.411 55.567C49.1963 56.3523 49.1963 57.6256 48.411 58.411C47.6256 59.1963 46.3523 59.1963 45.567 58.411L42.589 55.433Z" stroke="white" stroke-width="2"/><circle cx="31" cy="42" r="11" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M31 35C30.0807 35 29.1705 35.1811 28.3212 35.5328C27.4719 35.8846 26.7003 36.4002 26.0503 37.0503C25.4002 37.7003 24.8846 38.4719 24.5328 39.3212C24.1811 40.1705 24 41.0807 24 42" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>',
				'title'      => __( 'Take a look at your site', 'crocoblock-wizard' ),
				'link'       => home_url( '/' ),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H23.8V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H34.3V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><path d="M16 59V54.0851L33.6709 36.4142L38.5858 41.3291L20.9149 59H16Z" stroke="white" stroke-width="2"/><path d="M18.4142 51L24.0711 56.6569L22.6569 58.0711L17 52.4142L18.4142 51Z" fill="white"/><path d="M36.9829 32.908C38.1936 31.6973 40.1565 31.6973 41.3672 32.908L42.092 33.6328C43.3027 34.8435 43.3027 36.8064 42.092 38.0171L38.5233 41.5858L33.4142 36.4767L36.9829 32.908Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><rect x="6" y="19" width="16" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'      => __( 'Proceed to editing pages', 'crocoblock-wizard' ),
				'link'       => admin_url( 'edit.php?post_type=page' ),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
			array(
				'icon'       => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H56C57.6569 1 59 2.34315 59 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M0 13V43C0 45.2 1.8 47 4 47H7V45H4C2.9 45 2 44.1 2 43V15H58V43C58 44.1 57.1 45 56 45H51.5V47H56C58.2 47 60 45.2 60 43V13H0Z" fill="white"/><rect x="8" y="21" width="24" height="14" rx="2" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M32 46H37" stroke="white" stroke-width="2"/><rect x="8" y="41" width="24" height="12" rx="2" stroke="white" stroke-width="2"/><rect x="38" y="36" width="14" height="17" rx="2" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><rect x="38" y="21" width="14" height="9" rx="2" stroke="white" stroke-width="2"/></svg>',
				'title'      => __( 'Go to Crocoblock Dashboard', 'crocoblock-wizard' ),
				'link'       => admin_url( 'admin.php?page=jet-dashboard' ),
				'link_label' => __( 'Click here', 'crocoblock-wizard' ),
			),
		) );
	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['onboarding'] = 'onboarding/main';
		return $templates;

	}

}