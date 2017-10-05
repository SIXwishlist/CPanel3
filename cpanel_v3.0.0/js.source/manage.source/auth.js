
/*! auth */

/* global g_loading_div, Cover, CMSUtil, CDictionary, g_root_url, Validate, ManageModules, CPopup, FrontUtils, USER_NOT_EXIST, ACCOUNT_EXPIRED, USER_TYPE_MASTER, USER_TYPE_ORG, USER_TYPE_CHECKER, USER_TYPE_ENTRY, ManageOrganization, HomeDisplay, ErrorUtil, TplUtil, RequestUtil, AccountModule */

var gYes = '';
var gNo  = '';

$(document).ready(function(){

    //alert('auth.js Loaded');

    try{

        $('body').bind(AdminAuth.LOGIN_LOADED, AccountModule.display_module);

    } catch (err) {
        console.error('Error in auth main :[' + err + ']');
    }

});


function AdminAuth(){}

AdminAuth.LOGIN_LOADED = "ADMIN_AUTH__LOGIN_LOADED";

AdminAuth.check_login         = function(){

    try{

        var main_div = $("#head").find("#top-menu");

        var data = "action=check_login";

        RequestUtil.quick_post_request(main_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    AdminAuth.set_login_vars(outputArray);

                }
                
                $('body').trigger(AdminAuth.LOGIN_LOADED);

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

        gYes = CDictionary.get_text('Yes_lbl');
        gNo  = CDictionary.get_text('No_lbl');

    } catch (err) {
        console.error('Error in : AdminAuth - check login :[' + err + ']');
    }

};

AdminAuth.get_form_properties = function(){

    try{

        var name = 'admin';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "username",  type : "text",     label : '<i class="fa fa-user" aria-hidden="true"></i> ' + CDictionary.get_text('LoginForm_Username_lbl') + " :" },//, grid : 'cell-x2' },
                { name : "password",  type : "password", label : '<i class="fa fa-lock" aria-hidden="true"></i> ' + CDictionary.get_text('LoginForm_Password_lbl') + " :" },//, grid : 'cell-x2' },
                
                { type : "separator", label : "" },

                { type : "clear",     label : "" },

                //{ name : "remember",  postfix:"radio1", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'1' },//, grid : 'cell-x2' },
                //{ name : "remember",  postfix:"radio2", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'2' },//, grid : 'cell-x2' },
                //{ name : "remember",  postfix:"radio3", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'3' },//, grid : 'cell-x2' },

                //{ name : "remember2",  postfix:"radio4", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'4' },//, grid : 'cell-x2' },
                //{ name : "remember2",  postfix:"radio5", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'5' },//, grid : 'cell-x2' },
                //{ name : "remember2",  postfix:"radio6", type : "radio", label : CDictionary.get_text('LoginForm_Remeber_lbl')+'6' },//, grid : 'cell-x2' },

                { name : "remember",  type : "checkbox", label : CDictionary.get_text('LoginForm_Remeber_lbl') },//, grid : 'cell-x2' },
                
                //{ type : "clear",     label : "" },
                //{ type : "separator",  label : "" },
                //{ name : "remember",  type : "radio",    label : CDictionary.get_text('LoginForm_Remeber_lbl') + " :" },//, grid : 'cell-x2' },

                { type : "separator",  label : "" },

                { name : "", label : "", type : "custom", value : '<div class="note">Forget password...<a href="#" onclick="ForgetForm.show_form(); return false;">click here</a></div>' }

            ],

            action  : '',
            method  : 'post',
            enctype : 'application/x-www-form-urlencoded',

            style   : 'style1 centered-form'

        };

        var form_object = new CForm(form_properties);

    } catch (err) {
        console.error('Error in : AdminAuth - get admin login form properties :[' + err + ']');
    }

    return form_object;
};

AdminAuth.show_form           = function(){

    try {

        var title_div   = $("#body").find("#title");
        var content_div = $("#body").find("#content");
        var menu_div    = $("#body").find("#menu");
        
        menu_div.hide();
        
        menu_div.html('');

        title_div.show().html('Sign in to your account');
        content_div.html('');


        //var form_div = TplUtil.get_hidden_div('login_form_tpl', false);
        var form_div = AdminAuth.get_form_properties().get_form_div();

        var form_options = {
            cont_div: content_div,
            //tpl_path: g_template_url + "?tpl=admin_login",
            form_div: form_div,
            form_action: g_request_url + "?action=authenticate",
            complete_callback: AdminAuth.callback,
            prepare_func: null,
            post_func: null,
            post_args: null,
            validate_func: AdminAuth.validate,
            validate_notes_func: AdminAuth.validate_notes,
            animated: true
        };
        
        CMSUtil.create_form(form_options);
        
        AdminAuth.form_div = form_div;

    } catch (err) {
        console.error('Error in : AdminAuth - show login form :[' + err + ']');
    }
};

