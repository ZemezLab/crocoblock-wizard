<?php
/**
 * Plugin Name: Crocoblock Wizard
 * Plugin URI:  https://crocoblock.com/
 * Description: A powerful tool to install Crocoblock package, Jet Plugins, export and import skins quick and easy!
 * Version:     1.1.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
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

	define( 'CB_WIZARD_VERSION', '1.1.0' );

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

register_activation_hook( __FILE__, 'crocoblock_wizard_activation' );

/**
 * Callback for plugin activation hook
 *
 * @return void
 */
function crocoblock_wizard_activation() {
	set_transient( 'crocoblock_wizard_redirect', true, MINUTE_IN_SECONDS );
}
