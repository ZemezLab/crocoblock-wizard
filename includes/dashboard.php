<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Dashboard manager class
 */
class Dashboard {

	private $subpage = null;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	/**
	 * Register menu page
	 *
	 * @return void
	 */
	public function register_menu_page() {

		add_management_page(
			__( 'CrocoBlock Wizard', 'crocoblock-wizard' ),
			__( 'CrocoBlock Wizard', 'crocoblock-wizard' ),
			'manage_options',
			'crocoblock-wizard',
			array( $this, 'render_wizard' )
		);

	}

	/**
	 * Wizard assets
	 *
	 * @return void
	 */
	public function assets( $hook ) {

		if ( 'tools_page_crocoblock-wizard' !== $hook ) {
			return;
		}

		require_once CB_WIZARD_PATH . 'framework/vue-ui/cherry-x-vue-ui.php';

		$ui = new \CX_Vue_UI( array(
			'url'  => CB_WIZARD_URL . 'framework/vue-ui/',
			'path' => CB_WIZARD_PATH . 'framework/vue-ui/',
		) );

		$ui->enqueue_assets();

		/**
		 * Fires before enqueue page assets
		 */
		do_action( 'crocoblock-wizard/dashboard/before-enqueue-assets', $this );

		/**
		 * Fires before enqueue page assets with dynamic subpage name
		 */
		do_action( 'crocoblock-wizard/dashboard/before-enqueue-assets/' . $this->get_subpage(), $this );

		wp_enqueue_script(
			'crocoblock-wizard',
			CB_WIZARD_URL . 'assets/js/wizard.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

		wp_localize_script(
			'crocoblock-wizard',
			'CBWPageConfig',
			apply_filters( 'crocoblock-wizard/dashboard/js-page-config', array(
				'title'       => false,
				'next_label'  => false,
				'next_page'   => false,
				'prev_page'   => false,
				'cover'       => false,
				'wrapper_css' => false,
			), $this->get_subpage() )
		);

		add_action( 'admin_footer', array( $this, 'print_js_templates' ) );

	}

	/**
	 * Print JS templates for current page
	 *
	 * @return [type] [description]
	 */
	public function print_js_templates() {

		$templates = apply_filters( 'crocoblock-wizard/dashboard/js-page-template', array(
			'main' => 'common/main',
		), $this->get_subpage() );

		foreach ( $templates as $name => $path ) {
			ob_start()
			include Plugin::instance()->get_view( $path );
			$content = ob_get_clean();

			printf(
				'<script type="text/x-template" id="cbw_%1$s">%2$s</script>',
				$name
				$content
			);
		}

	}

	/**
	 * Returns current subpage slug
	 *
	 * @return string
	 */
	public function get_subpage() {

		if ( null === $this->subpage ) {
			$this->subpage = isset( $_GET['sub'] ) ? esc_attr( $_GET['sub'] ) : $this->get_initial_page();
		}

		return $this->subpage;
	}

	/**
	 * Returns wizard initial subpage
	 *
	 * @return string
	 */
	public function get_initial_page() {
		return 'license';
	}

	/**
	 * Render installation wizard page
	 *
	 * @return void
	 */
	public function render_wizard() {
		include Plugin::instance()->get_view( 'common/page' );
	}

}
