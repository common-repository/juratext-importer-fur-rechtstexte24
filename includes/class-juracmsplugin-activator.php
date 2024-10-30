<?php

/**
 * Fired during plugin activation
 *
 * @link       http://sirconic-group.de
 * @since      1.0.0
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 * @author     sirconic-group <info@sirconic-group.de>
 */
class Juracmsplugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$dbdata = "CREATE TABLE ". $wpdb->prefix . "rechtstext (
					  id INT(11) NOT NULL AUTO_INCREMENT,
					  typ INT(11) NULL,
					  version INT(11) NULL,
					  content VARCHAR(10000) NULL,
					  PRIMARY KEY (id)
					);";
		dbDelta( $dbdata );

		$dbdata = "CREATE TABLE ". $wpdb->prefix . "juracms_kunde (
					  id INT(11) NOT NULL AUTO_INCREMENT,
					  kundennummer INT(11) NULL,
					  pubkey VARCHAR(1000) NULL,
					  PRIMARY KEY (id)
					);";
		dbDelta( $dbdata );

		//wp_schedule_single_event(time()+60, 'update_rechtstexte');
	}

}
