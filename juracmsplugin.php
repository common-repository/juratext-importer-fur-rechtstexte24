<?php

/**
 *
 * @link              http://sirconic-group.de
 * @since             1.0.0
 * @package           JuraText-Importer
 *
 * @wordpress-plugin
 * Plugin Name:       JuraText-Importer für rechtstexte24
 * Plugin URI:        http://rechtstexte24.de
 * Description:       WP-Plugin zum importieren der Texte für Ihre Webseite
 * Version:           1.1.0
 * Author:            sirconic-group
 * Author URI:        http://sirconic-group.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       juratextimporter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-juracmsplugin-activator.php
 */
function activate_juracmsplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-juracmsplugin-activator.php';
	Juracmsplugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-juracmsplugin-deactivator.php
 */
function deactivate_juracmsplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-juracmsplugin-deactivator.php';
	Juracmsplugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_juracmsplugin' );
register_deactivation_hook( __FILE__, 'deactivate_juracmsplugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-juracmsplugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_juracmsplugin() {

	$plugin = new Juracmsplugin();
	$plugin->run();

}
run_juracmsplugin();