<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linknacional.com.br/
 * @since      2.0.0
 *
 * @package    Payment_Checkout_Pagseguro_For_Lifterlms
 * @subpackage Payment_Checkout_Pagseguro_For_Lifterlms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Payment_Checkout_Pagseguro_For_Lifterlms
 * @subpackage Payment_Checkout_Pagseguro_For_Lifterlms/admin
 * @author     Link Nacional
 */
final class Payment_Checkout_Pagseguro_For_Lifterlms_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
        
    public static function add_settings_fields($default_fields, $gateway_id) {
        $gateway = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_lifter_gateway( 'pagseguro-v1' );
    
        $fields = array();
    
        // Field for Payment instructions.
        $fields[] = array(
            'id' => $gateway->get_option_name( 'payment_instructions' ),
            'desc' => '<br>' . __( 'Displayed to the user when this gateway is selected during checkout. Add information here instructing the student on how to send payment.', 'lifterlms' ),
            'title' => __( 'Payment Instructions', 'lifterlms' ),
            'type' => 'textarea',
        );
    
        // Field for PagSeguro email.
        $fields[] = array(
            'id' => $gateway->get_option_name( 'email' ),
            'title' => __( 'E-mail of PagSeguro', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
            'desc' => '<br>' . __( 'E-mail registred in the administrative area of PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
            'type' => 'text',
        );
    
        // Field for PagSeguro env type.
        $fields[] = array(
            'id' => $gateway->get_option_name( 'env_type' ),
            'title' => __( 'Type of environment', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
            'desc' => '<br>' . __('Enable environment of test or production.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            'type' => 'radio',
            'default' => 'sandbox',
            'options' => array(
                'sandbox' => __('Sandbox', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
                'production' => __('Production', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            ),
        );
        
        // Field for PagSeguro token key.
        $fields[] = array(
            'id' => $gateway->get_option_name( 'token_key' ),
            'title' => __( 'PagSeguro Token', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
            'desc' => '<br>' . __( 'API service key of PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
            'type' => 'password',
        );
    
        if ($gateway->id == $gateway_id) {
            $default_fields = array_merge($default_fields, $fields);
        }
    
        wp_enqueue_script( 'lknPaymentCheckoutPagseguroForLifterlmsSettingsJs', plugin_dir_url( __FILE__ ) . 'js/payment-checkout-pagseguro-for-lifterlms-admin-settings.js', array(), 'all' ); 
        wp_localize_script( 'lknPaymentCheckoutPagseguroForLifterlmsSettingsJs', 'lknPaymentCheckoutPagseguroForLifterlmsPhpVariables', array(
            'seeLogs' => __('See logs', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
        ));
        return $default_fields;
    }
}