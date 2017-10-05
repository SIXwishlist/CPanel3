
/*! checkout */

/* global CForm, CDictionary, Validate, UrlUtil, RequestUtil, Utils, g_root_url, CPopup, Globals, USER_NOT_EXIST, ACCOUNT_SUSPENDED, ACCOUNT_BLOCKED, TreeJSON, LoginForm, UserAuth */

$(document).ready(function(){

    try{

        $('body').bind(CDictionary.DICTIONARY_LOADED, CheckoutForm.init_form);

    } catch (err) {
        console.error('Error in : checkout - document - ready [' + err + ']');
    }

});

function CheckoutForm(){}

CheckoutForm.init_form       = function(){
    
    try{

        var checkout_div = $('body').find('#checkout');

        CheckoutForm.trails = 0;

        var form_div = checkout_div.find('form');

        form_div.attr( "action", g_root_url+"ajax.php?action=checkout" );

        //a few workarounds :)
        //form_div.find( 'input[id=remember]'  ).attr("id",  "remember1");
        //form_div.find( 'label[for=remember]' ).attr("for", "remember1");
        
        form_div.find( 'input[name=expiration_date]' ).datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            dateFormat: 'mm/yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
        
        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();
                
                if( Globals.user_id <= 0 ){
                    LoginForm.show_form();
                    return false;
                }
                
                var outputArray = CheckoutForm.validate_inputs();

                //alert( 'outputArray.errors : ' + outputArray.errors );
                //alert( form_div.html() );

                if( outputArray.errors <= 0 ){

                    if( CheckoutForm.trails > -1 ){
                        CheckoutForm.trails++;
                    }

                    form_div.submit();

                }

                return false;

            } catch(err) {
                console.error('Error in : CheckoutForm - show form - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event) {

            try{

                CheckoutForm.reset_inputs();

            } catch(err) {
                console.error('Error in : CheckoutForm - show form - reset : [' + err +']');
            }
        });
        
        RequestUtil.init_post_form(form_div, CheckoutForm.callback, false);
        
        CheckoutForm.form_div = form_div;

    } catch(err) {
        console.error('Error in : CheckoutForm - show form [' + err +']');
    }
};

CheckoutForm.check_captcha   = function(status){

    try{
        
        var form_div = CheckoutForm.form_div;

        if( status == CAPATCHA_REQUIRED || CheckoutForm.trails > CAPATCHA_TRIALS ){

            CheckoutForm.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    }catch(err) {
        console.error('Error in : CheckoutForm - check captcha : [' + err +']');
    }

};

CheckoutForm.reset_inputs    = function(){

    //alert('CheckoutForm.reset_inputs');
    
    try{
 
        var form_div = CheckoutForm.form_div;
        
        form_div.find("*").removeClass("required");

        var error_div = form_div.find('.errors');

        form_div.find( 'input[name=username]' ).val('');
        form_div.find( 'input[name=password]' ).val('');
        form_div.find( 'input[name=remember]' ).val('');

        error_div.html('').hide();
        
    }catch(err) {
        console.error('Error in : CheckoutForm - reset inputs : [' + err +']');
    }
};

CheckoutForm.validate_inputs = function(){

    //console.log("CheckoutForm.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form  = CheckoutForm.form_div;

        parent_form.find("*").removeClass("required");


        var element = null;

        element = parent_form.find('input[name=card_number]');
        
        if( Validate.empty( element.val() ) ){
        //if( Validate.empty( element.val() ) || ! Validate.numbers( element.val() ) ){
            element.addClass("required");
            errors++;
        }

        element = parent_form.find('input[name=expiration_date]');
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }

        element = parent_form.find('input[name=card_code]');
        
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
        console.error('Error in : CheckoutForm - validate inputs : [' + err +']');
    }

    return outputArray;
};

CheckoutForm.callback        = function(outputArray){

    try{

        CPopup.close();


        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        CheckoutForm.check_captcha(status);

        CheckoutForm.reset_inputs();

        var title   = '';
        var message = '';
        var type = '';

        var html = '';

        if( status > 0 ){

            title   = CDictionary.get_text('CheckoutCallback_Success_Title_lbl');
            message = CDictionary.get_text('CheckoutCallback_Success_Message_lbl');
            type = 'success';

        }else{
            
            switch ( status ){

                case USER_NOT_EXIST:
                    title   = CDictionary.get_text('CheckoutCallback_NotExist_Title_lbl');
                    message = CDictionary.get_text('CheckoutCallback_NotExist_Message_lbl');
                    break;

                case ACCOUNT_SUSPENDED:
                    title   = CDictionary.get_text('CheckoutCallback_Suspended_Title_lbl');
                    message = CDictionary.get_text('CheckoutCallback_Suspended_Message_lbl');
                    break;

                case ACCOUNT_BLOCKED:
                    title   = CDictionary.get_text('CheckoutCallback_Blocked_Title_lbl');
                    message = CDictionary.get_text('CheckoutCallback_Blocked_Message_lbl');
                    break;

                default:
                    title   = CDictionary.get_text('CheckoutCallback_Failure_Title_lbl');
                    message = CDictionary.get_text('CheckoutCallback_Failure_Message_lbl');
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
            //location.href = UrlUtil.get_home_href();
        }
    
    } catch(err) {
        console.error('Error in : CheckoutForm - callback : [' + err +']');
    }

};

CheckoutForm.get_credit_card_type = function(account_number){

    //start without knowing the credit card type
    var result = "unknown";

    //first check for MasterCard
    if (/^5[1-5]/.test(account_number)){
        result = "mastercard";
    }

    //then check for Visa
    else if (/^4/.test(account_number)){
        result = "visa";
    }

    //then check for AmEx
    else if (/^3[47]/.test(account_number)){
        result = "amex";
    }

    return result;
};