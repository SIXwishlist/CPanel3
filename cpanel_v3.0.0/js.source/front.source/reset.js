
/*! reset */

/* global CPopup, RequestUtil, Validate, Utils, g_root_url, CAPATCHA_TRIALS, CAPATCHA_REQUIRED, CDictionary, UserAuth, USER_ALREADY_EXIST, UrlUtil */

$(document).ready(function(){

    try{

        var input_div = $('body').find('input[name=reset_form][value=1]' );

        if( input_div.length > 0 ){

            window.setTimeout(function(){ 
                ResetForm.show_form();
            }, 1000);

            //ResetForm.show_form();
        }


    } catch(err) {
        console.error('Error in : ResetForm - document - ready : [' + err +']');
    }

});

////////////////////////////////////////////////////////////////////////////////

function ResetForm(){
    this.trails = 0;
    this.form_tpl_html = '';
}

ResetForm.show_form       = function(){
    
    try{

        ResetForm.trails = 0;

        var form_tpl_html = $('body > #hidden').find('.reset_form_tpl').html();

        CPopup.display(form_tpl_html, CDictionary.get_text('ResetForm_lbl'));


        var user_key = $("#main-side").find('input[name=user_key]').val();
        var user_id  = $("#main-side").find('input[name=user_id]').val();


        var form_div = $("#overlay").find('form');

        form_div.attr( "action", g_root_url+"ajax.php?action=reset_password" );

        form_div.find( 'input[name=user_key]' ).val( user_key );
        form_div.find( 'input[name=user_id]'  ).val( user_id  );


        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();
                
                var outputArray = ResetForm.validate_inputs();

                //alert( 'outputArray.errors : ' + outputArray.errors );
                //alert( form_div.html() );

                if( outputArray.errors <= 0 ){

                    if( ResetForm.trails > -1 ){
                        ResetForm.trails++;
                    }

                    form_div.submit();

                }

                return false;

            } catch(err) {
                console.error('Error in : ResetForm - show form - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event) {

            try{

                ResetForm.reset_inputs();

            } catch(err) {
                console.error('Error in : ResetForm - show form - reset : [' + err +']');
            }
        });

        RequestUtil.init_post_form(form_div, ResetForm.callback, false);
        
        ResetForm.form_div = form_div;

    } catch(err) {
        console.error('Error in : ResetForm - show form [' + err +']');
    }
};

ResetForm.check_captcha   = function(status){

    try{
        
        var form_div = ResetForm.form_div;

        if( status == CAPATCHA_REQUIRED || ResetForm.trails > CAPATCHA_TRIALS ){

            ResetForm.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    }catch(err) {
        console.error('Error in : ResetForm - check captcha : [' + err +']');
    }

};

ResetForm.reset_inputs    = function(){

    //alert('ResetForm.reset_inputs');
    
    try{
 
        var form_div = ResetForm.form_div;

        form_div.find("*").removeClass("required");

        var error_div = form_div.find('.errors');

        form_div.find( 'input[name=username]' ).val('');

        error_div.html('').hide();

    }catch(err) {
        console.error('Error in : ResetForm - reset inputs : [' + err +']');
    }
};

ResetForm.validate_inputs = function(){

    //console.log("ResetForm.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form  = ResetForm.form_div;

        parent_form.find("*").removeClass("required");
        
        var error_div = parent_form.find('.errors');

        var element = null;

        element = parent_form.find('input[name=new_password]');
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        element = parent_form.find('input[name=new_password_confirm]');
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        var element1 = parent_form.find('input[name=new_password]');
        var element2 = parent_form.find('input[name=new_password_confirm]');

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
        console.error('Error in : ResetForm - validate inputs : [' + err +']');
    }

    return outputArray;
};

ResetForm.callback        = function(outputArray){

    try{

        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        var form_div = ResetForm.form_div;

        var title   = '';
        var message = '';
        var type = '';

        if( status > 0 ){

            CPopup.close();

            ResetForm.reset_inputs();
            
        }else{

            ResetForm.check_captcha(status);

        }

        if( status > 0 ){

            title   = CDictionary.get_text('ResetCallback_Success_Title_lbl');
            message = CDictionary.get_text('ResetCallback_Success_Message_lbl');
            type = 'success';

        }else{
            
            switch ( status ){

                case USER_NOT_EXIST:
                    title   = CDictionary.get_text('ResetCallback_UserNotExist_Title_lbl');
                    message = CDictionary.get_text('ResetCallback_UserNotExist_Message_lbl');
                    break;

                default:
                    title   = CDictionary.get_text('ResetCallback_Failed_Title_lbl');
                    message = CDictionary.get_text('ResetCallback_Failed_Message_lbl');
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
        
        if( status > 0 ){
            location.href = UrlUtil.get_home_href();
            //location.reload();
        }

    } catch(err) {
        console.error('Error in : ResetForm - callback : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////