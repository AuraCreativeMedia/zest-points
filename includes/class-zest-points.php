<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://digitalzest.co.uk
 * @since      1.0.0
 *
 * @package    Zest_Points
 * @subpackage Zest_Points/includes
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
 * @package    Zest_Points
 * @subpackage Zest_Points/includes
 * @author     Digital Zest <hello@digitalzest.co.uk>
 */
class Zest_Points {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Zest_Points_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	/** @var string the user points log database tablename */
	public $user_points_log_db_tablename;

	/** @var string the user points database tablename */
	public $user_points_db_tablename;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		global $wpdb;

		if ( defined( 'ZEST_POINTS_VERSION' ) ) {
			$this->version = ZEST_POINTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'zest-points';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_table_list_hooks();
		$this->define_points_controller();
		$this->define_public_hooks();


		$this->user_points_log_db_tablename = $wpdb->prefix . 'zest_points_transactions';
		$this->user_points_db_tablename     = $wpdb->prefix . 'zest_points';

	}

	

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Zest_Points_Loader. Orchestrates the hooks of the plugin.
	 * - Zest_Points_i18n. Defines internationalization functionality.
	 * - Zest_Points_Admin. Defines all hooks for the admin area.
	 * - Zest_Points_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zest-points-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zest-points-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zest-points-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zest-points-user-list-table.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zest-points-controller.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-zest-points-public.php';



		$this->loader = new Zest_Points_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Zest_Points_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Zest_Points_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Zest_Points_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );

	//	$this->loader->add_action( 'admin_init', $plugin_admin, 'display_settings_page_form' );

		$this->loader->add_action( 'admin_post_zest_points_settings', $plugin_admin, 'save_zest_settings' );

		$this->loader->add_action( 'woocommerce_admin_field_conversion_ratio', $plugin_admin, 'render_conversion_ratio_field' );
		$this->loader->add_action( 'woocommerce_admin_field_points_expiry', $plugin_admin, 'render_points_expiry' );

		$this->loader->add_filter( 'woocommerce_admin_settings_sanitize_option_zest_points_earn_points_ratio', $plugin_admin, 'save_conversion_ratio_field', 10, 3 );
		$this->loader->add_filter( 'woocommerce_admin_settings_sanitize_option_zest_points_redeem_points_ratio', $plugin_admin, 'save_conversion_ratio_field', 10, 3 );
		$this->loader->add_filter( 'woocommerce_admin_settings_sanitize_option_zest_points_expiry', $plugin_admin, 'save_points_expiry', 10 ,3 );


	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_table_list_hooks() {

		//$plugin_list_table = new Zest_Manage_Points_List_Table();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_points_controller() {

		$points_controller = new Zest_Points_Controller($this);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Zest_Points_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Zest_Points_Loader    Orchestrates the hooks of the plugin.
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
