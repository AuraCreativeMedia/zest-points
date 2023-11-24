<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://digitalzest.co.uk
 * @since      1.0.0
 *
 * @package    Zest_Points
 * @subpackage Zest_Points/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zest_Points
 * @subpackage Zest_Points/admin
 * @author     Digital Zest <hello@digitalzest.co.uk>
 */


require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/traits/pages/Settings_Page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/traits/pages/Log_Page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/traits/pages/Users_Page.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';

class Zest_Points_Admin {

	use Settings_Page, Log_Page, Users_Page;

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zest-points-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook_suffix) {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zest-points-admin.js', array( 'jquery' ), $this->version, false );


		if ($hook_suffix == 'toplevel_page_zest-settings' || $hook_suffix == 'zest-points_page_zest-user-control' || $hook_suffix == 'zest-points_page_zest-log-track') {

			wp_enqueue_script('tailwind-js', 'https://cdn.tailwindcss.com');
	
		}

	}



	public function add_admin_menu() {

        add_menu_page(
            'Zest Points', // Page title
            'Zest Points', // Menu title
            'manage_options', // Capability
            'zest-settings', // Menu slug
            array( $this, 'display_zest_settings_page' ), // Function to display the settings page
            'dashicons-admin-generic', // Icon URL (optional)
            81 // Position (optional)
        );

		// Add submenu page for User Control
		add_submenu_page(
			'zest-settings', // Parent slug
			'User Control', // Page title
			'User Control', // Menu title
			'manage_options', // Capability
			'zest-user-control', // Menu slug
			array( $this, 'display_zest_user_control_page' ) // Function to display the page
		);
	
		// Add submenu page for Logging
		add_submenu_page(
			'zest-settings', // Parent slug
			'Logging', // Page title
			'Logging', // Menu title
			'manage_options', // Capability
			'zest-log-track', // Menu slug
			array( $this, 'display_zest_log_track_page' ) // Function to display the page
		);
    }


    public function display_zest_settings_page() {
		$page_title =  __( 'Zest Points Settings', 'zest-points' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/zest-settings.php';
    }

	public function display_zest_user_control_page() {
		$page_title =  __( 'User Control', 'zest-points' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/zest-user-control.php';
	}
	
	public function display_zest_log_track_page() {
		$page_title =  __( 'Logging', 'zest-points' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/zest-log-track.php';
	}



}
