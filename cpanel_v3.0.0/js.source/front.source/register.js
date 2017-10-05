
/*! register */

/* global CPopup, RequestUtil, Validate, Utils, g_root_url, CAPATCHA_TRIALS, CAPATCHA_REQUIRED, CDictionary, UserAuth, USER_ALREADY_EXIST */

////////////////////////////////////////////////////////////////////////////////

function RegisterForm(){
    this.trails = 0;
    this.form_tpl_html = '';
}

RegisterForm.show_form       = function(){
    
    try{

        RegisterForm.trails = 0;

        var form_tpl_html = $('body > #hidden').find('.signup_form_tpl').html();

        CPopup.display(form_tpl_html, CDictionary.get_text('RegisterForm_lbl'));


        var form_div = $("#overlay").find('form');

        form_div.attr( "action", g_root_url+"ajax.php?action=signup" );


        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();
                
                var outputArray = RegisterForm.validate_inputs();

                //alert( 'outputArray.errors : ' + outputArray.errors );
                //alert( form_div.html() );

                if( outputArray.errors <= 0 ){

                    if( RegisterForm.trails > -1 ){
                        RegisterForm.trails++;
                    }

                    form_div.submit();

                }

                return false;

            } catch(err) {
                console.error('Error in : RegisterForm - show form - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event) {

            try{

                RegisterForm.reset_inputs();

            } catch(err) {
                console.error('Error in : RegisterForm - show form - reset : [' + err +']');
            }
        });

        RequestUtil.init_post_form(form_div, RegisterForm.callback, false);
        
        RegisterForm.form_div = form_div;

    } catch(err) {
        console.error('Error in : RegisterForm - show form [' + err +']');
    }
};

RegisterForm.check_captcha   = function(status){

    try{
        
        var form_div = RegisterForm.form_div;

        if( status == CAPATCHA_REQUIRED || RegisterForm.trails > CAPATCHA_TRIALS ){

            RegisterForm.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    }catch(err) {
        console.error('Error in : RegisterForm - check captcha : [' + err +']');
    }

};

RegisterForm.reset_inputs    = function(){

    //alert('RegisterForm.reset_inputs');
    
    try{
 
        var form_div = RegisterForm.form_div;

        form_div.find("*").removeClass("required");

        var error_div = form_div.find('.errors');

        form_div.find( 'input[name=username]'         ).val('');
        form_div.find( 'input[name=password]'         ).val('');
        form_div.find( 'input[name=password_confirm]' ).val('');
        form_div.find( 'input[name=accept]'           ).attr('checked', false);

        error_div.html('').hide();

    }catch(err) {
        console.error('Error in : RegisterForm - reset inputs : [' + err +']');
    }
};

RegisterForm.validate_inputs = function(){

    //console.log("RegisterForm.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form  = RegisterForm.form_div;

        parent_form.find("*").removeClass("required");
        
        var error_div = parent_form.find('.errors');

        var element = null;

        element = parent_form.find('input[name=username]');
        
        if( Validate.empty( element.val() ) ){
        //if( Validate.empty( element.val() ) || ! Validate.numbers( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        element = parent_form.find('input[name=email]');
        
        if( Validate.empty( element.val() ) || !Validate.email( element.val() ) ){
            element.addClass("required");
            errors++;
        }

        element = parent_form.find('input[name=password]');
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        var element1 = parent_form.find('input[name=password]');
        var element2 = parent_form.find('input[name=password_confirm]');

        if( element1.val() != element2.val() ){
            error_div.html( CDictionary.get_text('CommonError_Validate_PasswordNotMatch_lbl') ).show();
            element1.addClass("required");
            element2.addClass("required");
            errors++;
        }
        
        var pwindicator = parent_form.find('#pwindicator');

        if( pwindicator.attr('class') == 'pw-very-weak' ){
            error_div.html( CDictionary.get_text('CommonError_Validate_PasswordVeryWeak_lbl') ).show();
            element1.addClass("required");
            element2.addClass("required");
            errors++;
        }
        
        //element = parent_form.find('input[name=accept]');
        //if( Utils.get_int( element.val() ) <= 0 ){
        //    element.parent().addClass("required");
        //    errors++;
        //}

        if( errors > 0 ){
            error_div.show();
            errors++;
        }
        
        //alert('valid:    ' + valid    );
        //alert('erros:    ' + errors   );
        //alert('messages: ' + messages );

        outputArray.valid    = valid;
        outputArray.errors   = errors;
        outputArray.messages = messages;
        outputArray.valid    = (errors<=0);


    } catch(err) {
        console.error('Error in : RegisterForm - validate inputs : [' + err +']');
    }

    return outputArray;
};

RegisterForm.callback        = function(outputArray){

    try{

        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        var form_div = RegisterForm.form_div;

        var title   = '';
        var message = '';
        var type = '';

        if( status > 0 ){

            CPopup.close();

            RegisterForm.reset_inputs();
            
        }else{

            RegisterForm.check_captcha(status);

        }

        if( status > 0 ){
            
            var user = {};

            user.user_id     = outputArray["user_id"];
            user.rule_id     = outputArray["rule_id"];
            user.name        = outputArray["name"];

            title   = CDictionary.get_text('RegisterCallback_Success_Title_lbl');
            message = CDictionary.get_text('RegisterCallback_Success_Message_lbl');
            type = 'success';

        }else{
            
            switch ( status ){

                case USER_ALREADY_EXIST:
                    title   = CDictionary.get_text('RegisterCallback_UserAlreadyExist_Title_lbl');
                    message = CDictionary.get_text('RegisterCallback_UserAlreadyExist_Message_lbl');
                    break;

                default:
                    title   = CDictionary.get_text('RegisterCallback_Failed_Title_lbl');
                    message = CDictionary.get_text('RegisterCallback_Failed_Message_lbl');
                    break;

            }

            type = 'error';
        }

        swal({
          title: title,
          text:  message+",\r\n\r\n  It will close in 5.5 seconds.",
          timer: 5500,
          showConfirmButton: false,
          allowEscapeKey: true,
          type: type
        });
    
    } catch(err) {
        console.error('Error in : RegisterForm - callback : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////