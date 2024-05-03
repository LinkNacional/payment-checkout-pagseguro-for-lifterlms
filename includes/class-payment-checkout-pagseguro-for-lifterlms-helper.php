<?php

/**
 * @see        https://www.linknacional.com/
 * @since      1.0.0
 * @author     Link Nacional
 */
final class Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper {
    /**
     * Show plugin dependency notice.
     *
     * @since 1.0.1
     */
    final public static function verify_plugin_dependencies(): bool {
        // Load plugin helper functions.
        if ( ! function_exists('deactivate_plugins') || ! function_exists('is_plugin_active')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        
        // Flag to check whether deactivate plugin or not.
        $is_deactivate_plugin = null;

        // Verify minimum LifterLMS plugin version.
        if (
            defined('LLMS_VERSION')
            && version_compare(LLMS_VERSION, LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_MIN_LIFTERLMS_VERSION, '<')
        ) {
            // Show admin notice.
            Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::dependency_alert();

            $is_deactivate_plugin = true;
        }
        
        // LifterLMS don't have BASENAME constant.
        $LLMS_BASENAME = defined('LLMS_PLUGIN_FILE') ? plugin_basename(LLMS_PLUGIN_FILE) : '';

        $is_Lifter_active = ('' !== $LLMS_BASENAME) ? is_plugin_active($LLMS_BASENAME) : false;

        // Verify if LifterLMS plugin is actived.
        if ( ! $is_Lifter_active) {
            // Show admin notice.
            Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::inactive_alert();

            $is_deactivate_plugin = true;
        }

        // Deactivate plugin.
        if ($is_deactivate_plugin) {
            deactivate_plugins(LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_BASENAME);

            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }

            return false;
        }

        return true;
    }

    public static function register_lkn_give_cielo_qr_code_check_payment_cron_actions(): void {
        // Obter todos os ganchos cron agendados
        $cron_array = _get_cron_array();
        // Iterar sobre os ganchos cron
        foreach ($cron_array as $hook => $events) {
            // Verificar se o gancho cron começa com o prefixo desejado
            foreach ($events as $event => $even) {
                if (strpos($event, 'payment_checkout_pagseguro_for_lifterlms_check_payment_') === 0) {
                    add_action($event, array('Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway', 'check_payment'), 10, 2);
                }
            }
        }
    }

    /**
     * Notice for lifterLMS dependecy.
     *
     * @since 1.0.1
     */
    final public static function dependency_notice(): void {
        // Admin notice.
        $message = sprintf(
            '<div class="notice notice-error"><p><strong>%1$s</strong> %2$s <a href="%3$s" target="_blank">%4$s</a>  %5$s %6$s+ %7$s</p></div>',
            __('Activation Error:', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            __('You must have', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            'https://lifterlms.com',
            __('LifterLMS', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            __('version', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_MIN_LIFTERLMS_VERSION,
            __('for the LifterLMS PagSeguro to activate.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG)
        );

        echo $message;
    }

    /**
     * Notice for No Core Activation.
     *
     * @since 1.0.1
     */
    final public static function inactive_notice(): void {
        // Admin notice.
        $message = sprintf(
            '<div class="notice notice-error"><p><strong>%1$s</strong> %2$s <a href="%3$s" target="_blank">%4$s</a> %5$s</p></div>',
            __('Activation Error:', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            __('You must have', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            'https://lifterlms.com',
            __('LifterLMS', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG),
            __('plugin installed and activated for the LifterLMS PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG)
        );

        echo $message;
    }

    final public static function dependency_alert(): void {
        add_action('admin_notices', array('Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper', 'dependency_notice'));
    }

    final public static function inactive_alert(): void {
        add_action('admin_notices', array('Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper', 'inactive_notice'));
    }

    /**
     * Plugin row meta links.
     *
     * @since 1.0.1
     *
     * @param array $plugin_meta An array of the plugin's metadata.
     * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
     *
     * @return array
     */
    public static function add_plugin_row_meta($plugin_meta, $plugin_file) {
        $new_meta_links['setting'] = sprintf(
            '<a href="%1$s">%2$s</a>',
            admin_url('admin.php?page=llms-settings&tab=checkout&section=pagseguro-v1'),
            __('Settings', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG)
        );

        return array_merge($plugin_meta, $new_meta_links);
    }

    /**
     * Returns an instance of an gateway.
     *
     * @since 1.0.1
     *
     * @param string $gateway_id
     *
     * @return object gateway
     */
    public static function get_lifter_gateway($gateway_id) {
        return llms()->payment_gateways()->get_gateway_by_id( $gateway_id );
    }

    /**
     * Array for pick the data of the gateways settings in LifterLMS.
     *
     * @since 1.0.1
     *
     * @return array $configs
     */
    final public static function get_configs() {
        $configs = array();

        $configs['logEnabled'] = get_option('llms_gateway_pagseguro-v1_logging_enabled', 'no');

        $configs['paymentInstruction'] = get_option('llms_gateway_pagseguro-v1_payment_instructions', __('Check the payment area below.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG));
        $configs['lknLicense'] = get_option('llms_gateway_pagseguro-v1_plugin_license');
        $configs['email'] = get_option('llms_gateway_pagseguro-v1_email');
        $configs['tokenKey'] = get_option('llms_gateway_pagseguro-v1_token_key');
        $configs['env'] = get_option('llms_gateway_pagseguro-v1_env_type', 'sandbox');

        if ('production' === $configs['env']) {
            $configs['urlQuery'] = 'https://api.pagseguro.com';
            $configs['urlPost'] = '	https://api.pagseguro.com';
        } else {
            $configs['urlQuery'] = 'https://sandbox.api.pagseguro.com';
            $configs['urlPost'] = 'https://sandbox.api.pagseguro.com';
        }

        return $configs;
    }
}