<?php
namespace Crocoblock_Wizard\Modules\Install_Theme;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	public $settings = array(
		'parent_data' => 'installed_parent',
		'child_data'  => 'installed_child',
	);

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'install-theme';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-theme',
			CB_WIZARD_URL . 'assets/js/theme.js',
			array( 'cx-vue-ui', 'crocoblock-wizard-mixins' ),
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

		$config['title']       = __( 'Use child theme?', 'crocoblock-wizard' );
		$config['cover']       = CB_WIZARD_URL . 'assets/img/cover-2.png';
		$config['body']        = 'cbw-install-theme';
		$config['wrapper_css'] = 'vertical-flex';
		$config['prev_step']   = Plugin::instance()->dashboard->page_url( 'license' );
		$config['next_step']   = Plugin::instance()->dashboard->page_url( 'select-skin' );
		$config['get_child']   = __( 'Generating child theme...', 'crocoblock-wizard' );
		$config['choices']     = array(
			array(
				'value'       => 'parent',
				'label'       => __( 'Continue with parent theme', 'crocoblock-wizard' ),
				'description' => __( 'Skip child theme installation and continute with paarent theme.', 'crocoblock-wizard' ),
			),
			array(
				'value'       => 'child',
				'label'       => __( 'Use child theme', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
		);

		return $config;

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['install_theme'] = 'install-theme/main';
		return $templates;

	}

	/**
	 * Process parent theme installation
	 *
	 * @return void
	 */
	public function install_parent() {

		$install_data = Plugin::instance()->storage->get( 'theme_data' );
		$theme_url    = isset( $install_data['link'] ) ? $install_data['link'] : false;
		$theme_slug   = isset( $install_data['id'] ) ? $install_data['id'] : false;

		/**
		 * Allow to filter parent theme URL
		 *
		 * @var string
		 */
		$theme_url = apply_filters( 'crocoblock-wizard/install-theme/parent-zip-url', $theme_url );

		if ( ! $theme_url ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Theme URL was lost. Please refresh page and try again.', 'crocoblock-wizard' ),
			) );
		}

		$api = new API( $theme_url );

		$result = $api->do_theme_install();

		if ( true !== $result['success'] ) {
			wp_send_json_error( array(
				'message' => $result['message'],
			) );
		}

		Plugin::instance()->storage->store( $this->settings['parent_data'], array(
			'TextDomain' => $theme_slug,
			'ThemeName'  => ucfirst( $theme_slug ),
		) );

		/**
		 * Fires when parent installed before sending result.
		 */
		do_action( 'crocoblock-wizard/install-theme/parent-installed' );

		wp_send_json_success( array(
			'message'     => $result['message'],
			'doNext'      => true,
			'nextRequest' => array(
				'action'  => Plugin::instance()->dashboard->page_slug . '/install-theme',
				'handler' => 'activate_parent',
			),
		) );
	}

	/**
	 * Process parent theme activation.
	 *
	 * @return void
	 */
	public function activate_parent() {
		$this->activate_theme( 'parent', $this->get_page_link() );
	}

	/**
	 * Perforem child theme installation
	 *
	 * @return void
	 */
	public function get_child() {

		$theme_data   = Plugin::instance()->storage->get( $this->settings['parent_data'] );
		$install_data = Plugin::instance()->storage->get( 'theme_data' );
		$id           = isset( $install_data['id'] ) ? esc_attr( $install_data['id'] ) : false;
		$slug         = isset( $theme_data['TextDomain'] ) ? esc_attr( $theme_data['TextDomain'] ) : false;
		$name         = isset( $theme_data['ThemeName'] ) ? esc_attr( $theme_data['ThemeName'] ) : false;

		if ( ! $id || ! $slug || ! $name ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Installation data lost, please return to previous step and try again.', 'crocoblock-wizard' ),
			) );
		}

		$api        = new Child_API( $id, $slug, $name );
		$child_data = $api->api_call();

		if ( empty( $child_data['success'] ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Request failed. Please, try again later.', 'crocoblock-wizard' ),
			) );
		}

		if ( false === $child_data['success'] ) {
			wp_send_json_error( $child_data['data']['message'] );
		}

		$theme_url = $child_data['data']['theme'];

		wp_send_json_success( array(
			'message'     => esc_html__( 'Child theme generated. Installing...', 'crocoblock-wizard' ),
			'doNext'      => true,
			'nextRequest' => array(
				'action'  => Plugin::instance()->dashboard->page_slug . '/' . $this->get_slug(),
				'handler' => 'install_child',
				'child'   => $theme_url,
			),
		) );
	}

	/**
	 * Perform child theme installation
	 *
	 * @return void
	 */
	public function install_child() {

		$theme_url = isset( $_REQUEST['child'] ) ? esc_url( $_REQUEST['child'] ) : false;

		if ( false !== $theme_url && false === strpos( $theme_url, 'http' ) ) {
			$theme_url = 'http:' . $theme_url;
		}

		/**
		 * Allow to rewrite child theme URL.
		 *
		 * @var string
		 */
		$theme_url = apply_filters( 'crocoblock-wizard/install-theme/child-theme-url', $theme_url );

		$api = new API( $theme_url );

		$result = $api->do_theme_install();

		if ( true !== $result['success'] ) {
			wp_send_json_error( array(
				'message' => $result['message'],
			) );
		}

		$parent_data = Plugin::instance()->storage->get( $this->settings['parent_data'] );
		$slug        = isset( $parent_data['TextDomain'] ) ? esc_attr( $parent_data['TextDomain'] ) : false;
		$name        = isset( $parent_data['ThemeName'] ) ? esc_attr( $parent_data['ThemeName'] ) : false;

		Plugin::instance()->storage->store( $this->settings['child_data'], array(
			'TextDomain' => $slug . '-child',
			'ThemeName'  => $name . ' Child',
		) );

		/**
		 * Fires when child theme installed before sending result.
		 */
		do_action( 'crocoblock-wizard/install-theme/child-installed' );

		wp_send_json_success( array(
			'message'     => $result['message'],
			'doNext'      => true,
			'nextRequest' => array(
				'action'  => Plugin::instance()->dashboard->page_slug . '/' . $this->get_slug(),
				'handler' => 'activate_child',
			),
		) );
	}

	/**
	 * Perfrorm child theme activation
	 *
	 * @return void
	 */
	public function activate_child() {
		$this->activate_theme( 'child', Plugin::instance()->dashboard->page_url( 'select-skin' ) );
	}

	/**
	 * Perform theme activation by type.
	 *
	 * @param  string $type Paretn/child.
	 * @return void
	 */
	public function activate_theme( $type = 'parent', $redirect = false ) {

		if ( ! in_array( $type, array( 'parent', 'child' ) ) ) {
			$type = 'parent';
		}

		$option     = $type . '_data';
		$theme_data = Plugin::instance()->storage->get( $this->settings[ $option ] );

		/**
		 * Fires before theme activation
		 */
		do_action( 'crocoblock-wizard/install-theme/before-activation', $type, $theme_data );

		if ( empty( $theme_data['TextDomain'] ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Can\'t find theme to activate', 'crocoblock-wizard' ),
			) );
		}

		$theme_name    = $theme_data['TextDomain'];
		$current_theme = wp_get_theme();

		if ( $current_theme->stylesheet === $theme_name ) {
			$message = esc_html__( 'The theme is already active. Redirecting...', 'crocoblock-wizard' );
		} else {
			$message = esc_html__( 'The theme is sucessfully activated. Redirecting...', 'crocoblock-wizard' );
			switch_theme( $theme_name );
		}

		/**
		 * Fires after parent theme activation
		 */
		do_action( 'crocoblock-wizard/install-theme/after-activation', $type, $theme_data );

		$response = apply_filters( 'crocoblock-wizard/install-theme/activate-theme-response', array(
			'message'  => $message,
			'redirect' => $redirect,
		), $type );

		wp_send_json_success( $response );

	}

}
