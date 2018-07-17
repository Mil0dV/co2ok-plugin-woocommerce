<?php

        add_action( 'admin_post_co2ok_save_options', 'co2ok_save_options' );
        
        function co2ok_save_options() {
            echo('Hello World');
            print_r($_POST);
        }

?>

<div style="margin-top: 20px;">

    <img src="<?php echo esc_url(plugins_url('../../../images/logo.svg', __FILE__)); ?>" style="float:left;width:110px;"/>
    <h1 style="margin-left: 20px;display: inline-block;"> Plugin Settings </h1>
</br>
</br>

    <div id="col-container">
        
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3>
                        <h1>Thanks for helping us fight climate change! :)</h1>
                        <img src="<?php echo esc_url(plugins_url('../../../images/happy-flower300.gif', __FILE__)); ?>"/>
                        <p>You are our hero. We strongly believe that no fight has been more important, and this needs
                            to be fought in any way possible. And it's not only the climate that benefits;</p>
                            <img src="<?php echo esc_url(plugins_url('../../../images/Lesotho-cookstoves.jpg', __FILE__)); ?>" width=300px/>
                            <p><small>Additional benefits are less deforestation and health benefits, due to decreasing 
                                smoke and poisonous carbon monoxide.</small>
                                
                        <h2>Want to help us some more?</h2>
                        <p>If you do, please leave us a <a href=https://wordpress.org/support/view/plugin-reviews/co2ok-for-woocommerce?rate=5#new-post>5★ rating on WordPress.org</a>. It would be of great help to us.</p>
                        <p>We need everybody on our team. So follow us on social media, share our posts, spread the love!</p>
                        <h2>Like us on:</h2>
                        <p><a href="https://www.instagram.com/co2ok.eco/" target="_blank"><span>Instagram</span></a></p>
                        <p><a href="https://www.facebook.com/CO2ok/" target="_blank"><span>Facebook</span></a></p>
                        <p><a href="https://twitter.com/CO2ok_eco" target="_blank" ><span>Twitter</span></a></p>

                        <h2>Coming soon! </h2>
                        <p>In our next update we will give you the choice to set the default state of the compensation 
                            option. This feature is almost done, but we won't ship it until it's extremely well tested and polished. Let us know if you have other
                            ideas how we can improve our service/plugin!</p>     

                        <h2>Compensation preferences:</h2>
                        <p>By default we have set the button to OFF. But you can decide to set the CO2 OK button to default ON. This way you are in control,
                        helping the environment even more!</p>
                        
                        <form method="POST">
                        
                            <input type="radio" name="co2ok_optout" id="on" value="on" <?php if($co2ok_optout == 'on') echo "checked" ?> >
                            <label style="display: inline" for="on">Compensation default ON. (Preferred)</label>
                            <br>
                            <input type="radio" name="co2ok_optout" id="off" value="off" <?php if($co2ok_optout == 'off') echo "checked" ?> >
                            <label style="display: inline" for="off">Compensation default OFF.</label>
                            
                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>
                        </form>

                        <h2>Choose Button Style:</h2>
                        <p>We've done our best to create an optimised and fetching design for our button - but we've also created a minimal design, for the minimalists :)</p>
                        
                        <form method="POST">
                        
                            <!-- Radiobutton for Default Button Design -->
                            <input type="radio" name="co2ok_button_template" id="button_style_radio_default" value="co2ok_button_template_default"
                            <?php if($co2ok_button_template == 'co2ok_button_template_default') echo "checked"; ?> >
                            <label style="display: inline-block; height: 100%; vertical-align: middle;" for="button_style_radio_default">
                                <img src="<?php echo esc_url(plugins_url('../../../images/button_default_co2ok.png', __FILE__));?>" 
                                style="vertical-align: middle; width: 210px;"/>
                            </label>
                            <br>
                            
                            <!-- Radiobutton for Minimal Button Design -->
                            <input type="radio" name="co2ok_button_template" id="button_style_radio_minimal" value="co2ok_button_template_minimal" 
                            <?php if($co2ok_button_template == 'co2ok_button_template_minimal') echo "checked"; ?> >
                            <label style="display: inline-block; height: 100%; vertical-align: middle;" for="button_style_radio_minimal">
                                <img src="<?php echo esc_url(plugins_url('../../../images/button_minimal_co2ok.png', __FILE__));?>" 
                                style="vertical-align: middle; width: 200px;"/>
                            </label>

                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>

                        </form>
                    

                        <h2>Something not working for you? Have a great idea or any other feedback? </h2>
                        <p>Call/text/WhatsApp us: <a href="tel:+31639765259">+31639765259</a></p>
                        <p>Drop us a line: <a href="mailto: make@co2ok.eco"><span>make@co2ok.eco</span></a></p>
                        <br>
                        <p>Thanks,<br>The CO&#8322;ok team.</p>
                        <p><a href="http://www.co2ok.eco" target="_blank">www.co2ok.eco</a></p>
                        <br>
                        <hr>

                    </h3>
                </div>
            </div>
        </div>
    </div>
 