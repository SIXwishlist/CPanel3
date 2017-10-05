/*
 *
 */

/*! login */

/* global CForm, CDictionary, Validate, UrlUtil, RequestUtil, Utils, g_root_url, CPopup, Globals, USER_NOT_EXIST, ACCOUNT_SUSPENDED, ACCOUNT_BLOCKED, TreeJSON */

////////////////////////////////////////////////////////////////////////////////

function UserAuth(){}

UserAuth.FINISH_CHECK_LOGGING = "FINISH_CHECK_LOGGING";

UserAuth.check_login          = function(){

    try{

        var main_div = $("#head").find("#sign");

        var data = "action=check_login";

        RequestUtil.quick_post_request(main_div, data, function(outputArray){

            try{

                var status = outputArray["status"];

                var html = '';

                if( status > 0 ){

                    var user = {};

                    user.user_id      = outputArray["user_id"];
                    user.rule_id      = outputArray["rule_id"];
                    user.name         = outputArray["name"];
                    user.user_status  = outputArray["user_status"];

                    Globals.user_id = user.user_id;
                    Globals.user    = user;

                    html = UserAuth.get_logged_links();

                }else{

                    html = UserAuth.get_non_logged_links();

                }

                main_div.html( html );
                
                $('body').trigger(UserAuth.FINISH_CHECK_LOGGING);
                
            } catch (err) {
                console.error('Error in : UserAuth - request [' + err + ']');
            }

        });

    } catch (err) {
        console.error('Error in : UserAuth - check login [' + err + ']');
    }
};

UserAuth.logout               = function(){

    try{
    
        var main_div = $("#head").find("#sign");

        var seqId = Math.floor(Math.random()*1000);

        var data = "action=logout"
                +"&seqId="+seqId;

        RequestUtil.quick_ajax_request(main_div, data, function(outputArray){

            try{

                //console.log(JSON.stringify(outputArray));

                var status = Utils.get_int( outputArray["status"] );

                var html  = UserAuth.get_non_logged_links();

                main_div.html( html );

                if( status > 0 ){

                    Globals.user_id = -1;
                    Globals.user    = null;

                }else{

                    Globals.user_id = -1;
                    Globals.user    = null;
                    
                    console.error('Can not logout');

                }

                main_div.html( html );
                
                window.location = UrlUtil.get_home_href();
                
                if( status > 0 ){
                    location.reload();
                }

            } catch (err) {
                console.error('error in request [' + err + ']');
            }

        });

    } catch (err) {
        console.error('Error in : UserAuth - logout [' + err + ']');
    }
};

UserAuth.get_logged_links     = function(){

    var html  = '';

    try{

        var user          = Globals.user;
        var notifications = Globals.notifications;

        html  = '<a href="'+UrlUtil.get_home_href(Globals.lang)+'"                                               ><i class="fa fa-home"    aria-hidden="true"></i> '+CDictionary.get_text('Home_lbl')         +'</a></div>'
              + '<a href="'+UrlUtil.get_user_profile_href(user.user_id, user.name, Globals.lang)+'"              ><i class="fa fa-user"    aria-hidden="true"></i> '+CDictionary.get_text('Welcome_lbl')      + ' ' + user.name +'</a></div>'
              + '<a href="javascript:UserAuth.logout();"                                                         ><i class="fa fa-sign-in" aria-hidden="true"></i> '+CDictionary.get_text('Logout_lbl')       +'</a>';
      
              //<i class="fa fa-shopping-cart" aria-hidden="true"></i>

    } catch (err) {
        console.error('Error in : UserAuth - get logged links [' + err + ']');
    }
    
    return html;
};

UserAuth.get_non_logged_links = function(){

    var html  = '';

    try{

        html  = '<a href="'+UrlUtil.get_home_href(Globals.lang)+'"       ><i class="fa fa-home"      aria-hidden="true"></i> '+CDictionary.get_text('Home_lbl')     +'</a>'
              + '<span> | </span>'
              + '<a href="#login"   onclick="LoginForm.show_form();"     ><i class="fa fa-sign-in"   aria-hidden="true"></i> '+CDictionary.get_text('Login_lbl')    +'</a>'
              + '<span> | </span>'
              + '<a href="#sign-up" onclick="RegisterForm.show_form();"  ><i class="fa fa-user-plus" aria-hidden="true"></i> '+CDictionary.get_text('SignUp_lbl')   +'</a>';

    } catch (err) {
        console.error('Error in : UserAuth - get non logged links [' + err + ']');
    }

    return html;
};

////////////////////////////////////////////////////////////////////////////////

function LoginForm(){
    this.trails = 0;
    this.form_tpl_html = '';
}

