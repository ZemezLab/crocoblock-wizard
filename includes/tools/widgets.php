<?php
namespace Crocoblock_Wizard\Tools;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Widgets class
 */
class Widgets {

	/**
	 * Returns available widgets data.
	 *
	 * @return array
	 */
	public static function available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls   = $wp_registered_widget_controls;
		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {

			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base']] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name'] = $widget['name'];
			}

		}

		return apply_filters( 'crocoblock-wizard/tools/widgets/available-widgets', $available_widgets );

	}

}
