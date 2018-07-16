<div class="co2ok_container co2ok_container_minimal"data-cart="<?php echo $cart ?>">

    <span class="co2ok_checkbox_container co2ok_checkbox_container_minimal <?php echo ($co2_ok_session_opted == 1 || $co2ok_optin == 'on' ? 'selected' : 'unselected' )?>">
        <?php
            woocommerce_form_field('co2ok_cart', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2_ok_session_opted);
        ?>


          <div class="inner_checkbox_label inner_checkbox_label_minimal">
            <div id="checkbox">  
            </div>

              <span class="make_minimal co2ok_adaptive_color_default"><?php echo __( 'Make ', 'co2ok-for-woocommerce' ); ?></span>
              <?php 
                    // Replaced co2ok_logo with co2ok_logo_minimal to keep the same logo, rather than switching between a white and default logo.
                  echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo', 'co2ok_logo_minimal');
              ?>
              <div class="comp_amount_label_minimal"> <!-- Creates Outer Border for Compensation Amount label -->
                  <div class="inner_comp_amount_label_minimal"> <!-- Creates inner shape for Compensation Amount label -->
                    <span class="compensation_amount_minimal">+<?php echo $currency_symbol.''. $surcharge ?> </span>
                </div>
              </div>


              <span class="co2ok_payoff_minimal">
                    <span id="p_minimal">
                      <span class="co2ok_info_hitarea">
                        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
                      </span>
                    </span>
                </span>
                
          </div>
            <span class="co2ok_payoff_sentence_minimal co2ok_adaptive_color_default">
              <?php
                  echo  __( 'Make my purchase climate neutral', 'co2ok-for-woocommerce' );
              ?>
            </span>


    </span>

    <div class="co2ok_infobox_container co2ok-popper" id="infobox-view">

        <div class="inner-wrapper">
          <p class="text-block greyBorder"><?php echo __('During manufacturing and shipping of products, greenhouse gases are emitted',  'co2ok-for-woocommerce' );?></p>
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/fout.svg', 'svg-img', '  co2ok_info_hover_image'); ?>
        </div>

        <div class="inner-wrapper">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/even.svg', 'svg-img', '  co2ok_info_hover_image'); ?>
          <p class="text-block greyBorder"><?php echo __('We make sure the same amount of emissions is prevented',  'co2ok-for-woocommerce' );?></p>
        </div>

        <div class="inner-wrapper">
          <p class="text-block"><?php echo __('This way, your purchase is climate neutral!',  'co2ok-for-woocommerce' );?></p>
        </div>

        <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo hover-link', 'co2ok_logo_minimal_info'); ?></a>
        <span class="hover-link">
          <a  class="hover-link" target="_blank" href="http://www.co2ok.eco/co2-compensatie"><?php
            echo  __( 'How CO&#8322; compensation works', 'co2ok-for-woocommerce' );
            ?></a> </span>
    </div>


</div>
