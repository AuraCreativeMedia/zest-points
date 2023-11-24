<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://digitalzest.co.uk
 * @since             1.0.0
 * @package           Zest_Points
 *
 * @wordpress-plugin
 * Plugin Name:       Zest Points
 * Plugin URI:        https://digitalzest.co.uk
 * Description:       Foster customer loyalty and engagement by offering redeemable points that turn regular shopping into a rewarding adventure.
 * Version:           1.0.0
 * Author:            Digital Zest
 * Author URI:        https://digitalzest.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zest-points
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ZEST_POINTS_VERSION', '1.0.0' );
define( 'ZEST_PLUGIN_NAME', 'zest-points');
define( 'ZEST_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'ZEST_PLUGIN_ROOT_URI', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zest-points-activator.php
 */
function activate_zest_points() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zest-points-activator.php';
	Zest_Points_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zest-points-deactivator.php
 */
function deactivate_zest_points() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zest-points-deactivator.php';
	Zest_Points_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zest_points' );
register_deactivation_hook( __FILE__, 'deactivate_zest_points' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-zest-points.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_zest_points() {

	$plugin = new Zest_Points();
	$plugin->run();

}
run_zest_points();
