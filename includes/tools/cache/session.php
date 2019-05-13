<?php
namespace Crocoblock_Wizard\Tools\Cache;

/**
 * Seesion cahce handler
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define session cache class
 */
class Session extends Base {

	/**
	 * Base caching group name
	 *
	 * @var string
	 */
	private $base_group = null;

	/**
	 * Constructor for the class
	 */
	public function __construct( $base_group ) {

		if ( ! session_id() ) {
			session_start();
		}

		$this->base_group = $base_group;
	}

	/**
	 * Store passed value in cache with passed key.
	 *
	 * @param  string $key   Caching key.
	 * @param  mixed  $value Value to save.
	 * @param  string $group Caching group.
	 * @return bool
	 */
	public function update( $key = null, $value = null, $group = 'global' ) {

		$this->setup_cahe_group( $group );

		$_SESSION[ $this->base_group ][ $group ][ $key ] = $value;

	}

	/**
	 * Returns all stored cache
	 *
	 * @return array
	 */
	public function get_all() {
		if ( ! isset( $_SESSION[ $this->base_group ] ) ) {
			return array();
		}
	}

	/**
	 * Returns whole stored group
	 *
	 * @return array
	 */
	public function get_group( $group = 'global' ) {
		if ( ! isset( $_SESSION[ $this->base_group ][ $group ] ) ) {
			return array();
		}
		return $_SESSION[ $this->base_group ][ $group ];
	}

	/**
	 * Returns current value by key
	 *
	 * @return array
	 */
	public function get( $key = null, $group = 'global' ) {

		if ( ! isset( $_SESSION[ $this->base_group ][ $group ] ) ) {
			return false;
		}

		if ( ! isset( $_SESSION[ $this->base_group ][ $group ][ $key ] ) ) {
			return false;
		}

		return $_SESSION[ $this->base_group ][ $group ][ $key ];
	}

	/**
	 * Create base caching group if not exist.
	 */
	public function setup_cahe() {

		if ( ! isset( $_SESSION[ $this->base_group ] ) ) {
			$_SESSION[ $this->base_group ] = array();
		}

	}

	/**
	 * Create new group in base caching group if not exists
	 */
	public function setup_cahe_group( $group = 'global' ) {

		$this->setup_cahe();

		if ( ! isset( $_SESSION[ $this->base_group ][ $group ] ) ) {
			$_SESSION[ $this->base_group ][ $group ] = array();
		}

	}

	/**
	 * Returns whole stored group
	 *
	 * @return void
	 */
	public function clear_cache( $group = null ) {

		if ( isset( $_SESSION[ $this->base_group ]['mapping'] ) ) {
			update_option( 'cache', $_SESSION[ $this->base_group ]['mapping'] );
		}

		if ( null !== $group ) {
			$_SESSION[ $this->base_group ][ $group ] = array();
		} else {
			$_SESSION[ $this->base_group ] = array();
		}


	}

}
