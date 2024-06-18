<?php

/**
 * Class of PagSeguro Gateway.
 *
 * @since 2.0.0
 *
 * @version 2.0.0
 */
defined( 'ABSPATH' ) || exit;

/*
 * Class of PagSeguro Gateway.
 *
 * @since 2.0.0
 */
if (class_exists('LLMS_Payment_Gateway')) {
    final class Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway extends LLMS_Payment_Gateway {
        /**
         * A description of the payment proccess.
         *
         * @var string
         *
         * @since 2.0.0
         */
        protected $payment_instructions;

        /**
         * Constructor.
         *
         * @since   2.0.0
         *
         * @since 2.0.0         */
        public function __construct() {
            $this->set_variables();
            
            if (is_admin()) {
                require_once LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_DIR . 'admin/class-payment-checkout-pagseguro-for-lifterlms-admin.php';
                add_filter( 'llms_get_gateway_settings_fields', array('Payment_Checkout_Pagseguro_For_Lifterlms_Admin', 'add_settings_fields'), 10, 2 );
            }
            add_action( 'lifterlms_before_view_order_table', array($this, 'before_view_order_table') );
            add_action( 'lifterlms_after_view_order_table', array($this, 'after_view_order_table') );
        }

        /**
         * Output payment instructions if the order is pending | on-hold.
         *
         * @since 2.0.0
         */
        public function before_view_order_table(): void {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

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
         * @since 2.0.0
         */
        public function after_view_order_table(): void {
            global $wp;

            if ( ! empty( $wp->query_vars['orders'] ) ) {
                $order = new LLMS_Order( (int) $wp->query_vars['orders']  );

                // Verification of the gateway, to not execute in other gateways which has no defined this function.
                if ($order->get( 'payment_gateway' ) === $this->id) {
                    // Getting helper functions and values.
                    $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

                    // Getting orderId number.
                    $orderId = $order->get('id');

                    // Getting obj $order from key.
                    $pagseguroObjOrder = llms_get_order_by_key('#' . $orderId);

                    // Getting URL for PagSeguro Checkout.
                    $urlPagseguroCheckout = $pagseguroObjOrder->pagcheckout_url;

                    $title = esc_html__('Payment Area', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $span = esc_html__('Secure payment by SSL encryption.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $buttonTitle = esc_html__('Pay', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $buttonDesc = esc_html__('PagSeguro Checkout Payment', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $imgAlt = esc_html__('PagSeguro payment methods logos', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
                    $imgTitle = esc_html__('This site accepts payments with the most of flags and banks, balance in PagSeguro account and bank slip.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    $descript = esc_html__('Pay with PagSeguro by clicking on button &ldquo;Pay&rdquo; right below', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);

                    // Make the HTML for present the Payment Area.
                    $paymentArea = <<<HTML
                    <h2>{$title}</h2>
                    <div class="lkn_pagseguro_payment_area">
                        <div id="lkn_secure_site_wrapper">
                            <!-- Padlock HTML code (LifterLMS don't have one icons library) -->
                            &#128274;
                            <span>
                                {$span}
                            </span>
                        </div>
                        <img class="lifter_logo_pagseguro" src="//assets.pagseguro.com.br/ps-integration-assets/banners/pagamento/todos_animado_125_150.gif" alt="{$imgAlt}" title="{$imgTitle}">
                        <p id="text_desc_pagseguro"><b>{$descript}</b></p>
                        <a id="lkn_pagseguro_pay" href="{$urlPagseguroCheckout}" target="_blank"><button id="lkn_pagseguro_pay_button" title="{$buttonDesc}">{$buttonTitle}</button></a>
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
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

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
                $this->proccess_order($order);
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro Gateway - Switch payment method process error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway');
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
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

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

                return llms_add_notice( __( 'This gateway cannot process transactions for less than R$ 5,00.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG ), 'error' );
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
                            'payment_type' => 'single'
                        )
                    );
                }

                return $this->complete_transaction( $order );
            }

            // Process PagSeguro Order.
            $this->proccess_order($order);

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
             * @since 2.0.0.
             *
             * @param LLMS_Order $order The order object.
             */
            do_action( 'lifterlms_handle_pending_order_complete', $order );

            llms_redirect_and_exit( $order->get_view_link() );
        }        

        /**
         * Proccess the PagSeguro order.
         *
         * @since 2.0.0
         *
         * @param LLMS_Order $order order object
         */
        public function proccess_order($order) {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

            // Get the order total price.
            $total = $order->get_price( 'total', array(), 'float' );

            // Payer information
            $payerEmail = $order->billing_email;
            $payerName = $order->get_customer_name();

            $ddd = substr($order->billing_phone, 0, -9);
            $numberPhone = substr($order->billing_phone, -9);

            $payerCurrency = $order->currency;
            if ('BRL' !== $payerCurrency) {
                return llms_add_notice( 'PagSeguro Currency error: Only BRL payments are avaliable.', 'error' );
            } else {
                $itemPriceCents = number_format(filter_var($total, \FILTER_SANITIZE_NUMBER_FLOAT), 2, '.', '');
            }

            // POST parameters
            $tokenKey = $configs['tokenKey'];
            $emailKey = $configs['email'];
            $orderId = $order->get( 'id' );
            
            $itemDesc = $order->product_title . ' | ' . $order->plan_title . ' (ID# ' . $order->get('plan_id') . ')' ?? $order->plan_title;
            $itemDesc = preg_replace(array('/(á|à|ã|â|ä)/', '/(Á|À|Ã|Â|Ä)/', '/(é|è|ê|ë)/', '/(É|È|Ê|Ë)/', '/(í|ì|î|ï)/', '/(Í|Ì|Î|Ï)/', '/(ó|ò|õ|ô|ö)/', '/(Ó|Ò|Õ|Ô|Ö)/', '/(ú|ù|û|ü)/', '/(Ú|Ù|Û|Ü)/', '/(ñ)/', '/(Ñ)/', '/(ç)/', '/(Ç)/'), explode(' ', 'a A e E i I o O u U n N c C'), $itemDesc);
            $itemDesc = substr($itemDesc, 0, 100); // Catch first 100 characters of string (PagSeguro description limit).
            $itemDesc = sanitize_text_field($itemDesc);
            $itemId = $order->product_id;
            $returnUrl = home_url();
            $body = array(
                'reference_id' => $orderId,
                'customer' => array(
                    "name" => $payerName,
                    'email' => $payerEmail,
                ),
                'items' => array(
                    array(
                        'reference_id' => $itemId,
                        'name' => "Curso",
                        'description' => $itemDesc,
                        'quantity' => 1,
                        'unit_amount' => $total * 100,
                    ),
                ),
                'payment_method' => array(
                    'type' => 'CREDIT_CARD',
                    'installments' => 1, // If subsequent is string
                ),
                "redirect_url" => $returnUrl,
                "notification_urls" => array($returnUrl.'/wp-json/lkn-lifter-pagseguro-listener/v1/notification')
            );
            // Header
            $dataHeader = array(
                'Authorization' => 'Bearer ' . $tokenKey,
                'accept' => 'application/json',
                'Content-type' => 'application/json',
            );

            // Reset the order_key of obj $order for further search.
            update_post_meta($orderId, '_llms_order_key', '#' . $orderId);

            // Make the request.
            $requestResponse = json_decode($this->pagseguro_request($body, $dataHeader, "/checkouts"), true);
            $message = empty($requestResponse['error_messages'])? null: array($requestResponse["error_messages"]["error"]);
            // Log request error if not success.
            if (($message) ) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log( 'PagSeguro Gateway `handle_pending_order()` ended with api request errors', 'PagSeguro - Gateway');
                }

                return llms_add_notice( 'PagSeguro API Error - Operation rejected, reason: ' . $message[0], 'error' );
            }
            // If request is success, save the important data for further use in payment area.
            if (isset($requestResponse)) {
                if ( ! $message) {
                    // Build the URL for PagSeguro Checkout with the Code.
                    $pagseguroCheckoutUrl = $requestResponse["links"][1]['href'];
                    // Save URL in object property `pagcheckout_url`.   
                    $order->set('pagcheckout_url', $pagseguroCheckoutUrl);
                } else {
                    return llms_add_notice( 'PagSeguro API Error - Operation rejected, reason: ' . $message, 'error' );
                }
            }
        }  

        /**
         * PagSeguro Request.
         *
         * @since 2.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */       
        public function pagseguro_request($dataBody, $dataHeader, $url) {
            try {
                $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();
                $req = array(
                    "body" => wp_json_encode($dataBody),
                    "headers" => $dataHeader
                );
                $result = wp_remote_post($configs["urlPost"] . $url, $req );
                // Verifica se a solicitação foi bem-sucedida
                if ( is_wp_error( $result ) ) {
                    throw new Exception($result->get_error_message(), 1);
                } else {
                    $response = wp_remote_retrieve_body( $result );
                    return $response;
                }
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro gateway POST error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway Error');
                }

                return array();
            }
        }

        /**
         * PagSeguro Query.
         *
         * @since 2.0.0
         *
         * @param mixed $dataBody
         * @param mixed $dataHeader
         * @param mixed $url
         *
         * @return array
         */
        public static function pagseguro_query($header, $url, $query, $configs) {
            try {
                $req = array(
                    "headers" => $header
                );

                $response = wp_remote_get($url . $query, $req);
                if ( is_wp_error( $response ) ) {
                    throw new Exception($response->get_error_message(), 1);
                } else {
                    $response = wp_remote_retrieve_body( $response );
                    return $response;
                }
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro gateway GET error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway Error');
                }

                return array();
            }
        }

        /**
         * PagSeguro status Listener.
         *
         * @since 2.0.0
         *
         * @param WP_REST_Request $request Request Object
         *
         * @return WP_REST_Response
         */
        public static function pagseguro_listener($request) {
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();
            $token = $configs['tokenKey'];
            $payload = $request->get_body();
        
            // Recebendo a assinatura do header.
            $received_signature = $request->get_header('x-authenticity-token');
        
            // Combinando o token e o payload com um hífen entre eles.
            $data_to_sign = $token . '-' . $payload;
        
            // Gerando a assinatura usando SHA-256.
            $generated_signature = hash('sha256', $data_to_sign);
            
            // Compare a assinatura gerada com a assinatura recebida.
            if (hash_equals($generated_signature, $received_signature)) {        
                $body = json_decode($payload);
                $order = llms_get_order_by_key('#' . $body->reference_id);
                $result = $body->status;
        
                try {
                    $recurrency = $order->is_recurring();
                    Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway::order_set_status($order, $result, $recurrency);
        
                    return $result;
                } catch (Exception $e) {
                    // Registre o erro e retorne a mensagem de erro.
                    llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro gateway listener error: ' . var_export($e, true) . \PHP_EOL, 'PagSeguro - Gateway Listener');
                    return $e->getMessage();
                }
            } else {
                // Assinatura inválida, descarte o evento.
                return __('Invalid signature. Event discarded.', LKN_PAYMENT_CHECKOUT_PAGSEGURO_FOR_LIFTERLMS_SLUG);
            }
        }

        /**
         * Set the order status.
         *
         * @since 2.0.0
         *
         * @param LLMS_Order $order      Instance of the LLMS_Order
         * @param string     $status
         * @param bool       $recurrency
         * @param string     $gatewayId
         * @param string     $gatewayName
         */
        public static function order_set_status($order, $status, $recurrency) {
            try {
                $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

                if ('PAID' == $status) {
                    if ($recurrency) {
                        $order->set('status', 'llms-active');

                        Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway::record_pagseguro_transaction($order, 'Signature', 'recurring');
                    } else {
                        $order->set('status', 'llms-completed');

                        Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Gateway::record_pagseguro_transaction($order, 'Signature', 'single');
                    }
                } elseif ('DECLINED' == $status) {
                    $order->set('status', 'llms-failed');
                } elseif ('WAITING' == $status) {
                    $order->set('status', 'llms-pending');
                } elseif ('CANCELED' == $status) {
                    $order->set('status', 'llms-cancelled');
                } else {
                    return $status;
                }
            } catch (Exception $e) {
                if ('yes' === $configs['logEnabled']) {
                    llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro gateway - set order status error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway');
                }
            }
        }

        /**
         * Update the record transaction dashboard with PagSeguro transactions.
         *
         * @since 2.0.0
         *
         * @param LLMS_Order $order       Instance of the LLMS_Order
         * @param string     $description
         * @param string     $paymentType
         * @param string     $gatewayId
         */
        public static function record_pagseguro_transaction($order, $description, $paymentType): void {
            $order->record_transaction(
                array(
                    'amount' => $order->get_price( 'total', array(), 'float' ),
                    'source_description' => __( $description, 'lifterlms' ),
                    'transaction_id' => uniqid(),
                    'status' => 'llms-txn-succeeded',
                    'payment_gateway' => $order->get_gateway(),
                    'payment_type' => $paymentType
                )
            );
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
            $configs = Lkn_Payment_Checkout_Pagseguro_For_Lifterlms_Helper::get_configs();

            // Switch order status to "on hold" if it's a paid order.
            if ( $order->get_price( 'total', array(), 'float' ) > 0 ) {
                // Update status.
                $order->set_status( 'on-hold' );

                try {
                    $this->proccess_order($order);
                } catch (Exception $e) {
                    if ('yes' === $configs['logEnabled']) {
                        llms_log('Date: ' . gmdate('d M Y H:i:s') . ' PagSeguro gateway - recurring order process error: ' . $e->getMessage() . \PHP_EOL, 'PagSeguro - Gateway');
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
                'test_mode' => false
            );

            $this->admin_order_fields = wp_parse_args(
                array(
                    'customer' => true,
                    'source' => true,
                    'subscription' => false
                ),
                $this->admin_order_fields
            );
        }
    }
}
