
/*! extra utils */
/* Based on: [ CDictionary, Cover, CPopup, RequestUtil ] */
/* global CDictionary, Cover, CPopup, RequestUtil */


function CMSExtraUtil(){}

CMSExtraUtil.sleep_for       = function( sleepDuration ){
    var now = new Date().getTime();
    while( new Date().getTime() < now + sleepDuration ){ /* do nothing */ }
};

CMSExtraUtil.print_div_popup = function(data, title, width, height) {

    try{

        title = ( title == null ) ? "" : title ;
        data  = ( data  == null ) ? "" : data  ;

        width  = ( width  == null ) ? 600 : width  ;
        height = ( height == null ) ? 400 : height ;

        var mywindow = window.open('', ''+title+'', 'height='+height+',width='+width+'');

        mywindow.document.write( '<html>'
                                     + '<head>'
                                         + '<title>'+title+'</title>'
                                         + '<link rel="stylesheet" href="'+g_root_url+'css/print.css" type="text/css" />'
                                     + '</head>'
                                     + '<body>' + data +'</body>'
                                + '</html>' );

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

    } catch(err) {
        throw 'Error in : CMSUtil - print div popup : [' + err +']';
    }

    return true;
};

CMSExtraUtil.show_popup      = function(message){

    try{
        //alert(message);
        CPopup.display(message);

    } catch(err) {
        throw 'Error in : CMSUtil - show popup : [' + err +']';
    }
};

CMSExtraUtil.show_success    = function(title, message){

    try{
        //alert('Success : '+message);

        title   = ( title   == null ) ? "" : title;
        message = ( message == null ) ? "" : message;

        swal({
          title: title,
          text:  message+" It will close in 1.5 seconds.",
          timer: 1500,
          showConfirmButton: false,
          allowEscapeKey: true,
          type: 'success'
        });

    } catch(err) {
        throw 'Error in : CMSUtil - show success : [' + err +']';
    }
};

CMSExtraUtil.show_error      = function(title, message){

    try{
        //alert('Error : '+message);

        title   = ( title   == null ) ? "" : title;
        message = ( message == null ) ? "" : message;

        swal({
          title: title,
          text:  message+" It will close in 10 seconds.",
          timer: 10000,
          showConfirmButton: false,
          allowEscapeKey: true,
          type: 'error'
        });

    } catch(err) {
        throw 'Error in : CMSUtil - show error : [' + err +']';
    }
};

CMSExtraUtil.delete_popup    = function(delete_func, title, message){

    title   = ( title   == null ) ? "Confirm Delete ?"                  : title;
    message = ( message == null ) ? "Are you sure you want to delete ?" : message;

    try{
        swal({
          title: title,  //"Confirm Delete ?",
          text:  message, //"Are you sure you want to delete ?",
          type:  "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: true
        }, delete_func );

    } catch(err) {
        throw 'Error in : CMSUtil - delete popup : [' + err +']';
    }
};


CMSExtraUtil.get_error_div   = function(text){

    var error_html = '<div class="error floated">'+text+'</div>';

    return error_html;
};


CMSExtraUtil.get_parameter_by_name = function(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};



CMSExtraUtil.show_list    = function(class_param){
    
    try{

        var list_div = class_param.list_div;
        var path_div = class_param.path_div;
        var form_div = class_param.form_div;

        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        form_div.html('');
        list_div.html('');

        if( path_div != undefined && path_div != null ){
            $(path_div).fadeIn(1000);
            path_div.html('');
        }

    } catch (err) {
        console.error('Error in : CMSExtraUtil - show list :[' + err + ']');
    }
};

CMSExtraUtil.show_form    = function(class_param){

    try{        

        var list_div = class_param.list_div;
        var path_div = class_param.path_div;
        var form_div = class_param.form_div;

        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);

        form_div.html('');
        list_div.html('');

        if( path_div != undefined && path_div != null ){
            $(path_div).fadeOut(1000);
            path_div.html('');
        }

    } catch (err) {
        console.error('Error in : CMSExtraUtil - show form :[' + err + ']');
    }
};


//function number_pad(n,w,c){c=c||'0';n=n+'';return n.length >= w ? n : new Array(w - n.length + 1).join(c) + n;}

$.extend({
  password: function (length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
  }
});

function number_pad(number, width, char) {
  char   = char || '0';
  number = number + '';
  number = number.length >= width ? number : new Array(width - number.length + 1).join(char) + number;
  return number;
}

////////////////////////////
//// Create jQuery plugin //
////////////////////////////
//(function ( $ ) {
//
//    $.fn.greenify = function() {
//        this.css( "color", "green" );
//        return this;
//    };
//
//    $.fn.validate_required = function(message) {
//
//        if( this.val() == null || this.val() == "" ){
//            //write your message after element
//        }
//
//        return this;
//    };
//
//}( jQuery ));
