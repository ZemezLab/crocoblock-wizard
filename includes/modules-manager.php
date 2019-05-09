<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Modules manager class
 */
class Modules_Manager {

	/**
	 * Modules map
	 *
	 * @var array
	 */
	private $_modules = array(
		'license'         => '\\Crocoblock_Wizard\\Modules\\License\\Module',
		'install-theme'   => '\\Crocoblock_Wizard\\Modules\\Install_Theme\\Module',
		'select-skin'     => '\\Crocoblock_Wizard\\Modules\\Select_Skin\\Module',
		'install-plugins' => '\\Crocoblock_Wizard\\Modules\\Install_Plugins\\Module',
		'import-content'  => '\\Crocoblock_Wizard\\Modules\\Import_Content\\Module',
	);

	private $_loaded_modules = array();

	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_modules' ) );
	}

	/**
	 * Initialize modules on aproppriate AJAX or  on module page
	 *
	 * @return [type] [description]
	 */
	public function init_modules() {

		if ( wp_doing_ajax() ) {
			$this->maybe_load_module_on_ajax();
		} else {
			$this->maybe_load_module();
		}
	}

	/**
	 * Maybe load on ajax request
	 *
	 * @return [type] [description]
	 */
	public function maybe_load_module_on_ajax() {

		$action = ! empty( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;

		if ( ! $action ) {
			return;
		}

		$parts = explode( '/', $action );

		if ( empty( $parts[1] ) || Plugin::instance()->dashboard->page_slug !== $parts[0] ) {
			return;
		}

		$module = $parts[1];

		$this->load_module( $module );

	}

	/**
	 * Maybe load on regular request
	 *
	 * @return [type] [description]
	 */
	public function maybe_load_module() {

		if ( ! Plugin::instance()->dashboard->is_dashboard_page() ) {
			return;
		}

		$module = Plugin::instance()->dashboard->get_subpage();

		$this->load_module( $module );

	}

	/**
	 * Load module by slug
	 *
	 * @param  [type] $module [description]
	 * @return [type]         [description]
	 */
	public function load_module( $module ) {

		if ( ! isset( $this->_modules[ $module ] ) ) {
			return;
		}

		$class_name = $this->_modules[ $module ];

		return new $class_name();

	}

}
