/*
 *
 */

$(document).ready(function(){
    //alert('contact us js loaded');

    prepare_contact_form();

    //_check_page_contact();

});

function _check_page_contact(){
    
    var page = get_variable("page");
    
    if( page == "contact" ){
        prepare_contact_form();
    }
}
function prepare_contact_form(){

    //alert('add contact us form');
    var parentDiv = $("#ContactForm" );

    parentDiv.find( "#contact_form" ).attr( "action", g_root_url+"ajax.php?page=contact_action&action=0" );
    //alert( parentDiv.html() );

    parentDiv.find( "#contact_form" ).find('input[name=button]').click(function(){

        var outputArray = _check_contact_form_inputs();

        //alert( 'outputArray.errors : ' + outputArray.errors );
        //alert( parentDiv.html() );

        if( outputArray.errors <= 0 ){
            
            parentDiv.find( "#contact_form" ).submit();

        }else{
            var title = get_dictionary_text('ContactUsFormCheck_Failure_Title_lbl');

            _show_contact_form_errors(title, outputArray.messages);
        }

    });

    $(function () {
        parentDiv.find( "#contact_form" ).iframePostForm({
            post : function () {
                //alert("sending request");
            },
            complete : function (response) {
                //alert("complete request");
                var jsonOutput = $.evalJSON( response );
                _contact_form_callback(jsonOutput);
            }
        });
    });
}
function _reset_contact_form_inputs(){

    //alert('_reset_contact_form_inputs');

    var parentDiv = $("#contact_form" );
    
    parentDiv.find( 'input[name=name]'     ).val('');
    parentDiv.find( 'input[name=email]'    ).val('');
    parentDiv.find( 'textarea[name=text]'  ).val('');
    parentDiv.find( 'input[name=captcha_text]' ).val('');

}
function _check_contact_form_inputs(){

    //console.log("_check_contact_form_inputs");

    var outputArray = [];

    var valid    = false;
    var errors   = 0;
    var messages = '';

    var parentForm = $("#contact_form" );

    var name    = parentForm.find('input[name=name]'   ).val();
    var email   = parentForm.find('input[name=email]'  ).val();
    var text    = parentForm.find('textarea[name=text]').val();
    var captcha_text = parentForm.find('input[name=captcha_text]').val();

    //alert('name        : ' + name        );
    //alert('email       : ' + email       );
    //alert('text        : ' + text        );
    //alert('captcha_text : ' + captcha_text );

    valid     = validate_required( name );
    messages += (!valid) ? get_dictionary_text('ContactUsFormCheck_Name_Required_lbl')+'<br />' : '';
    errors    = (!valid) ? ++errors : errors;

    valid     = validate_required( email );
    messages += (!valid) ? get_dictionary_text('ContactUsFormCheck_Email_Required_lbl')+'<br />' : '';
    errors    = (!valid) ? ++errors : errors;   
    
    if( valid ){
        valid     = validate_email( email );
        messages += (!valid) ? get_dictionary_text('ContactUsFormCheck_Email_Incorrect_lbl')+'<br />' : '';
        errors    = (!valid) ? ++errors : errors;
    }

    valid     = validate_required( text );
    messages += (!valid) ? get_dictionary_text('ContactUsFormCheck_Text_Required_lbl')+'<br />' : '';
    errors    = (!valid) ? ++errors : errors;

    valid     = validate_required( captcha_text );
    messages += (!valid) ? get_dictionary_text('ContactUsFormCheck_Captcha_Required_lbl')+'<br />' : '';
    errors    = (!valid) ? ++errors : errors;

    //alert('valid:    ' + valid    );
    //alert('erros:    ' + errors   );
    //alert('messages: ' + messages );

    outputArray.valid    = valid;
    outputArray.errors   = errors;
    outputArray.messages = messages;
    outputArray.valid    = (errors<=0);

    return outputArray;
}
function _contact_form_callback(outputArray){

    //alert( 'outputArray.status : '+outputArray.status );
    var status = get_int( outputArray.status );

    $('#loading_form').hide();

    _reset_contact_form_inputs();

    var title   = '';
    var message = '';

    if( status > 0 ){
        title   = get_dictionary_text('ContactUsCallback_Success_Title_lbl');
        message = get_dictionary_text('ContactUsCallback_Success_Message_lbl');
        _show_contact_form_errors(title, message);
    }else{
        title   = get_dictionary_text('ContactUsCallback_Failure_Title_lbl');
        message = get_dictionary_text('ContactUsCallback_Failure_Message_lbl');
        _show_contact_form_errors(title, message);
    }

    //output.status;
    //output.messages;
    //output.warnings;
    //output.errors

}

function _show_contact_form_errors(title, message){
    
    display_popup(message, title);
}
