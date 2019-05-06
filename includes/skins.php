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
			'model' => __( 'Models', 'crocoblock-wizard' ),
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
	public function get_skin_data( $key = null ) {

		if ( empty( $this->skin ) ) {

			$skin = isset( $_GET['skin'] ) ? esc_attr( $_GET['skin'] ) : false;

			if ( ! $skin ) {
				return false;
			}

			$data = Plugin::instance()->settings->get( array( 'skins', $skin ) );
			$this->the_skin( $skin, $data );

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
	public function get_skin_plugins( $slug = null ) {

		$skins = $this->get_skins();
		$skin  = isset( $skins[ $slug ] ) ? $skins[ $slug ] : false;

		if ( ! $skin ) {
			return '';
		}

		$plugins = $skin[ 'full' ];

		if ( empty( $plugins ) ) {
			return '';
		}

		$registered  = Plugin::instance()->settings->get( array( 'plugins' ) );
		$plugins_str = '';

		foreach ( $plugins as $plugin ) {

			$plugin_data = isset( $registered[ $plugin ] ) ? $registered[ $plugin ] : false;

			if ( ! $plugin_data ) {
				continue;
			}

			$plugins_str .= sprintf( $format, $plugin_data['name'] );
		}

		return $registered;
	}

}
