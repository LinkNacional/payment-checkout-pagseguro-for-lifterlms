<?php

/**
 * The admin-specific additional settings of the pagseguro gateway.
 *
 * @see        https://www.linknacional.com/
 * @since      1.0.0
 */
defined( 'ABSPATH' ) || exit;

if (class_exists('LLMS_Payment_Gateway')) {
    final class Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Settings {
        public static function checkout_pagseguro_settings_fields($default_fields, $gateway_id) {
            $gateway = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_gateways( 'pagseguro-v1' );

            $fields = array();

            // Field for Payment instructions.
            $fields[] = array(
                'id' => $gateway->get_option_name( 'payment_instructions' ),
                'desc' => '<br>' . __( 'Displayed to the user when this gateway is selected during checkout. Add information here instructing the student on how to send payment.', 'lifterlms' ),
                'title' => __( 'Payment Instructions', 'lifterlms' ),
                'type' => 'textarea',
            );

            // Field for Lkn License.
            $fields[] = array(
                'id' => $gateway->get_option_name( 'plugin_license' ),
                'title' => __( 'License', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                'desc' => '<br>' . sprintf(
                    __( 'To use this payment method, acquire a license at %1$shttps://www.linknacional.com.br%2$s', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                    '<a href="https://www.linknacional.com.br">',
                    '</a>'
                ),
                'type' => 'password',
            );

            // Field for PagSeguro email.
            $fields[] = array(
                'id' => $gateway->get_option_name( 'email' ),
                'title' => __( 'E-mail of PagSeguro', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                'desc' => '<br>' . __( 'E-mail registred in the administrative area of PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                'type' => 'text',
            );

            // Field for PagSeguro token key.
            $fields[] = array(
                'id' => $gateway->get_option_name( 'token_key' ),
                'title' => __( 'PagSeguro Token', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                'desc' => '<br>' . __( 'API service key of PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ),
                'type' => 'password',
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

            if ($gateway->id == $gateway_id) {
                $default_fields = array_merge($default_fields, $fields);
            }

            return $default_fields;
        }
    }
}