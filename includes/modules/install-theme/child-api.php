<?php
namespace Crocoblock_Wizard\Modules\Install_Theme;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Child_API class
 */
class Child_API {

	/**
	 * Passed Template ID holder.
	 *
	 * @var int
	 */
	private $id = null;

	/**
	 * Passed theme slug holder.
	 *
	 * @var string
	 */
	private $slug = null;

	/**
	 * Passed theme name holder.
	 *
	 * @var string
	 */
	private $name = null;

	/**
	 * Endpoint for updated list
	 *
	 * @var string
	 */
	protected $server = 'https://account.crocoblock.com/free-download/child-themes/';

	/**
	 * Constructor for the class
	 *
	 * @param int    $template_id Template ID from templatemonster.com.
	 * @param string $order_id    Order ID from user order details.
	 * @param string $order_id    Order ID from user order details.
	 */
	function __construct( $id = null, $slug = null, $name = null ) {
		$this->id   = $id;
		$this->slug = $slug;
		$this->name = $name;
	}

	/**
	 * Perform an API call and return call body.
	 *
	 * @param  string $endpoint Requested endpoint.
	 * @param  array  $data     Request data.
	 * @return array
	 */
	public function api_call() {

		return array(
			'success' => true,
			'data'    => array(
				'theme' => $this->server . $this->slug . '-child.zip',
			),
		);
	}

}
