<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linknacional.com.br/
 * @since             1.0.0
 * @package           Payment_Checkout_Pagseguro_For_Lifterlms
 *
 * @wordpress-plugin
 * Plugin Name:       LifterLMS PagSeguro
 * Plugin URI:        https://www.linknacional.com.br/wordpress/plugins/
 * Description:       Adiciona novas formas de pagamento ao LifterLMS usando a API v1 da PagSeguro.
 * Version:           1.0.0
 * Author:            Link Nacional
 * Author URI:        https://www.linknacional.com.br/
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       payment-checkout-pagseguro-for-lifterlms
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
define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_VERSION', '1.0.0' );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_MIN_LIFTERLMS_VERSION', '7.2.0' );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_FILE', __FILE__ );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG', 'payment-checkout-pagseguro-for-lifterlms' );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_DIR', plugin_dir_path(LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_FILE) );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_URL', plugin_dir_url(LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_FILE) );

define( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_BASENAME', plugin_basename(LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_FILE) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-payment-checkout-pagseguro-for-lifterlms-activator.php
 */
function activate_payment_checkout_pagseguro_for_lifterlms(): void {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms-activator.php';
    Payment_Checkout_Pagseguro_For_Lifterlms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-payment-checkout-pagseguro-for-lifterlms-deactivator.php
 */
function deactivate_payment_checkout_pagseguro_for_lifterlms(): void {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms-deactivator.php';
    Payment_Checkout_Pagseguro_For_Lifterlms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_payment_checkout_pagseguro_for_lifterlms' );
register_deactivation_hook( __FILE__, 'deactivate_payment_checkout_pagseguro_for_lifterlms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_payment_checkout_pagseguro_for_lifterlms(): void {
    $plugin = new Payment_Checkout_Pagseguro_For_Lifterlms();
    $plugin->run();
}
run_payment_checkout_pagseguro_for_lifterlms();