<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Settings manager class
 */
class Settings {

	/**
	 * Manifest file content
	 *
	 * @var array
	 */
	private $all_settings = null;

	/**
	 * External settings
	 *
	 * @var array
	 */
	private $external_settings = array();

	/**
	 * Manifest defaults
	 *
	 * @var array
	 */
	private $defaults = null;

	/**
	 * Has registered external config
	 *
	 * @var boolean
	 */
	private $has_external = false;

	/**
	 * Get settings from array.
	 *
	 * @param  array  $settings Settings trail to get.
	 * @return mixed
	 */
	public function get( $settings = array() ) {

		$all_settings = $this->get_all_settings();

		if ( ! $all_settings ) {
			return false;
		}

		if ( ! is_array( $settings ) ) {
			$settings = array( $settings );
		}

		$count  = count( $settings );
		$result = $all_settings;

		for ( $i = 0; $i < $count; $i++ ) {

			if ( empty( $result[ $settings[ $i ] ] ) ) {
				return false;
			}

			$result = $result[ $settings[ $i ] ];

			if ( $count - 1 === $i ) {
				return $result;
			}

		}

	}

	/**
	 * Check if is kava theme
	 *
	 * @return boolean [description]
	 */
	public function is_kava() {

		if ( ! $this->has_external() ) {
			return false;
		}

		if ( empty( $this->external_settings['plugins']['get_from'] ) ) {
			return false;
		}

		$plugins_url = $this->external_settings['plugins']['get_from'];

		if ( false === strpos( $plugins_url, 'account.crocoblock.com' ) ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Add new 3rd party configuration
	 * @param  array  $config [description]
	 * @return [type]         [description]
	 */
	public function register_external_config( $config = array() ) {
		$this->has_external      = true;
		$this->external_settings = array_merge( $this->external_settings, $config );
	}

	/**
	 * Return external config status
	 * @return boolean [description]
	 */
	public function has_external() {
		return $this->has_external;
	}

	/**
	 * Get mainfest
	 *
	 * @return mixed
	 */
	public function get_all_settings() {

		if ( null !== $this->all_settings ) {
			return $this->all_settings;
		}

		$settings = $this->external_settings;

		$all_settings = array(
			'plugins' => isset( $settings['plugins'] ) ? $settings['plugins'] : $this->get_defaults( 'plugins' ),
			'skins'   => isset( $settings['skins'] )   ? $settings['skins']   : $this->get_defaults( 'skins' ),
			'texts'   => isset( $settings['texts'] )   ? $settings['texts']   : $this->get_defaults( 'texts' ),
			'remap'   => isset( $settings['texts'] )   ? $settings['texts']   : $this->get_defaults( 'remap' ),
			'import'  => isset( $settings['import'] )   ? $settings['import']   : $this->get_defaults( 'import' ),
			'export'  => isset( $settings['export'] )   ? $settings['export']   : $this->get_defaults( 'export' ),
		);

		$this->all_settings = $this->maybe_update_remote_data( $all_settings );

		return $this->all_settings;
	}

	/**
	 * Maybe update remote settings data
	 *
	 * @param  array $settings Plugins settings
	 * @return array
	 */
	public function maybe_update_remote_data( $settings ) {

		if ( ! empty( $settings['plugins']['get_from'] ) ) {
			$settings['plugins'] = $this->get_remote_data( $settings['plugins']['get_from'], 'crocoblock_wizard_plugins' );
		}

		if ( ! empty( $settings['skins']['get_from'] ) ) {
			$settings['skins'] = $this->get_remote_data( $settings['skins']['get_from'], 'crocoblock_wizard_skins' );
		}

		return $settings;

	}

	/**
	 * Get remote data for wizard
	 *
	 * @param  [type] $url           [description]
	 * @param  [type] $transient_key [description]
	 * @return [type]                [description]
	 */
	public function get_remote_data( $url, $transient_key ) {

		$data = get_site_transient( $transient_key );

		if ( $this->has_external() ) {
			$data = false;
		}

		if ( ! $data ) {

			$response = wp_remote_get( $url, array(
				'timeout'   => 60,
				'sslverify' => false,
			) );

			$data = wp_remote_retrieve_body( $response );
			$data = json_decode( $data, true );

			if ( empty( $data ) ) {
				$data = array();
			}

			if ( ! $this->has_external() ) {
				set_site_transient( $transient_key, $data, 2 * DAY_IN_SECONDS );
			}

		}

		return $data;

	}

	/**
	 * Get all registered plugins list
	 *
	 * @return array
	 */
	public function get_all_plugins() {
		$registered = $this->get( array( 'plugins' ) );
		return $registered;
	}

	/**
	 * Clear transien data cahces
	 *
	 * @return [type] [description]
	 */
	public function clear_transient_data() {
		set_site_transient( 'crocoblock_wizard_plugins', null );
		set_site_transient( 'crocoblock_wizard_skins', null );
	}

	/**
	 * Get wizard defaults
	 *
	 * @param  string $part What part of manifest to get (optional - if empty return all)
	 * @return array
	 */
	public function get_defaults( $part = null ) {

		if ( null === $this->defaults ) {

			$plugins = array(
				'get_from' => 'https://account.crocoblock.com/wp-content/uploads/static/wizard-plugins.json',
			);

			$skins = array(
				'get_from' => 'https://account.crocoblock.com/wp-content/uploads/static/wizard-skins-new.json',
			);

			$texts = array(
				'theme-name' => 'Kava'
			);

			$import = array(
				'chunk_size'            => 10,
				'regenerate_chunk_size' => 3,
				'allow_types'           => false,
			);

			$remap = array(
				'post_meta' => array(),
				'term_meta' => array(),
				'options'   => array(
					'jet_woo_builder',
					'woocommerce_catalog_columns',
					'woocommerce_catalog_rows',
				),
			);

			$export = array(
				'options' => array(),
				'tables'  => array(),
			);

			$this->defaults = array(
				'plugins' => $plugins,
				'skins'   => $skins,
				'texts'   => $texts,
				'remap'   => $remap,
				'import'  => $import,
				'export'  => $export,
			);

		}

		if ( ! $part ) {
			return $this->defaults;
		}

		if ( isset( $this->defaults[ $part ] ) ) {
			return $this->defaults[ $part ];
		}

		return array();

	}

}
