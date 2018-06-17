<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           SD_Shop
 *
 * @wordpress-plugin
 * Plugin Name:       Skin Deep Shop
 * Plugin URI:        TODO
 * Description:       Integration with Skin Deep's Snipcart e-Commerce
 * Version:           1.0.0
 * Author:            Sam Briggs
 * Author URI:        http://github.com/briggySmalls
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sd-shop
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
define( 'SD_SHOP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sd-shop-activator.php
 */
function activate_sd_shop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sd-shop-activator.php';
	SD_Shop_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sd-shop-deactivator.php
 */
function deactivate_sd_shop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sd-shop-deactivator.php';
	SD_Shop_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sd_shop' );
register_deactivation_hook( __FILE__, 'deactivate_sd_shop' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sd-shop.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sd_shop() {

	$plugin = new SD_Shop();
	$plugin->run();

}
run_sd_shop();
