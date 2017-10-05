
/*! ResourceUtil */
/* Based on: [...] */
/* global  */

function ResourceUtil() {}

ResourceUtil.include_js   = function (jsFilePath) {
    
    try{

        $.ajax({
            type: "GET",
            url: jsFilePath,
            dataType: "script"
        });

    } catch(err) {
        throw 'ResourceUtil - Error in - include js : [' + err +']';
    }

    return null;
};

ResourceUtil.include_css  = function (cssFilePath) {
    
    try{

        $('head').append('<link rel="stylesheet" href="'+cssFilePath+'" type="text/css" />');

    } catch(err) {
        throw 'ResourceUtil - Error in - include css : [' + err +']';
    }

    return null;
};

ResourceUtil.get_div_html = function ( divElm ){

    try{

        var htmlOutput = $('<div></div>').append( divElm ).html();

        return htmlOutput;

    } catch(err) {
        throw 'ResourceUtil - Error in - include js : [' + err +']';
    }
};

ResourceUtil.get_div_element = function ( htmlOutput ){

    try{

        var divElm = $( htmlOutput );

        return divElm;

    } catch(err) {
        throw 'ResourceUtil - Error in - include js : [' + err +']';
    }

};

//
//function get_tpl_from(page){
//
//    var tpl = $.ajax({
//          url: "./resource.php?page="+page,
//          global: false,
//          type: "GET",
//          data: null,
//          dataType: "html",
//          async:false,
//          success: function(msg){
//             //alert(msg);
//          }
//       }
//    ).responseText;
//
//    return tpl;
//
//}
//
//function include_js( jsFilePath ){
//
//    $.ajax({
//        type: "GET",
//        url: jsFilePath,
//        dataType: "script"
//    });
//
//}
//
//function get_tpl_html( query ){
//
//    var hiddenDiv = $(".hidden");
//
//    var tplDiv  = hiddenDiv.find( query ).clone();
//
//    var tplHtml = $( '<div></div>' ).append( tplDiv ).html();
//
//    return tplHtml;
//}
//
//function get_tpl_div( query ){
//
//    var hiddenDiv = $(".hidden");
//
//    var tplDiv = hiddenDiv.find( query ).clone();
//
//    return tplDiv;
//}
//
//function get_tpl_div_by_class( className ){
//
//    var hiddenDiv = $(".hidden");
//
//    var tplDiv = hiddenDiv.find("."+className).clone();
//
//    return tplDiv;
//}
//
//function get_tpl_div_by_id( id ){
//
//    var hiddenDiv = $(".hidden");
//
//    var tplDiv = hiddenDiv.find("#"+id).clone();
//
//    return tplDiv;
//}
//
//function get_note_div( title, message ){
//
//    //var content = $( get_tpl_from('note_popup') );
//    var content = $( get_tpl_div('.note_popup_tpl').html() );
//
//    content.find( "#Title" ).html(title);
//    content.find( "#Body"  ).html(message);
//
//    return content;
//}
//
