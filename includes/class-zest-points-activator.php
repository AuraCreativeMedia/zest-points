<?php

/**
 * Fired during plugin activation
 *
 * @link       https://digitalzest.co.uk
 * @since      1.0.0
 *
 * @package    Zest_Points
 * @subpackage Zest_Points/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Zest_Points
 * @subpackage Zest_Points/includes
 * @author     Digital Zest <hello@digitalzest.co.uk>
 */
class Zest_Points_Activator {



    public static function activate() {

		global $zest_points_db_ver;
		$zest_points_db_ver = '1.0';

		self::db_install();
        self::db_install_data();
        self::check_plugin_upgrade();
	
	}

	public static function db_install() {

        global $wpdb;
        global $zest_points_db_ver;

        $installed_ver = get_option("zest_points_db_ver");

        if ($installed_ver != $zest_points_db_ver) {
            // Table creation logic goes here

            $charset_collate = $wpdb->get_charset_collate();
            
			// SQL to create Points Table with Indexes
			$sql_points_table = "CREATE TABLE {$wpdb->prefix}zest_points (
				point_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id BIGINT UNSIGNED NOT NULL,
				points INT NOT NULL,
				earned_date DATETIME NOT NULL,
				expiry_date DATETIME NOT NULL,
				last_activity_date DATETIME NOT NULL,
				PRIMARY KEY (point_id),
				INDEX idx_user_id (user_id),
				INDEX idx_expiry_date (expiry_date),
				INDEX idx_earned_date (earned_date),
				FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
			) $charset_collate;";

			// SQL to create Points Transaction Table with Indexes
			$sql_points_transaction_table = "CREATE TABLE {$wpdb->prefix}zest_points_transactions (
				transaction_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id BIGINT UNSIGNED NOT NULL,
				point_id BIGINT UNSIGNED,
				points INT NOT NULL,
				transaction_type VARCHAR(50) NOT NULL,
				transaction_date DATETIME NOT NULL,
				PRIMARY KEY (transaction_id),
				INDEX idx_user_id (user_id),
				INDEX idx_point_id (point_id),
				INDEX idx_transaction_date (transaction_date),
				FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID),
				FOREIGN KEY (point_id) REFERENCES {$wpdb->prefix}zest_points(point_id)
			) $charset_collate;";

			// SQL to create Points Expiry Table with Indexes
			$sql_points_expiry_table = "CREATE TABLE {$wpdb->prefix}zest_points_expiry (
				expiry_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id BIGINT UNSIGNED NOT NULL,
				point_id BIGINT UNSIGNED,
				points INT NOT NULL,
				expiry_date DATETIME NOT NULL,
				PRIMARY KEY (expiry_id),
				INDEX idx_user_id (user_id),
				INDEX idx_expiry_date (expiry_date),
				INDEX idx_point_id (point_id),
				FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID),
				FOREIGN KEY (point_id) REFERENCES {$wpdb->prefix}zest_points(point_id)
			) $charset_collate;";

        // Include the upgrade script and create the tables
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql_points_table);
            dbDelta($sql_points_transaction_table);
            dbDelta($sql_points_expiry_table);

            // Update the version in the database
            update_option("zest_points_db_ver", $zest_points_db_ver);
        }
    }


	public static function db_install_data() {
        // This function can be used to insert initial data into your tables
    }

	
	public static function zest_points_update_db_check() {

        global $zest_points_db_ver;

        if (get_site_option('zest_points_db_ver') != $zest_points_db_ver) {
            self::db_install();
        }
    }

	
	public static function check_plugin_upgrade() {
        add_action('plugins_loaded', array('Zest_Points_Activator', 'zest_points_update_db_check'));
    }
}