LoginForm.show_form       = function(){
    
    try{

        LoginForm.trails = 0;

        var form_tpl_html = $('body > #hidden').find('.login_form_tpl').html();

        CPopup.display(form_tpl_html, CDictionary.get_text('LoginForm_lbl'));


        var form_div = $("#overlay").find('form');

        form_div.attr( "action", g_root_url+"ajax.php?action=login" );

        //a few workarounds :)
        form_div.find( 'input[id=remember]'  ).attr("id",  "remember1");
        form_div.find( 'label[for=remember]' ).attr("for", "remember1");
        
        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();
                
                var outputArray = LoginForm.validate_inputs();

                //alert( 'outputArray.errors : ' + outputArray.errors );
                //alert( form_div.html() );

                if( outputArray.errors <= 0 ){

                    if( LoginForm.trails > -1 ){
                        LoginForm.trails++;
                    }

                    form_div.submit();

                }

                return false;

            } catch(err) {
                console.error('Error in : LoginForm - show form - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event) {

            try{

                LoginForm.reset_inputs();

            } catch(err) {
                console.error('Error in : RegisterForm - show form - reset : [' + err +']');
            }
        });
        
        RequestUtil.init_post_form(form_div, LoginForm.callback, false);
        
        LoginForm.form_div = form_div;

    } catch(err) {
        console.error('Error in : LoginForm - show form [' + err +']');
    }
};

LoginForm.check_captcha   = function(status){

    try{
        
        var form_div = LoginForm.form_div;

        if( status == CAPATCHA_REQUIRED || LoginForm.trails > CAPATCHA_TRIALS ){

            LoginForm.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    }catch(err) {
        console.error('Error in : LoginForm - check captcha : [' + err +']');
    }

};

LoginForm.reset_inputs    = function(){

    //alert('LoginForm.reset_inputs');
    
    try{
 
        var form_div = LoginForm.form_div;
        
        form_div.find("*").removeClass("required");

        var error_div = form_div.find('.errors');

        form_div.find( 'input[name=username]' ).val('');
        form_div.find( 'input[name=password]' ).val('');
        form_div.find( 'input[name=remember]' ).val('');

        error_div.html('').hide();
        
    }catch(err) {
        console.error('Error in : LoginForm - reset inputs : [' + err +']');
    }
};

LoginForm.validate_inputs = function(){

    //console.log("LoginForm.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form  = LoginForm.form_div;

        parent_form.find("*").removeClass("required");


        var element = null;

        element = parent_form.find('input[name=username]');
        
        if( Validate.empty( element.val() ) ){
        //if( Validate.empty( element.val() ) || ! Validate.numbers( element.val() ) ){
            element.addClass("required");
            errors++;
        }

        element = parent_form.find('input[name=password]');
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        //element = parent_form.find('input[name=accept]');
        //if( Utils.get_int( element.val() ) <= 0 ){
        //    element.parent().addClass("required");
        //    errors++;
        //}

        //alert('valid:    ' + valid    );
        //alert('erros:    ' + errors   );
        //alert('messages: ' + messages );

        outputArray.valid    = valid;
        outputArray.errors   = errors;
        outputArray.messages = messages;
        outputArray.valid    = (errors<=0);


    } catch(err) {
        console.error('Error in : LoginForm - validate inputs : [' + err +']');
    }

    return outputArray;
};

LoginForm.callback        = function(outputArray){

    try{

        CPopup.close();


        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        LoginForm.check_captcha(status);

        LoginForm.reset_inputs();

        var title   = '';
        var message = '';
        var type = '';

        var sign_div = $("#head").find("#sign");

        var html = '';

        if( status > 0 ){
            
            var user = {};

            user.user_id     = outputArray["user_id"];
            user.rule_id     = outputArray["rule_id"];
            user.name        = outputArray["name"];

            Globals.user_id = user.user_id;
            Globals.user    = user;

            html = UserAuth.get_logged_links();


            title   = CDictionary.get_text('LoginCallback_Success_Title_lbl');
            message = CDictionary.get_text('LoginCallback_Success_Message_lbl');
            type = 'success';

        }else{

            html = UserAuth.get_non_logged_links();
            
            switch ( status ){

                case USER_NOT_EXIST:
                    title   = CDictionary.get_text('LoginCallback_NotExist_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_NotExist_Message_lbl');
                    break;

                case ACCOUNT_SUSPENDED:
                    title   = CDictionary.get_text('LoginCallback_Suspended_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_Suspended_Message_lbl');
                    break;

                case ACCOUNT_BLOCKED:
                    title   = CDictionary.get_text('LoginCallback_Blocked_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_Blocked_Message_lbl');
                    break;

                default:
                    title   = CDictionary.get_text('LoginCallback_Failure_Title_lbl');
                    message = CDictionary.get_text('LoginCallback_Failure_Message_lbl');
                    break;

            }

            type = 'error';
        }

        sign_div.html( html );

        swal({
          title: title,
          text:  message+",\r\n\r\n  It will close in 5.5 seconds.",
          timer: 5500,
          showConfirmButton: false,
          allowEscapeKey: true,
          type: type
        });
        
        if( status > 0 ){
            //location.href = UrlUtil.get_home_href();
            location.reload();
        }
    
    } catch(err) {
        console.error('Error in : LoginForm - callback : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////