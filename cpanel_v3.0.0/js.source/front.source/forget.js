
/*! forget */

/* global CPopup, RequestUtil, Validate, Utils, g_root_url, CAPATCHA_TRIALS, CAPATCHA_REQUIRED, CDictionary, UserAuth, USER_ALREADY_EXIST, USER_NOT_EXIST */

////////////////////////////////////////////////////////////////////////////////

function ForgetForm(){
    this.trails = 0;
    this.form_tpl_html = '';
}

ForgetForm.show_form       = function(){
    
    try{

        ForgetForm.trails = 0;

        var form_tpl_html = $('body > #hidden').find('.forget_form_tpl').html();

        CPopup.display(form_tpl_html, CDictionary.get_text('ForgetForm_lbl'));


        var form_div = $("#overlay").find('form');

        form_div.attr( "action", g_root_url+"ajax.php?action=forget_password" );


        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();
                
                var outputArray = ForgetForm.validate_inputs();

                //alert( 'outputArray.errors : ' + outputArray.errors );
                //alert( form_div.html() );

                if( outputArray.errors <= 0 ){

                    if( ForgetForm.trails > -1 ){
                        ForgetForm.trails++;
                    }

                    form_div.submit();

                }

                return false;

            } catch(err) {
                console.error('Error in : ForgetForm - show form - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event) {

            try{

                ForgetForm.reset_inputs();

            } catch(err) {
                console.error('Error in : ForgetForm - show form - reset : [' + err +']');
            }
        });

        RequestUtil.init_post_form(form_div, ForgetForm.callback, false);
        
        ForgetForm.form_div = form_div;

    } catch(err) {
        console.error('Error in : ForgetForm - show form [' + err +']');
    }
};

ForgetForm.check_captcha   = function(status){

    try{
        
        var form_div = ForgetForm.form_div;

        if( status == CAPATCHA_REQUIRED || ForgetForm.trails > CAPATCHA_TRIALS ){

            ForgetForm.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    }catch(err) {
        console.error('Error in : ForgetForm - check captcha : [' + err +']');
    }

};

ForgetForm.reset_inputs    = function(){

    //alert('ForgetForm.reset_inputs');
    
    try{
 
        var form_div = ForgetForm.form_div;

        form_div.find("*").removeClass("required");

        var error_div = form_div.find('.errors');

        form_div.find( 'input[name=username]' ).val('');

        error_div.html('').hide();

    }catch(err) {
        console.error('Error in : ForgetForm - reset inputs : [' + err +']');
    }
};

ForgetForm.validate_inputs = function(){

    //console.log("ForgetForm.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form  = ForgetForm.form_div;

        parent_form.find("*").removeClass("required");
        
        var error_div = parent_form.find('.errors');

        var element = null;

        element = parent_form.find('input[name=username]');
        
        if( Validate.empty( element.val() ) ){
        //if( Validate.empty( element.val() ) || ! Validate.numbers( element.val() ) ){
            element.addClass("required");
            errors++;
        }

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
        console.error('Error in : ForgetForm - validate inputs : [' + err +']');
    }

    return outputArray;
};

ForgetForm.callback        = function(outputArray){

    try{

        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        var form_div = ForgetForm.form_div;

        var title   = '';
        var message = '';
        var type = '';

        if( status > 0 ){

            CPopup.close();

            ForgetForm.reset_inputs();
            
        }else{

            ForgetForm.check_captcha(status);

        }

        if( status > 0 ){

            title   = CDictionary.get_text('ForgetCallback_Success_Title_lbl');
            message = CDictionary.get_text('ForgetCallback_Success_Message_lbl');
            type = 'success';

        }else{
            
            switch ( status ){

                case USER_NOT_EXIST:
                    title   = CDictionary.get_text('ForgetCallback_UserNotExist_Title_lbl');
                    message = CDictionary.get_text('ForgetCallback_UserNotExist_Message_lbl');
                    break;

                default:
                    title   = CDictionary.get_text('ForgetCallback_Failed_Title_lbl');
                    message = CDictionary.get_text('ForgetCallback_Failed_Message_lbl');
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
        console.error('Error in : ForgetForm - callback : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////