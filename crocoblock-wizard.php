<?php
/**
 * Plugin Name: CrocoBlock Wizard
 * Plugin URI:
 * Description:
 * Version:     1.0.0
 * Author:      CrocoBlock
 * Author URI:
 * Text Domain: crocoblock-wizard
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'plugins_loaded', 'crocoblock_wizard_init' );

/**
 * Initializes plugin on plugins_loaded hook
 *
 * @return void
 */
function crocoblock_wizard_init() {

	define( 'CB_WIZARD_VERSION', '1.0.0-' . time() );

	define( 'CB_WIZARD__FILE__', __FILE__ );
	define( 'CB_WIZARD_PLUGIN_BASE', plugin_basename( CB_WIZARD__FILE__ ) );
	define( 'CB_WIZARD_PATH', plugin_dir_path( CB_WIZARD__FILE__ ) );
	define( 'CB_WIZARD_URL', plugins_url( '/', CB_WIZARD__FILE__ ) );

	require CB_WIZARD_PATH . 'includes/plugin.php';

}

/**
 * Returns Plugin class instance
 *
 * @return Crocoblock_Wizard\Plugin
 */
function crocoblock_wizard() {
	return Crocoblock_Wizard\Plugin::instance();
}
