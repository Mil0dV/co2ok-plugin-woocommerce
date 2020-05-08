<?php
/**
 * Plugin Name: CO2ok for WooCommerce
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 1.0.6.1
 *         (Remember to change the VERSION constant, below, as well!)
 *
 * Tested up to: 5.3.2
 * WC tested up to: 3.9.2
 *
 * Author:
 * Milo de Vries,
 * Chris Fuller,
 * Ryan George,
 * Michiel van Tienhoven
 * Text Domain: co2ok-for-woocommerce
 * Author URI: http://www.co2ok.eco/
 * License: GPLv2
 * @package co2ok-plugin-woocommerce
 *
 */
namespace co2ok_plugin_woocommerce;

/*
* First line of defense; our plugin should never impact webshop functionality
*/

register_shutdown_function('\co2ok_plugin_woocommerce\failWithGrace');

function failWithGrace() { 
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) { 
        $error_string = implode(", ", $error);
        Co2ok_Plugin::failGracefully($error_string);
    } 
}


/*
* Freemius integration
*/

// Create a helper function for easy SDK access.
function co2okfreemius() {
    global $co2okfreemius;

    if ( ! isset( $co2okfreemius ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $co2okfreemius = fs_dynamic_init( array(
            'id'                  => '2027',
            'slug'                => 'co2ok-for-woocommerce',
            'type'                => 'plugin',
            'public_key'          => 'pk_84d5649b281a6ee8e02ae09c6eb58',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'co2ok-plugin',
                'account'        => false,
                'support'        => false,
            ),
        ) );
    }

    return $co2okfreemius;
}


// Freemius opt-in Text Customization
// TODO text bij verse install, string heet connect-message ipv connect-message_on-update

// Init Freemius.
co2okfreemius();
// Signal that SDK was initiated.
do_action( 'co2okfreemius_loaded' );

global $co2okfreemius;

