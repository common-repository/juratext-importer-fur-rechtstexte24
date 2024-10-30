<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://sirconic-group.de
 * @since      1.0.0
 *
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Juracmsplugin
 * @subpackage Juracmsplugin/includes
 * @author     sirconic-group <info@sirconic-group.de>
 */
class Juracmsplugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Juracmsplugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	protected $shortcodes;

	public function __construct() {

		$this->plugin_name = 'juratext-importer-fur-rechtstexte24';
		$this->version = '1.1.0';

		add_shortcode('rechtstext', array($this, 'handleShortcodes'));

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Juracmsplugin_Loader. Orchestrates the hooks of the plugin.
	 * - Juracmsplugin_i18n. Defines internationalization functionality.
	 * - Juracmsplugin_Admin. Defines all hooks for the admin area.
	 * - Juracmsplugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-juracmsplugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-juracmsplugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-juracmsplugin-admin.php';


		require_once plugin_dir_path(dirname(__FILE__)) . 'extClasses/Crypt/RSA.php';

		$this->loader = new Juracmsplugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Juracmsplugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Juracmsplugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function handleShortcodes ($atts) {

		$standard_atts = shortcode_atts( array(
			'typ' => 'Sie müssen Ihren Shortcode modifizieren.'
		), $atts );

		$output = wp_kses_post($standard_atts['typ']);

		global $wpdb;

		$sql = "SELECT * FROM ".$wpdb->prefix."rechtstext";

		$result = $wpdb->get_results($sql);

		switch (wp_kses_post($standard_atts['typ'])):
			case "agb":
				foreach ($result as $item) {
					if ($item->typ == 1) {
						$output = $item->content;
						break;
					}
				}
				break;
			case "datenschutz":
				foreach ($result as $item) {
					if ($item->typ == 2) {
						$output = $item->content;
						break;
					}
				}
				break;
			case "widerrufsbelehrung":
				foreach ($result as $item) {
					if ($item->typ == 3) {
						$output = $item->content;
						break;
					}
				}
				break;
			case "widerrufserklaerung":
				foreach ($result as $item) {
					if ($item->typ == 4) {
						$output = $item->content;
						break;
					}
				}
				break;
			case "impressum":
				foreach ($result as $item) {
					if ($item->typ == 5) {
						$output = $item->content;
						break;
					}
				}
				break;
		endswitch;

		return $output;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Juracmsplugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_my_custom_menu');

			if (isset($_GET["page"]) && $_GET["page"] == trim($this->plugin_name,'-')."/admin/partials/juracmsplugin-admin-display.php") {

				$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
				$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

			}

			$this->loader->add_action('jura_tifr_cron_event', $plugin_admin, 'jura_tifr_cron');


			$this->loader->add_filter('mce_external_plugins', $plugin_admin, 'add_plugin');
			$this->loader->add_filter('mce_buttons', $plugin_admin, 'register_button');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Juracmsplugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
