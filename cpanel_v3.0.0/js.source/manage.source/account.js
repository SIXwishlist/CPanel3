
/*! account */

/* global AdminAuth */

$(document).ready(function(){

    //alert('auth.js Loaded');

    try{

        $('body').bind(AdminAuth.LOGIN_LOADED, AccountModule.display_module);

    } catch (err) {
        console.error('Error in account main :[' + err + ']');
    }

});


function AccountModule(){}

AccountModule.display_module = function(){

    try{

        var admin_id = AdminAuth.admin_id;

        if( admin_id > 0 ){
            AdminAuth.show_user_account();
        }else{
            AdminAuth.show_form();
        }

    } catch (err) {
        console.error('Error in : AccountModule - display module :[' + err + ']');
    }

    return;
};