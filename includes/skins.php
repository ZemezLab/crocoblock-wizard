<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Skins manager class
 */
class Skins {

	/**
	 * Holder for skins list.
	 *
	 * @var array
	 */
	private $skins = null;

	private $uploaded_skins_settings = array();

	/**
	 * Currently disabled plugins list
	 *
	 * @var array
	 */
	private $disabled_plugins = array();

	/**
	 * Holder for current skin data.
	 *
	 * @var array
	 */
	private $skin = null;

	public function get_all_skins() {

		if ( ! empty( $this->skins ) ) {
			return $this->skins;
		}

		$this->skins = Plugin::instance()->settings->get( array( 'skins' ) );

		if ( empty( $this->skins ) ) {
			$this->skins = array();
		}

		return $this->skins;

	}

	/**
	 * Return available skins list
	 *
	 * @return array
	 */
	public function get_skins( $by_type = false ) {

		$skins = $this->get_all_skins();

		if ( $by_type ) {
			return array_filter( $skins, function( $skin ) use ( $by_type ) {
				if ( ! isset( $skin['type'] ) ) {
					return 'skin' === $by_type;
				} else {
					return $skin['type'] === $by_type;
				}
			} );
		} else {
			return $skins;
		}

	}

	/**
	 * Get all skins grouped by all allowed types
	 *
	 * @return array
	 */
	public function get_skins_by_types() {

		$result = array();

		foreach ( $this->get_types() as $type => $label ) {
			$result[ $type ] = $this->get_skins( $type );
		}

		return $result;
	}

	/**
	 * Returns allowed skins types
	 *
	 * @return [type] [description]
	 */
	public function get_types() {
		return array(
			'skin'  => __( 'Pre-made sites', 'crocoblock-wizard' ),
			//'model' => __( 'Models', 'crocoblock-wizard' ),
		);
	}

	/**
	 * Setup processed skin data
	 *
	 * @param  string $slug Skin slug.
	 * @param  array  $data Skin data.
	 * @return void
	 */
	public function the_skin( $slug = null, $data = array() ) {
		$data['slug'] = $slug;
		$this->skin = $data;
	}

	/**
	 * Retrun processed skin data
	 *
	 * @return array
	 */
	public function get_skin() {
		return $this->skin;
	}

	/**
	 * Get info by current screen.
	 *
	 * @param  string $key Key name.
	 * @return mixed
	 */
	public function get_skin_data( $key = null, $skin = null ) {

		if ( empty( $this->skin ) ) {

			if ( ! $skin ) {
				$skin = isset( $_GET['skin'] ) ? esc_attr( $_GET['skin'] ) : false;
			}

			if ( ! $skin ) {
				return false;
			}

			$data = Plugin::instance()->settings->get( array( 'skins', $skin ) );
			$this->the_skin( $skin, $data );

		}

		if ( ! $key ) {
			return $this->skin;
		}

		if ( empty( $this->skin[ $key ] ) ) {
			return false;
		}

		return $this->skin[ $key ];

	}

	/**
	 * Returns skin plugins list
	 *
	 * @param  string $slug Skin name.
	 * @return string
	 */
	public function get_skin_plugins( $slug = null, $is_uploaded = false ) {

		if ( $is_uploaded ) {
			return $this->get_uploaded_skin_plugins( $slug );
		}

		$skins = $this->get_skins();
		$skin  = isset( $skins[ $slug ] ) ? $skins[ $slug ] : false;

		if ( ! $skin ) {
			return '';
		}

		$plugins = $skin[ 'full' ];

		if ( empty( $plugins ) ) {
			return array();
		} else {
			return $plugins;
		}

	}

	/**
	 * Returns plugins list for uploaded skin
	 */
	public function get_uploaded_skin_plugins( $slug ) {

		$settings = $this->get_uploaded_skin_settings( $slug );

		if ( empty( $settings['plugins'] ) ) {
			return array();
		}

		return isset( $settings['plugins'] ) ? array_keys( $settings['plugins'] ) : array();

	}

	/**
	 * Returns uploaded skins settings
	 *
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function get_uploaded_skin_settings( $slug ) {

		if ( empty( $this->uploaded_skins_settings[ $slug ] ) ) {

			$path = Plugin::instance()->files_manager->base_path() . $slug . '/settings.json';

			if ( ! is_readable( $path ) ) {
				return array();
			}

			ob_start();
			include $path;
			$settings = ob_get_clean();
			$settings = json_decode( $settings, true );

			$this->uploaded_skins_settings[ $slug ] = $settings;

		}

		return $this->uploaded_skins_settings[ $slug ];

	}

	/**
	 * Returns all plugin allowed for license
	 *
	 * @return [type] [description]
	 */
	public function get_plugins_for_license() {

		$plugins          = Plugin::instance()->settings->get_all_plugins();
		$filtered_plugins = array();
		$excluded_plugins = get_option( 'jet_excluded_plugins', array() );

		if ( ! is_array( $excluded_plugins ) ) {
			$excluded_plugins = array();
		}

		foreach ( $plugins as $slug => $plugin ) {
			if ( 'crocoblock' === $plugin['source'] && ! in_array( $slug, $excluded_plugins ) ) {
				$filtered_plugins[ $slug ] = $plugin;
			} else {
				$this->disabled_plugins[ $slug ] = $plugin;
			}
		}

		return $filtered_plugins;
	}

	/**
	 * Check if plugin is disabled
	 *
	 * @param  [type]  $slug [description]
	 * @return boolean       [description]
	 */
	public function is_plugin_disabled( $slug ) {

		if ( empty( $this->disabled_plugins ) ) {
			return false;
		}

		return isset( $this->disabled_plugins[ $slug ] );

	}

	/**
	 * Return disabled plugins
	 *
	 * @return [type] [description]
	 */
	public function disabled_plugins() {
		return array_keys( $this->disabled_plugins );
	}

	/**
	 * Returns all registered plugins
	 *
	 * @return [type] [description]
	 */
	public function get_all_plugins( $skin = null, $is_uploaded = false ) {

		$plugins = Plugin::instance()->settings->get_all_plugins();

		if ( $skin && $is_uploaded ) {

			$settings = $this->get_uploaded_skin_settings( $skin );

			if ( ! empty( $settings['plugins'] ) ) {
				$plugins = array_merge( $plugins, $settings['plugins'] );
			}
		}

		return $plugins;

	}

}
