
/*! products filter */

$(document).ready(function() {

    ProductsFilter.init();

});

function ProductsFilter(){}

////////////////////////////////////////////////////////////////////////////////

ProductsFilter.init   = function() {

    var nav_div  = $("#nav-side");
    var main_div = $("#main-side");
    
    var options = { "featured":0, "offer":0, "sale":0, "recent":0 };

    var options_div = nav_div.find("#product-options");
    
    if( options_div.length <= 0 ) return;
    

    ProductsFilter.nav_div  = nav_div;
    ProductsFilter.main_div = main_div;

    ProductsFilter.options  = options;
    
    
    options_div.find(".option").removeClass('selected');
    
    options_div.find(".option").each(function(i) {

        var option_div = $(this);

        option_div.css("cursor", "pointer");

        option_div.click(function() {
            
            var options = ProductsFilter.options;
            
            var filter = option_div.data("filter");

            if( option_div.hasClass('selected') ){
                
                option_div.removeClass('selected');

                options[filter] = 0;
                
            }else{
                
                option_div.addClass('selected');
                
                options[filter] = 1;
                
            }
            
            ProductsFilter.options  = options;
            
            ProductsFilter.filter();
            
        });

    });
};

ProductsFilter.filter = function (){

    var main_div = ProductsFilter.main_div;
    var options  = ProductsFilter.options;

    main_div.find("#pagination").hide();
    main_div.find("#results"   ).hide();

//    main_div.find("#list").find(".list-item-product").filter(
//            '[data-featured=' + options.featured + ']' +
//            '[data-offer='    + options.offer    + ']' +
//            '[data-sale='     + options.sale     + ']' +
//            '[data-recent='   + options.recent   + ']'
//        ).show();

    var list_items = main_div.find("#list").find(".list-item-product");

    list_items.hide();

    var filter = '';

    if( options.featured > 0 ){
        filter += '[data-featured=1]';
    }
    
    if( options.offer > 0 ){
        filter += '[data-offer=1]';
    }
    
    if( options.sale > 0 ){
        filter += '[data-sale=1]';
    }
    
    if( options.recent > 0 ){
        filter += '[data-recent=1]';
    }

    list_items.filter( filter ).show();

    if( options.featured <= 0 && options.offer <= 0 && options.sale <= 0 && options.recent <= 0 ){
       list_items.show(); 
    }

    /*alert(
            '[data-featured=' + options.featured + ']' +
            '[data-offer='    + options.offer    + ']' +
            '[data-sale='     + options.sale     + ']' +
            '[data-recent='   + options.recent   + ']'
        );*/
};

////////////////////////////////////////////////////////////////////////////////
