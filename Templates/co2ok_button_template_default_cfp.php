<div class="co2ok_container co2ok_container_default cfp-selected" data-cart="<?php echo $cart ?>">

    <span class="co2ok_checkbox_container co2ok_checkbox_container_default cfp-selected>">
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
				<div class="inner_checkbox_label inner_checkbox_label_default co2ok_global_temp" style="text-align: center; background: linear-gradient(#1DEFAC -50.09%, #11D071 51.05%, #10CC6B 56.81%, #05B139 100%)" id="default_co2ok_temp">
                    <span class="make make_co2ok_default" style="color: white; text-align:center; margin-bottom: -2px; font-size: 18px;"><?php echo __( 'This shop is', 'co2ok-for-woocommerce' ); ?></span>
					<div style="margin-bottom: -2px;">
						<?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo_wit.svg', 'co2ok_logo', 'co2ok_logo_default', 'co2ok_logo', 'skip-lazy'); ?>
					</div>
				</div>
            </a>
        </div>
    </span>


    <span class="co2ok_payoff">
        <span class="co2ok_payoff_text co2ok_adaptive_color_default">
                <span>
                    <?php
                        echo  __( 'Make my purchase climate friendly', 'co2ok-for-woocommerce' );
                        ?>
                </span>
                <span>
                    <?php

                    $variables = array(
                        '{COMPENSATION_COUNT}' => $compensation_count,
                        '{IMPACT}' => $impact_total);
                    echo strtr( __('{COMPENSATION_COUNT}x compensated; {IMPACT}t CO&#8322 reduction', 'co2ok-for-woocommerce' ), $variables);
                    ?>
                </span>
                <span>
                    <?php

                    $variables = array(
                        '{KM}' => $impact_total * 5000);
                    echo strtr( __('This is equivalent to {KM} km of flying ✈️', 'co2ok-for-woocommerce' ), $variables);
                    ?>
              </span>
        </span>
        <a href="#!" input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea" style="outline: none; -webkit-appearance: none;">
            <span id="p">
                <span class="co2ok_info_hitarea">
                    <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
                </span>
            </span>
        </a>

		<div class="co2ok_infobox_container co2ok-popper" id="infobox-view">

            <div class="cfp-wrapper cfp-hovercard">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/factory.png', 'info-hover-png', 'cfp-png-left cfp-hovercard', 'a3-notlazy', 'style="top: 10px;"'); ?>
                <p class="cfp-steps cfp-step-one cfp-right cfp-hovercard" style="padding-top: 8px !important;">
                    <?php echo __('Every product has a climate impact through transport and production',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <div class="cfp-road cfp-hovercard">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/gray_road.png', 'info-hover-road-png', 'cfp-road-png cfp-top-road cfp-hovercard', 'a3-notlazy'); ?>
            </div>

            <div class="cfp-wrapper cfp-hovercard">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_truck.png', 'info-hover-png', 'cfp-png-right cfp-hovercard', 'a3-notlazy'); ?>
                <p class="cfp-steps cfp-step-two cfp-left cfp-hovercard">
                    <?php echo __('This webshop neutralizes these by financing projects that prevent the same amount of impact',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <div class="cfp-road cfp-hovercard">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_road_right.png', 'info-hover-road-png', 'cfp-bottom-road cfp-road-png cfp-hovercard', 'a3-notlazy'); ?>
            </div>

            <div class="cfp-wrapper cfp-hovercard">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/renewable_energy.png', 'info-hover-png', 'cfp-png-left cfp-png-renewable cfp-hovercard', 'a3-notlazy'); ?>
                <p class="cfp-steps cfp-step-three cfp-right cfp-hovercard" style="padding-bottom: 8px !important;">
                    <?php echo __('That means you can shop guilt-free and together we help the climate 💚',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'logo-hovercard', 'cfp-logo-hovercard cfp-hovercard', 'a3-notlazy'); ?></a>

            <span class="cfp-button-hovercard-links cfp-hovercard">
                <a class="cfp-co2ok-button cfp-hovercard" href="http://www.co2ok.eco/co2-compensatie"><?php
                    echo  __( 'How do we do this', 'co2ok-for-woocommerce' );
                ?></a>
            </span>
            <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/branch.png', 'branch-png', 'cfp-branch-png cfp-hovercard', 'a3-notlazy'); ?>

        </div>

    </span>
</div>
