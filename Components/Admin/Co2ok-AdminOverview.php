<?php
/**
 * Created by PhpStorm.
 * User: Chris-Home
 * Date: 11/20/2017
 * Time: 19:33
 */

namespace co2ok_plugin_woocommerce\Components\Admin;

class Co2ok_AdminOverview
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'co2ok_plugin_setup_menu'));


        //add_action('admin_enqueue_scripts', array($this, 'co2ok_admin_style'),100000000);
        //add_action('admin_enqueue_scripts', array($this, 'co2ok_admin_javascript'),100000000);

    }

    function co2ok_admin_style($hook)
    {
        if($hook != 'co2ok-plugin') {
          //  return;
        }
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('admin-style.css', __FILE__) );

        wp_register_style('co2ok_stylesheet', plugins_url('../../css/co2ok.css', __FILE__) );
        wp_enqueue_style('co2ok_stylesheet');



        wp_enqueue_style( 'co2ok-google-fonts', 'http://fonts.googleapis.com/css?family=Roboto:300,400', false );
    }

    function co2ok_admin_javascript()
    {
        wp_register_script('co2ok_js_wp', plugins_url('../../js/co2ok-plugin.js', __FILE__) );
        wp_enqueue_script('co2ok_js_wp');
    }

    function co2ok_plugin_setup_menu()
    {
        add_menu_page( 'Co2ok Plugin Page', 'CO&#8322;ok Plugin', 'manage_options', 'co2ok-plugin', array($this, 'co2ok_plugin_admin_overview'));
    }

    function co2ok_plugin_admin_overview()
    {
        // Receives Post from Plugin-Settings in the browser and updates 
        // the state of the co2ok button style to WP database
        if (isset($_POST['co2ok_button_template'])) {
            update_option('co2ok_button_template', $_POST['co2ok_button_template']);
        }

        if (isset($_POST['co2ok_statistics']))
        {
            update_option('co2ok_statistics', 'on');
        }

        if (isset($_POST['co2ok_optout']))
        {
            update_option('co2ok_optout', $_POST['co2ok_optout']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if (!isset($_POST['co2ok_statistics']))
            {
                $_POST['co2ok_statistics'] = 'off';
                update_option('co2ok_statistics', 'off');
            }

            if (!isset($_POST['co2ok_optout']))
            {
                $_POST['co2ok_optout'] = 'off';
                update_option('co2ok_optout', 'off');
            }

            $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(\co2ok_plugin_woocommerce\Co2ok_Plugin::$co2okApiUrl);

            $merchantId = get_option('co2ok_id', false);
            $co2ok_statistics = get_option('co2ok_statistics', 'off');
            $co2ok_optout = get_option('co2ok_optout', 'off');

            $graphQLClient->mutation(function ($mutation) use ($merchantId, $co2ok_statistics, $co2ok_optout)
            {
                $mutation->setFunctionName('updateMerchant');

                $mutation->setFunctionParams(
                    array(
                        'merchantId' => $merchantId,
                        'sendStats' => $co2ok_statistics,
                        'optout' => $co2ok_optout
                    )
                );
                $mutation->setFunctionReturnTypes(array('ok'));
            }
                , function ($response)// Callback after request
                {
                    // echo print_r($response,1);
                    // TODO error handling
                });
        }

        $co2ok_button_template = get_option('co2ok_button_template', 'co2ok_button_template_default');
        $co2ok_statistics = get_option('co2ok_statistics', 'off');
        $co2ok_optout = get_option('co2ok_optout', 'off');
      
        include_once plugin_dir_path(__FILE__).'views/default.php';
    }

}
$admin = new Co2ok_AdminOverview();