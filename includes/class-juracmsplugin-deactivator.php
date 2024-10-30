<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://sirconic-group.de
 * @since      1.0.0
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 * @author     sirconic-group <info@sirconic-group.de>
 */
class Juracmsplugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;

		$datadb = "DROP TABLE IF EXISTS ".$wpdb->prefix."rechtstext;";
		$wpdb->query($datadb);

		$datadb = "DROP TABLE IF EXISTS ".$wpdb->prefix."juracms_kunde;";
		$wpdb->query($datadb);

		if (wp_next_scheduled ( 'rechtstexte_hourly_event' )) {
			wp_clear_scheduled_hook('rechtstexte_hourly_event');
		}
	}

}
