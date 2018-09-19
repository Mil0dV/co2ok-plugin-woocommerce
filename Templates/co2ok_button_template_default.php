<div class="co2ok_container co2ok_container_default" data-cart="<?php echo $cart ?>">

    <span class="co2ok_checkbox_container co2ok_checkbox_container_default <?php echo ($co2ok_session_opted == 1 ? 'selected' : 'unselected' )?>">
        <?php
            woocommerce_form_field('co2ok_cart', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2ok_session_opted);
        ?>

        <div id="checkbox_label">
            <a href="#!" input type="button" role="button" tabindex="0" style="outline: none; -webkit-appearance: none;">
                <div class="inner_checkbox_label inner_checkbox_label_default">
                    <div id="checkbox">  
                    </div>

                    <span class="make"><?php echo __( 'Make ', 'co2ok-for-woocommerce' ); ?> </span>
                    <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo', 'co2ok_logo'); ?>
                    <span class="compensation_amount_default">+<?php echo $currency_symbol.''. $surcharge ?> </span>
                    
                </div>
            </a>
        </div>
    </span>
    


    <span class="co2ok_payoff">
        <span class="co2ok_adaptive_color_default">
        <?php
            echo  __( 'Make my purchase climate neutral', 'co2ok-for-woocommerce' );
            ?>
        </span>
        <a href="#!" input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea" style="outline: none; -webkit-appearance: none;">
        <span id="p">
            <span class="co2ok_info_hitarea">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
            </span>
        </span>
        </a>

        <div class="co2ok_infobox_container co2ok-popper" id="infobox-view">

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text first-text-to-select" style="outline: none; -webkit-appearance: none;">
        <p class="text-block greyBorder"><?php echo __('During manufacturing and shipping of products, greenhouse gases are emitted',  'co2ok-for-woocommerce' );?></p>
        </a>
        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/fout.svg', 'svg-img', '  co2ok_info_hover_image'); ?>
        </div>

        <div class="inner-wrapper">
        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/even.svg', 'svg-img-large', '  co2ok_info_hover_image'); ?>
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text" style="outline: none; -webkit-appearance: none;">
        <p class="text-block greyBorder"><?php echo __('We prevent the same amount of emissions',  'co2ok-for-woocommerce' );?></p>
        </a>
        </div>

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text" style="outline: none; -webkit-appearance: none;">
        <p class="text-block"><?php echo __('This way, your purchase is climate neutral!',  'co2ok-for-woocommerce' );?></p>
        </a>
        </div>

        <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo hover-link', 'co2ok_logo'); ?></a>
        <span class="hover-link">
          <a  class="hover-link" target="_blank" href="http://www.co2ok.eco/co2-compensatie"><?php
            echo  __( 'How CO&#8322; compensation works', 'co2ok-for-woocommerce' );
            ?></a> </span>
        </div>

        
        <div class="co2ok_videoRewardBox_container" id="videoRewardBox-view">

            <video width="320" height="240" autoplay id="co2ok_videoReward">
            <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderRandomizedVideo(); ?>
                Your browser does not support the video tag.
            </video>

        </div>


    </span>


    


</div>
