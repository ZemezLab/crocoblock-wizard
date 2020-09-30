<?php
namespace Crocoblock_Wizard\Tools\Cache;

use Crocoblock_Wizard\Plugin as Plugin;

/**
 * Base class for caching method. All caching methods must be extends from this class.
 * All methods are required for child classes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define file cache class
 */
class File extends Base {

	private $_object_cache = null;
	private $_updated      = false;

	/**
	 * Store passed value in cache with passed key.
	 *
	 * @param  string $key   Caching key.
	 * @param  mixed  $value Value to save.
	 * @param  string $group Caching group.
	 * @return bool
	 */
	public function update( $key = null, $value = null, $group = 'global' ) {

		$this->setup_cache_group( $group );

		$this->_object_cache[ $group ][ $key ] = $value;

		$this->_updated = true;
	}

	/**
	 * Returns all stored cache
	 *
	 * @return array
	 */
	public function get_all() {
		if ( ! $this->_object_cache ) {
			return array();
		} else {
			$this->_object_cache;
		}
	}

	/**
	 * Returns whole stored group
	 *
	 * @return array
	 */
	public function get_group( $group = 'global' ) {

		$this->setup_cache_group( $group );

		if ( ! isset( $this->_object_cache[ $group ] ) ) {
			return array();
		}

		return $this->_object_cache[ $group ];
	}

	/**
	 * Returns current value by key
	 *
	 * @return array
	 */
	public function get( $key = null, $group = 'global' ) {

		$this->setup_cache_group( $group );

		if ( ! isset( $this->_object_cache[ $group ] ) ) {
			return false;
		}

		if ( ! isset( $this->_object_cache[ $group ][ $key ] ) ) {
			return false;
		}

		return $this->_object_cache[ $group ][ $key ];
	}

	/**
	 * Create base caching group if not exist.
	 */
	public function setup_cache() {

		if ( null === $this->_object_cache ) {

			$current = Plugin::instance()->files_manager->get_json( 'cache.json' );

			if ( ! $current ) {
				$this->_object_cache = array();
			} else {
				$this->_object_cache = $current;
			}

		}

	}

	/**
	 * Create new group in base caching group if not exists
	 */
	public function setup_cache_group( $group = 'global' ) {

		$this->setup_cache();

		if ( ! isset( $this->_object_cache[ $group ] ) ) {
			$this->_object_cache[ $group ] = array();
		}

	}

	/**
	 * Returns whole stored group
	 *
	 * @return void
	 */
	public function clear_cache( $group = null ) {

		if ( isset( $this->_object_cache['mapping'] ) ) {
			update_option( 'cache', $this->_object_cache['mapping'] );
		}

		if ( null !== $group ) {
			$this->_object_cache[ $group ] = array();
			$this->write_cache();
		} else {
			$this->_object_cache = array();
			Plugin::instance()->files_manager->delete( 'cache.json' );
		}

	}

	/**
	 * Write static cache
	 *
	 * @return [type] [description]
	 */
	public function write_cache() {

		if ( true === $this->_updated ) {
			Plugin::instance()->files_manager->put_json( 'cache.json', $this->_object_cache );
		}

	}

}
