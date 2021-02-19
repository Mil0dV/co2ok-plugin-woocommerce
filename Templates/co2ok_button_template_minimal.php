<div class="co2ok_container co2ok_container_minimal"data-cart="<?php echo $cart ?>">

    <span class="co2ok_checkbox_container co2ok_checkbox_container_minimal <?php echo ($co2ok_session_opted == 1 ? 'selected' : 'unselected' )?>">
        <?php
            woocommerce_form_field('co2ok_cart', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2ok_session_opted);
        ?>

        <a href="#!" input type="button" role="button" tabindex="0" style="outline: none; -webkit-appearance: none;" class="co2ok_nolink">
          <div class="inner_checkbox_label inner_checkbox_label_minimal co2ok_global_temp" id="minimal_co2ok_temp" input type="button" role="button" tabindex="0" style="outline: none; -webkit-appearance: none;">
            <div id="checkbox">
            </div>

              <span class="make_co2ok_minimal co2ok_adaptive_color_default make_co2ok_global"><?php echo __( 'Make ', 'co2ok-for-woocommerce' ); ?></span>
              <?php
                    // Replaced co2ok_logo with co2ok_logo_minimal to keep the same logo, rather than switching between a white and default logo.
                  echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo', 'co2ok_logo_minimal', 'co2ok_logo_minimal', 'skip-lazy');
              ?>
              <div class="comp_amount_label_minimal"> <!-- Creates Outer Border for Compensation Amount label -->
                  <div class="inner_comp_amount_label_minimal"> <!-- Creates inner shape for Compensation Amount label -->
                    <span class="compensation_amount_minimal compensation_amount_global">+<?php echo $currency_symbol.''. $surcharge ?> </span>
                </div>
              </div>

              <span class="co2ok_payoff_minimal">
                <span input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea co2ok_nolink" style="outline: none; -webkit-appearance: none; ">
                    <span id="p_minimal">

                      <span class="co2ok_info_hitarea">
                        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
                      </span>
                    </span>
               </span>
              </span>

          </div>
          </a>

          <span class="co2ok_payoff_sentence_minimal">
            <span class="co2ok_payoff_text  co2ok_adaptive_color_default">
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
                echo strtr( __('{COMPENSATION_COUNT}x compensated; {IMPACT}t CO₂ reduction', 'co2ok-for-woocommerce' ), $variables); 
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
          </span>

    </span>

    
    <div class="co2ok_infobox_container co2ok-popper default-info-hovercard" id="infobox-view">

      <div class="default-wrapper default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/factory.png', 'default-png', 'default-png default-png-right default-info-hovercard', 'a3-notlazy'); ?>
          <p class="default-steps step-one default-left default-info-hovercard" style="padding-top: 18px !important;">
              <?php echo __('Every product has a climate impact through transport and production',  'co2ok-for-woocommerce' );?>
          </p>
      </div>

      <div class="hovercard-road default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/gray_road.png', 'default-road-png', 'default-road-png default-top-road default-info-hovercard', 'a3-notlazy'); ?>
      </div>

      <div class="default-wrapper default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_truck.png', 'default-png', 'default-png-left default-png default-png-truck default-info-hovercard', 'a3-notlazy'); ?>
          <p class="default-steps step-two default-right default-info-hovercard">
              <?php echo __('This webshop neutralizes emissions from transport',  'co2ok-for-woocommerce' );?>
          </p>
      </div>

      <div class="hovercard-road default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_road_left.png', 'default-road-png', 'default-road-png default-middle-road default-info-hovercard', 'a3-notlazy'); ?>

      </div>

      <div class="default-wrapper default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/checkmark.png', 'default-png', 'default-png-button default-png default-info-hovercard', 'a3-notlazy'); ?>
          <p class="default-steps step-three default-left default-info-hovercard">
              <?php echo __('You can neutralize the impact of production with your contribution',  'co2ok-for-woocommerce' );?>
          </p>
      </div>

      <div class="hovercard-road default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_road_right.png', 'default-road-png', 'default-bottom-road default-road-png default-info-hovercard', 'a3-notlazy'); ?>
      </div>

      <div class="default-wrapper default-info-hovercard">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/renewable_energy.png', 'default-png', 'default-png default-png-left default-png-renewable default-info-hovercard', 'a3-notlazy'); ?>
          <p class="default-steps step-four default-right default-info-hovercard" style=" margin-left: 96px !important;">
              <?php echo __('We finance projects that directly prevent emissions and together we help the climate 💚',  'co2ok-for-woocommerce' );?>
          </p>
      </div>

      <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'default-logo-hovercard', 'default-logo-hovercard default-info-hovercard', 'a3-notlazy'); ?></a>

      <span class="default-button-hovercard-links default-info-hovercard">
          <a class="default-co2ok-button default-info-hovercard" target="_blank" href="http://www.co2ok.eco/co2-compensatie"><?php
              echo  __( 'How do we do this', 'co2ok-for-woocommerce' );
          ?></a>
      </span>
      <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/branch.png', 'default-branch-png', 'default-branch-png default-info-hovercard', 'a3-notlazy'); ?>

    </div>

    <?php if ( $co2ok_gif_feature == 'on' ): ?>
    <div class="co2ok_videoRewardBox_container" id="videoRewardBox-view">

        <video width="320" height="240" autoplay id="co2ok_videoReward" playsinline>
        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderRandomizedVideo(); ?>
          Your browser does not support the video tag.
        </video>

    </div>
    <?php endif;?>

</div>
