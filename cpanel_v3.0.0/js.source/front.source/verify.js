
/*! verify */

/* global CDictionary, USER_ALREADY_EXIST */

$(document).ready(function(){

    try{

        var input_div = $('body').find('input[name=register_verify][value=1]' );

        if( input_div.length > 0 ){

            window.setTimeout(function(){ 
                RegisterVerify.show_result();
            }, 1000);

            //ResetForm.show_form();
        }


    } catch(err) {
        console.error('Error in : ResetForm - document - ready : [' + err +']');
    }

});

function RegisterVerify(){}

RegisterVerify.show_result = function(){

    try{
        
        var title, message, type, status = 0;

        if( status > 0 ){

            title   = CDictionary.get_text('RegisterVerifyCallback_Success_Title_lbl');
            message = CDictionary.get_text('RegisterVerifyCallback_Success_Message_lbl');
            type = 'success';

        }else{

            title   = CDictionary.get_text('RegisterVerifyCallback_Failed_Title_lbl');
            message = CDictionary.get_text('RegisterVerifyCallback_Failed_Message_lbl');
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
        console.error('Error in : RegisterVerify - show result : [' + err +']');
    }
};