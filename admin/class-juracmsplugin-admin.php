<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://sirconic-group.de
 * @since      1.0.0
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/admin
 * @author     sirconic-group <info@sirconic-group.de>
 */
class Juracmsplugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Juracmsplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Juracmsplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/juracmsplugin-admin(pre).css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Juracmsplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Juracmsplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/juracmsplugin-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'JSParams', array("pluginDir" => plugin_dir_url(dirname(__FILE__))));
	}

	public function add_my_custom_menu() {
		//add an item to the menu
		add_menu_page (
			'juratextimporter',
			'JuraText-Importer',
			'manage_options',
			'juratext-importer-fur-rechtstexte24/admin/partials/juracmsplugin-admin-display.php',
			'',
			'',
			'65'
		);
	}

	public function register_button( $buttons ) {
		array_push( $buttons, "juratextloader" );
		//array_unshift($buttons, "juratextloader");
		return $buttons;
	}

	function add_plugin( $plugin_array ) {
		$plugin_array['juratextloader'] = plugin_dir_url(__FILE__) . 'js/juracmsplugin-admin-editor.js';
		return $plugin_array;
	}

	public function jura_tifr_cron () {

		if (!class_exists("jura_tifr_Request")) require_once plugin_dir_path(dirname(__FILE__)) . "admin/class-juracmsplugin-request.php";
		jura_tifr_Request::jura_tifr_getDataFromBackend();
	}

}
