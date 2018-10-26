var global = document.querySelector('.co2ok_global_temp');


function minimumButton()
{

  var cad_minimal = document.querySelector('.compensation_amount_minimal');
  var make_minimal = document.querySelector('.make_co2ok_global');
  var co2ok_logo_minimal = document.querySelector('.co2ok_logo_minimal');
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

function defaultButton()
{

  var cad = document.querySelector('.compensation_amount_default');
  var make = document.querySelector('.make_co2ok_default');
  var co2ok_logo = document.querySelector('.co2ok_logo_default');

    var qty = document.querySelector('.qty');console.log(qty.value.length);
    var qtyVal = qty.value.length;

    if(qtyVal > 1)
    {

       cad.style.fontSize = 18 - qtyVal+'px';
       cad.style.marginTop = 12 + qtyVal+'px';
       make.style.fontSize = 21 - qtyVal+'px';
       co2ok_logo.style.width = 55 - qtyVal+'px';

    }else{

      cad.style.fontSize = '18px';
      cad.style.marginTop = '12px';
      make.style.fontSize = '21px';
      co2ok_logo.style.width = '55px';

    }


}


if(document.querySelector('.qty') != null && document.querySelector('.compensation_amount_default') != null){

   defaultButton();

}else if(document.querySelector('.qty') != null && document.querySelector('.compensation_amount_default') == null){

   minimumButton();

}


var co2ok_global = {

    IsMobile: function()
    {
        // if one of the Mobile models, IsMobile is true.
        var IsMobile = false;
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
        IsMobile = true;

      return IsMobile;

    },

}

var Co2ok_JS = function ()
{

    var image_url = plugin.url;

    function getBackground(jqueryElement) {
        // Is current element's background color set?
        var color = jqueryElement.css("background-color");

        if (color !== "rgba(0, 0, 0, 0)") {
            // if so then return that color
            return color;
        }

        // if not: are you at the body element?
        if (jqueryElement.is("body")) {
            // return known 'false' value
            return false;
        } else {
            // call getBackground with parent item
            return getBackground(jqueryElement.parent());
        }
    }

    function calcBackgroundBrightness($) {
        var bgColor = getBackground(jQuery("#co2ok_cart")); //Grab the background colour of the element

        var rgb = bgColor.substring(bgColor.indexOf("(") + 1, bgColor.lastIndexOf(")")).split(/,\s*/), // Calculate the brightness of the element
            red = rgb[0],
            green = rgb[1],
            blue = rgb[2],
            brightness = Math.sqrt((0.241 * (red * red)) + (0.671 * (green * green)) + (0.068 * (blue * blue)));

        return brightness;
    }

    function adaptiveTextColor() {
        var isIE = /*@cc_on!@*/false || !!document.documentMode, // Internet Explorer 6-11
        isEdge = !isIE && !!window.StyleMedia; // Edge 20+

        // Check if Internet Explorer 6-11 OR Edge 20+
        if(isIE || isEdge) {
            jQuery( ".co2ok_adaptive_color_default" ).removeClass( "co2ok_adaptive_color" );
        }
        else if (calcBackgroundBrightness() > 185) { //Set the text color based on the brightness
            jQuery( ".co2ok_adaptive_color_default" ).removeClass( "co2ok_adaptive_color" );
        } else {
            jQuery( ".co2ok_adaptive_color_default" ).addClass( "co2ok_adaptive_color" );
        }
    };

    return {

        Init: function ()
        {

            this.RegisterBindings();
            this.RegisterInfoBox();

            var _this = this;


            jQuery(document).ready(function () {
                function compensationAmountTextSize() {

                    //cad = compensation_amount_default
                    _this.GetPercentageFromMiddleware();
                    var pathName = window.location.pathname;
                    var make_minimal = document.querySelector('.make_co2ok_minimal');

                    var cad = document.querySelector('.compensation_amount_default');
                    var make = document.querySelector('.make');
                    var co2ok_logo = document.querySelector('.co2ok_logo_default');

                    //cad = compensation_amount_minimun
                    var cad_minimal = document.querySelector('.compensation_amount_minimal');
                    var co2ok_logo_minimal = document.querySelector('.co2ok_logo_minimal');
                    var compensation_amount_global = document.querySelector('.compensation_amount_global');
                    var comp_amount_label_minimal = document.querySelector('.comp_amount_label_minimal');
                  //  var global = document.querySelector('.global');

                    if(document.querySelector('.qty') != null){

                      var qty = document.querySelector('.qty');
                      var qtyVal = qty.value.length;

                      if(global.id == 'default_co2ok_temp')
                      {

                        defaultButton();

                      }else{

                        minimumButton();

                      }

                    }else{

                       checkoutButton();

                    }


                    function defaultButton()
                    {

                      if(qtyVal > 1)
                      {

                        cad.style.fontSize = 18 - qtyVal+'px';
                        cad.style.marginTop = 12 + qtyVal+'px';
                        make.style.fontSize = 21 - qtyVal+'px';
                        co2ok_logo.style.width = 55 - qtyVal+'px';

                      }else{

                        cad.style.fontSize = '18px';
                        cad.style.marginTop = '12px';
                        make.style.fontSize = '21px';
                        co2ok_logo.style.width = '55px';

                      }

                    }


                    function minimumButton()
                    {

                      var compensation_amount_minimal = document.querySelector('.compensation_amount_minimal');

                      if(qtyVal > 1)
                      {

                         cad_minimal.style.fontSize = 15 - qtyVal+'px';
                        // cad_minimal.style.marginTop = 9 + qtyVal+'px';
                         make_minimal.style.fontSize = 18 - qtyVal+'px';
                         co2ok_logo_minimal.style.width = 52 - qtyVal+'px';
                         comp_amount_label_minimal.style.marginLeft = -(10 + compensation_amount_minimal.textContent.length) - qtyVal +'px';

                      }else{

                        cad_minimal.style.fontSize = '18px';
                      //  cad_minimal.style.marginTop = '12px';
                        make_minimal.style.fontSize = '21px';
                        co2ok_logo_minimal.style.width = '55px';
                        comp_amount_label_minimal.style.marginLeft = '-3px';

                      }

                    }


                    function checkoutButton()
                    {

                      var productQuantity = document.querySelector('.product-quantity');
                      var comp_amount_label_minimal = document.querySelector('.comp_amount_label_minimal');
                      var productQuantityLength = productQuantity.textContent.length - 2;
                      var compensation_amount_minimal = document.querySelector('.compensation_amount_minimal');

                        var compensationAmountLength = compensation_amount_global.textContent.length;
                        if(global.id == 'default_co2ok_temp')
                        {

                            if(productQuantityLength > 1)
                            {

                              cad.style.fontSize = 18 - productQuantityLength +'px';
                              cad.style.marginTop = 12 + productQuantityLength +'px';
                              make.style.fontSize = 21 - productQuantityLength +'px';
                              co2ok_logo.style.width = 55 - productQuantityLength +'px';

                            }else{

                              cad.style.fontSize = '18px';
                              make.style.fontSize = '21px';
                              co2ok_logo.style.width = '55px';

                            }

                        }else{

                            if(compensationAmountLength > 8)
                            {

                              cad_minimal.style.fontSize = 18 - (compensationAmountLength - 3)+'px';
                              make_minimal.style.fontSize = 21 - (compensationAmountLength - 3)+'px';
                              co2ok_logo_minimal.style.width = 55 - (compensationAmountLength - 3)+'px';
                              comp_amount_label_minimal.style.marginLeft = -(10 + compensation_amount_minimal.textContent.length) - compensationAmountLength +'px';

                            }else{

                              cad_minimal.style.fontSize = '18px';
                              make_minimal.style.fontSize = '21px';
                              co2ok_logo_minimal.style.width = '55px';
                              comp_amount_label_minimal.style.marginLeft = '-3px';

                            }

                        }

                    }
                }

                if(jQuery(".co2ok_container").length ) {
                    compensationAmountTextSize();
                }

                jQuery( document.body ).on( 'updated_cart_totals', function(){
                    compensationAmountTextSize();

                });

                _this.GetPercentageFromMiddleware();

            });

            if (!(jQuery('#co2ok_cart').is(":checked"))) {
                jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
            }

            if(jQuery('#co2ok_cart').is(":checked"))
            {
                jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
            }

            if(jQuery("#co2ok_cart").length){ // if the co2ok cart is present, set text and logo based on background brightness
                // adaptiveTextColor();

                // if(calcBackgroundBrightness() > 185){ // picks logo based on background brightness for minimal button design
                    jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo.svg');
                // }
                // else {
                //     jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo_licht.svg');
                // }
            }

        },
        GetPercentageFromMiddleware: function()
        {
            var merchant_id = jQuery('.co2ok_container').attr('data-merchant-id');
            var products = JSON.parse(decodeURIComponent(jQuery('.co2ok_container').attr('data-cart')));

            var CartData = {
                products: []
            }

            jQuery(products).each(function(i)
            {
                var ProductData ={
                    name: products[i].name,
                    brand: products[i].brand,
                    description: products[i].description,
                    shortDescription: products[i].shortDescription,
                    sku: products[i].sku,
                    gtin: products[i].gtin,
                    price: products[i].price,
                    taxClass: products[i].taxClass,
                    weight: products[i].weight,
                    attributes: products[i].attributes,
                    defaultAttributes: products[i].defaultAttributes,
                    quantity: products[i].quantity,
                }
                CartData.products.push(ProductData);
            });

            var promise = CO2ok.getFootprint(merchant_id,CartData);

            promise.then(function(percentage)
            {
                var data = {
                    'action': 'co2ok_ajax_set_percentage',
                    'percentage': percentage
                };
                jQuery.post(ajax_object.ajax_url, data, function(response)
                {
                    if (typeof response.compensation_amount != 'undefined') {
                        jQuery('[class*="compensation_amount"]').html('+'+response.compensation_amount);
                    }
                });
            });

        },

        RegisterBindings: function()
        {

            jQuery('#co2ok_cart').click(function (event)
            {

                 function placeVideoRewardBox() {

                    var infoButton = jQuery(".co2ok_info");
                    var videoRewardBox = jQuery(".co2ok_videoRewardBox_container");
                    var offset = infoButton.offset();

                    videoRewardBox.remove();
                    jQuery("body").append(videoRewardBox);

                    if (jQuery(window).width() < 480) {
                      offset.top = offset.top + infoButton.height();
                      videoRewardBox.css({
                        top: offset.top,
                        margin: "0 auto",
                        left: "50%",
                        transform: "translateX(-50%)"
                      });
                    } else {
                      offset.left = offset.left - videoRewardBox.width() / 2;
                      offset.top = offset.top + infoButton.height();
                      videoRewardBox.css({
                        top: offset.top,
                        left: offset.left,
                        margin: "0",
                        transform: "none"
                      });
                    }
                  }
                  function ShowVideoRewardBox()
                  {
                    jQuery(".co2ok_videoRewardBox_container").removeClass('VideoRewardBox-hidden')
                    jQuery(".co2ok_videoRewardBox_container").addClass('ShowVideoRewardBox')
                    jQuery(".co2ok_videoRewardBox_container").css({
                        marginBottom: 200
                    });

                    jQuery('#co2ok_videoReward').get(0).play();


                    if (co2ok_global.IsMobile() == true ) {
                        var elmnt = document.getElementById("videoRewardBox-view");
                        elmnt.scrollIntoView(false); // false leads to bottom of the infobox

                        jQuery("#co2ok_videoReward").css(
                            "width", "266px",
                            "padding-bottom", "0px"
                        );

                        jQuery(".co2ok_videoRewardBox_container").css(
                            "height", "230px"
                        );
                    }
                  }
                  function hideVideoRewardBox()
                  {
                      jQuery(".co2ok_videoRewardBox_container").removeClass('ShowVideoRewardBox')
                      jQuery(".co2ok_videoRewardBox_container").addClass('VideoRewardBox-hidden')
                      jQuery(".co2ok_videoRewardBox_container").css({
                        marginBottom: 0
                      });
                  }

                if (!(jQuery(this).is(":checked"))) {
                    jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
                    hideVideoRewardBox();
                }

                if(jQuery(this).is(":checked"))
                {
                    jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');


                    placeVideoRewardBox();
                    ShowVideoRewardBox();

                    jQuery('#co2ok_videoReward').on('ended',function(){
                        hideVideoRewardBox();
                    });

                    jQuery('.co2ok_checkbox_container').addClass('selected');
                    jQuery('.co2ok_checkbox_container').removeClass('unselected');
                    jQuery('.woocommerce-cart-form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_cart_hidden" checked value="1" style="display:none">');

                    if (jQuery('#co2ok_checkout_hidden').length === 0) {
                        jQuery('form.woocommerce-checkout, .woocommerce form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="1" style="display:none">');
                    }
                    else {
                        jQuery('#co2ok_checkout_hidden').remove();
                        jQuery('form.woocommerce-checkout, .woocommerce form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="1" style="display:none">');
                    }

                }else {
                    jQuery('.co2ok_checkbox_container').removeClass('selected');
                    jQuery('.co2ok_checkbox_container').addClass('unselected');
                    jQuery('.woocommerce-cart-form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_cart_hidden"  checked value="0" style="display:none">');

                    if (jQuery('#co2ok_checkout_hidden').length === 0) {
                        jQuery('form.woocommerce-checkout, .woocommerce form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="0" style="display:none">');
                    }
                    else {
                        jQuery('#co2ok_checkout_hidden').remove();
                        jQuery('form.woocommerce-checkout, .woocommerce form').append('<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="0" style="display:none">');
                    }

                }


                jQuery('.woocommerce-cart-form, .woocommerce form').find('input.qty').first().unbind();
                jQuery('.woocommerce-cart-form, .woocommerce form').find('input.qty').first().bind('change', function()
                {
                    setTimeout(function()
                    {
                        jQuery("[name='update_cart']").trigger("click");
                    },200);
                });

                setTimeout(function()
                {
                    jQuery('body').trigger('update_checkout');
                    jQuery("[name='update_cart']").trigger("click");
                },200);


                jQuery('.woocommerce-cart-form').find('input.qty').first().trigger("change");
            });

            jQuery('#co2ok_cart, #checkbox_label, .co2ok_checkbox_container').click(function(event)
            {
                if(!jQuery(this).is("#co2ok_cart"))
                {
                    jQuery("[id='co2ok_cart']").trigger("click");
                }
                event.stopPropagation();
            });
        },
        placeInfoBox : function() {
          var infoButton = jQuery(".co2ok_info");
          var infoBox = jQuery(".co2ok_infobox_container");
          var offset = infoButton.offset();

          infoBox.remove();
          jQuery("body").append(infoBox);

          if (jQuery(window).width() < 480) {
            offset.top = offset.top + infoButton.height();
            infoBox.css({
              top: offset.top,
              margin: "0 auto",
              left: "50%",
              transform: "translateX(-50%)"
            });
          } else {
            offset.left = offset.left - infoBox.width() / 2;
            offset.top = offset.top + infoButton.height();
            infoBox.css({
              top: offset.top,
              left: offset.left,
              margin: "0",
              transform: "none"
            });
          }
        },
        ShowInfoBox  : function()
        {
            jQuery(".co2ok_infobox_container").removeClass('infobox-hidden')
            jQuery(".co2ok_infobox_container").addClass('ShowInfoBox')
            jQuery(".co2ok_container").css({
              marginBottom: 200
            });
            if (co2ok_global.IsMobile() == true ) {
                var elmnt = document.getElementById("infobox-view");
                elmnt.scrollIntoView(false); // false leads to bottom of the infobox
            }
        },

        hideInfoBox : function()
        {
            jQuery(".co2ok_infobox_container").removeClass('ShowInfoBox')
            jQuery(".co2ok_infobox_container").addClass('infobox-hidden')
            jQuery(".co2ok_container").css({
              marginBottom: 0
            });
        },




        modalRegex: function(e)
         {
             return jQuery(e.target).hasClass("svg-img") ||
             jQuery(e.target).hasClass("svg-img-large") ||
             jQuery(e.target).hasClass("text-block") ||
             jQuery(e.target).hasClass("inner-wrapper") ||
             jQuery(e.target).hasClass("co2ok_info") ||
             jQuery(e.target).hasClass("co2ok_info_hitarea") ||
             jQuery(e.target).hasClass("co2ok_infobox_container") ||
             jQuery(e.target).hasClass("hover-link");
         },


        RegisterInfoBox : function()
        {

            var _this = this;

            jQuery(".co2ok_info_keyboardarea").focus(function(){
                _this.ShowInfoBox();
                jQuery(".first-text-to-select").focus();
            });

            jQuery('body').click(function(e)
            {
              if(!_this.modalRegex(e))
              {
                _this.hideInfoBox();
              }
              else {
                _this.ShowInfoBox();
              }

            });

            jQuery('body').on("touchstart",function(e){

              if(!_this.modalRegex(e)){
                _this.hideInfoBox();
              }
              else {
                _this.ShowInfoBox();
              }
            });

            if(!co2ok_global.IsMobile())
            {
              jQuery(".co2ok_info , .co2ok_info_hitarea").mouseenter(function() {
                _this.placeInfoBox();
              });

              jQuery(document).mouseover(function(e) {
                  if (!(_this.modalRegex(e)))
                  {
                    _this.hideInfoBox();
                  }
                  else {
                    _this.ShowInfoBox();
                  }
                });
            }
        }
    }
}

jQuery(document).ready(function() {
    if(jQuery("#co2ok_cart").length){
        Co2ok_JS().Init()
    }
})
