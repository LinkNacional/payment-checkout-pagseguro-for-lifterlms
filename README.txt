=== Payment Checkout PagSeguro for LifterLMS ===
Contributors: linknacional
Donate link: https://www.linknacional.com/wordpress/plugins/
Tags: lifterlms, pagseguro, pagbank, credit, debit
Requires at least: 5.5
Tested up to: 6.9
Stable tag: 2.0.7
Requires PHP: 7.2
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Enable PagSeguro Checkout payment (include debit card, credit card, bank slip, payment with account balance, etc) for LifterLMS.

== Description ==

The [Payment Checkout PagSeguro for LifterLMS](https://www.linknacional.com/wordpress/plugins/) is a WordPress plugin that integrates PagSeguro payment gateway with LifterLMS learning management system. This plugin allows your students to purchase courses and memberships using multiple payment methods supported by PagSeguro, including:

* Credit and debit cards
* Bank slips (boleto bancário)
* PagSeguro account balance
* PIX payments
* Digital wallets

The plugin uses PagSeguro API v1 and provides a secure checkout experience with SSL encryption. All transactions are processed directly through PagSeguro's secure servers, ensuring the safety of your customers' payment information.

**Key Features:**
* Easy integration with LifterLMS
* Support for all major PagSeguro payment methods
* Secure SSL-encrypted transactions
* Sandbox mode for testing
* Automatic order status updates
* Multilingual support (Portuguese and English)

**Dependencies**

Payment Checkout PagSeguro for LifterLMS plugin is dependent on [LifterLMS plugin](https://wordpress.org/plugins/lifterlms/), please make sure LifterLMS is installed and properly configured before starting Payment Checkout PagSeguro for LifterLMS installation.

**Configuration Instructions**

1. Go to LifterLMS > Settings > Checkout in your WordPress admin dashboard;

2. Select the "Payment Gateways" tab;

3. Find "PagSeguro (v1)" in the list of available gateways;

4. Click on "PagSeguro (v1)" to access the configuration settings;

5. Enable the payment gateway by checking the "Enable / Disable" option;

6. Enter your PagSeguro email address in the "E-mail of PagSeguro" field (the email registered in your PagSeguro merchant account);

7. Enter your PagSeguro API token in the "PagSeguro Token" field (you can generate this token in your PagSeguro merchant dashboard);

8. Choose the environment type: "Sandbox" for testing or "Production" for live transactions;

9. Configure additional settings such as payment instructions as needed;

10. Click "Save Changes" to apply the configuration;

Your PagSeguro payment gateway is now configured and ready to accept payments for your LifterLMS courses and memberships.


== Installation ==

1. Go to your WordPress admin dashboard;

2. Navigate to Plugins > Add New;

3. Search for "Payment Checkout PagSeguro for LifterLMS" or upload the plugin zip file if you have downloaded it;

4. Click "Install Now" and then "Activate" the plugin;

5. Make sure LifterLMS plugin is installed and activated before configuring this payment gateway;

After completing these steps, the Payment Checkout PagSeguro for LifterLMS is activated and ready to be configured in LifterLMS settings.

== External Services ==

This plugin connects to PagSeguro's payment processing services to handle transactions. This service is required for the plugin's core functionality of processing payments.

**PagSeguro API Integration:**
- **What it's used for:** Processing credit card payments, bank slips, PIX, and other payment methods supported by PagSeguro
- **Data sent:** Order information, customer payment details, transaction amounts, and customer information necessary for payment processing
- **When data is sent:** When customers initiate a payment transaction through the LifterLMS checkout process
- **Production API:** https://api.pagseguro.com - Used for live transactions
- **Sandbox API:** https://sandbox.api.pagseguro.com - Used for testing purposes

**Legal Information:**
- PagSeguro Terms of Service: https://pagseguro.uol.com.br/v3/termos-de-uso-e-politica-de-privacidade/
- PagSeguro Privacy Policy: https://pagseguro.uol.com.br/sobre/politica-de-privacidade/

All transactions are processed securely through PagSeguro's SSL-encrypted servers. No sensitive payment information is stored on your WordPress site.

== Frequently Asked Questions ==

= What is the plugin license? =

This plugin is released under GPL v3 or later license.

= What is needed to use this plugin? =

* LifterLMS version 7.2.0 or latter installed and active.


== Screenshots ==

== Changelog ==
= 2.0.6/2.0.7 =
**03/03/2026**
* Updated plugin images for better clarity and branding in the WordPress directory.

= 2.0.5 =
**20/02/2026**
* Fix Translations

= 2.0.4 =
**10/10/2024**
* Add action for WordPress releases.

= 2.0.3 =
**09/10/2024**
* Fix plugin wordpress issues.

= 2.0.2 =
**03/10/2024**
* Fix plugin wordpress issues.

= 2.0.1 =
**25/09/2024**
* Fix plugin checker issues.

= 2.0.0 =
**22/08/2024**
* Verify order status dinamically;
* Bringing the PagSeguro API to the new version.

= 1.0.0 =
**18/09/2023**
* Plugin launch.

== Upgrade Notice ==

= 2.0.6/2.0.7 =
**03/03/2026**
* Updated plugin images for better clarity and branding in the WordPress directory.

= 2.0.0 =
**22/08/2024**
* Verify order status dinamically;
* Bringing the PagSeguro API to the new version.

= 1.0.0 =
**18/09/2023**
* Plugin launch.