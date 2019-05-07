<?php
namespace Crocoblock_Wizard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Storage manager class
 */
class Storage {

	private $slug      = 'crocoblock-wizard';
	private $data      = array();

	/**
	 * Add new value to Wizard status storage
	 *
	 * @param  string $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function store( $key = '', $value = null ) {

		$this->data[ $key ] = $value;
		$saved              = $this->get_saved_data();
		$saved[ $key ]      = $value;

		set_transient( $this->slug, $saved, 3 * DAY_IN_SECONDS );

	}

	/**
	 * Returns saved data from DB
	 *
	 * @return [type] [description]
	 */
	public function get_saved_data() {

		$saved = get_transient( $this->slug );

		if ( ! $saved ) {
			$saved = array();
		}

		return $saved;

	}

	/**
	 * Get value from temporary storage
	 *
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function get( $key = '' ) {

		if ( empty( $this->data ) ) {
			$this->data = $this->get_saved_data();
		}

		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : false;

	}

}
