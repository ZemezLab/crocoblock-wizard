<?php
namespace Crocoblock_Wizard\Modules\Install_Plugins;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin installation process extensions
 */
class Extensions {

	/**
	 * Constructor for the class.
	 */
	public function __construct() {

		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_bp_redirect' ) );
		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_elementor_redirect' ) );
		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_bbp_redirect' ) );
		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_booked_redirect' ) );
		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_tribe_redirect' ) );
		add_action( 'crocoblock-wizard/after-plugin-activation', array( $this, 'prevent_woo_redirect' ) );
		add_action( 'crocoblock-wizard/install-finished', array( $this, 'ensure_prevent_booked_redirect' ) );

		// Booked sometimes not processed correctly and still redirect so pervent it hard
		add_filter( 'pre_transient__booked_welcome_screen_activation_redirect', array( $this, 'hard_prevent_booked_redirect' ), 10, 2 );
	}

	/**
	 * Ensure that we prevent booked plugin redirect
	 *
	 * @return void
	 */
	public function ensure_prevent_booked_redirect() {

		delete_transient( '_booked_welcome_screen_activation_redirect' );
		update_option( 'booked_welcome_screen', false );

	}

	/**
	 * Set theme wizard success redirect.
	 *
	 * @param string|bool $redirect Redirect
	 */
	public function theme_wizard_success_redirect( $redirect ) {

		$redirect = jet_plugins_wizard()->get_page_link( array( 'step' => 1, 'advanced-install' => 1 ) );
		$skin     = false;

		if ( jet_plugins_wizard_data()->is_single_skin_theme() ) {
			$skin = jet_plugins_wizard_data()->get_first_skin();
		}

		if ( false !== $skin && jet_plugins_wizard_data()->is_single_type_skin( $skin['skin'] ) ) {
			$redirect = jet_plugins_wizard()->get_page_link(
				array( 'step' => 'configure-plugins', 'skin' => $skin['skin'], 'type' => 'full' )
			);
		}

		if ( false !== $skin && ! jet_plugins_wizard_data()->is_single_type_skin( $skin['skin'] ) ) {
			$redirect = jet_plugins_wizard()->get_page_link(
				array( 'step' => 2, 'skin' => $skin['skin'] )
			);
		}

		return $redirect;
	}

	/**
	 * Hard prevent booked redirect
	 *
	 * @param  bool $pre   Pre-get value.
	 * @param  bool $value Default transient value.
	 * @return mixed
	 */
	public function hard_prevent_booked_redirect( $pre, $value ) {
		return null;
	}

	/**
	 * Adds required theme plugins on dashboard page.
	 *
	 * @param object $builder   Builder module instance.
	 * @param object $dashboard Dashboard plugin instance.
	 */
	public function add_dashboard_plugins_section( $builder, $dashboard ) {

		$plugins = jet_plugins_wizard_settings()->get( array( 'plugins' ) );

		if ( empty( $plugins ) ) {
			return;
		}

		ob_start();

		foreach ( $plugins as $slug => $plugin ) {
			$this->single_plugin_item( $slug, $plugin );
		}

		$content = ob_get_clean();

		$builder->register_section(
			array(
				'crocoblock-wizard' => array(
					'title' => esc_html__( 'Recommended plugins', 'crocoblock-wizard' ),
					'class' => 'tm-dashboard-section tm-dashboard-section--crocoblock-wizard',
					'view'  => $dashboard->plugin_dir( 'admin/views/section.php' ),
				),
			)
		);

		$builder->register_html(
			array(
				'crocoblock-wizard-content' => array(
					'parent' => 'crocoblock-wizard',
					'html'   => $content,
				),
			)
		);

	}

	/**
	 * Print single plugin item for dashbaord list.
	 *
	 * @param  string $slug   Plugins slug.
	 * @param  array  $plugin Plugins data.
	 * @return void
	 */
	public function single_plugin_item( $slug, $plugin ) {

		$plugin_data = get_plugins( '/' . $slug );
		$pluginfiles = array_keys( $plugin_data );
		$installed   = true;
		$activated   = false;
		$plugin_path = null;

		if ( empty( $pluginfiles ) ) {
			$installed = false;
		} else {
			$plugin_path = $slug . '/' . $pluginfiles[0];
			$activated   = is_plugin_active( $plugin_path );
		}

		$data = array_merge(
			array(
				'slug'       => $slug,
				'pluginpath' => $plugin_path,
				'installed'  => $installed,
				'activated'  => $activated,
			),
			$plugin
		);

		jet_plugins_wizard()->get_template( 'dashboard/item.php', $data );
	}

	/**
	 * Prevent redirect after WooCommerce activation.
	 *
	 * @param  string $plugin Plugin slug.
	 * @return bool
	 */
	public function prevent_woo_redirect( $plugin ) {

		if ( 'woocommerce' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( '_wc_activation_redirect' );

		return true;
	}

	/**
	 * Prevent BuddyPress redirect.
	 *
	 * @return bool
	 */
	public function prevent_bp_redirect( $plugin ) {

		if ( 'buddypress' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( '_bp_activation_redirect' );
		delete_transient( '_bp_is_new_install' );

		return true;
	}

	/**
	 * Prevent Elementor redirect.
	 *
	 * @return bool
	 */
	public function prevent_elementor_redirect( $plugin ) {

		if ( 'elementor' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( 'elementor_activation_redirect' );

		return true;
	}



	/**
	 * Prevent BBPress redirect.
	 *
	 * @return bool
	 */
	public function prevent_bbp_redirect( $plugin ) {

		if ( 'bbpress' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( '_bbp_activation_redirect' );

		return true;
	}

	/**
	 * Prevent booked redirect.
	 *
	 * @return bool
	 */
	public function prevent_booked_redirect( $plugin ) {

		if ( 'booked' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( '_booked_welcome_screen_activation_redirect' );
		update_option( 'booked_welcome_screen', false );

		return true;
	}

	/**
	 * Prevent tribe events calendar redirect.
	 *
	 * @return bool
	 */
	public function prevent_tribe_redirect( $plugin ) {

		if ( 'the-events-calendar' !== $plugin['slug'] ) {
			return false;
		}

		delete_transient( '_tribe_tickets_activation_redirect' );
		delete_transient( '_tribe_events_activation_redirect' );

		return true;
	}

	/**
	 * Add multi-install argument.
	 *
	 * @param  array  $data   Send data.
	 * @param  string $plugin Plugin slug.
	 * @return array
	 */
	public function add_multi_arg( $data = array(), $plugin = '' ) {

		if ( in_array( $plugin, array( 'woocommerce', 'booked' ) ) ) {
			$data['activate-multi'] = true;
		}

		return $data;
	}

}
