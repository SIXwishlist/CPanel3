
/*! products filter */

var g_options  = { "featured":0, "offer":0, "sale":0, "recent":0 };

var g_nav_div  = null;
var g_main_div = null;

$(document).ready(function() {

    g_nav_div  = $("#NavSide");
    g_main_div = $("#MainSide");

    init_products_filter();

});

////////////////////////////////////////////////////////////////////////////////

function init_products_filter() {

    var options_div = g_nav_div.find("#product-options");
    
    options_div.find(".option").removeClass('selected');
    
    options_div.find(".option").each(function(i) {

        var option_div = $(this);

        option_div.css("cursor", "pointer");

        option_div.click(function() {
            
            var filter = option_div.data("filter");

            if( option_div.hasClass('selected') ){
                
                option_div.removeClass('selected');

                g_options[filter] = 0;
                
            }else{
                
                option_div.addClass('selected');
                
                g_options[filter] = 1;
                
            }
            
            filter_products();
            
        });

    });
}

function filter_products() {

    g_main_div.find("#pagination").hide();
    g_main_div.find("#results"   ).hide();

//    g_main_div.find("#list").find(".list-item-s1").filter(
//            '[data-featured=' + g_options.featured + ']' +
//            '[data-offer='    + g_options.offer    + ']' +
//            '[data-sale='     + g_options.sale     + ']' +
//            '[data-recent='   + g_options.recent   + ']'
//        ).show();

    var list_items = g_main_div.find("#list").find(".list-item-s1");

    list_items.hide();

    if( g_options.featured > 0 ){
        list_items.filter( '[data-featured=1]' ).show();
    }
    
    if( g_options.offer > 0 ){
        list_items.filter( '[data-offer=1]'    ).show();
    }
    
    if( g_options.sale > 0 ){
        list_items.filter( '[data-sale=1]'     ).show();
    }
    
    if( g_options.recent > 0 ){
        list_items.filter( '[data-recent=1]'   ).show();
    }

    if( g_options.featured <= 0 && g_options.offer <= 0 && g_options.sale <= 0 && g_options.recent <= 0 ){
       list_items.show(); 
    }

    /*alert(
            '[data-featured=' + g_options.featured + ']' +
            '[data-offer='    + g_options.offer    + ']' +
            '[data-sale='     + g_options.sale     + ']' +
            '[data-recent='   + g_options.recent   + ']'
        );*/
}
////////////////////////////////////////////////////////////////////////////////
