<?php
namespace Crocoblock_Wizard\Tools;

/**
 * Data cache handler
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Cache class
 */
class Cache {

	/**
	 * Import data caching metod.
	 *
	 * @var string
	 */
	private $caching_method = 'session';

	/**
	 * Active cache handler instance
	 *
	 * @var null
	 */
	private $handler = null;

	/**
	 * Registered cache handlers array
	 *
	 * @var array
	 */
	private $handlers = array();

	/**
	 * Base caching group name
	 *
	 * @var string
	 */
	public $base_group = 'crocoblock-wizard';

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		$method = $this->get_caching_method();

		if ( isset( $this->handlers[ $method ] ) ) {
			$handler = $this->handlers[ $method ];
		} else {
			$handler = 'Jet_Data_Importer_Session_Cache';
		}

		switch ( $method ) {
			case 'file':
				$this->handler = new Cache\File( $this->base_group );
				break;

			default:
				$this->handler = new Cache\Session( $this->base_group );
				break;
		}

	}

	/**
	 * Returns appropriate caching method for current server/
	 *
	 * @return string
	 */
	private function get_caching_method() {

		if ( ! session_id() ) {
			$this->caching_method = 'file';
		} else {
			$this->caching_method = 'session';
		}

		$cache_handler = get_option( 'crocoblock_wizard_cache_handler', 'session' );

		if ( $cache_handler ) {
			$this->caching_method = $cache_handler;
		}

		return $this->caching_method;
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
		$this->handler->update( $key, $value, $group );
	}

	/**
	 * Get value from cache by key.
	 *
	 * @param  string $key   Caching key.
	 * @param  string $group Caching group.
	 * @return bool
	 */
	public function get( $key = null, $group = 'global' ) {
		return $this->handler->get( $key, $group );
	}

	/**
	 * Get all group values from cache by group name.
	 *
	 * @param  string $group Caching group.
	 * @return bool
	 */
	public function get_group( $group = 'global' ) {
		return $this->handler->get_group( $group );
	}

	/**
	 * Clear cache for passed group or all cache if group not provided.
	 *
	 * @param  string $group Caching group to clear.
	 * @return bool
	 */
	public function clear_cache( $group = null ) {
		return $this->handler->clear_cache( $group );
	}

	/**
	 * Write object cahce to static.
	 *
	 * @param  string $group Caching group to clear.
	 * @return bool
	 */
	public function write_cache() {
		return $this->handler->write_cache();
	}

}
