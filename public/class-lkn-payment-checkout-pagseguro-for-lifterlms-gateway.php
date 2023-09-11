<?php

/**
 * Class of PagSeguro Gateway.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || exit;

/*
 * Class of PagSeguro Gateway.
 *
 * @since 1.0.0
 */
if (class_exists('LLMS_Payment_Gateway')) {
    final class Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway extends LLMS_Payment_Gateway {
        /**
         * A description of the payment proccess.
         *
         * @var string
         *
         * @since 1.0.0
         */
        protected $payment_instructions;

        /**
         * Constructor.
         *
         * @since   1.0.0
         *
         * @version 1.0.0
         */
        public function __construct() {
            $this->set_variables();
            
            if (is_admin()) {
                require_once LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_DIR . 'admin/lkn-payment-checkout-pagseguro-for-lifterlms-settings.php';
                add_filter( 'llms_get_gateway_settings_fields', array('Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Settings', 'checkout_pagseguro_settings_fields'), 10, 2 );
            }
            add_action( 'lifterlms_before_view_order_table', array($this, 'before_view_order_table') );
            add_action( 'lifterlms_after_view_order_table', array($this, 'after_view_order_table') );
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_tooltip_scripts') );
        }

        /**
         * Enqueue tooltip for using in Payment Area buttons.
         *
         * @since   1.0.0
         */
        public function enqueue_tooltip_scripts(): void {
            wp_enqueue_script('tooltip-js', 'https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js', array('jquery'), '2.11.6', true);
            wp_enqueue_script('tooltip-init', 'https://unpkg.com/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery', 'tooltip-js'), '5.3.0', true);
        }

        /**
         * Output payment instructions if the order is pending | on-hold.
         *
         * @since 1.0.0
         */
        public function before_view_order_table(): void {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

            // Get Payment Instruction value.
            $paymentInstruction = esc_html__($configs['paymentInstruction']);

            $payInstTitle = esc_html__( 'Payment Instructions', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG );

            // Make the HTML for present the Payment Instructions.
            $paymentInst = <<<HTML
            <div class="llms-notice llms-info">
                <h3>
                {$payInstTitle}
                </h3>
                {$paymentInstruction}
            </div>
HTML;

            // Below is the verification of payment of the order, to present or not the Instructions.
            global $wp;

            if ( ! empty( $wp->query_vars['orders'] ) ) {
                $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                if (
                    $order->get( 'payment_gateway' ) === $this->id
                    && in_array( $order->get( 'status' ), array('llms-pending', 'llms-on-hold', true), true )
                ) {
                    echo apply_filters( 'llms_get_payment_instructions', $paymentInst, $this->id );
                }
            }
        }
        
        /**
         * Output payment area if the order is pending.
         *
         * @since 1.0.0
         */
        public function after_view_order_table(): void {
            global $wp;

            if ( ! empty( $wp->query_vars['orders'] ) ) {
                $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                // Verification of the gateway, to not execute in other gateways which has no defined this function.
                if ($order->get( 'payment_gateway' ) === $this->id) {
                    // Getting helper functions and values.
                    $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

                    // Getting orderId number.
                    $orderId = $order->get('id');

                    // Getting obj $order from key.
                    $objOrder = llms_get_order_by_key('#' . $orderId);

                    // Getting URL code for PagSeguro Checkout.
                    $urlCodePagseguro = $objOrder->pagseguro_return_code;

                    // Build PagSeguro Checkout URL.
                    $urlPagseguroCheckout = $configs['urlQuery'] . 'v1/checkout/payment.html?code=' . $urlCodePagseguro;

                    $title = esc_html__('Payment Area', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $span = esc_html__('Secure payment by SSL encryption.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $buttonTitle = esc_html__('Pay', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $buttonTooltip = esc_html__('PagSeguro Checkout Payment', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $imgAlt = esc_html__('PagSeguro payment methods logos', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $imgTitle = esc_html__('This site accepts payments with the most of flags and banks, balance in PagSeguro account and bank slip.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $descript = esc_html__('Pay with PagSeguro by clicking on button "Pay" right below', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    // Make the HTML for present the Payment Area.
                    $paymentArea = <<<HTML
                    <h2>{$title}</h2>
                    <div class="lkn_payment_area">
                        <div id="lkn_secure_site_wrapper">
                            <span class="llms-icon padlock"></span>
                            <span>
                                {$span}
                            </span>
                        </div>
                        <img class="logo_pagseguro" src="//assets.pagseguro.com.br/ps-integration-assets/banners/pagamento/todos_animado_125_150.gif" alt="{$imgAlt}" title="{$imgTitle}">
                        <p id="text_desc_pagseguro"><b>{$descript}</b></p>
                        <a id="lkn_pagseguro_pay" href="{$urlPagseguroCheckout}" target="_blank"><button id="lkn_pagseguro_pay_button" data-toggle="tooltip" data-placement="top" title="{$buttonTooltip}">{$buttonTitle}</button></a>
                    </div>
HTML;

                    // Below is the verification of payment of the order, to present or not the Payment Area.
                    global $wp;

                    if ( ! empty( $wp->query_vars['orders'] ) ) {
                        $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                        if (
                            $order->get( 'payment_gateway' ) === $this->id
                            && in_array( $order->get( 'status' ), array('llms-pending', 'llms-on-hold', true), true )
                        ) {
                            echo apply_filters( 'llms_get_payment_instructions', $paymentArea, $this->id );
                        }
                    }
                }
            }
        }

        /**
         * Called when the Update Payment Method form is submitted from a single order view on the student dashboard.
         *
         * Gateways should do whatever the gateway needs to do to validate the new payment method and save it to the order
         * so that future payments on the order will use this new source
         *
         * @param obj   $order     Instance of the LLMS_Order
         * @param array $form_data Additional data passed from the submitted form (EG $_POST)
         *
         * @since    3.10.0
         *
         */
        public function handle_payment_source_switch($order, $form_data = array()): void {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

            $previous_gateway = $order->get( 'payment_gateway' );

            if ( $this->get_id() === $previous_gateway ) {
                return;
            }

            $order->set( 'payment_gateway', $this->get_id() );
            $order->set( 'gateway_customer_id', '' );
            $order->set( 'gateway_source_id', '' );
            $order->set( 'gateway_subscription_id', '' );

            // Proccess the switch for PagSeguro Order.
            try {
                $this->lkn_pagseguro_proccess_order($order);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' PagSeguro Gateway - Switch payment method process error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway');
                }
            }

            $order->add_note( sprintf( __( 'Payment method switched from "%1$s" to "%2$s"', 'lifterlms' ), $previous_gateway, $this->get_admin_title() ) );
        }

        /**
         * Handle a Pending Order.
         *
         * @since 3.0.0
         * @since 3.10.0 Unknown.
         * @since 6.4.0 Use `llms_redirect_and_exit()` in favor of `wp_redirect()` and `exit()`.
         *
         * @param LLMS_Order       $order   order object
         * @param LLMS_Access_Plan $plan    access plan object
         * @param LLMS_Student     $student student object
         * @param LLMS_Coupon|bool $coupon  coupon object or `false` when no coupon is being used for the order
         */
        public function handle_pending_order($order, $plan, $student, $coupon = false) {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

            // Make log.
            if ('yes' === $configs['logEnabled']) {
                $this->log( 'PagSeguro Gateway `handle_pending_order()` started', $order, $plan, $student, $coupon );
            }

            // Make error log.
            if ( ! is_ssl() ) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log( 'PagSeguro Gateway `handle_pending_order()` ended with validation errors' );
                }

                return llms_add_notice( __('Not secure payment by SSL encryption.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG), 'error' );
            }

            $total = $order->get_price( 'total', array(), 'float' );

            // Validate min value.
            if ( $total < 5.00 ) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log( 'PagSeguro Gateway `handle_pending_order()` ended with validation errors', 'Less than minimum order amount.' );
                }

                return llms_add_notice( sprintf( __( 'This gateway cannot process transactions for less than R$ 5,00.', 'min transaction amount error', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ) ), 'error' );
            }

            // Free orders (no payment is due).
            if ( (float) 0 === $order->get_initial_price( array(), 'float' ) ) {
                // Free access plans do not generate receipts.
                if ( $plan->is_free() ) {
                    $order->set( 'status', 'completed' );

                    // Free trial, reduced to free via coupon, etc....
                    // We do want to record a transaction and then generate a receipt.
                } else {
                    // Record a $0.00 transaction to ensure a receipt is sent.
                    $order->record_transaction(
                        array(
                            'amount' => (float) 0,
                            'source_description' => __( 'Free', 'lifterlms' ),
                            'transaction_id' => uniqid(),
                            'status' => 'llms-txn-succeeded',
                            'payment_gateway' => 'pagseguro-v1',
                            'payment_type' => 'single',
                        )
                    );
                }

                return $this->complete_transaction( $order );
            }

            // Process PagSeguro Order.
            $this->lkn_pagseguro_proccess_order($order);

            /*
             * Action triggered when a pagseguro payment is due.
             *
             * @hooked LLMS_Notification: manual_payment_due - 10
             *
             * @since Unknown.
             *
             * @param LLMS_Order                  $order   The order object.
             * @param LLMS_Payment_Gateway_Manual $gateway Manual gateway instance.
             */
            do_action( 'llms_manual_payment_due', $order, $this );

            /*
             * Action triggered when the pending order processing has been completed.
             *
             * @since 1.0.0.
             *
             * @param LLMS_Order $order The order object.
             */
            do_action( 'lifterlms_handle_pending_order_complete', $order );

            llms_redirect_and_exit( $order->get_view_link() );
        }        

        /**
         * Proccess the PagSeguro order.
         *
         * @since 1.0.0
         *
         * @param LLMS_Order $order order object
         */
        public function lkn_pagseguro_proccess_order($order) {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

            // Get the order total price.
            $total = $order->get_price( 'total', array(), 'float' );

            // Payer information
            $payerEmail = $order->billing_email;
            $payerName = $order->get_customer_name();
            $payerPhone = $order->billing_phone;
            $payerPhoneDDD = substr($payerPhone, 0, 2);
            $payerPhone = substr($payerPhone, 2);

            // POST parameters
            $url = $configs['urlPost'];
            $orderId = $order->get( 'id' );
            $notificationUrl = site_url() . '/wp-json/lkn-paghiper-pix-listener/v1/notification'; // TODO arrumar o listener e url.
            $itemQtd = '1';
            $itemDesc = $order->product_title . ' | ' . $order->plan_title . ' (ID# ' . $order->get('plan_id') . ')' ?? $order->plan_title;
            $itemId = $order->product_id;
            $itemPriceCents = number_format($total, 2, '', '');
            $tokenKey = $configs['tokenKey'];
            $returnUrl = home_url();

            // Create date object for checkout duration.
            $actualDate = new DateTime();
            $dateTomorrow = $actualDate->modify('+1 day');
            $expirDate = $dateTomorrow::ATOM;

            // Body
            $body = array(
                'reference_id' => $orderId,
                'expiration_date' => $expirDate,
                'customer' => array(
                    'name' => $payerName,
                    'email' => $payerEmail,
                    'tax_id' => '00000000000',
                    'phone' => array(
                        'country' => '+55',
                        'area' => $payerPhoneDDD,
                        'number' => $payerPhone
                    )
                ),
                'customer_modifiable' => true,
                'items' => array(
                    'reference_id' => $itemId,
                    'name' => $itemDesc,
                    'quantity' => $itemQtd,
                    'unit_amount' => $itemPriceCents
                ),
                'additional_amount' => 0,
                'discount_amount' => 0,
                'redirect_url' => $returnUrl,
                'return_url' => $returnUrl,
                'notification_urls' => array($notificationUrl),
                'payment_notification_urls' => array($notificationUrl)
            );

            // Header
            $dataHeader = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $tokenKey,
            );

            // Build request body json.
            $dataBody = json_encode($body);

            // Reset the order_key of obj $order for further search.
            update_post_meta($orderId, '_llms_order_key', '#' . $orderId);

            // Make the request.
            $requestResponse = $this->lkn_lifter_pagseguro_request($dataBody, $dataHeader, $url);

            if ('yes' === $configs['logEnabled']) {
                llms_log( $requestResponse . \PHP_EOL, 'PagSeguro - Testes');
            }

            if (isset($requestResponse)) {
                // Log request error if not success.
                if (($message[0]) && ( ! $returnCode[0])) {
                    if ('yes' === $configs['logEnabled']) {
                        llms_log( 'PagSeguro Gateway `handle_pending_order()` ended with api request errors', 'PagSeguro - Gateway ');
                    }

                    return llms_add_notice( 'PagSeguro API error: ' . $message, 'error' );
                }

                // If request is success, save the important data for further use in payment area.
                if ( ! $message[0] && $returnCode[0]) {
                    $order->set('pagseguro_return_code', $returnCode[0]);
                } else {
                    // TODO colocar função para settar status do pedido como "Falha".
                    return llms_add_notice( 'PagSeguro API Error - Operation rejected, reason: ' . $message, 'error' );
                }
            }
        }        

        /**
         * PagSeguro Request.
         *
         * @since 1.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */
        public function lkn_lifter_pagseguro_request($dataBody, $dataHeader, $url) {
            try {
                $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

                // Make the request args.
                $args = array(
                    'headers' => $dataHeader,
                    'body' => $dataBody,
                    'timeout' => '10',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                );

                // Make the request.
                $request = wp_remote_post($url, $args);

                // Register log.
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' PagSeguro gateway POST: ' . var_export($request, true) . \PHP_EOL, 'PagSeguro - Gateway ');
                }

                return wp_remote_retrieve_body($request);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log('Date: ' . date('d M Y H:i:s') . ' PagSeguro gateway POST error: ' . $e->getMessage() . \PHP_EOL );
                }

                return array();
            }
        }

        /**
         * PagSeguro Query.
         *
         * @since 1.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */
        public function lkn_lifter_pagseguro_query($dataHeader, $url) {
            try {
                $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

                // Make the query args.
                $args = array(
                    'headers' => $dataHeader,
                    'timeout' => '10',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                );

                // Make the query.
                $query = wp_remote_get($url, $args);

                // Register log.
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . date('d M Y H:i:s') . ' PagSeguro gateway GET: ' . var_export($query, true) . \PHP_EOL, 'PagSeguro - Gateway ');
                }

                return wp_remote_retrieve_body($query);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    $this->log('Date: ' . date('d M Y H:i:s') . ' PagSeguro gateway GET error: ' . $e->getMessage() . \PHP_EOL );
                }

                return array();
            }
        }

        /**
         * Called by scheduled actions to charge an order for a scheduled recurring transaction
         * This function must be defined by gateways which support recurring transactions.
         *
         * @param obj $order Instance LLMS_Order for the order being processed
         *
         * @return mixed
         *
         * @since    3.10.0
         *
         * @version  3.10.0
         */
        public function handle_recurring_transaction($order) {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::lkn_pagseguro_get_configs();

            // Switch order status to "on hold" if it's a paid order.
            if ( $order->get_price( 'total', array(), 'float' ) > 0 ) {
                // Update status.
                $order->set_status( 'on-hold' );

                try {
                    $this->lkn_pagseguro_proccess_order($order);
                } catch (Exception $e) {
                    if ('yes' === $configs['logEnabled']) {
                        llms_log('Date: ' . date('d M Y H:i:s') . ' PagSeguro gateway - recurring order process error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway');
                    }
                }

                // @hooked LLMS_Notification: manual_payment_due - 10
                do_action( 'llms_manual_payment_due', $order, $this );
            }
        }

        /**
         * Determine if the gateway is enabled according to admin settings checkbox.
         *
         * @return bool
         */
        public function is_enabled() {
            return ( 'yes' === $this->get_enabled() ) ? true : false;
        }

        protected function set_variables(): void {
            /*
             * The gateway unique ID.
             *
             * @var string
             */
            $this->id = 'pagseguro-v1';

            /*
             * The title of the gateway displayed in admin panel.
             *
             * @var string
             */
            $this->admin_title = __( 'PagSeguro (v1)', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG );

            /*
             * The description of the gateway displayed in admin panel on settings screens.
             *
             * @var string
             */
            $this->admin_description = __( 'Allow customers to purchase courses and memberships using PagSeguro.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG );

            /*
             * The title of the gateway.
             *
             * @var string
             */
            $this->title = __( 'PagSeguro Checkout', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG );

            /*
             * The description of the gateway displayed to users.
             *
             * @var string
             */
            $this->description = __( 'Payment via PagSeguro checkout', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG );

            $this->supports = array(
                'checkout_fields' => true,
                'refunds' => false, // Significa que compras feitas com esse gateway podem ser reembolsadas, porém, esse gateway não funciona como um método de reembolso.
                'single_payments' => true,
                'recurring_payments' => true,
                'test_mode' => false,
            );

            $this->admin_order_fields = wp_parse_args(
                array(
                    'customer' => true,
                    'source' => true,
                    'subscription' => false,
                ),
                $this->admin_order_fields
            );
        }
    }
}
