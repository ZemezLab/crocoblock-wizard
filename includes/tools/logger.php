<?php
namespace Crocoblock_Wizard\Tools;

use Crocoblock_Wizard\Plugin as Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define logger class
 */
class Logger {

	/**
	 * Add warning message into log.
	 *
	 * @param  string $message Log message.
	 * @return void
	 */
	public function warning( $message = null ) {
		$this->add_message( $message, 'warnings' );
	}

	/**
	 * Add info message into log.
	 *
	 * @param  string $message Log message.
	 * @return void
	 */
	public function info( $message = null ) {
		$this->add_message( $message, 'info' );
	}

	/**
	 * Add notice message into log.
	 *
	 * @param  string $message Log message.
	 * @return void
	 */
	public function notice( $message = null ) {
		$this->add_message( $message, 'notice' );
	}

	/**
	 * Add debug message into log.
	 *
	 * @param  string $message Log message.
	 * @return void
	 */
	public function debug( $message = null ) {
		$this->add_message( $message, 'debug' );
	}

	/**
	 * Add error message into log.
	 *
	 * @param  string $message Log message.
	 * @return void
	 */
	public function error( $message = null ) {
		$this->add_message( $message, 'error' );
	}

	/**
	 * Add passed message into passed log group.
	 *
	 * @param  string $message Log message.
	 * @param  string $type    Message type.
	 * @return void
	 */
	public function add_message( $message = null, $type = 'info' ) {

		$messages = Plugin::instance()->cache->get( $type, 'log' );

		if ( empty( $messages ) || ! is_array( $messages ) ) {
			$messages = array();
		}

		$messages[] = $message;

		Plugin::instance()->cache->update( $type, $messages, 'log' );
	}

}