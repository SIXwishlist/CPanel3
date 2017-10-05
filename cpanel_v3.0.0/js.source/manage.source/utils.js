
/*! utils */


/* global CDictionary, REQUEST_NOT_FOUND, CAPATCHA_REQUIRED, CAPATCHA_INCORRECT, SERVER_ERROR, USER_NOT_EXIST, ACCOUNT_EXPIRED, g_base_url, CForm */


function ErrorUtil(){}

ErrorUtil.get_common_messages = function(status){
    
    var error_object = {};

    var title   = '';
    var message = '';
    
    try{    
        switch ( status ){

            case REQUEST_NOT_FOUND:
                title   = CDictionary.get_text('CommonError_RequestNotFound_Title_lbl');
                message = CDictionary.get_text('CommonError_RequestNotFound_Message_lbl');
                break;

            case CAPATCHA_REQUIRED:
                title   = CDictionary.get_text('CommonError_CapatchaRequired_Title_lbl');
                message = CDictionary.get_text('CommonError_CapatchaRequired_Message_lbl');
                break;

            case CAPATCHA_INCORRECT:
                title   = CDictionary.get_text('CommonError_CapatchaIncorrect_Title_lbl');
                message = CDictionary.get_text('CommonError_CapatchaIncorrect_Message_lbl');
                break;

            case SERVER_ERROR:
                title   = CDictionary.get_text('CommonError_ServerError_Title_lbl');
                message = CDictionary.get_text('CommonError_ServerError_Message_lbl');
                break;

            default:
                break;

        }

        var type = 'error';
        
        error_object.title   = title;
        error_object.message = message;
        error_object.type    = type;

    } catch (err) {
        console.error('Error in : AdminAuth - callback :[' + err + ']');
    }
    
    return error_object;
};



function TplUtil(){}

TplUtil.get_hidden_div = function(tpl_class_name, apply_form_settings){
    
    var form_div = null;
    
    try{
        
        form_div = $('body').find('#hidden').find('.'+tpl_class_name).clone().removeClass(tpl_class_name);

        if( apply_form_settings ){
            CForm.apply_custom_settings(form_div);
        }

    } catch (err) {
        console.error('Error in : TplUtil - get form div :[' + err + ']');
    }
    
    return form_div;
};



function UrlUtil(){}

UrlUtil.get_home_url   = function(p_lang){

    var href = '';
    
    try{    

        //p_lang = ( p_lang == null ) ? CDictionary.lang : p_lang;

        //href = g_base_url+p_lang;

        href = g_base_url;

    } catch (err) {
        console.error('Error in : UrlUtil - get home url :[' + err + ']');
    }
    
    return href;
};

UrlUtil.get_about_url  = function(p_lang){

    var href = '';
    
    try{

        //p_lang = ( p_lang == null ) ? CDictionary.lang : p_lang;

        href = g_base_url + ( CDictionary.get_text("About_lbl") ).toLowerCase();

    } catch (err) {
        console.error('Error in : UrlUtil - get about url :[' + err + ']');
    }
    
    return href;
};

UrlUtil.get_manage_url = function(p_lang){

    var href = '';
    
    try{

        //p_lang = ( p_lang == null ) ? CDictionary.lang : p_lang;

        href = g_base_url + ( CDictionary.get_text("Manage_lbl") ).toLowerCase();

    } catch (err) {
        console.error('Error in : UrlUtil - get manage url :[' + err + ']');
    }
    
    return href;
};

UrlUtil.get_login_url  = function(p_lang){

    var href = '';
    
    try{

        //p_lang = ( p_lang == null ) ? CDictionary.lang : p_lang;

        href = g_base_url + ( CDictionary.get_text("Login_lbl") ).toLowerCase();

    } catch (err) {
        console.error('Error in : UrlUtil - get login url :[' + err + ']');
    }
    
    return href;
};

UrlUtil.get_signup_url = function(p_lang){

    var href = '';
    
    try{

        //p_lang = ( p_lang == null ) ? CDictionary.lang : p_lang;

        href = g_base_url + ( CDictionary.get_text("SignUp_lbl") ).toLowerCase().replace(/ /g, '-');

    } catch (err) {
        console.error('Error in : UrlUtil - get signup url :[' + err + ']');
    }
    
    return href;
};



