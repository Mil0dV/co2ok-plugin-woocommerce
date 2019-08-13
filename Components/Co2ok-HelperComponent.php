<?php
namespace co2ok_plugin_woocommerce\Components;

if ( !class_exists( 'co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent' ) ) :

    class Co2ok_HelperComponent
    {
        public function __construct()
        {

        }

        static public function RenderImage($uri, $class = null, $class_global = null, $id = null)
        {
            $img_html = '<img alt="Maak mijn aankoop klimaatneutraal " title="Maak mijn aankoop klimaatneutraal " src="' .esc_url(plugins_url($uri, __FILE__)) . '" ';
            $img_html = str_ireplace( '/Components', '', $img_html );
            if (isset($class))
                $img_html .= 'class="' . $class .' '. $class_global .  '"';
            if (isset($id))
                $img_html .= 'id="' . $id . '" ';

            return $img_html . ' />';
        }

        static public function RenderRandomizedVideo()
        {
            $rewardVideo[] = array();
            // $rewardVideo[0] = 'happy-piggy-loop';
            $rewardVideo[0] = 'happy-flower';
            // $rewardVideo[2] = 'cat-high-five';
            $rewardVideo[1] = 'happy-globe';

            $pickedVideo = mt_rand(0,count($rewardVideo) - 1);

            $videopath = esc_url(plugins_url('images/'.$rewardVideo[$pickedVideo], __FILE__));
            $videopath = str_ireplace( '/Components', '', $videopath );

            $video_html = '<source src="' . $videopath . '.mp4" type="video/mp4">';

            return $video_html;
        }

        public function RenderCheckbox($surcharge, $cart)
        {
            global $woocommerce;

            $templateRenderer = new Co2ok_TemplateRenderer(plugin_dir_path(__FILE__).'../Templates/');

            // Render checkbox / button according to admin settings
            echo $templateRenderer->render(get_option('co2ok_button_template', 'co2ok_button_template_default'),
            array('cart' => $cart,
                    'co2ok_session_opted' =>  $woocommerce->session->co2ok,
                    'currency_symbol' =>get_woocommerce_currency_symbol(),
                    'surcharge' => $surcharge,
                    'co2ok_gif_feature' => get_option('co2ok_gif_feature')
                )
            );



        }

    }

endif;
