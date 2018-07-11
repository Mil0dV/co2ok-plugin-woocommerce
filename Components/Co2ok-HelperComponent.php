<?php
namespace co2ok_plugin_woocommerce\Components;

if ( !class_exists( 'co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent' ) ) :

    class Co2ok_HelperComponent
    {
        public function __construct()
        {

        }

        static public function RenderImage($uri, $class = null, $id = null)
        {
            $img_html = '<img alt="Maak mijn aankoop klimaatneutraal " title="Maak mijn aankoop klimaatneutraal " src="' .esc_url(plugins_url($uri, __FILE__)) . '" ';
            $img_html = str_ireplace( '/Components', '', $img_html );
            if (isset($class))
                $img_html .= 'class="' . $class . '" ';
            if (isset($id))
                $img_html .= 'id="' . $id . '" ';

            return $img_html . ' />';
        }


        public function RenderCheckbox($surcharge, $cart)
        {
            global $woocommerce;
            global $Co2ok_Plugin;

            $templateRenderer = new Co2ok_TemplateRenderer(plugin_dir_path(__FILE__).'../Templates/');

            $optinIsTrue = get_option('co2ok_optin', 'off');

            if ( ! function_exists('write_log')) {
                function write_log ( $log ) {
                    if ( is_array( $log ) || is_object( $log ) ) {
                        error_log( print_r( $log, true ) );
                    } else {
                        error_log( $log );
                    }
                }
            }
            
            write_log($woocommerce->session->co2ok);
            write_log('henk');

            // $co2okPlugin = new Co2ok_Plugin();

            // TODO onderstaande dubbele conditie fixen
            // !$woocommerce->session->co2ok moet dus zijn: als die niet bestaat 
            // if ($optinIsTrue && !$woocommerce->session->co2ok) {

            if ($optinIsTrue) {
                $woocommerce->session->co2ok = 1;
                // \Co2ok_Plugin\co2ok_woocommerce_custom_surcharge();
                // $cart = $woocommerce->cart->get_cart();
                // $Co2ok_Plugin->co2ok_woocommerce_custom_surcharge($cart);
            }

            // Render checkbox / button according to admin settings
            echo $templateRenderer->render(get_option('co2ok_button_template', 'co2ok_button_template_default'),
            array('cart' => $cart,
                    'co2_ok_session_opted' =>  $woocommerce->session->co2ok,
                    'co2ok_optin' =>  $optinIsTrue,
                    'currency_symbol' =>get_woocommerce_currency_symbol(),
                    'surcharge' => $surcharge
                )
            );
            

            
        }

    }

endif;
