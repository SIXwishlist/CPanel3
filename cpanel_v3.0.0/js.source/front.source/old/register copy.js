/*
 *
 */

/*! register */

/* global CForm, CDictionary, Validate, RequestUtil, CustomUI, Utils */

$(document).ready(function(){

    //alert('register js loaded');

    try{

        var form_div = $('body').find("#register_form" );

        if( form_div.length > 0 ){
            RegisterForm22.init(form_div);
        }

    } catch(err) {
        console.error('Error in : RegisterForm22 - document - ready : [' + err +']');
    }

});

function RegisterForm22(){
    this.trails = 0;
}

RegisterForm22.init              = function(form_div){

    try{

        RegisterForm22.trails = 0;

        form_div.attr( "action", g_root_url+"ajax.php?action=register" );
        //alert( form_div.html() );

        //CForm.use_upload_button(form_div);
        //var year = ((new Date()).getFullYear()) - 10;
        
        var date = new Date();
        
        var year = date.getFullYear();

        form_div.find('input[name=birth_date]').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:"+year,
            dateFormat: "yy-mm-dd"
        });
        
        form_div.find('input[name=birth_date]').datepicker("setDate", new Date(date.getFullYear(),date.getMonth(),date.getDay()));

        form_div.find('input[name=birth_date]').val("");

        $('body').bind(CustomUI.DROPDOWN_ITEM_CLICKED, RegisterForm22.drop_down_changed);

        form_div.find('input[name=password]').pwstrength();

        //form_div.find('input[name=button]').click(function(){
        form_div.find('a.submit').click(function(){

            var outputArray = RegisterForm22.validate_inputs();

            //alert( 'outputArray.errors : ' + outputArray.errors );
            //alert( parentDiv.html() );

            if( outputArray.errors <= 0 ){

                if( RegisterForm22.trails > -1 ){
                    RegisterForm22.trails++;
                }

                form_div.submit();

            }

        });

        RequestUtil.init_post_form(form_div, RegisterForm22.callback, false);

        RegisterForm22.form_div = form_div;

    } catch(err) {
        console.error('Error in : RegisterForm22 - init : [' + err +']');
    }
};

RegisterForm22.drop_down_changed = function(event, $li, $dropdown){

    try{
        
        //console.log(CustomUI.DROPDOWN_ITEM_CLICKED, $li, $dropdown);

        var form_div = $dropdown.parents('form');

        var dial = $li.data("dial");
        
        //console.log("dial", dial);

        form_div.find('input[name=dial]').val(dial);


    } catch(err) {
        console.error('Error in : RegisterForm22 - drop_down_changed : [' + err +']');
    }
};

RegisterForm22.check_captcha     = function (status){

    try{
    
        var form_div = RegisterForm22.form_div;

        if( status == NEED_CAPATCHA || RegisterForm22.trails > CAPATCHA_TRIALS ){

            RegisterForm22.trails = -1;

            form_div.find('.captcha').html(
                '<img src="'+g_root_url+'captcha.php" />'+
                '<input class="text" name="captcha_text" value="" placeholder="'+CDictionary.get_text('CapatchaText_lbl')+'" type="text">'
            );
        }

        if( status > 0 ){
            form_div.find('.captcha').html('');
        }

    } catch(err) {
        console.error('Error in : RegisterForm22 - check captcha : [' + err +']');
    }
};

RegisterForm22.reset_inputs      = function(){

    try{

        //alert('RegisterForm22.reset_inputs');

        var form_div = RegisterForm22.form_div;

        //form_div.find( 'input[name=username]'         ).val('');
        form_div.find( 'input[name=name]'             ).val('');
        form_div.find( 'input[name=email]'            ).val('');
        form_div.find( 'input[name=password]'         ).val('');
        form_div.find( 'input[name=password_confirm]' ).val('');
        form_div.find( 'input[name=birth_date]'       ).val('');
        form_div.find( 'input[name=country]'          ).val('');

        form_div.find('#pwindicator').removeClass();
        form_div.find('#pwindicator').find('.label').html('');

        form_div.find( '.error'   ).html('');
        form_div.find( '.captcha' ).html('');

        form_div.find( 'span.checkbox'     ).removeClass('checked');
        form_div.find( 'span.radio'        ).removeClass('checked');
        form_div.find( '.drop-down > span' ).html('');

    } catch(err) {
        console.error('Error in : RegisterForm22 - reset inputs : [' + err +']');
    }
};