AdminAuth.callback            = function(outputArray){
    
    try{

        var title   = '';
        var message = '';
        var type    = '';
        
        var form_div = AdminAuth.form_div;

        var status = outputArray["status"];

        if( status > 0 ){

            title   = CDictionary.get_text('LoginCallback_Success_Title_lbl');
            message = CDictionary.get_text('LoginCallback_Success_Message_lbl');
            type = 'success';

        }else{
            
            switch ( status ){
                
                case USER_NOT_EXIST:
                    title   = CDictionary.get_text('LoginCallback_UserNotExist_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_UserNotExist_Message_lbl');
                    break;

                case ACCOUNT_EXPIRED:
                    title   = CDictionary.get_text('LoginCallback_Account_Expired_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_Account_Expired_Message_lbl');
                    break;

                default:
                    var error_object = ErrorUtil.get_common_messages(status);
                    title   = error_object.title;
                    message = error_object.message;
                    break;

            }

            type = 'error';
        }

        if( status > 0 ){
            
            AdminAuth.set_login_vars(outputArray);

            AdminAuth.show_user_account();
            
            HomeDisplay.update_top_menu();

        }else{

            //swal({
            //  title: 'Failed to login',
            //  text:  message+",\r\n\r\n  It will close in 3.5 seconds.",
            //  timer: 3500,
            //  showConfirmButton: false,
            //  allowEscapeKey: true,
            //  type: type
            //});

            form_div.find('.errors').html( message ).show();

        }

    } catch (err) {
        console.error('Error in : AdminAuth - callback :[' + err + ']');
    }  
};

AdminAuth.validate            = function(form_div){
    
    var errors = 0;
    
    try{

        form_div.find('.error').html('').remove();

        //name input
        var name_input = form_div.find('input[name=username]');

        if(   ! Validate.required( name_input.val() )   ){
            errors++;
            //name_input.after( CMSExtraUtil.get_error_div('Name required !') );
        }


        //password input
        var password_input = form_div.find('input[name=password]');

        if(   ! Validate.required( password_input.val() )   ){
            errors++;
            //password_input.after( CMSExtraUtil.get_error_div('Password required !') );
        }

        if( errors > 0 ){
            console.log( 'errors : '+ errors );
            //alert( 'errors : '+form_div.find('.error').length );
        }
    
    } catch (err) {
        console.error('Error in : AdminAuth - validate form :[' + err + ']');
    }

    return errors;
};

AdminAuth.validate_notes      = function(form_div){

    try{

        var errors = 0;
    
        form_div.find('.error').html('').remove();

        //name input
        var name_input = form_div.find('input[name=username]');

        if(   ! Validate.required( name_input.val() )   ){
            errors++;
            name_input.after( CMSExtraUtil.get_error_div('Name required !') );
        }

        //password input
        var password_input = form_div.find('input[name=password]');

        if(   ! Validate.required( password_input.val() )   ){
            errors++;
            password_input.after( CMSExtraUtil.get_error_div('Password required !') );
        }


        //if( errors > 0 ){
        //
        //    var message = 'Please check your inputs';
        //
        //    CPopup.display(message);
        //
        //}

        console.log( 'errors : '+ errors );
        //alert( 'errors : '+form_div.find('.error').length );

    }catch(err){
        console.error('Error in - AdminAuth - show notes :['+err+']');
    }

};

AdminAuth.show_user_account   = function(){

    try{

        var menu_div    = $("#body").find("#menu");
        var title_div   = $("#body").find("#title");

        menu_div.show();
        title_div.hide();

        ManageModules.show_main_modules();

        $('body').trigger( "in_background" );

    } catch (err) {
        console.error('Error in : AdminAuth - show user account :[' + err + ']');
    }
};

AdminAuth.set_login_vars      = function(outputArray){

    try{

        AdminAuth.admin_id = outputArray["admin_id"];
        AdminAuth.rule_id  = outputArray["rule_id"];
        AdminAuth.name     = outputArray["name"];

    } catch (err) {
        console.error('Error in : AdminAuth - set login vars :[' + err + ']');
    }
};

AdminAuth.logout              = function(){
    
    try{

        var main_div = $("#body").find("#content");

        var data = "action=logout";

        RequestUtil.quick_post_request(main_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                AdminAuth.admin_id  = -1;
                AdminAuth.rule_id  = -1;
                AdminAuth.username = '';

                $("#head").find("#rule-name").html('');

                if( status > 0 ){
                    AdminAuth.show_form();
                }else{
                    AdminAuth.show_form();
                }

                HomeDisplay.update_top_menu();
                
                //location.reload(); 

            } catch (err) {
                console.error('Error in AdminAuth.logout :[' + err + ']');
            }

        });

    } catch (err) {
        console.error('Error in : AdminAuth - show user account :[' + err + ']');
    }
};



