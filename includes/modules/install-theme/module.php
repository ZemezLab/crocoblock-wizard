<?php
namespace Crocoblock_Wizard\Modules\Install_Theme;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $message = null;

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

		$config['body']        = 'cbw-select-theme';
		$config['wrapper_css'] = 'theme-page';
		$config['install']     = $this->get_install_config();
		$config['select']      = $this->get_select_config();

		return $config;

	}

	/**
	 * Returns select theme onfiguration data
	 *
	 * @return [type] [description]
	 */
	public function get_select_config() {
		return array(
			'next_step' => array(
				'selected' => 'cbw-install-theme',
				'current'  => Plugin::instance()->dashboard->page_url( 'select-skin' ),
			),
			'themes'    => $this->get_available_themes(),
			'prev'      => Plugin::instance()->dashboard->page_url( 'license' ),
		);
	}

	/**
	 * Returns available themes list
	 * @return [type] [description]
	 */
	public function get_available_themes() {
		return apply_filters( 'crocoblock-wizard/install-theme/available-themes', array(
			'kava' => array(
				'source' => 'crocoblock',
				'logo'   => CB_WIZARD_URL . 'assets/img/kava.png',
			),
			'oceanwp' => array(
				'source' => 'wordpress',
				'logo'   => CB_WIZARD_URL . 'assets/img/ocean.png',
			),
			'astra' => array(
				'source' => 'wordpress',
				'logo'   => CB_WIZARD_URL . 'assets/img/astra.png',
			),
			'generatepress' => array(
				'source' => 'wordpress',
				'logo'   => CB_WIZARD_URL . 'assets/img/generatepress.png',
			),
			'hello-elementor' => array(
				'source' => 'wordpress',
				'logo'   => CB_WIZARD_URL . 'assets/img/hello.png',
			),
			'blocksy' => array(
				'source' => 'wordpress',
				'logo'   => CB_WIZARD_URL . 'assets/img/blocksy.png',
			),
		) );
	}

	/**
	 * Returns theme URL
	 *
	 * @return [type] [description]
	 */
	public function get_theme_url( $theme ) {

		$themes = $this->get_available_themes();
		$data   = ! empty( $themes[ $theme ] ) ? $themes[ $theme ] : false;

		if ( ! $data ) {
			return false;
		}

		switch ( $data['source'] ) {

			case 'wordpress':

				if ( ! function_exists( 'themes_api' ) ) {
					include_once( ABSPATH . 'wp-admin/includes/theme.php' );
				}

				$api = themes_api(
					'theme_information',
					array(
						'slug'   => $theme,
						'fields' => array( 'sections' => false ),
					)
				);

				if ( is_wp_error( $api ) ) {
					$this->message = $api->get_error_message();
					return false;
				} else {
					return $api->download_link;
				}

			case 'remote':
				return isset( $data['url'] ) ? $data['url'] : false;
		}

	}

	/**
	 * Returns install theme configuration data
	 *
	 * @return [type] [description]
	 */
	public function get_install_config() {
		return array(
			'get_parent' => __( 'Installing theme...', 'crocoblock-wizard' ),
			'prev'      => array(
				'to'   => 'cbw-install-theme',
				'type' => 'component',
			),
			'choices'   => array(
				array(
					'value'       => 'parent',
					'label'       => __( 'Continue with parent theme', 'crocoblock-wizard' ),
					'description' => __( 'Install the Parent theme only, and skip the installation of the Child theme', 'crocoblock-wizard' ),
				),
				array(
					'value'       => 'child',
					'label'       => __( 'Use child theme', 'crocoblock-wizard' ),
					'description' => __( 'Download and install the Child theme. We recommend doing this because it’s the safest way to make future modifications', 'crocoblock-wizard' ),
				),
			),
		);
	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['install_theme'] = 'install-theme/install';
		$templates['select_theme']  = 'install-theme/select';
		return $templates;

	}

	/**
	 * Process parent theme installation
	 *
	 * @return void
	 */
	public function install_parent() {

		$theme         = ! empty( $_REQUEST['theme'] ) ? esc_attr( $_REQUEST['theme'] ) : false;
		$install_child = ! empty( $_REQUEST['child'] ) ? esc_attr( $_REQUEST['child'] ) : false;

		if ( ! $theme ) {
			$theme = 'kava';
		}

		if ( 'kava' === $theme ) {
			$license      = Plugin::instance()->modules->load_module( 'license' );
			$api          = $license->get_api();
			$install_data = $api->get_kava_installation_data();
		} else {

			$theme_url = $this->get_theme_url( $theme );

			if ( ! $theme_url ) {
				if ( ! $this->message ) {
					wp_send_json_error( array(
						'message' => __( 'Theme URL not found', 'crocoblock-wizard' ),
					) );
				} else {
					wp_send_json_error( array(
						'message' => $this->message,
					) );
				}
			}

			$install_data = array(
				'id'   => $theme,
				'link' => $theme_url,
			);
		}

		$theme_url  = isset( $install_data['link'] ) ? $install_data['link'] : false;
		$theme_slug = isset( $install_data['id'] ) ? $install_data['id'] : false;

		Plugin::instance()->storage->store( 'theme_data', $install_data );

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

		switch ( $theme_slug ) {
			case 'generatepress':
				$theme_name = 'GeneratePress';
				break;

			case 'oceanwp':
				$theme_name = 'OceanWP';
				break;

			default:
				$theme_name = ucfirst( $theme_slug );
				break;
		}

		Plugin::instance()->storage->store( $this->settings['parent_data'], array(
			'TextDomain' => $theme_slug,
			'ThemeName'  => $theme_name,
		) );

		/**
		 * Fires when parent installed before sending result.
		 */
		do_action( 'crocoblock-wizard/install-theme/parent-installed' );

		$install_child = filter_var( $install_child, FILTER_VALIDATE_BOOLEAN );

		if ( $install_child ) {
			$handler = 'get_child';
		} else {
			$handler = 'activate_parent';
		}

		wp_send_json_success( array(
			'message'     => $result['message'],
			'doNext'      => true,
			'nextRequest' => array(
				'action'  => Plugin::instance()->dashboard->page_slug . '/install-theme',
				'handler' => $handler,
			),
		) );
	}

	/**
	 * Process parent theme activation.
	 *
	 * @return void
	 */
	public function activate_parent() {
		$this->activate_theme( 'parent', Plugin::instance()->dashboard->page_url( 'select-skin' ) );
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
