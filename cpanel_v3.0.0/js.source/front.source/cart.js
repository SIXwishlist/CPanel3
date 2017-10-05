
/*! cart */

/* global CForm, CDictionary, Validate, UrlUtil, RequestUtil, Utils, g_root_url, CPopup, Globals, USER_NOT_EXIST, ACCOUNT_SUSPENDED, ACCOUNT_BLOCKED, TreeJSON, LoginForm */

$(document).ready(function(){

    try{

        $('body').bind(CDictionary.DICTIONARY_LOADED, CartView.init);

    } catch (err) {
        console.error('Error in : cart - document - ready [' + err + ']');
    }

});

function CartView(){}

CartView.CART_LOADED      = "cart-loaded";

CartView.init             = function(){
        
    try {

        var cart_div = $('body').find('#cart');

        CartView.init_controls();

        CartView.init_cart_page();

        CartView.load();

        CartView.cart_div = cart_div;

    } catch(err) {
        console.error('Error in : CartView - init : [' + err +']');
    }

};

CartView.load             = function(){
        
    try {

        var cart_div = $('body').find('#cart');

        var data = "action=cart";

        RequestUtil.quick_post_request(cart_div, data, function(outputArray){
            
            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var cart = {};

                    cart.cart_items  = outputArray["cart_items"];
                    cart.total_items = outputArray["total_items"];
                    cart.total_price = outputArray["total_price"];

                    Globals.cart = cart;

                    cart_div.find('.cart-items-count').html( cart.total_items );

                }else{

                    Globals.cart = null;

                    cart_div.find('.cart-items-count').html('0');

                }
                
                $('body').trigger(CartView.CART_LOADED, [ cart ]);
                                
            } catch (err) {
                console.error('Error in : CartView - request [' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : CartView - init : [' + err +']');
    }

};

CartView.init_cart_page   = function(){
        
    try {

        var cart_view_div = $('body').find('#cart_view');

        if( cart_view_div.length <= 0 ){ return; }

        CartView.cart_view_div = cart_view_div;

        var proceed_checkout = cart_view_div.find('input[name=proceed_checkout]');

        proceed_checkout.click( CartView.proceed_checkout );

        //$('body').bind(CartView.CART_LOADED, CartView.update_cart_page);

    } catch(err) {
        console.error('Error in : CartView - init controls : [' + err +']');
    }

};

CartView.update_cart_page = function(evt, cart){

    try {


    } catch(err) {
        console.error('Error in : CartView - update cart page : [' + err +']');
    }

};

CartView.proceed_checkout = function(evt){

    try {

        if( Globals.user_id > 0 ){
            window.location = UrlUtil.get_checkout_href();
        }else{
            LoginForm.show_form();
        }

    } catch(err) {
        console.error('Error in : CartView - proceed checkout : [' + err +']');
    }

};

CartView.init_controls    = function(){
        
    try {

        var cart_controls_div = $('body').find('.cart_controls');

        if( cart_controls_div.length <= 0 ){ return; }

        CartView.cart_controls_div = cart_controls_div;

        var add_to_cart      = cart_controls_div.find('input[name=add_to_cart]');
        var update_quantity  = cart_controls_div.find('input[name=update_quantity]');
        var remove_from_cart = cart_controls_div.find('input[name=remove_from_cart]');

        add_to_cart.click(      CartView.add_item    );
        update_quantity.click(  CartView.update_item );
        remove_from_cart.click( CartView.remove_item );

        add_to_cart.hide();
        update_quantity.hide();
        remove_from_cart.hide();

        $('body').bind(CartView.CART_LOADED, CartView.update_controls);

    } catch(err) {
        console.error('Error in : CartView - init controls : [' + err +']');
    }

};

CartView.update_controls  = function(evt, cart){

    try {

        var cart_controls_div = CartView.cart_controls_div;

        var product_id   = cart_controls_div.find('input[name=product_id]').val();

        var product_item = CartView.get_item(product_id);

        var add_to_cart      = cart_controls_div.find('input[name=add_to_cart]');
        var update_quantity  = cart_controls_div.find('input[name=update_quantity]');
        var remove_from_cart = cart_controls_div.find('input[name=remove_from_cart]');

        if( product_item == null ){

            cart_controls_div.find('input[name=quantity]').val( '1' );

            add_to_cart.show();
            update_quantity.hide();
            remove_from_cart.hide();

        }else{

            cart_controls_div.find('input[name=quantity]').val( product_item.quantity );

            add_to_cart.hide();
            update_quantity.show();
            remove_from_cart.show();

        }

    } catch(err) {
        console.error('Error in : CartView - init controls : [' + err +']');
    }

};

CartView.get_item         = function(pid){

    var product_item = null;

    try {

        var cart = Globals.cart;
        
        if( cart == null ) {
            return product_item;
        }

        var cart_items = cart.cart_items;

        for(var i=0; i<cart_items.length; i++){

            if( pid == cart_items[i].pid ){
                product_item = cart_items[i];
            }

        }
        
    } catch(err) {
        console.error('Error in : CartView - get item : [' + err +']');
    }

    return product_item;
};

CartView.add_item         = function(){
        
    try {

        var cart_controls_div = CartView.cart_controls_div;

        var pid      = cart_controls_div.find('input[name=product_id]').val();
        var quantity = cart_controls_div.find('input[name=quantity]').val();
        var price    = cart_controls_div.find('input[name=price]').val();

        var data = {
            "action"   : "add_item",
            "pid"      : pid,
            "quantity" : quantity,
            "price"    : price
        };

        RequestUtil.quick_post_request(cart_controls_div, data, CartView.callback);

    } catch(err) {
        console.error('Error in : CartView - add item : [' + err +']');
    }

};

CartView.update_item      = function(){

    try {

        var cart_controls_div = CartView.cart_controls_div;

        var pid      = cart_controls_div.find('input[name=product_id]').val();
        var quantity = cart_controls_div.find('input[name=quantity]').val();
        var price    = cart_controls_div.find('input[name=price]').val();

        var data = {
            "action"   : "update_item",
            "pid"      : pid,
            "quantity" : quantity,
            "price"    : price
        };

        RequestUtil.quick_post_request(cart_controls_div, data, CartView.callback);

    } catch(err) {
        console.error('Error in : CartView - update item : [' + err +']');
    }

};

CartView.remove_item      = function(){

    try {

        var cart_controls_div = CartView.cart_controls_div;

        var pid      = cart_controls_div.find('input[name=product_id]').val();
        var quantity = cart_controls_div.find('input[name=quantity]').val();
        var price    = cart_controls_div.find('input[name=price]').val();

        var data = {
            "action"   : "remove_item",
            "pid"      : pid,
            "quantity" : quantity,
            "price"    : price
        };

        RequestUtil.quick_post_request(cart_controls_div, data, CartView.callback);

    } catch(err) {
        console.error('Error in : CartView - remove item : [' + err +']');
    }

};

CartView.callback         = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            console.log('Status - 1');
        }else{
            console.log('Status - 0');
        }

        //CommonCallback_Success_Title_lbl

        //title   = ( title   == null ) ? "" : title;
        //message = ( message == null ) ? "" : message;
        //
        //swal({
        //  title: title,
        //  text:  message+" It will close in 1.5 seconds.",
        //  timer: 1500,
        //  showConfirmButton: false,
        //  allowEscapeKey: true,
        //  type: 'success'
        //});

        CartView.load();

        $('body').trigger( "users_updated" );

    }catch(err){
        console.error('Error in : CartView - callback :['+err+']');
    }
};

CartView.empty_cart       = function(){

    try {

        var cart_controls_div = CartView.cart_controls_div;

        var data = {
            "action"   : "empty_cart"
        };

        RequestUtil.quick_post_request(cart_controls_div, data, function(outputArray){
            
            try{

                var status = outputArray["status"];

                if( status > 0 ){
                    console.log('Status - 1');
                }else{
                    console.log('Status - 0');
                }
                                
            } catch (err) {
                console.error('Error in : CartView - request [' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : CartView - empty cart : [' + err +']');
    }

};

CartView.get_cart         = function(){

    try {

        var cart_controls_div = CartView.cart_controls_div;

        var data = {
            "action"   : "cart"
        };

        RequestUtil.quick_post_request(cart_controls_div, data, function(outputArray){
            
            try{

                var status = outputArray["status"];

                if( status > 0 ){
                    console.log('Status - 1');
                }else{
                    console.log('Status - 0');
                }
                                
            } catch (err) {
                console.error('Error in : CartView - request [' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : CartView - get cart : [' + err +']');
    }

};