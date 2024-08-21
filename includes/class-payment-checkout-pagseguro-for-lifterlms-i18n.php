<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linknacional.com.br/
 * @since      2.0.0
 *
 * @package    Payment_Checkout_Pagseguro_For_Lifterlms
 * @subpackage Payment_Checkout_Pagseguro_For_Lifterlms/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Payment_Checkout_Pagseguro_For_Lifterlms
 * @subpackage Payment_Checkout_Pagseguro_For_Lifterlms/includes
 * @author     Link Nacional
 */
final class Payment_Checkout_Pagseguro_For_Lifterlms_i18n {
    /**
     * Load the plugin text domain for translation.
     *
     * @since    2.0.0
     */
    public function load_plugin_textdomain(): void {
        load_plugin_textdomain(
            'payment-checkout-pagseguro-for-lifterlms',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }
}