function ForgetForm(){}

ForgetForm.show_form = function(){

    try{

        var main_div = $("#body").find("#content");

        var form_options = { 
            cont_div          : main_div,
            //tpl_path          : g_root_url+"mvc/views/tpl/js/forms/admin_forget_password.tpl",
            tpl_path          : g_template_url+"?tpl=forget_password",
            form_action       : g_request_url+"?action=forget_password",
            complete_callback : ForgetForm.callback,
            prepare_func      : ForgetForm.prepare,
            post_func         : null, 
            post_args         : null, 
            validate_func     : ForgetForm.validate,
            animated          : false
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in - ForgetForm - show form :['+err+']');
    }
};

ForgetForm.callback  = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_popup('Reset password link has been sent, please check your email', STATUS_ICON_TRUE);
        }else{
            CMSExtraUtil.show_popup('Failed to send email, Incorrect information', STATUS_ICON_FALSE);
        }

    }catch(err){
        console.error('Error in - ForgetForm - callback :['+err+']');
    }
};

ForgetForm.prepare   = function(form_div){
};

ForgetForm.validate  = function(form_div){
    
    try{

        var errors = 0;

        form_div.find('.error').html('').remove();

        //username or email input
        var email_input = form_div.find('input[name=email]');

        if(   ! Validate.required( email_input.val() )   ){
            errors++;
            email_input.after( CMSExtraUtil.get_error_div('Username or e-Mail required !') );
        }

    }catch(err){
        console.error('Error in - ForgetForm - validate :['+err+']');
    }

    return errors;
};



function AuthUtil(){}

AuthUtil.highlight_button = function(button){
    
    try{

        $("#menu").find('a').removeClass();

        $(button).addClass('active');

    } catch (err) {
        console.error('Error in : AdminAuth - highlight button :[' + err + ']');
    }
};

AuthUtil.get_rule_name    = function(rule){
    
    var rule_name = '';
    
    switch (rule){

        case USER_TYPE_MASTER:
            rule_name = 'Master Admin';
            break;
            
        case USER_TYPE_ORG:
            rule_name = 'Organization';
            break;
            
        case USER_TYPE_CHECKER:
            rule_name = 'Checker';
            break;
            
        case USER_TYPE_ENTRY:
            rule_name = 'Entry';
            break;
            
        default:
            break;
    }
    
    return  rule_name;
};

AuthUtil.get_menu_by_rule = function(rule){
    
    var menu_html = '';

    switch (rule){

        case USER_TYPE_MASTER:
            menu_html = '<a href="#" onclick="ManageOrganization.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-building" aria-hidden="true"></i> '
                          + CDictionary.get_text('Organizations_lbl')
                      + '</a>'
                      + '<a href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-certificate" aria-hidden="true"></i> '
                          + CDictionary.get_text('Certificates_lbl')
                      + '</a>'
                      + '<a href="#" onclick="ManageUser.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-user" aria-hidden="true"></i> '
                          + CDictionary.get_text('Users_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="manage_settings();         AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-ios-home"></i> '
                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
                          + CDictionary.get_text('Settings_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
                          //+ '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';
            break;

        case USER_TYPE_ORG:
            menu_html = '<a href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-certificate" aria-hidden="true"></i> '
                          + CDictionary.get_text('Certificates_lbl')
                      + '</a>'
                      + '<a href="#" onclick="ManageUser.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-user" aria-hidden="true"></i> '
                          + CDictionary.get_text('Users_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="manage_settings();         AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-ios-home"></i> '
                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
                          + CDictionary.get_text('Settings_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
                          //+ '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';
            break;

        case USER_TYPE_CHECKER:
            menu_html = '<a href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-certificate" aria-hidden="true"></i> '
                          + CDictionary.get_text('Certificates_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="manage_settings();         AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-ios-home"></i> '
                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
                          + CDictionary.get_text('Settings_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
                          //+ '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';
            break;

        case USER_TYPE_ENTRY:
            menu_html = '<a href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-certificate" aria-hidden="true"></i> '
                          + CDictionary.get_text('Certificates_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="manage_settings();         AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-ios-home"></i> '
                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
                          + CDictionary.get_text('Settings_lbl')
                      + '</a>'
                      //+ '<span> | </span>'
                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
                          //+ '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';
            break;
            
        default:
            //menu_html = '<a href="#" onclick="AdminAuth.logout(); return false;">Log Out</a>';
            menu_html = '<a href="#" onclick="AdminAuth.logout(); AuthUtil.highlight_button(this); return false;">'
                          + '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';
            break;
    }

    return  menu_html;
};
