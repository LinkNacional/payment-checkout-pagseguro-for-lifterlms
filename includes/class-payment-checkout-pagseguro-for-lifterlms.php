<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://www.linknacional.com.br/
 * @since      2.0.0
 * @package    Payment_Checkout_Pagseguro_For_Lifterlms
 * @subpackage Payment_Checkout_Pagseguro_For_Lifterlms/includes
 * @author     Link Nacional
 */
final class Payment_Checkout_Pagseguro_For_Lifterlms {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      Payment_Checkout_Pagseguro_For_Lifterlms_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function __construct() {
        if ( defined( 'LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_VERSION' ) ) {
            $this->version = LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_VERSION;
        } else {
            $this->version = '2.0.0';
        }
        $this->plugin_name = 'payment-checkout-pagseguro-for-lifterlms';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Payment_Checkout_Pagseguro_For_Lifterlms_Loader. Orchestrates the hooks of the plugin.
     * - Payment_Checkout_Pagseguro_For_Lifterlms_i18n. Defines internationalization functionality.
     * - Payment_Checkout_Pagseguro_For_Lifterlms_Admin. Defines all hooks for the admin area.
     * - Payment_Checkout_Pagseguro_For_Lifterlms_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function load_dependencies(): void {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( __DIR__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( __DIR__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( __DIR__ ) . 'admin/class-payment-checkout-pagseguro-for-lifterlms-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( __DIR__ ) . 'public/class-payment-checkout-pagseguro-for-lifterlms-public.php';

        /**
         * The file responsible for useful functions of plugin.
         */
        require_once plugin_dir_path( __DIR__ ) . 'includes/class-payment-checkout-pagseguro-for-lifterlms-helper.php';
        
        /**
         * The class responsible for pagseguro gateway.
         */
        require_once plugin_dir_path( __DIR__ ) . 'public/class-lkn-payment-checkout-pagseguro-for-lifterlms-gateway.php';

        /**
         * The class responsible for plugin updater checker of plugin.
         */
        include_once plugin_dir_path( __DIR__ ) . 'includes/plugin-updater/plugin-update-checker.php';

        $this->loader = new Payment_Checkout_Pagseguro_For_Lifterlms_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Payment_Checkout_Pagseguro_For_Lifterlms_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function set_locale(): void {
        $plugin_i18n = new Payment_Checkout_Pagseguro_For_Lifterlms_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Adiciona o PagSeguro à lista de gateways disponíveis.
     * 
     * @since 2.0.0
     */
    public static function add_gateway($gateways) {
        $gateways[] = 'Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway';

        return $gateways;
    }

    /**
     * Routes register.
     *
     * @since   3.0.0
     */
    public function listener_register_routes(): void {
        register_rest_route('lknLifterPagseguro/v1', '/listener', array(
            'methods' => 'POST',
            'callback' => array('Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway', 'pagseguro_listener'),
            'permission_callback' => __return_empty_string(),
        ));
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_admin_hooks(): void {
        $this->loader->add_filter('plugin_action_links_' . LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_BASENAME, 'Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper', 'add_plugin_row_meta', 10, 2);
        $this->loader->add_action('plugins_loaded', 'Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper', 'verify_plugin_dependencies', 999);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_public_hooks(): void {
        $plugin_public = new Payment_Checkout_Pagseguro_For_Lifterlms_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        
        $this->loader->add_filter('lifterlms_payment_gateways', $this, 'add_gateway');
        $this->loader->add_action('rest_api_init', $this, 'listener_register_routes');
        $this->loader->add_action('init', 'Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper', 'register_lkn_give_cielo_qr_code_check_payment_cron_actions', 10);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    2.0.0
     */
    public function run(): void {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     2.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     2.0.0
     * @return    Payment_Checkout_Pagseguro_For_Lifterlms_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     2.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
