<?php
namespace Crocoblock_Wizard\Modules\Welcome;

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
		return 'welcome';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-welcome',
			CB_WIZARD_URL . 'assets/js/welcome.js',
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

		$config['body']        = 'cbw-welcome';
		$config['wrapper_css'] = 'welcome-page';
		$config['has_header']  = false;
		$config['actions']     = $this->get_allowed_actions();

		return $config;

	}

	/**
	 * Returns allowed actions list
	 *
	 * @return [type] [description]
	 */
	public function get_allowed_actions() {
		return apply_filters( 'crocoblock-wizard/welcome/actions', array(
			array(
				'icon'         => '<svg width="70" height="80" viewBox="0 0 70 80" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M13 22C13 22.4 8.33333 22.1667 6 22V3L9 1H22L31 8H61L64 10V22H57V17L55 14H15L13 16V22Z" fill="white"/><path d="M64.9996 21.1V11.9C64.9996 9.29999 62.8996 7.19999 60.3996 7.19999H32.2996C31.0996 7.19999 29.8996 6.69999 28.9996 5.79999L24.7996 1.49999C23.8996 0.599987 22.7996 0.0999869 21.4996 0.0999869H9.59956C7.09956 -1.30683e-05 4.99955 2.09999 4.99955 4.69999V21C2.59955 21.3 0.799555 23.5 0.999555 26.1L3.59955 55.6C3.79955 58.1 5.79955 60 8.19955 60H33.9996V76.6L30.6996 73.3C30.2996 72.9 29.6996 72.9 29.2996 73.3C28.8996 73.7 28.8996 74.3 29.2996 74.7L34.2996 79.7C34.4996 79.9 34.7996 80 34.9996 80C35.2996 80 35.4996 79.9 35.6996 79.7L40.6996 74.7C41.0996 74.3 41.0996 73.7 40.6996 73.3C40.2996 72.9 39.6996 72.9 39.2996 73.3L35.9996 76.6V60H61.7996C64.1996 60 66.1996 58.1 66.3996 55.7L68.9996 26.2C69.1996 23.6 67.3996 21.4 64.9996 21.1ZM9.59956 1.99999H21.4996C22.1996 1.99999 22.7996 2.29999 23.2996 2.79999L27.4996 7.09999C28.6996 8.39999 30.3996 9.09999 32.1996 9.09999H60.2996C61.6996 9.09999 62.8996 10.3 62.8996 11.8V21H57.8996V17C57.8996 14.8 56.0996 13 53.8996 13H15.9996C13.7996 13 11.9996 14.8 11.9996 17V21H6.99955V4.69999C6.99955 3.19999 8.19955 1.99999 9.59956 1.99999ZM55.9996 21H13.9996V17C13.9996 15.9 14.8996 15 15.9996 15H53.9996C55.0996 15 55.9996 15.9 55.9996 17V21ZM64.3996 55.5C64.2996 56.9 63.1996 58 61.7996 58H35.9996V52C35.9996 51.4 35.5996 51 34.9996 51C34.3996 51 33.9996 51.4 33.9996 52V58H8.19955C6.89955 58 5.69955 56.9 5.59955 55.5L2.99955 26C2.89955 25.2 3.19955 24.4 3.69955 23.9C4.19955 23.3 4.89955 23 5.59955 23H64.3996C65.0996 23 65.7996 23.3 66.2996 23.8C66.7996 24.4 67.0996 25.2 66.9996 25.9L64.3996 55.5Z" fill="white"/><path d="M20.4 37.2C21 37.2 21.5 36.7 21.5 36.1C21.5 35.5 21 35 20.4 35C18 35 16 37 16 39.5C16 42 18 44 20.4 44C21 44 21.5 43.5 21.5 42.9C21.5 42.3 21 41.8 20.4 41.8C19.2 41.8 18.2 40.8 18.2 39.6C18.2 38.2 19.2 37.2 20.4 37.2Z" fill="white"/><path d="M42.4 37.3C43 37.3 43.5 36.8 43.5 36.2C43.5 35.6 43 35.1 42.4 35.1C39.9 35.1 38 37.1 38 39.6C38 42.1 40 44.1 42.4 44.1C43 44.1 43.5 43.6 43.5 43C43.5 42.4 43 41.9 42.4 41.9C41.2 41.9 40.2 40.9 40.2 39.7C40.1 38.3 41.1 37.3 42.4 37.3Z" fill="white"/><path d="M26.5992 35C24.0992 35 22.1992 37 22.1992 39.5V42.9C22.1992 43.5 22.6992 44 23.2992 44C23.8992 44 24.3992 43.5 24.3992 42.9V39.5C24.3992 38.3 25.3992 37.3 26.5992 37.3C27.1992 37.3 27.6992 36.8 27.6992 36.2C27.6992 35.5 27.1992 35 26.5992 35Z" fill="white"/><path d="M32.7984 35C30.2984 35 28.3984 37 28.3984 39.5C28.3984 42 30.3984 43.9 32.7984 44C33.3984 44 33.8984 43.5 33.8984 42.9C33.8984 42.3 33.3984 41.8 32.7984 41.8C31.5984 41.8 30.5984 40.8 30.5984 39.6C30.5984 38.4 31.5984 37.4 32.7984 37.4C33.9984 37.4 34.9984 38.4 34.9984 39.6C34.9984 40.1 34.7984 40.6 34.5984 40.9C34.3984 41.1 34.2984 41.4 34.2984 41.7C34.2984 42.3 34.7984 42.8 35.3984 42.8C35.6984 42.8 36.0984 42.6 36.2984 42.4C36.8984 41.6 37.2984 40.7 37.2984 39.6C37.2984 37 35.2984 35 32.7984 35Z" fill="white"/><path d="M48.5992 35.1C46.0992 35.1 44.1992 37.1 44.1992 39.6C44.1992 42.1 46.1992 44 48.5992 44.1C49.1992 44.1 49.6992 43.6 49.6992 43C49.6992 42.4 49.1992 41.9 48.5992 41.9C47.3992 41.9 46.3992 40.9 46.3992 39.7C46.3992 38.5 47.3992 37.5 48.5992 37.5C49.7992 37.5 50.7992 38.5 50.7992 39.7C50.7992 40.2 50.5992 40.7 50.3992 41C50.1992 41.2 50.0992 41.5 50.0992 41.8C50.0992 42.4 50.5992 42.9 51.1992 42.9C51.4992 42.9 51.8992 42.7 52.0992 42.5C52.6992 41.7 53.0992 40.8 53.0992 39.7C52.9992 37.1 50.9992 35.1 48.5992 35.1Z" fill="white"/></svg>',
				'title'        => __( 'Install Crocoblock', 'crocoblock-wizard' ),
				'action_label' => __( 'Let\'s Go', 'crocoblock-wizard' ),
				'action_url'   => Plugin::instance()->dashboard->page_url( 'license' ),
				'desc'         => __( 'Choose this option to install Crocoblock products. This step allows installation of the plugins, or you can pick a skin to install on your site.', 'crocoblock-wizard' ),
				'featured'     => 1,
			),
			array(
				'icon'         => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H42C43.6569 1 45 2.34315 45 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M1 14H45V56C45 57.6569 43.6569 59 42 59H4C2.34314 59 1 57.6569 1 56V14Z" stroke="white" stroke-width="2"/><path d="M27 45C26.4477 45 26 44.5523 26 44C26 43.4477 26.4477 43 27 43L59 43C59.5523 43 60 43.4477 60 44C60 44.5523 59.5523 45 59 45L27 45Z" fill="white"/><path d="M54.7195 49.705C54.3261 50.0983 53.6884 50.0983 53.295 49.705C52.9017 49.3116 52.9017 48.6739 53.295 48.2805L58.2805 43.295C58.6739 42.9017 59.3116 42.9017 59.705 43.295C60.0983 43.6884 60.0983 44.3261 59.705 44.7195L54.7195 49.705Z" fill="white"/><path d="M54.7195 38.295C54.3261 37.9017 53.6884 37.9017 53.295 38.295C52.9017 38.6884 52.9017 39.3261 53.295 39.7195L58.2805 44.705C58.6739 45.0983 59.3116 45.0983 59.705 44.705C60.0983 44.3116 60.0983 43.6739 59.705 43.2805L54.7195 38.295Z" fill="white"/><rect x="6" y="19" width="15" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'        => __( 'Export skin', 'crocoblock-wizard' ),
				'action_label' => __( 'Click here', 'crocoblock-wizard' ),
				'action_url'   => Plugin::instance()->dashboard->page_url( 'export-skin' ),
				'desc'         => __( 'Choose this option in case you’ve built your own skin, the Wizard will help you export it to another site. The skin might contain: <ul><li>Elementor templates;</li><li>Custom post types;</li><li>Custom taxonomies;</li><li>Meta fields;</li><li>Required plugins;</li><li>Imagery.</li></ul>', 'crocoblock-wizard' ),
				'featured' => 0,
			),
			array(
				'icon'         => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H42C43.6569 1 45 2.34315 45 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M1 14H45V56C45 57.6569 43.6569 59 42 59H4C2.34314 59 1 57.6569 1 56V14Z" stroke="white" stroke-width="2"/><path d="M59 45C59.5523 45 60 44.5523 60 44C60 43.4477 59.5523 43 59 43L27 43C26.4477 43 26 43.4477 26 44C26 44.5523 26.4477 45 27 45L59 45Z" fill="white"/><path d="M31.2805 49.705C31.6739 50.0983 32.3116 50.0983 32.705 49.705C33.0983 49.3116 33.0983 48.6739 32.705 48.2805L27.7195 43.295C27.3261 42.9017 26.6884 42.9017 26.295 43.295C25.9017 43.6884 25.9017 44.3261 26.295 44.7195L31.2805 49.705Z" fill="white"/><path d="M31.2805 38.295C31.6739 37.9017 32.3116 37.9017 32.705 38.295C33.0983 38.6884 33.0983 39.3261 32.705 39.7195L27.7195 44.705C27.3261 45.0983 26.6884 45.0983 26.295 44.705C25.9017 44.3116 25.9017 43.6739 26.295 43.2805L31.2805 38.295Z" fill="white"/><rect x="6" y="19" width="15" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/></svg>',
				'title'        => __( 'Import skin', 'crocoblock-wizard' ),
				'action_label' => __( 'Click here', 'crocoblock-wizard' ),
				'action_url'   => Plugin::instance()->dashboard->page_url( 'import-skin' ),
				'desc'         => __( 'Choose this option to import the skin that’s been previously exported using Crocoblock Wizard. All the data that was included in it will be applied to this site. The wizard will do the following tasks: <ul><li>Install the required plugins;</li><li>Install the full demo content.</li></ul>', 'crocoblock-wizard' ),
				'featured'     => 0,
			),
			array(
				'icon'         => '<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 9C8.32843 9 9 8.32843 9 7.5C9 6.67157 8.32843 6 7.5 6C6.67157 6 6 6.67157 6 7.5C6 8.32843 6.67157 9 7.5 9Z" fill="white"/><path d="M12.5 9C13.3284 9 14 8.32843 14 7.5C14 6.67157 13.3284 6 12.5 6C11.6716 6 11 6.67157 11 7.5C11 8.32843 11.6716 9 12.5 9Z" fill="white"/><path d="M17.5 9C18.3284 9 19 8.32843 19 7.5C19 6.67157 18.3284 6 17.5 6C16.6716 6 16 6.67157 16 7.5C16 8.32843 16.6716 9 17.5 9Z" fill="white"/><path d="M15 28C15 26.3431 16.3431 25 18 25H56C57.6569 25 59 26.3431 59 28V38H15V28Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><path d="M21.5 33C22.3284 33 23 32.3284 23 31.5C23 30.6716 22.3284 30 21.5 30C20.6716 30 20 30.6716 20 31.5C20 32.3284 20.6716 33 21.5 33Z" fill="white"/><path d="M26.5 33C27.3284 33 28 32.3284 28 31.5C28 30.6716 27.3284 30 26.5 30C25.6716 30 25 30.6716 25 31.5C25 32.3284 25.6716 33 26.5 33Z" fill="white"/><path d="M31.5 33C32.3284 33 33 32.3284 33 31.5C33 30.6716 32.3284 30 31.5 30C30.6716 30 30 30.6716 30 31.5C30 32.3284 30.6716 33 31.5 33Z" fill="white"/><path d="M56 60H18C15.8 60 14 58.2 14 56V37H60V56C60 58.2 58.2 60 56 60ZM16 39V56C16 57.1 16.9 58 18 58H56C57.1 58 58 57.1 58 56V39H16Z" fill="white"/><path d="M21 43H35C35.6 43 36 43.4 36 44C36 44.6 35.6 45 35 45H21C20.4 45 20 44.6 20 44C20 43.4 20.4 43 21 43Z" fill="white"/><path d="M21 47H27C27.6 47 28 47.4 28 48C28 48.6 27.6 49 27 49H21C20.4 49 20 48.6 20 48C20 47.4 20.4 47 21 47Z" fill="white"/><path d="M30.6 58H4C2.9 58 2 57.1 2 56V15H44V25.8H46V13H0V56C0 58.2 1.8 60 4 60H30.6V58Z" fill="white"/><path d="M1 4C1 2.34315 2.34315 1 4 1H42C43.6569 1 45 2.34315 45 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/></svg>',
				'title'        => __( 'Install free templates', 'crocoblock-wizard' ),
				'action_label' => __( 'Click here', 'crocoblock-wizard' ),
				'action_url'   => Plugin::instance()->dashboard->page_url( 'import-template' ),
				'desc'         => __( 'Choose this option to install the free templates acquited from Crocoblock.', 'crocoblock-wizard' ),
				'featured'     => 0,
			),
		) );
	}

	/**
	 * Add welcome component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['welcome'] = 'welcome/main';
		return $templates;

	}

}
