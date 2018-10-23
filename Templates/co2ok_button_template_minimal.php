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
          <div class="inner_checkbox_label inner_checkbox_label_minimal global" id="minimalTemp" input type="button" role="button" tabindex="0" style="outline: none; -webkit-appearance: none;">
            <div id="checkbox">
            </div>

              <span class="make_minimal co2ok_adaptive_color_default make_global"><?php echo __( 'Make ', 'co2ok-for-woocommerce' ); ?></span>
              <?php
                    // Replaced co2ok_logo with co2ok_logo_minimal to keep the same logo, rather than switching between a white and default logo.
                  echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo', 'co2ok_logo_minimal', 'co2ok_logo_minimal');
              ?>
              <div class="comp_amount_label_minimal"> <!-- Creates Outer Border for Compensation Amount label -->
                  <div class="inner_comp_amount_label_minimal"> <!-- Creates inner shape for Compensation Amount label -->
                    <span class="compensation_amount_minimal compensation_amount_global">+<?php echo $currency_symbol.''. $surcharge ?> </span>
                </div>
              </div>

              <span class="co2ok_payoff_minimal">
                <span input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea co2ok_nolink" style="outline: none; -webkit-appearance: none;">
                    <span id="p_minimal">

                      <span class="co2ok_info_hitarea">
                        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
                      </span>
                    </span>
               </span>
              </span>

          </div>
          </a>
            <span class="co2ok_payoff_sentence_minimal co2ok_adaptive_color_default">
              <?php
                  echo  __( 'Make my purchase climate neutral', 'co2ok-for-woocommerce' );
              ?>
            </span>


    </span>

    <div class="co2ok_infobox_container co2ok-popper" id="infobox-view">

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text first-text-to-select" style="outline: none; -webkit-appearance: none;">
          <p class="text-block greyBorder"><?php echo __('During manufacturing and shipping of products, greenhouse gases are emitted',  'co2ok-for-woocommerce' );?></p>
        </a>
        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/fout.svg', 'svg-img', '  co2ok_info_hover_image'); ?>
        </div>

        <div class="inner-wrapper">
          <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/even.svg', 'svg-img-large', '  co2ok_info_hover_image'); ?>
          <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text">
          <p class="text-block greyBorder"><?php echo __('We prevent the same amount of emissions',  'co2ok-for-woocommerce' );?></p>
          </a>
        </div>

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text" style="outline: none; -webkit-appearance: none;">
          <p class="text-block"><?php echo __('This way, your purchase is climate neutral!',  'co2ok-for-woocommerce' );?></p>
          </a>
        </div>

        <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo hover-link', 'co2ok_logo_minimal_info'); ?></a>
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


</div>

<script type="text/javascript">

   //cad = compensation_amount_minimun
   var cad_minimal = document.querySelector('.compensation_amount_minimal');
   var make_minimal = document.querySelector('.make_minimal');
   var co2ok_logo_minimal = document.querySelector('.co2ok_logo_minimal');
   var global = document.querySelector('.global');


   function minimunButton()
   {

     var qty = document.querySelector('.qty');
     var qtyVal = qty.value.length;

     if(qtyVal > 1)
     {

        cad_minimal.style.fontSize = 15 - qtyVal+'px';
       // cad_minimal.style.marginTop = 9 + qtyVal+'px';
        make_minimal.style.fontSize = 18 - qtyVal+'px';
        co2ok_logo_minimal.style.width = 52 - qtyVal+'px';

     }else{

       cad_minimal.style.fontSize = '18px';
     //  cad_minimal.style.marginTop = '12px';
       make_minimal.style.fontSize = '21px';
       co2ok_logo_minimal.style.width = '55px';

     }

   }

   if(document.querySelector('.qty') != null){

      minimunButton();

   }


</script>
