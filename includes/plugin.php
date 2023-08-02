<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main file
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	public $dashboard;
	public $modules;
	public $skins;
	public $settings;
	public $files_manager;
	public $storage;
	public $framework;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register autoloader.
	 */
	private function register_autoloader() {
		require CB_WIZARD_PATH . 'includes/autoloader.php';
		Autoloader::run();
	}

	/**
	 * Returns path to view file
	 *
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function get_view( $path ) {
		return apply_filters(
			'crocoblock-wizard/get-view',
			CB_WIZARD_PATH . 'views/' . $path . '.php'
		);
	}

	/**
	 * Initialize plugin parts
	 *
	 * @return void
	 */
	public function init_components() {
		$this->dashboard     = new Dashboard();
		$this->modules       = new Modules_Manager();
		$this->settings      = new Settings();
		$this->skins         = new Skins();
		$this->files_manager = new Tools\Files_Manager();
		$this->storage       = new Tools\Storage();
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {

		// There is nothing to do on front-end
		if ( ! is_admin() ) {
			return;
		}

		$this->register_autoloader();
		$this->init_components();

		// Load framework
		add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

	}

	public function framework_loader() {

		require CB_WIZARD_PATH . 'framework/loader.php';

		$this->framework = new \Crocoblock_Wizard_CX_Loader(
			array(
				CB_WIZARD_PATH . 'framework/vue-ui/cherry-x-vue-ui.php',
			)
		);

	}

}

Plugin::instance();
