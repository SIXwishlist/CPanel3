
/*! CPopup */

/*#############################################################################*/


function CPopup() {
    this.context = this;
    this.div_loaded = false;
}

//CPopup.POPUP_SHOWN = "popup_shown";

CPopup.set_options = function (options) {
    this.style     = options.style;
    this.theme     = options.theme;
    this.next_func = options.next;
    this.prev_func = options.prev;
};


CPopup.init        = function () {

    try{

        this.div_html = this.get_div_tpl();
        
        if( this.div_html != null && this.div_html != undefined && this.div_html != "" ){
            this.div_loaded = true;
        }

    } catch(err) {
        throw 'CPopup - Error in - init : [' + err +']';
    }
};

CPopup.get_div_tpl = function () {

    var div_html = '';

    try{

        div_html = $('body > #hidden').find('.c_popup_tpl').html();

    } catch(err) {
        throw 'CPopup - Error in - ajax get sync request : [' + err +']';
    }

    return div_html;
};


CPopup.display     = function( content, title, theme, next_func, prev_func, options ){

    //alert( 'popup' );

    try{
        
        if( !this.div_loaded ){
            this.init();
        }

        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");

        //alert( 'popup.length : ' + popup.length );

        if( popup.length <= 0 ){

            var close_func = this.close;

            $('body').append('<div id="overlay"></div>');

            popup = $('body').find("#overlay");
            
            popup.append( this.div_html );


            popup.addClass("active");
            
            

            popup.find("#close").click( close_func );

            if( title != null ){
                popup.find("#title").html( title );
            }

            popup.find("#content").html( content );
            
            popup.find("#next").hide();
            popup.find("#prev").hide();

            if( options != null ){
                this.theme     = options.theme;
                this.next_func = options.next;
                this.prev_func = options.prev;               
            }

            if( theme != null ){
                popup.addClass( theme );
            }else{
                popup.addClass( 'default' );
            }

            if( next_func != null ){
                popup.find("#next").show();
                popup.find("#next").click( next_func );
            }
            if( prev_func != null ){
                popup.find("#prev").show();
                popup.find("#prev").click( prev_func );
            }


            var docHeight = $(document).height();
            popup.css("height", docHeight);

            $('html, body').animate({scrollTop:0}, 'slow');

            //$('body').trigger(CPopup.POPUP_SHOWN);

        }else{

            if( title != null ){
                popup.find("#title").html( title );
            }

            popup.find("#content").html( content );

            $('html, body').animate({scrollTop:0}, 'slow');

        }


    }catch(err){
        console.error('Error in : popup - display :['+err+']');
    }

};

CPopup.close       = function(){

    try{

        //var popup = $('body').find("#overlay.active");
        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");
        //alert( popup.html() );

        popup.hide().remove();

    }catch(err){
        console.error('Error in : popup - close :['+err+']');
    }

};

CPopup.get_object  = function(){

    var popup = null;

    try{

        //var popup = $('body').find("#overlay.active");
        //var popup = $('body').find("#overlay").hasClass("active");
        var popup = $('body').find("#overlay[class*='active']");
        //alert( popup.html() );

    }catch(err){
        console.error('Error in : popup - get object :['+err+']');
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