function co2ok_fs_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $product_title,
    $user_login,
    $site_link,
    $freemius_link
) {
    return sprintf(
        __( 'Hey %1$s', 'co2ok-for-woocommerce' ) . ',<br>' .
        __( 'Great that you want to help fight climate change! Press the blue button to help us improve CO2ok with some anonymous data.', 'co2ok-for-woocommerce' ),
        $user_first_name,
        '<b>' . $product_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

$co2okfreemius->add_filter('connect_message', 'co2ok_plugin_woocommerce\co2ok_fs_custom_connect_message_on_update', 10, 6);

// Freemius opt-in Icon Customization
function co2ok_fs_custom_icon() {
    return dirname( __FILE__ ) . '/images/co2ok_freemius_logo.png';
}
$co2okfreemius->add_filter( 'plugin_icon' , 'co2ok_plugin_woocommerce\co2ok_fs_custom_icon' );



/**
  * Only activate plugin on cart and checkout page
  */

/*
$request_uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$is_cart = strpos( $request_uri, '/cart/' );
$is_checkout = strpos( $request_uri, '/checkout/' );
$is_backend = strpos( $request_uri, '/wp-admin/' );
$load_plugin = ( ($is_cart) || ($is_checkout) || ($is_backend) ) ? true : false;

// add filter in front pages only
if ($load_plugin === false){
    return;
}
*/
use cbschuld\LogEntries;

require "vendor/autoload.php";

/**
 * Prevent data leaks
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Check if class exists
if ( !class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' ) ) :

    class Co2ok_Plugin
    {
    /**
     * This plugin's version
     */
    const VERSION = '1.0.6.1';

    static $co2okApiUrl = "https://test-api.co2ok.eco/graphql";

    // Percentage should be returned by the middleware, else: 2%
    private $percentage = 1.652892561983472;
    private $surcharge  = 0;
    
    private $helperComponent;

    /*
     * Returns the result of a debug_backtrace() as a pretty-printed string
     * @param  array   $trace Result of debug_backtrace()
     * @param  boolean $safe  Whether to remove exposing information from print
     * @return string         Formatted backtrace
     */
    final static function formatBacktrace($trace, $safe = true) {
        array_pop($trace); // remove {main}
        $log = "Backtrace:";
        foreach (array_reverse($trace) as $index => $line) {
            // Format file location
            $location = $line["file"];
            if ($safe) {
                // Z:\private\exposing\webserver\directory\co2ok-plugin-woocommerce\co2ok_plugin.php -> **\co2ok_plugin.php
                $location = preg_replace('#.*[\\\/]#', '**\\', $location);
            }

            // Format caller
            $caller = "";
            if (array_key_exists("class", $line)) {
                $caller = $line["class"] . $line["type"];
            }
            $caller .= $line["function"];

            // Format state, append to $caller
            if (!$safe || $index == count($trace) - 1) { // If unsafe or last call
                if (array_key_exists("object", $line) && !empty($line["object"])) {
                    $caller .= "\n      " . $line["class"] . ":";
                    foreach ($line["object"] as $name => $value) {
                        $caller .= "\n        " . print_r($name, true) . ': ' . print_r($value, true);
                    }
                }
                if (array_key_exists("args", $line) && !empty($line["args"])) {
                    $caller .= "\n      args:";
                    foreach ($line["args"] as $name => $value) {
                        $caller .= "\n        " . print_r($name, true) . ': ' . print_r($value, true);
                    }
                }
            }

            // Append contents to string
            $log .= sprintf("\n    %s(%d): %s", $location, $line["line"], $caller);
        }
        return $log;
    }

    /*
     * Fail silently
     * @param string $error Error message
     */
    final public static function failGracefully($error = "Unspecified error.")
    {
        // Format error notice
        $now = date("Ymd_HisT");
        $site_name = $_SERVER['SERVER_NAME'];
        $logmsg = function ($info) use ($now, $site_name, $error) { return sprintf("[%s:FAIL] %s\n%s\n", $now, $site_name, $error, $info); };

        // Generate backtrace
        $trace = debug_backtrace();
        array_shift($trace); // remove call to this method

        // Write to local log
        $local = $logmsg(Co2ok_Plugin::formatBacktrace($trace, false));
        if ( WP_DEBUG === true ) {
            error_log( $local );
        }

        // Write to remote log
        try {
            // NB currently enabled to troubleshoot missing transactions
            // We urgently need to discuss this with WP, figure out if this is allowable.
            //
            // @reviewers: we've done our best to limit the amount of logging, please
            // contact us if this approach is unacceptable
            //
            $token = "8acac111-633f-46b3-b14b-1605e45ae614"; // our LogEntries token
            $remote = LogEntries::getLogger($token, true, true);
            $remote->error( explode("\n", $logmsg(Co2ok_Plugin::formatBacktrace($trace))) ); // explode for multiline
        } catch (Exception $e) { // fail silently
        }
    }

    /*
     * Log remotely
     * @param string $error Error message
     */
    final public static function remoteLogging($message = "Unspecified message.")
    {

        // Write to remote log
        try {
            // Only called when user has opted in to allow anymous tracking
            // @reviewers: we've done our best to limit the amount of logging, please
            // contact us if this approach is unacceptable
            //
            $token = "8acac111-633f-46b3-b14b-1605e45ae614"; // our LogEntries token
            $remote = LogEntries::getLogger($token, true, true);
            $remote->info( $message );
        } catch (Exception $e) { // fail silently
        }
    }


    final static function registerMerchant()
    {
        $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

        $merchantName = $_SERVER['SERVER_NAME'];
        $merchantEmail = get_option('admin_email');

        $graphQLClient->mutation(function ($mutation) use ($merchantName, $merchantEmail)
        {
            $mutation->setFunctionName('registerMerchant');
            $mutation->setFunctionParams(array('name' => $merchantName, 'email' => $merchantEmail));
            $mutation->setFunctionReturnTypes(array('merchant' => array("secret", "id"), 'ok'));
        }
            , function ($response)// Callback after request
            {
                if (is_wp_error($response)) { // ignore valid responses
                    $formattedError = json_encode($response->errors) . ':' . json_encode($response->error_data);
                    // Co2ok_Plugin::failGracefully($formattedError);
                    return;
                }
                if(!is_array($response['body']))
                    $response = json_decode($response['body'], 1);

                if ($response['data']['registerMerchant']['ok'] == true)
                {
                    add_option('co2ok_id', sanitize_text_field($response['data']['registerMerchant']['merchant']['id']));
                    add_option('co2ok_secret', sanitize_text_field($response['data']['registerMerchant']['merchant']['secret']));
                }
                else // TO DO error handling...
                {
                    $formattedError = json_encode($response['data']);
                    // Co2ok_Plugin::failGracefully($formattedError);
                }
            });
    }

    final static function storeMerchantCode()
    {
        $id = get_option('co2ok_id');
        $secret = get_option('co2ok_secret');
        // Deterministic way to generate a unique, short and secret code (secret in that it can't be used to determine the id or secret)
        // password_hash creates the secure deterministic hash, using the first 8 chars of the md5 hash gives us a unique code 
        // that can't be used to determine the id/secret.
        $co2ok_code = substr(md5(password_hash($id, PASSWORD_BCRYPT, ["salt" => $secret])), 0, 8);
        add_option('co2ok_code', $co2ok_code);
    }

    //This function is called when the user activates the plugin.
    // NB: this is after constructing the class, so the function calls below should be redundant
    final static function co2ok_Activated()
    {
        $alreadyActivated = get_option('co2ok_id', false);

        if (!$alreadyActivated)
        {
            Co2ok_Plugin::registerMerchant();
            Co2ok_Plugin::storeMerchantCode();

            // Set optimal defaults
            update_option('co2ok_widgetmark_footer', 'on');
            update_option('co2ok_checkout_placement', 'checkout_order_review');
        }
        else {
            // The admin has updated this plugin ..
        }
    }

    //This function is called when the user deactivates the plugin.
    final static function co2ok_Deactivated()
    {
        $timestamp = wp_next_scheduled( 'co2ok_participation_cron_hook' );
        wp_unschedule_event( $timestamp, 'co2ok_participation_cron_hook' );
        $timestamp = wp_next_scheduled( 'co2ok_clv_cron_hook' );
        wp_unschedule_event( $timestamp, 'co2ok_clv_cron_hook' );
        $timestamp = wp_next_scheduled( 'co2ok_ab_results_cron_hook' );
        wp_unschedule_event( $timestamp, 'co2ok_ab_results_cron_hook' );
    }

    /**
     * Constructor.
     */
    final public function __construct()
    {
        /**
         * Check if WooCommerce is active
         **/        
        if (!function_exists('is_plugin_active')){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ))
        {
            $ab_research = get_option('co2ok_ab_research');
                
            if ($ab_research == 'on') {
                // Start session to enable A/B testing 
                add_action( 'woocommerce_init', function(){
                // add_action( 'init', function(){
                    
                    if (is_admin()){
                        return;
                    }
                    
                    if (is_user_logged_in()){
                        //do nothing :) (since there already is a session)
                    } elseif (isset(\WC()->session)) {
                        if ( ! \WC()->session->has_session() ) {
                            \WC()->session->set_customer_session_cookie( true );
                        }
                    } elseif (get_current_user_id() == 0) {
                        // When the cron task runs, there is no user
                        return;
                    } 
                    
                    // in some scenario's there is no WC session object; init it
                    if ( ! isset(\WC()->session)) {
                        \WC()->session = new \WC_Session_Handler();
                        \WC()->session->init();
                        \WC()->session->set_customer_session_cookie( true );
                    }

                    try {
                        $co2ok_hide_button = ord(md5(\WC()->session->get_customer_id())) % 2 == 0;
                        if ( $co2ok_hide_button) {   
                            if(!isset($_COOKIE['co2ok_hide_button'])) {                             
                                setcookie('co2ok_hide_button', 'true', time()+900);
                            }
                        }
                    } catch (Exception $e) { // fail silently
                    }

                } );
            }
                /**
                 * Load translations
                 */
                add_action('plugins_loaded', array($this, 'co2ok_load_plugin_textdomain'));
                require_once(plugin_dir_path(__FILE__) . '/co2ok-autoloader.php');

                $this->helperComponent = new \co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent();
                /*
                 * Use either default, shortcode or woocommerce specific area's for co2ok button placement
                 */
                $co2ok_checkout_placement = get_option('co2ok_checkout_placement', 'after_order_notes');

                if ($ab_research == 'on') {
                    add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
                        $order = wc_get_order( $order_id );

                        // _co2ok-shown should be immutable
                        if ($order->meta_exists('_co2ok-shown')){
                            return;
                        }

                        $customer_id = \WC()->session->get_customer_id();
                        if ( ! (ord(md5($customer_id)) % 2 == 0)) {
                            $order->update_meta_data( '_co2ok-shown', '1' );
                        } else {
                            $order->update_meta_data( '_co2ok-shown', '0' );
                        }
                        $order->save();
                    } , 10, 2);
                }

                $co2ok_disable_button_on_cart = get_option('co2ok_disable_button_on_cart', 'false');
                if ( $co2ok_disable_button_on_cart == 'false' )
                    add_action('woocommerce_cart_collaterals', array($this, 'co2ok_cart_checkbox'));

                switch ($co2ok_checkout_placement) {
                    case "before_checkout_form":
                        add_action('woocommerce_before_checkout_form', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    case "checkout_before_customer_details":
                        add_action('woocommerce_checkout_before_customer_details', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    case "after_checkout_billing_form":
                        add_action('woocommerce_after_checkout_billing_form', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    case "after_order_notes":
                        add_action('woocommerce_after_order_notes', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    case "review_order_before_submit":
                        add_action('woocommerce_review_order_before_submit', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    case "checkout_order_review":
                        add_action('woocommerce_checkout_order_review', array($this, 'co2ok_checkout_checkbox'));
                        break;
                    // The case below is temporarily removed due to a visual bug: The button hovering over the Place Order button
                    // on the checkout page of webshops
                    // ---------------------------------
                    // case "review_order_after_submit":
                    //     add_action('woocommerce_review_order_after_submit', array($this, 'co2ok_checkout_checkbox'));
                    //     add_action('woocommerce_cart_collaterals', array($this, 'co2ok_cart_checkbox'));
                    //     break;
                    // case "none": // this case is needed to remove the placement when you switch back to "Default" - don't remove this case
                        // break;
                    }



                add_action('woocommerce_cart_calculate_fees', array($this, 'co2ok_woocommerce_custom_surcharge'));


                /**
                 * Woocommerce' state for an order that's accepted and should be
                 * stored on our end is 'processing'
                 */
                add_action('woocommerce_order_status_changed',
                    array($this, 'co2ok_store_transaction_when_compensating'), 99, 3);

                /**
                 * I suspect some webshops might have a different flow, so let's log some events
                 * TODO
                 */


                /**
                 * Deal with the obscure way WC handles refunds
                 */

                add_action( 'woocommerce_order_refunded',
                    array($this, 'co2ok_woocommerce_order_refunded'), 10, 2 ); 
                

                /**
                 * Register Front End
                 */
                add_action('wp_enqueue_scripts', array($this, 'co2ok_stylesheet'));
                add_action('wp_enqueue_scripts', array($this, 'co2ok_font'));
                add_action('wp_enqueue_scripts', array($this, 'co2ok_javascript'));

                add_action('wp_ajax_nopriv_co2ok_ajax_set_percentage', array($this, 'co2ok_ajax_set_percentage'));
                add_action('wp_ajax_co2ok_ajax_set_percentage', array($this, 'co2ok_ajax_set_percentage'));

                // Check if merchant is registered, if for whatever reason this merchant is in fact not a registered merchant,
                // Maybe the api was down when this user registered the plugin, in that case we want to re-register !
                $alreadyActivated = get_option('co2ok_id', false);
                if (!$alreadyActivated) {
                    Co2ok_Plugin::registerMerchant();

                    // Set optimal defaults
                    update_option('co2ok_widgetmark_footer', 'on');
                    update_option('co2ok_checkout_placement', 'checkout_order_review');
                }

                // Check if merchant code is stored, otherwise do so
                $codeAlreadyStored = get_option('co2ok_code', false);
                if (!$codeAlreadyStored)
                    Co2ok_Plugin::storeMerchantCode();

                // set CO2ok_impact cookie, TTL 24 hours
                $co2okImpact = round(get_option('co2ok_impact', 242), 0);
                if(!isset($_COOKIE['co2ok_impact'])) {                             
                    setcookie('co2ok_impact', $co2okImpact, time()+86400, '/');
                }

                add_filter( 'cron_schedules', array($this, 'cron_add_weekly' ));
                add_filter( 'cron_schedules', array($this, 'cron_add_monthly' ));

                if ( ! wp_next_scheduled( 'co2ok_participation_cron_hook' ) ) {
                    // scheduled for now + 15 hours
                    wp_schedule_event( time() + 69000, 'weekly', 'co2ok_participation_cron_hook' );
                }
                add_action( 'co2ok_participation_cron_hook', array($this, 'co2ok_calculate_participation' ));

                
                if ( ! wp_next_scheduled( 'co2ok_clv_cron_hook' ) ) {
                    // scheduled for now + 15 hours and 5 min
                    wp_schedule_event( time() + 69300, 'monthly', 'co2ok_clv_cron_hook' );
                }
                add_action( 'co2ok_clv_cron_hook', array($this, 'co2ok_calculate_clv' ));

                if ( ! wp_next_scheduled( 'co2ok_impact_cron_hook' ) ) {
                    // scheduled for now + 16 hours
                    wp_schedule_event( time() + 72600, 'daily', 'co2ok_impact_cron_hook' );
                }
                add_action( 'co2ok_impact_cron_hook', array($this, 'co2ok_calculate_impact' ));

                add_action('init', array($this, 'co2ok_register_shortcodes'));

                if ($ab_research == 'on') {
                    if ( ! wp_next_scheduled( 'co2ok_ab_results_cron_hook' ) ) {
                        wp_schedule_event( time(), 'daily', 'co2ok_ab_results_cron_hook' );
                    }
                    
                    add_action( 'co2ok_ab_results_cron_hook', array($this, 'co2ok_calculate_ab_results' ));
                }

                $co2ok_widgetmark_footer = get_option('co2ok_widgetmark_footer', 'off');
                if ($co2ok_widgetmark_footer == 'on') {
                    add_action('wp_footer', array($this, 'co2ok_footer_widget'));
                }

        }
        else
        {
            add_action('admin_notices', array($this, 'co2ok_admin_message'));
        }
    }

    // Admin notice
    final public function co2ok_admin_message() {
        echo '<div class="error"><p>CO2ok for Woocommerce is enabled but not active. It requires WooCommerce in order to work.</p></div>';
    }

    final public function co2ok_ajax_set_percentage()
    {
        if( empty($_POST) )
            die('Security check');

        global $woocommerce;

        $this->percentage = filter_var ( $_POST['percentage'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        if ($this->percentage < 0) {
            die("Something went wrong. Please try again");
        }

        // in preparation of stripping out the middleware this is now hardcoded
        // $woocommerce->session->percentage = $this->percentage * 2;
        $woocommerce->session->percentage = 1.652892561983472;        ;

        $this->surcharge = $this->co2ok_calculateSurcharge($add_tax = true);
        // $this->surcharge = round($this->surcharge, 4);

        $return = array(
            'compensation_amount'	=> get_woocommerce_currency_symbol() . number_format($this->surcharge, 2, wc_get_price_decimal_separator(), ' ')
        );

        wp_send_json($return);
    }

    final public function co2ok_stylesheet()
    {
        wp_register_style('co2ok_stylesheet', plugins_url('css/co2ok.css', __FILE__).'?plugin_version='.self::VERSION);
        wp_enqueue_style('co2ok_stylesheet');
    }

    final public function co2ok_font()
    {
        wp_enqueue_style( 'co2ok-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700', false );
    }

    final public function co2ok_javascript()
    {
        wp_register_script('co2ok_js_cdn', 'https://s3.eu-central-1.amazonaws.com/co2ok-static/co2ok.js', null, null, true);
        // wp_register_script('co2ok_js_cdn', 'https://s3.eu-central-1.amazonaws.com/co2ok-static/co2ok-test.js', null, null, true);
        // wp_register_script('co2ok_js_cdn', 'http://localhost:8080/co2ok.js', null, null, true);
        wp_enqueue_script('co2ok_js_cdn');
        wp_register_script('co2ok_js_wp', plugins_url('js/co2ok-plugin.js', __FILE__).'?plugin_version='.self::VERSION);
        wp_enqueue_script('co2ok_js_wp', "", array('jquery'), null, true);
        wp_localize_script('co2ok_js_wp', 'ajax_object',
            array('ajax_url' => admin_url('admin-ajax.php')));
        wp_localize_script('co2ok_js_wp', 'plugin',
            array('url' => plugins_url('images', __FILE__)));

    }

    final public function co2ok_load_plugin_textdomain()
    {
        load_plugin_textdomain( 'co2ok-for-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    final private function co2ok_storeTransaction($order_id)
    {
        $order = wc_get_order($order_id);
        $fees = $order->get_fees();
        $customerEmail = $order->get_billing_email();

        $compensationCost = 0;
        foreach ($fees as $fee) {
            if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                $compensationCost = $fee->get_total();
                break;
            }
        }

        $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

        $merchantId = get_option('co2ok_id', false);
        $orderTotal = $order->get_total();

        $graphQLClient->mutation(function ($mutation) use ($merchantId, $order_id, $compensationCost, $orderTotal, $customerEmail)
        {
            $mutation->setFunctionName('storeTransaction');

            $mutation->setFunctionParams(
                array(
                    'merchantId' => $merchantId,
                    'orderId' => $order_id,
                    'compensationCost' => number_format($compensationCost, 2, '.', ''),
                    'orderTotal' => number_format($orderTotal, 2, '.', ''),
                    'currency' => get_woocommerce_currency(),
                    'customerEmail' => $customerEmail
                )
            );
            $mutation->setFunctionReturnTypes(array('ok'));
        }
        , function ($response)// Callback after request
        {
            if (is_wp_error($response)) { // ignore valid responses
                $formattedError = json_encode($response->errors) . ':' . json_encode($response->error_data);
                Co2ok_Plugin::failGracefully($formattedError);
            }
        });
    }


    final private function co2ok_deleteTransaction($order_id)
    {
        $order = wc_get_order($order_id);

        $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

        $merchantId = get_option('co2ok_id', false);

        $graphQLClient->mutation(function ($mutation) use ($merchantId, $order_id)
        {
            $mutation->setFunctionName('deleteTransaction');

            $mutation->setFunctionParams(
                array(
                    'merchantId' => $merchantId,
                    'orderId' => $order_id
                )
            );
            $mutation->setFunctionReturnTypes(array('ok'));
        }
            , function ($response)// Callback after request
            {
                if (is_wp_error($response)) { // ignore valid responses
                    $formattedError = json_encode($response->errors) . ':' . json_encode($response->error_data);
                    // Co2ok_Plugin::failGracefully($formattedError);
                }
            });
    }

    final public function co2ok_store_transaction_when_compensating($order_id, $old_status, $new_status)
    {
        global $woocommerce;
        switch ($new_status) {
            case "completed":
            case "processing":
                $order = wc_get_order($order_id);
                $fees = $order->get_fees();

                if ( co2okfreemius()->allow_tracking() ) {
                    $merchantId = get_option('co2ok_id', false);
                    $orderTotal = $order->get_total();
                    $compensationCost = 0;
                    foreach ($fees as $fee) {
                        if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                            $compensationCost = $fee->get_total();
                            break;
                        }
                    }
                    Co2ok_Plugin::remoteLogging(json_encode([$merchantId, $order_id, $orderTotal, $compensationCost]));
                }

                foreach ($fees as $fee) {
                    if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                        // The user did opt for co2 compensation
                        $this->co2ok_storeTransaction($order_id);
                    }
                }
                break;

            case "refunded":
            case "cancelled":
                $order = wc_get_order($order_id);
                
                // if ( 'shop_order_refund' === $order->get_type() )
                //     $order = wc_get_order($order.get_parent_id());

                $fees = $order->get_fees();

                foreach ($fees as $fee) {
                    if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                        $this->co2ok_deleteTransaction($order_id);
                    }
                }
                break;
        }
    }

    final public function co2ok_woocommerce_order_refunded( $order_id, $refund_id ) { 
        global $woocommerce;
        $order = wc_get_order($order_id);
        $fees = $order->get_fees();

        if ( co2okfreemius()->allow_tracking() ) {
            $merchantId = get_option('co2ok_id', false);
            $orderTotal = $order->get_total();
            $compensationCost = 0;
            foreach ($fees as $fee) {
                if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                    $compensationCost = $fee->get_total();
                    break;
                }
            }
            Co2ok_Plugin::remoteLogging(json_encode(["REFUND", $merchantId, $order_id, $orderTotal, $compensationCost]));
        }

        foreach ($fees as $fee) {
            if ($fee->get_name() == __( 'CO2 compensation', 'co2ok-for-woocommerce' )) {
                // The user did opt for co2 compensation
                $this->co2ok_deleteTransaction($order_id);
            }
        }

    }

    final private function co2ok_calculateSurcharge($add_tax=false)
    /**
	 * Returns surcharge, optionally with tax
     * Allways rounded to 2 decimals, otherwise WC makes a mess with rounding
	 */
    {
        global $woocommerce;

        if ($woocommerce->session->percentage)
            $this->percentage = $woocommerce->session->percentage;

        $order_total = $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total;
        $tax_rates = \WC_Tax::get_base_tax_rates( );
        $co2ok_rate = \WC_Tax::get_rates('co2ok');

        $order_total_with_tax = $order_total + array_sum(\WC_Tax::calc_tax($order_total, $tax_rates));

        // percentage magic 
        $joet = $order_total_with_tax / 100;
        $this->percentage = (2 - ($joet/(1 + $joet))) * 0.75;

        $surcharge = ($order_total_with_tax) * ($this->percentage / 100);
        $this->surcharge = filter_var ( $surcharge, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        // one of these could suffice (is not like the other) 
        $surcharge = round($this->surcharge, 2);
        $this->surcharge = round($this->surcharge, 2);
        
        if ($add_tax){
            if (count($co2ok_rate) > 0){
                $this->surcharge = $surcharge + array_sum(\WC_Tax::calc_tax($surcharge, $co2ok_rate));
            } else {
                $this->surcharge = $surcharge + array_sum(\WC_Tax::calc_tax($surcharge, $tax_rates));
            }
        }


        return $this->surcharge;
    }

    final private function co2ok_CartDataToJson()
    {
        global $woocommerce;
        $cart = array();

        $items = $woocommerce->cart->get_cart();
        foreach ($items as $item => $values)
        {
            $_product = $values['data'];

            $product_data = array();
            $product_data['name'] = $_product->get_name();
            $product_data['quantity'] = $values['quantity'];
            $product_data['brand'] = "";
            $product_data['description'] = $_product->get_description();
            $product_data['shortDescription'] = $_product->get_short_description();
            $product_data['sku'] = $_product->get_sku();
           // $product_data['gtin'] = $_product->get;
            $product_data['price'] = $_product->get_price();
            $product_data['taxClass'] = $_product->get_tax_class();
            $product_data['weight'] = $_product->get_weight();
            $product_data['attributes'] = $_product->get_attributes();
            $product_data['defaultAttributes'] = $_product->get_default_attributes();

            $cart[] = $product_data;
        }

        return $cart;
    }

    final public function renderCheckbox()
    {
        global $woocommerce;
        $this->surcharge = $this->co2ok_calculateSurcharge($add_tax=true);
        // $this->surcharge = round($this->surcharge, 4);
        $this->helperComponent->RenderCheckbox( esc_html(number_format($this->surcharge , 2, wc_get_price_decimal_separator(), ' ') ) , esc_attr(urlencode(json_encode($this->co2ok_CartDataToJson())) ));
    }

    final public function co2ok_cart_checkbox()
    {
        if (get_option('co2ok_ab_research') == 'on') {
            $co2ok_hide_button = ord(md5(\WC()->session->get_customer_id())) % 2 == 0;
        } else {
            $co2ok_hide_button = false;
        }
        
        if ( !$co2ok_hide_button) {
            $this->renderCheckbox();
        }
    }
    
    final public function co2ok_checkout_checkbox()
    {
        if (get_option('co2ok_ab_research') == 'on') {
            $co2ok_hide_button = ord(md5(\WC()->session->get_customer_id())) % 2 == 0;
        } else {
            $co2ok_hide_button = false;
        }
        
        if ( !$co2ok_hide_button) {
            $this->renderCheckbox();
        }
    }

    final public function co2ok_woocommerce_custom_surcharge($cart)
    {
        $this->surcharge = $this->co2ok_calculateSurcharge();

        global $woocommerce;

        if (isset($_POST['post_data'])) {
            parse_str($_POST['post_data'], $post_data);
        } else {
            $post_data = $_POST;
        }

        if (isset($post_data['co2ok_cart'])) {
            if ($post_data['co2ok_cart'] == 1) {
                $woocommerce->session->co2ok = 1;
            }
            else if ($post_data['co2ok_cart'] == 0) {
                $woocommerce->session->co2ok = 0;
            }
        }

        $optoutIsTrue = get_option('co2ok_optout', 'off');

        if ($optoutIsTrue == 'on' && ! $woocommerce->session->__isset('co2ok'))
            $woocommerce->session->co2ok = 1;
            
        if ($woocommerce->session->co2ok == 1)
            $woocommerce->cart->add_fee(__( 'CO2 compensation', 'co2ok-for-woocommerce' ), $this->surcharge, true, 'co2ok');

    }

    final public function co2ok_calculate_impact()
    {
        /**
         * Calculates compensation count and total impact this shop had in the fight against climate change
         * Result is stored in amount of compensations and kg of CO2 compensated
         */

        global $woocommerce;
        $args = array(
        // orders since the start of the CO2ok epoch
        'date_created' => '>1530422342',
        'limit' => -1,
        );
        $orders = wc_get_orders( $args );

        $compensationTotal = 0;
        $compensationCount = 0;
        
        foreach ($orders as $order) {
            $fees = $order->get_fees();
            foreach ($fees as $fee) {
                if (strpos ($fee->get_name(), 'CO2' ) !== false) {
                    $compensationTotal += $fee->get_total();
                    $compensationCount += 1;
                }
            }
        }

        // determine kg of CO2 compensated
        $impactTotal = $compensationTotal * 67;
        // fake it till you make it; 0 => 42kg && 0 => 1
        $impactTotal = ($impactTotal == 0 ? 42 : $impactTotal);
        $compensationCount = ($compensationCount == 0 ? 1 : $compensationCount);
        
        update_option('co2ok_compensation_count', $compensationCount);
        update_option('co2ok_impact', $impactTotal);
    }

    final public function co2ok_calculate_participation()
    {
        global $woocommerce;
        $args = array(
        // of mss date_paid, maar iig niet _completed
        'date_created' => '>' . ( time() - 2592000 ),
        'limit' => -1,
        );
        $orders = wc_get_orders( $args );

        $parti = 0; // participated
        
        foreach ($orders as $order) {
            $fees = $order->get_fees();
            foreach ($fees as $fee) {
                if (strpos ($fee->get_name(), 'CO2' ) !== false) {
                    $parti ++;
                }
            }
        }

        $participation = $parti / sizeof($orders);
        
        $site_name = $_SERVER['SERVER_NAME'];
        Co2ok_Plugin::remoteLogging(json_encode(["Participation last month", $site_name, round(($participation * 100), 2)]));
    }

    final public function cron_add_weekly( $schedules ) {
        // Adds once weekly to the existing schedules.
        $schedules['weekly'] = array(
            'interval' => 604800,
            'display' => __( 'Once Weekly' )
        );
        return $schedules;
    }

    final public function co2ok_calculate_ab_results()
    {
        global $woocommerce;

        $site_name = $_SERVER['SERVER_NAME'];
        Co2ok_Plugin::remoteLogging(json_encode(["A/B result calculation start", $site_name]));

        $args = array(
        'date_created' => '2019-10-01...2021-01-01',
        'order' => 'ASC',
        'limit' => -1,
        );
        $orders = wc_get_orders( $args );
        $shown_old_count = 0; // orders with CO2ok shown OLD
        $shown_count = 0; // orders with CO2ok shown
        $hidden_count = 0; // orders with CO2ok hidden
        $order_count = 0; // orders
        $ab_order_count = 0; // orders
        $shown_found = false; 
        $skipped_orders = 0;

        foreach ($orders as $order) {

            // wc_get_orders also returns refunds, so skip these to avoid errors.
	        if ( ! $order || 'shop_order_refund' === $order->get_type() )
                continue;

            // we prob don't need this anymore since adding above refund skip, but let's see results first
            // copied from https://github.com/mailchimp/mc-woocommerce/blob/9804e28bb555e5a22a453d699f2365bf5a0e85db/includes/api/class-mailchimp-woocommerce-transform-orders-wc3.php#L76
            // if the woo object does not have a "get_customer_id" method, then we need to skip this until
            // we know how to resolve these types of things.
            if (!method_exists($order, 'get_customer_id')) {
                $skipped_orders ++;
                continue;
            }

            $customer_id = $order->get_customer_id();
            $shown_old = $order->get_meta('co2ok-shown');
            $shown = $order->get_meta('_co2ok-shown');
            // since get_meta returns an empty string for non-existing keys, this is a little convoluted:
            $hidden = ($order->meta_exists('_co2ok-shown') ? ! $order->get_meta('_co2ok-shown') : 0);
            $order_count ++;
            
            // count the number of orders with CO2ok shown OLD
            if ($shown_old) {
                $shown_old_count ++;
                
                // reset the order count once the first is found
                if (! $shown_found) {
                    $shown_found = true;
                    $order_count = 1;
                }
            }

            if ($shown) {
                $shown_count ++;
                $ab_order_count ++;
            }
            if ($hidden) {
                $hidden_count ++;
                $ab_order_count ++;
            }
        }
        
        // Error-prevention:
        if ($order_count - $shown_old_count == 0){
            Co2ok_Plugin::remoteLogging(json_encode(["A/B test early return", $site_name, $shown_old_count, $order_count, "New", $shown_count, $hidden_count, $ab_order_count]));
            return;
        }

        // Error-prevention:
        if ($hidden_count == 0){
            Co2ok_Plugin::remoteLogging(json_encode(["A/B test hello world", $site_name, $shown_old_count, $order_count, "New", $shown_count, $hidden_count, $ab_order_count]));
            return;
        }

        $shown_old_count += $shown_count; // oud & nieuw :)

        $percentage_old = $shown_old_count / ($order_count - $shown_old_count);
        $percentage = $shown_count / $hidden_count;

        // remote log: site name, shown orders, total orders, percentage_old
        Co2ok_Plugin::remoteLogging(json_encode(["A/B test results", $site_name, $shown_old_count, $order_count, round(($percentage_old * 100 - 100), 2), "Skipped:", $skipped_orders, "New", $shown_count, $hidden_count, $ab_order_count, round(($percentage * 100 - 100), 2)]));
    }
    final public function cron_add_monthly( $schedules ) {
        // Adds once monthly to the existing schedules.
        $schedules['monthly'] = array(
            'interval' => 2592000,
            'display' => __( 'Once Monthly' )
        );
        return $schedules;
    }

    final public function co2ok_register_shortcodes() {
        add_shortcode('co2ok_widgetmark', array($this, 'co2ok_widgetmark_shortcode'));
        add_shortcode('co2ok_widget', array($this, 'co2ok_widget_shortcode'));
    }

    final public function co2ok_widgetmark_shortcode() {
        $code = get_option('co2ok_code');
        /*
        '<script src="https://co2ok.eco/widget/co2okWidgetMark-' . $code . '.js"></script>'.
        '<script src="http://localhost:8080/widget/co2okWidgetMark.js"></script>'.
        */

        $widget_code = 
        '<div id="widgetContainer" style="width:auto;height:auto;display:flex;flex-direction:row;align-items:center;margin-top: 5px;"></div>'.
        '<script src="https://co2ok.eco/widget/co2okWidgetMark-' . $code . '.js"></script>'.
        "<script>Co2okWidget.merchantCompensations('widgetContainer','". $code . "')</script>";
        
        return $widget_code;
    }

    final public function co2ok_widget_shortcode($atts = []){ 
    
        // override default attributes with user attributes
        $co2ok_atts = shortcode_atts([
            'size' => 'XL',
            'color' => 'default',
        ], $atts);

        $code = get_option('co2ok_code');
        $size = $co2ok_atts['size'];
        $color = $co2ok_atts['color'];
        /*
        '<script src="https://co2ok.eco/widget/co2okWidgetXL-' . $code . '.js"></script>'.
        '<script src="http://localhost:8080/widget/co2okWidgetXL.js"></script>'.
        */
        
        $widget_code = 
        '<div id="widgetContainerXL" style="width:auto;height:auto;display:flex;flex-direction:row;align-items:center;margin-top: 5px;"></div>'.
        '<script src="https://co2ok.eco/widget/co2okWidgetXL-' . $code . '.js"></script>'.
        "<script>Co2okWidgetXL.merchantCompensations('widgetContainerXL','" . $code . "','" . $size . "','" . $color .  "')</script>";
        
        return $widget_code;
    }

    final public function rutime($ru, $rus, $index) {
        return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
         -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000)) . "ms";
    }

    final public function co2ok_calculate_clv(){

        // start timer
        $rustart = getrusage();

        // get customers
        global $wpdb;
        $ids = $wpdb->get_col( "SELECT DISTINCT `order_id` FROM `{$wpdb->prefix}woocommerce_order_items`" );
        foreach ( $ids as $id ) {
            $email[] = get_post_meta( $id, '_billing_email' );
        }
        $customers = array_unique( wp_list_pluck( $email, 0 ));

        // determine CLV
        foreach ( $customers as $customer) {
            $co2ok_ness = false;
            $query = new \WC_Order_Query();                          
            $query->set( 'customer', $customer ); 
            // $query->set( 'date_created', '2019-08-13...2020-01-01' );
            $orders = $query->get_orders(); 
            $total = 0;
            foreach( $orders as $order ) {
                $total += $order->get_total();
        
                // determine CO2ok-ness
                $fees = $order->get_fees();
                foreach ($fees as $fee) {
                    if (strpos ($fee->get_name(), 'CO2' ) !== false)
                        $co2ok_ness = true;            
                }
            }
            
            if ($co2ok_ness) {
                $clv_co2okees[] = $total;
            } else {
                $clv_muggles[] = $total;
            }
            
            wp_reset_query();
        }

        // Bail if only muggles found
        if (!$clv_co2okees)
            return;

        // time reporting
        $ru = getrusage();
        $runtime = $this->rutime($ru, $rustart, "utime");

        // CLV improvement calc
        $clv_co2okees_avg = array_sum($clv_co2okees) / count($clv_co2okees);
        $clv_muggles_avg = array_sum($clv_muggles) / count($clv_muggles);
        $co2ok_clv_improvement = round(($clv_co2okees_avg / $clv_muggles_avg - 1) * 100, 1) . "%";

        $site_name = $_SERVER['SERVER_NAME'];
        Co2ok_Plugin::remoteLogging(json_encode(["CLV increase", $site_name, $co2ok_clv_improvement, $runtime]));

    }

    final public function co2ok_footer_widget() {    
        $code = get_option('co2ok_code');
        /*
        '<script src="https://co2ok.eco/widget/co2okWidgetMark-' . $code . '.js"></script>'.
        '<script src="http://localhost:8080/widget/co2okWidgetMark.js"></script>'.
        */
        
        $footer_code =
        '<script>jQuery("footer").find("div").first().append(\'' . 
        '<div id="widgetContainer" style="width:180px;height:auto;display:flex;flex-direction:row;justify-content:center;align-items:center;"></div>' .
        '\' );</script>';
        
        $widget_js = 
        // '<script src="http://localhost:8080/widget/co2okWidgetMark.js"></script>'.
        '<script src="https://co2ok.eco/widget/co2okWidgetMark-' . $code . '.js"></script>'.
        '<script>Co2okWidget.merchantCompensations("widgetContainer", "'. $code . '")</script>';

        echo $footer_code;
        echo $widget_js;
    }

}
endif; //! class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' )



// called only after woocommerce has finished loading
// add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );


// // add_action( 'woocommerce_init', 'process_post' );

// // function process_post() {
// //      error_log('stuff');
// // }

// if (in_array('woocommerce/woocommerce.php', apply_filters(
//     'active_plugins', get_option('active_plugins'))))
// {
//     // WooCommerce::init();
//     // add_action( 'muplugins_loaded', 'my_plugin_override' );

//     // if ( !function_exists( 'is_checkout' ) || !function_exists( 'is_cart' ) ) {

//     //         error_log("Should not render");

//     //     } else {

//     //         if( is_checkout() || is_cart() ) error_log("Should render");

//     //     }
// }

$co2okPlugin = new \co2ok_plugin_woocommerce\Co2ok_Plugin();

register_activation_hook( __FILE__, array( '\\co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Activated' ) );
register_deactivation_hook( __FILE__, array( '\\co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Deactivated' ) );
?>
