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
                        <p>If you do, please leave us a <a href=https://wordpress.org/support/view/plugin-reviews/co2ok-for-woocommerce?rate=5#new-post>5★ rating on WordPress.org</a>. It would be a great help to us.</p>
                        <p>We need everybody on our team. So follow us on social media, share our posts, spread the love!</p>
                        <h2>Follow us on:</h2>
                        <p><a href="https://www.instagram.com/co2ok.eco/" target="_blank"><span>Instagram</span></a></p>
                        <p><a href="https://www.facebook.com/CO2ok/" target="_blank"><span>Facebook</span></a></p>
                        <p><a href="https://twitter.com/CO2ok_eco" target="_blank" ><span>Twitter</span></a></p> 

                        <h2>A/B research</h2>
                        <p>Some webshops are not as forward thinking as yours. To convince them to participate in the fight, we need to show them CO2ok doesn't impact conversion (or rather - that it helps!). Help us show them and get them on board!</p>

                        <p>This will show the CO2ok option (and widget when present) to 50% of your customers, and report the difference in conversion.</p>
                        
                        <form method="POST">
                        
                            <input type="radio" name="co2ok_ab_research" id="on" value="on" <?php if($co2ok_ab_research == 'on') echo "checked" ?> >
                            <label style="display: inline" for="on">A/B research enabled</label>
                            <br>
                            <input type="radio" name="co2ok_ab_research" id="off" value="off" <?php if($co2ok_ab_research == 'off') echo "checked" ?> >
                            <label style="display: inline" for="off">A/B research disabled</label>
                            
                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>
                        </form>

                        <h2>Widget-mark</h2>
                        <p>Not all shops contribute to the fight against climate change like you. Wear it like a badge of honor!</p>
                        <img src="<?php echo esc_url(plugins_url('../../../images/widgetmark.png', __FILE__));?>" 
                                style="vertical-align: middle"/>
                        
                        <form method="POST">
                        
                            <input type="radio" name="co2ok_widgetmark_footer" id="on" value="on" <?php if($co2ok_widgetmark_footer == 'on') echo "checked" ?> >
                            <label style="display: inline" for="on">Show widget in footer</label>
                            <br>
                            <input type="radio" name="co2ok_widgetmark_footer" id="off" value="off" <?php if($co2ok_widgetmark_footer == 'off') echo "checked" ?> >
                            <label style="display: inline" for="off">Use the WP shortcode [co2ok_widgetmark]</label>
                            
                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>
                        </form>

                        <h2>GIF feature</h2>
                        <p>We believe in putting smiles on customers faces - a happy customer is a returning one. One of the ways we try to put smiles on peoples faces is our GIF feature - it shows a fun GIF like the one above to customers if they choose CO2 compensation. Of course there are differing opinions on this - use the below setting to disable this feature.</p>
                        
                        <form method="POST">
                        
                            <input type="radio" name="co2ok_gif_feature" id="on" value="on" <?php if($co2ok_gif_feature == 'on') echo "checked" ?> >
                            <label style="display: inline" for="on">GIFs ON. (Preferred)</label>
                            <br>
                            <input type="radio" name="co2ok_gif_feature" id="off" value="off" <?php if($co2ok_gif_feature == 'off') echo "checked" ?> >
                            <label style="display: inline" for="off">GIFs OFF.</label>
                            
                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>
                        </form>

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
                            <label style="display: inline-block; vertical-align: middle;" for="button_style_radio_default">
                                <img src="<?php echo esc_url(plugins_url('../../../images/button_default_co2ok.png', __FILE__));?>" 
                                style="vertical-align: middle; width: 210px;"/>
                            </label>
                            <br>
                            
                            <!-- Radiobutton for Minimal Button Design -->
                            <input type="radio" name="co2ok_button_template" id="button_style_radio_minimal" value="co2ok_button_template_minimal" 
                            <?php if($co2ok_button_template == 'co2ok_button_template_minimal') echo "checked"; ?> >
                            <label style="display: inline-block; vertical-align: middle;" for="button_style_radio_minimal">
                                <img src="<?php echo esc_url(plugins_url('../../../images/button_minimal_co2ok.png', __FILE__));?>" 
                                style="vertical-align: middle; width: 200px;"/>
                            </label>

                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>

                        </form>
                    
                        <h2>Button Placement:</h2>
                        <p>By default we automatically place the button on the Checkout page. But you can also decide to place the button on specific locations on your Checkout webpage: </p>
                        
                        <form method="POST">

                            <select name="co2ok_checkout_placement" style="display:block;" id="co2ok_checkout_placement">
                                <option value="after_order_notes" <?php if($co2ok_checkout_placement == 'after_order_notes') echo "selected" ?>>After Order Notes</option>
                                <option value="before_checkout_form" <?php if($co2ok_checkout_placement == 'before_checkout_form') echo "selected" ?>>Before the Checkout Form</option>
                                <option value="checkout_before_customer_details" <?php if($co2ok_checkout_placement == 'checkout_before_customer_details') echo "selected" ?>>Before Customer Details</option>
                                <option value="after_checkout_billing_form" <?php if($co2ok_checkout_placement == 'after_checkout_billing_form') echo "selected" ?>>After Billing Details Form</option>
                                <!-- Disabled since it breaks click functionality <option value="review_order_before_submit" <?php if($co2ok_checkout_placement == 'review_order_before_submit') echo "selected" ?>>Before "Place Order"</option> -->
                                <option value="checkout_order_review" <?php if($co2ok_checkout_placement == 'checkout_order_review') echo "selected" ?>>Before Order Review (Recommended)</option>
                            </select>

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
 