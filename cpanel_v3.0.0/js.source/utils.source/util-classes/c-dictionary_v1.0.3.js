/*! CDictionary */

/* global RequestUtil */

function CDictionary() {
}

CDictionary.DICTIONARY_LOADED = "dictionary-loaded";

CDictionary.init      = function () {

    try{

        this.context = this;

        this.array   = [];
        this.loaded  = false;

    } catch(err) {
        throw 'CDictionary - Error in : init : [' + err +']';
    }
};

CDictionary.set_lang  = function (lang) {
    this.lang = lang;
};

CDictionary.load      = function(){

    try {

        var __context = this;

        var data = "action=dictionary";

        RequestUtil.quick_ajax_request(null, data, function (outputArray){

            try{

                __context.array  = outputArray.dictionary;

                __context.loaded = true;

                //console.log('CDictionary.DICTIONARY_LOADED inside         : '+CDictionary.DICTIONARY_LOADED);
                //console.log('__context.DICTIONARY_LOADED inside           : '+__context.DICTIONARY_LOADED);
                //console.log('CDictionary.context.DICTIONARY_LOADED inside : '+CDictionary.context.DICTIONARY_LOADED);

                $('body').trigger(__context.DICTIONARY_LOADED, [ outputArray.dictionary ]);

            } catch (err) {
                //because it's inside function
                console.error('CDictionary - Error in : parse ajax request :[' + err + ']');
                throw 'CDictionary - Error in : parse ajax request :[' + err + ']';
            }

        });

    } catch (err) {
        throw 'CDictionary - Error in : load :[' + err + ']';
    }

};

CDictionary.is_loaded = function () {
    return this.loaded;
};

CDictionary.get_text  = function(name){

    try{

        var text = this.array[this.lang][name];

        if( text == null){
            return "";
        }else{
            return text;
        }

    } catch (err) {
        throw 'CDictionary - Error in : get text :[' + err + ']';
    }
};

CDictionary.get_array = function(name){

    try{

        var array = this.array[this.lang][name];

        if( array == null ){
            return [];
        }else{
            return array;
        }

    } catch (err) {
        throw 'CDictionary - Error in : get array :[' + err + ']';
    }
};


CDictionary.get_labels = function (labels){

    var new_labels = [];

    try{

        for(var i=0; i<labels.length; i++){
            new_labels.push( CDictionary.get_text(labels[i]) );
        }

    } catch(err) {
        throw 'Error in : CDictionary - get labels : [' + err +']';
    }
    
    return new_labels;
};

CDictionary.get_text_by_lang = function(object, varname, camel_case) {

    var text   = '';

    try{

        var newvar = '';

        camel_case = ( camel_case != null && camel_case != undefined ) ? camel_case : false;

        if( camel_case ){
            newvar = varname + Utils.capitalize(lang);
        }else{   
            newvar = varname+'_'+lang;
        }

        text = object[newvar];

    } catch (err) {
        throw 'CDictionary - Error in : get array :[' + err + ']';
    }

    return text;
};

//############################################################################//
//
//$(document).ready(function(){
//
//    alert('CDictionary load...');
//
//    try{
//
//        console.log('CDictionary.DICTIONARY_LOADED : '+CDictionary.DICTIONARY_LOADED);
//
//        $('body').bind(CDictionary.DICTIONARY_LOADED, check_words);
//        
//
//        CDictionary.init();
//        CDictionary.load();
//        CDictionary.set_lang(lang);
//
//
//    } catch (err) {
//        throw 'CDictionary - Error in : main controls :[' + err + ']';
//    }
//
//});
//
//function check_words(){
//
//    alert( CDictionary.loaded );
//    
//    alert( CDictionary.is_loaded() );
//    console.log( CDictionary.array );
//    console.log( CDictionary.web_service_url );
//    console.log( CDictionary.request_url );
//    console.log( CDictionary.context );
//    console.log( CDictionary.context.loaded );
//    console.log( CDictionary.context.web_service_url );
//
//    alert('CDictionary loaded');
//    alert( CDictionary.get_text('Home_lbl') );
//
//}
