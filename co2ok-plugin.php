<?php
/**
 * Plugin Name: CO2ok for WooCommerce
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 1.0.1.7
 *         (Remember to change the VERSION constant, below, as well!)
 *
 * Tested up to: 4.9.6
 * WC tested up to: 3.4.2
 *
 * Author:
 * Milo de Vries,
 * Chris Fuller,
 * Ryan George,
 * Michiel Tienhoven
 * Text Domain: co2ok-for-woocommerce
 * Author URI: http://www.co2ok.eco/
 * License: GPLv2
 *
 * @package co2ok-plugin-woocommerce
 */
namespace co2ok_plugin_woocommerce;

/*
* Freemius integration
*/

// Create a helper function for easy SDK access.
function co2okfreemius() {
	global $co2okfreemius;

	if ( ! isset( $co2okfreemius ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/freemius/start.php';

		$co2okfreemius = fs_dynamic_init(
			array(
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
			)
		);
	}

	return $co2okfreemius;
}

// Init Freemius.
co2okfreemius();
// Signal that SDK was initiated.
do_action( 'co2okfreemius_loaded' );

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

require 'vendor/autoload.php';

/**
 * Prevent data leaks
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if class exists
if ( ! class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' ) ) :

	class Co2ok_Plugin {

		/**
	 * This plugin's version
	 */
		const VERSION = '1.0.1.7';

		static $co2okApiUrl = 'https://test-api.co2ok.eco/graphql';

		// Percentage should be returned by the middleware, else: 1%
		private $percentage = 1;
		private $surcharge  = 0;

		private $helperComponent;

		/*
		* Returns the result of a debug_backtrace() as a pretty-printed string
		* @param  array   $trace Result of debug_backtrace()
		* @param  boolean $safe  Whether to remove exposing information from print
		* @return string         Formatted backtrace
		*/
		final static function formatBacktrace( $trace, $safe = true ) {
			array_pop( $trace ); // remove {main}
			$log = 'Backtrace:';
			foreach ( array_reverse( $trace ) as $index => $line ) {
				// Format file location
				$location = $line['file'];
				if ( $safe ) {
					// Z:\private\exposing\webserver\directory\co2ok-plugin-woocommerce\co2ok_plugin.php -> **\co2ok_plugin.php
					$location = preg_replace( '#.*[\\\/]#', '**\\', $location );
				}

				// Format caller
				$caller = '';
				if ( array_key_exists( 'class', $line ) ) {
					$caller = $line['class'] . $line['type'];
				}
				$caller .= $line['function'];

				// Format state, append to $caller
				if ( ! $safe || $index == count( $trace ) - 1 ) { // If unsafe or last call
					if ( array_key_exists( 'object', $line ) && ! empty( $line['object'] ) ) {
						$caller .= "\n      " . $line['class'] . ':';
						foreach ( $line['object'] as $name => $value ) {
							$caller .= "\n        " . print_r( $name, true ) . ': ' . print_r( $value, true );
						}
					}
					if ( array_key_exists( 'args', $line ) && ! empty( $line['args'] ) ) {
						$caller .= "\n      args:";
						foreach ( $line['args'] as $name => $value ) {
							$caller .= "\n        " . print_r( $name, true ) . ': ' . print_r( $value, true );
						}
					}
				}

				// Append contents to string
				$log .= sprintf( "\n    %s(%d): %s", $location, $line['line'], $caller );
			}
			return $log;
		}

		/*
		* Fail silently
		* @param string $error Error message
		*/
		final public static function failGracefully( $error = 'Unspecified error.' ) {
			// Format error notice
			$now = date( 'Ymd_HisT' );
			$logmsg = function ( $info ) use ( $now, $error ) {
				return sprintf( "[%s:FAIL] %s\n%s\n", $now, $error, $info );
			};

			// Generate backtrace
			$trace = debug_backtrace();
			array_shift( $trace ); // remove call to this method

			// Write to local log
			$local = $logmsg( Co2ok_Plugin::formatBacktrace( $trace, false ) );
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
				$token = '8acac111-633f-46b3-b14b-1605e45ae614'; // our LogEntries token
				$remote = LogEntries::getLogger( $token, true, true );
				$remote->error( explode( "\n", $logmsg( Co2ok_Plugin::formatBacktrace( $trace ) ) ) ); // explode for multiline
			} catch ( Exception $e ) { // fail silently
			}
		}

		final static function registerMerchant() {
			$graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient( Co2ok_Plugin::$co2okApiUrl );

			$merchantName = $_SERVER['SERVER_NAME'];
			$merchantEmail = get_option( 'admin_email' );

			$graphQLClient->mutation(
				function ( $mutation ) use ( $merchantName, $merchantEmail ) {
					$mutation->setFunctionName( 'registerMerchant' );
					$mutation->setFunctionParams(
						array(
							'name' => $merchantName,
							'email' => $merchantEmail,
						)
					);
					$mutation->setFunctionReturnTypes(
						array(
							'merchant' => array( 'secret', 'id' ),
							'ok',
						)
					);
				}
				, function ( $response ) {
					if ( is_wp_error( $response ) ) { // ignore valid responses
						$formattedError = json_encode( $response->errors ) . ':' . json_encode( $response->error_data );
						// Co2ok_Plugin::failGracefully($formattedError);
						return;
					}
					if ( ! is_array( $response['body'] ) ) {
						$response = json_decode( $response['body'], 1 );
					}

					if ( $response['data']['registerMerchant']['ok'] == true ) {
						add_option( 'co2ok_id', sanitize_text_field( $response['data']['registerMerchant']['merchant']['id'] ) );
						add_option( 'co2ok_secret', sanitize_text_field( $response['data']['registerMerchant']['merchant']['secret'] ) );
					} else // TO DO error handling...
					{
						$formattedError = json_encode( $response['data'] );
						// Co2ok_Plugin::failGracefully($formattedError);
					}
				}
			);
		}

		// This function is called when the user activates the plugin.
		final static function co2ok_Activated() {
			$alreadyActivated = get_option( 'co2ok_id', false );

			if ( ! $alreadyActivated ) {
				Co2ok_Plugin::registerMerchant();

			} else {
				// The admin has updated this plugin ..
			}
		}

		// This function is called when the user activates the plugin.
		final static function co2ok_Deactivated() {

		}

		/**
	 * Constructor.
	 */
		final public function __construct() {
			/**
		 * Check if WooCommerce is active
		 */
			if ( in_array(
				'woocommerce/woocommerce.php', apply_filters(
					'active_plugins', get_option( 'active_plugins' )
				)
			) ) {
				/**
				 * Load translations
				 */
				add_action( 'plugins_loaded', array( $this, 'co2ok_load_plugin_textdomain' ) );
				require_once  plugin_dir_path( __FILE__ ) . '/co2ok-autoloader.php' ;

				$this->helperComponent = new \co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent();

				add_action( 'woocommerce_after_order_notes', array( $this, 'co2ok_checkout_checkbox' ) );
				add_action( 'woocommerce_cart_collaterals', array( $this, 'co2ok_cart_checkbox' ) );
				add_action( 'woocommerce_cart_calculate_fees', array( $this, 'co2ok_woocommerce_custom_surcharge' ) );

				/**
				 * Woocommerce' state for an order that's accepted and should be
				 * stored on our end is 'processing'
				 */
				add_action(
					'woocommerce_order_status_changed',
					array( $this, 'co2ok_store_transaction_when_compensating' ), 99, 3
				);

					/**
				 * I suspect some webshops might have a different flow, so let's log some events
				 * TODO
				 */

					/**
				 * Register Front End
				 */
					add_action( 'wp_enqueue_scripts', array( $this, 'co2ok_stylesheet' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'co2ok_font' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'co2ok_javascript' ) );

					add_action( 'wp_ajax_nopriv_co2ok_ajax_set_percentage', array( $this, 'co2ok_ajax_set_percentage' ) );
					add_action( 'wp_ajax_co2ok_ajax_set_percentage', array( $this, 'co2ok_ajax_set_percentage' ) );

					// Check if merchant is registered, if for whatever reason this merchant is in fact not a registered merchant,
					// Maybe the api was down when this user registered the plugin, in that case we want to re-register !
					$alreadyActivated = get_option( 'co2ok_id', false );
				if ( ! $alreadyActivated ) {
					Co2ok_Plugin::registerMerchant();
				}
			} else {
				// TODO this needs to be a prettier warning, but at least it doesn't break WP.
				trigger_error( __( 'Co2ok Plugin needs Woocommerce to work, please install woocommerce and try again.', 'co2ok-for-woocommerce' ), E_USER_WARNING );
			}
		}

		final public function co2ok_ajax_set_percentage() {
			if ( empty( $_POST ) ) {
				die( 'Security check' );
			}

			global $woocommerce;

			$this->percentage = filter_var( $_POST['percentage'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION );
			if ( $this->percentage < 0 ) {
				die( 'Something went wrong. Please try again' );
			}

			$woocommerce->session->percentage = $this->percentage;

			$this->surcharge = $this->co2ok_calculateSurcharge( $add_tax = true );

			$return = array(
				'compensation_amount'   => get_woocommerce_currency_symbol() . number_format( $this->surcharge, 2, ',', ' ' ),
			);

			wp_send_json( $return );
		}

		final public function co2ok_stylesheet() {
			wp_register_style( 'co2ok_stylesheet', plugins_url( 'css/co2ok.css', __FILE__ ) . '?plugin_version=' . self::VERSION );
			wp_enqueue_style( 'co2ok_stylesheet' );
		}

		final public function co2ok_font() {
			wp_enqueue_style( 'co2ok-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700', false );
		}

		final public function co2ok_javascript() {
			wp_register_script( 'co2ok_js_cdn', 'https://s3.eu-central-1.amazonaws.com/co2ok-static/co2ok.js', null, null, true );
			wp_enqueue_script( 'co2ok_js_cdn' );
			wp_register_script( 'co2ok_js_wp', plugins_url( 'js/co2ok-plugin.js', __FILE__ ) . '?plugin_version=' . self::VERSION );
			wp_enqueue_script( 'co2ok_js_wp', '', array(), null, true );
			wp_localize_script(
				'co2ok_js_wp', 'ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
			);
			wp_localize_script(
				'co2ok_js_wp', 'plugin',
				array( 'url' => plugins_url( 'images', __FILE__ ) )
			);

		}

		final public function co2ok_load_plugin_textdomain() {
			load_plugin_textdomain( 'co2ok-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		final private function co2ok_storeTransaction( $order_id ) {
			$order = wc_get_order( $order_id );
			$fees = $order->get_fees();

			$compensationCost = 0;
			foreach ( $fees as $fee ) {
				if ( $fee->get_name() == __( 'CO2 compensation (Inc. VAT)', 'co2ok-for-woocommerce' ) ) {
					$compensationCost = $fee->get_total();
					break;
				}
			}

			$graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient( Co2ok_Plugin::$co2okApiUrl );

			$merchantId = get_option( 'co2ok_id', false );
			$orderTotal = $order->get_total();

			$graphQLClient->mutation(
				function ( $mutation ) use ( $merchantId, $order_id, $compensationCost, $orderTotal ) {
					$mutation->setFunctionName( 'storeTransaction' );

					$mutation->setFunctionParams(
						array(
							'merchantId' => $merchantId,
							'orderId' => $order_id,
							'compensationCost' => number_format( $compensationCost, 2, '.', '' ),
							'orderTotal' => number_format( $orderTotal, 2, '.', '' ),
							'currency' => get_woocommerce_currency(),
						)
					);
					$mutation->setFunctionReturnTypes( array( 'ok' ) );
				}
				, function ( $response ) {
					if ( is_wp_error( $response ) ) { // ignore valid responses
						$formattedError = json_encode( $response->errors ) . ':' . json_encode( $response->error_data );
						Co2ok_Plugin::failGracefully( $formattedError );
					}
				}
			);
		}


		final private function co2ok_deleteTransaction( $order_id ) {
			$order = wc_get_order( $order_id );

			$graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient( Co2ok_Plugin::$co2okApiUrl );

			$merchantId = get_option( 'co2ok_id', false );

			$graphQLClient->mutation(
				function ( $mutation ) use ( $merchantId, $order_id, $compensationCost, $orderTotal ) {
					$mutation->setFunctionName( 'deleteTransaction' );

					$mutation->setFunctionParams(
						array(
							'merchantId' => $merchantId,
							'orderId' => $order_id,
						)
					);
					$mutation->setFunctionReturnTypes( array( 'ok' ) );
				}
				, function ( $response ) {
					if ( is_wp_error( $response ) ) { // ignore valid responses
						$formattedError = json_encode( $response->errors ) . ':' . json_encode( $response->error_data );
						// Co2ok_Plugin::failGracefully($formattedError);
					}
				}
			);
		}

		final public function co2ok_store_transaction_when_compensating( $order_id, $old_status, $new_status ) {
			global $woocommerce;
			switch ( $new_status ) {
				case 'processing':
					$order = wc_get_order( $order_id );
					$fees = $order->get_fees();

					foreach ( $fees as $fee ) {
						if ( $fee->get_name() == __( 'CO2 compensation (Inc. VAT)', 'co2ok-for-woocommerce' ) ) {
							// The user did opt for co2 compensation
							$this->co2ok_storeTransaction( $order_id );
						}
					}
					break;

				case 'refunded':
				case 'cancelled':
					$order = wc_get_order( $order_id );
					$fees = $order->get_fees();

					foreach ( $fees as $fee ) {
						if ( $fee->get_name() == __( 'CO2 compensation (Inc. VAT)', 'co2ok-for-woocommerce' ) ) {
							$this->co2ok_deleteTransaction( $order_id );
						}
					}
					break;
			}
		}

		final private function co2ok_calculateSurcharge( $add_tax = false ) {
			global $woocommerce;

			if ( $woocommerce->session->percentage ) {
				$this->percentage = $woocommerce->session->percentage;
			}

			$order_total = $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total;
			$tax_rates = \WC_Tax::get_base_tax_rates();

			$highest_tax_rate = 0;
			foreach ( $tax_rates as $tax_rate ) {
				if ( $highest_tax_rate < $tax_rate['rate'] ) {
					$highest_tax_rate = $tax_rate['rate'];
				}
			}
			$highest_tax_rate = ( (int) $highest_tax_rate) / 100;
			$order_total_with_tax = ($order_total * $highest_tax_rate) + $order_total;

			$surcharge = ($order_total_with_tax) * ($this->percentage / 100);
			$this->surcharge = filter_var( $surcharge, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

			if ( $add_tax ) {
				$this->surcharge = (1 + $highest_tax_rate) * round( $surcharge, 2 );
			}

			return $this->surcharge;
		}

		final private function co2ok_CartDataToJson() {
			global $woocommerce;
			$cart = array();

			$items = $woocommerce->cart->get_cart();
			foreach ( $items as $item => $values ) {
				$_product = $values['data'];

				$product_data = array();
				$product_data['name'] = $_product->get_name();
				$product_data['quantity'] = $values['quantity'];
				$product_data['brand'] = '';
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

		final private function renderCheckbox() {
			global $woocommerce;
			$this->surcharge = $this->co2ok_calculateSurcharge( $add_tax = true );
			$this->helperComponent->RenderCheckbox( esc_html( number_format( $this->surcharge , 2, ',', ' ' ) ) , esc_attr( urlencode( json_encode( $this->co2ok_CartDataToJson() ) ) ) );
		}

		final public function co2ok_cart_checkbox() {
			$this->renderCheckbox();
		}

		final public function co2ok_checkout_checkbox() {
			$this->renderCheckbox();
		}

		final public function co2ok_woocommerce_custom_surcharge( $cart ) {
			$this->surcharge = $this->co2ok_calculateSurcharge();

			global $woocommerce;

			if ( isset( $_POST['post_data'] ) ) {
				parse_str( $_POST['post_data'], $post_data );
			} else {
				$post_data = $_POST;
			}

			if ( isset( $post_data['co2ok_cart'] ) ) {
				if ( $post_data['co2ok_cart'] == 1 ) {
					$woocommerce->session->co2ok = 1;
				} elseif ( $post_data['co2ok_cart'] == 0 ) {
					$woocommerce->session->co2ok = 0;
				}
			}

			if ( $woocommerce->session->co2ok == 1 ) {
				$woocommerce->cart->add_fee( __( 'CO2 compensation (Inc. VAT)', 'co2ok-for-woocommerce' ), $this->surcharge, true, '' );
			}

		}
	}
endif; // ! class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' )



// called only after woocommerce has finished loading
// add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
// // add_action( 'woocommerce_init', 'process_post' );
// // function process_post() {
// //      error_log('stuff');
// // }
// if (in_array('woocommerce/woocommerce.php', apply_filters(
// 'active_plugins', get_option('active_plugins'))))
// {
// WooCommerce::init();
// add_action( 'muplugins_loaded', 'my_plugin_override' );
// if ( !function_exists( 'is_checkout' ) || !function_exists( 'is_cart' ) ) {
		// error_log("Should not render");
// } else {
// if( is_checkout() || is_cart() ) error_log("Should render");
			// }
// }
$co2okPlugin = new \co2ok_plugin_woocommerce\Co2ok_Plugin();

register_activation_hook( __FILE__, array( 'co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Activated' ) );
register_deactivation_hook( __FILE__, array( 'co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Deactivated' ) );