RegisterForm22.validate_inputs   = function(){

    //console.log("RegisterForm22.validate_inputs");

    var outputArray = {};

    try{

        var valid    = false;
        var errors   = 0;
        var messages = '';

        var parent_form = RegisterForm22.form_div;
        
        var error_div = parent_form.find('.error');

        parent_form.find("*").removeClass("required");

        //alert('name        : ' + name        );
        //alert('email       : ' + email       );
        //alert('text        : ' + text        );
        //alert('captcha_text : ' + captcha_text );

        var element = null;

        //element = parent_form.find('input[name=username]');
        
        //if( Validate.empty( element.val() ) ){
        ////if( Validate.empty( element.val() ) || ! Validate.numbers( element.val() ) ){
        //    element.addClass("required");
        //    errors++;
        //}

        element = parent_form.find('input[name=name]');
        
        if( Validate.empty( element.val() ) ){
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
        
        if( Validate.empty( element.val() ) ){
            element.addClass("required");
            errors++;
        }
        
        var element1 = parent_form.find('input[name=password]');
        var element2 = parent_form.find('input[name=password_confirm]');

        if( element1.val() != element2.val() ){
            error_div.html( CDictionary.get_text('RegisterForm22_Validate_PasswordNotMatch_lbl') ).show();
            element1.addClass("required");
            element2.addClass("required");
            errors++;
        }
        
        var pwindicator = parent_form.find('#pwindicator');

        if( pwindicator.attr('class') == 'pw-very-weak' ){
            error_div.html( CDictionary.get_text('RegisterForm22_Validate_PasswordVeryWeak_lbl') ).show();
            element1.addClass("required");
            element2.addClass("required");
            errors++;
        }

        element = parent_form.find('input[name=accept]');
        
        if( Utils.get_int( element.val() ) <= 0 ){
            element.parent().addClass("required");
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
        console.error('Error in : RegisterForm22 - validate inputs : [' + err +']');
    }

    return outputArray;
};

RegisterForm22.callback          = function(outputArray){

    try{

        var main_div = RegisterForm22.form_div;

        var status = Utils.get_int( outputArray.status );

        $('#loading_form').hide();

        RegisterForm22.reset_inputs();

        RegisterForm22.check_captcha(status);

        var title   = '';
        var message = '';
        var type = '';
        
        var html = '';

        if( status > 0 ){

            title   = CDictionary.get_text('RegisterCallback_Success_Title_lbl');
            message = CDictionary.get_text('RegisterCallback_Success_Message_lbl');
            type = 'success';

        }else{

            title   = CDictionary.get_text('RegisterCallback_Failure_Title_lbl');
            message = CDictionary.get_text('RegisterCallback_Failure_Message_lbl');
            type = 'error';
        }

        //alert('Success : '+message);

        //title   = ( title   == null ) ? "" : title;
        //message = ( message == null ) ? "" : message;

        swal({
          title: title,
          text:  message+",\r\n\r\n  It will close in 7.5 seconds.",
          timer: 7500,
          showConfirmButton: false,
          allowEscapeKey: true,
          type: type
        });
        
        var output = '';
        
        if( status > 0 ){
            output += '<div class="alert-success" style="display: inline-block;">' + 
                '<i class="fa fa-check-circle-o" aria-hidden="true"></i> ' + 
                CDictionary.get_text("RegisterCallback_Success_Title_lbl") +
                '<br />' + 
                CDictionary.get_text("RegisterCallback_Success_Message_lbl") +
            '</div>';
        } else {
            output += '<div class="alert-error">' + 
                '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' + 
                CDictionary.get_text("RegisterCallback_Failure_Title_lbl") +
                '<br />' + 
                CDictionary.get_text("RegisterCallback_Failure_Message_lbl") +
            '</div>';
        }
        
        main_div.replaceWith( output );
    
    } catch(err) {
        console.error('Error in : RegisterForm22 - callback : [' + err +']');
    }

};
