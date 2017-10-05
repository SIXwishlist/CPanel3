
/*! CPopupOld */

/*#############################################################################*/


function CPopupOld() {
    this.context = this;
}

CPopupOld.C_POPUP_TPL_PATH = g_root_url+"mvc/views/tpl/js/popup/popup.tpl";
//CPopupOld.C_POPUP_TPL_PATH = g_template_url + "?tpl=popup";

CPopupOld.C_POPUP_SHOWN = "popup_shown";

CPopupOld.div_loaded = false;

CPopupOld.set_options = function (options) {
    this.style = options.style;
};


CPopupOld.init = function () {

    try{
        
        if( g_template_url == null || g_template_url == "" ){
            throw 'CPopupOld - Error in - init : [ Please init "g_template_url" constant in config file ]';
        }

        this.load_div_tpl();

    } catch(err) {
        throw 'CPopupOld - Error in - init : [' + err +']';
    }
};

CPopupOld.load_div_code = function () {

    this.div_html = '<div id="popup" class="clearfix">' + 

                '<div id="title-bar" class="clearfix">' + 
                    '<div id="title"></div>' + 
                    '<div id="close"></div>' + 
                '</div>' + 

                '<div id="content">' + 
                '</div>' + 

            '</div>';

    this.div_loaded = true;
};

CPopupOld.load_div_tpl  = function () {

    try{

        var tpl_path = g_template_url + "?tpl=popup";

        this.div_html = $.ajax({
              url:      tpl_path,
              global:   false,
              type:     "GET",
              data:     null,
              dataType: "html",
              async:    false,
              success: function(msg){
                //alert(msg);
              }
           }
        ).responseText;

    } catch(err) {
        throw 'CPopupOld - Error in - ajax get sync request : [' + err +']';
    }
    
    this.div_loaded = true;
};


CPopupOld.display     = function( content, title ){

    //alert( 'popup' );

    try{
        
        if( !this.div_loaded ){
            this.init();
        }

        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");

        //alert( 'popup.length : ' + popup.length );

        if( popup.length <= 0 ){

            var style_prop = this.style;
            var close_func = this.close;

            $('body').append('<div id="overlay"></div>');

            popup = $('body').find("#overlay");
            
            popup.append( this.div_html );

            //popup.load(CPopupOld.C_POPUP_TPL_PATH, null, function(){

                popup.addClass("active");
                popup.addClass(style_prop);

                popup.find("#close").click( close_func );

                if( title != null ){
                    popup.find("#title").html( title );
                }

                popup.find("#content").html( content );


                var docHeight = $(document).height();
                popup.css("height", docHeight);

                $('html, body').animate({scrollTop:0}, 'slow');

                //$('body').trigger(CPopupOld.POPUP_SHOWN);

            //});

        }else{

            if( title != null ){
                popup.find("#title").html( title );
            }

            popup.find("#content").html( content );

            $('html, body').animate({scrollTop:0}, 'slow');

        }


    }catch(err){
        console.log('error in - popup - display :['+err+']');
    }

};

CPopupOld.close       = function(){

    try{

        //var popup = $('body').find("#overlay.active");
        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");
        //alert( popup.html() );

        popup.hide().remove();

    }catch(err){
        console.log('error in - popup - close :['+err+']');
    }

};

CPopupOld.get_object  = function(){

    var popup = null;

    try{

        //var popup = $('body').find("#overlay.active");
        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");
        //alert( popup.html() );

    }catch(err){
        console.log('error in - popup - get object :['+err+']');
    }

    return popup;
};




//$(document).ready(function() {
//    
//    //alert('done');
//    
//    var popup = new Popup( { "style" : "red_style" } );
//    
//    popup.display("content ", "title");
//    //alert('done');
//    //popup.close();
//    
//    //popup.display("content ", "CertVeri System");
//    
//    
//    $('body').append('<div id="test">Click Here</div>');
//    
//    $('body').find("#test").click(function(){
//        
//        popup.display("content ", "CertVeri System");
//        
//    });
//    
//});