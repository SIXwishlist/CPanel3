
/*! PrintUtil */
/* Based on: [...] */
/* global CDictionary, Cover, CPopup */

function PrintUtil() {
    this.myvar1    = false;
    this.css_files = [];
}

PrintUtil.css_files = [];

PrintUtil.add_css_file = function (css_file){
    
    try{

        this.css_files.push(css_file);
    
    } catch (err) {
        throw 'PrintUtil - print_content :[' + err + ']';
    }
};

PrintUtil.print_content = function (content){
    
    try{

        console.log('print content');

        //var iframe = $('body').find('iframe[id=print-iframe]');
        //var iframe = $('body').find("#print-iframe");
        var iframe = $('body').find('iframe#print-iframe');

        if( iframe.length == 0 ){
            $('body').append('<iframe id="print-iframe" name="print-iframe" style="display:none"></iframe>');
            iframe = $('body').find('iframe#print-iframe');
        }else{
            iframe.contents().find("head").html('');
            iframe.contents().find("body").html('');
        }

        for(var i=0; i<this.css_files.length; i++ ){

            var css_file = this.css_files[i];

            iframe.contents().find('head').append('<link href="'+css_file+'" rel="stylesheet" type="text/css" />');
        }

        iframe.contents().find('body').append(content);
        //iframe.contents().find('body').append('<body onload="window.print();">'+content+'</body>');

        iframe.contents().find('head').append("<script type='text/javascript'>console.log('start printing');</script>");
        iframe.contents().find('head').append("<script type='text/javascript'>;window.print();</script>");
        iframe.contents().find('head').append("<script type='text/javascript'>console.log('end printing');</script>");

        //iframe.get(0).contentWindow.focus();
        //iframe.get(0).contentWindow.print();

        //iframe.contents().focus();
        //iframe.contents().print();

        console.log('done');

    } catch (err) {
        throw 'PrintUtil - print_content :[' + err + ']';
    }

    return false;
};

//////////////////////////////////////////////////////////////////////////////////
//
//function print_content_dom(content){
//
//    console.log('print_content');
//
//    //var iframe = $('body').find('iframe[id=print-iframe]');
//    //var iframe = $('body').find("#print-iframe");
//
//    var iframe = $('body').find('iframe#print-iframe');
//    //var iframe = document.getElementById("print-iframe");
//
//    iframe.html("<!DOCTYPE html><html><head></head><body></body></html>");
//
//    iframe.find('head').append("<script type='text/javascript'>console.log('loaded');</script>");
//
//    iframe.find('head').append('<link href="/css/print.css" rel="stylesheet" type="text/css">');
//
//    iframe.find('body').append('<body onload="window.print()">'+content+'</body>');
//
//
//    //iframe.get(0).contentWindow.print();
//
//    //var iframe = document.getElementById("print-iframe");
//    //iframe.contentWindow.print();
//
//    var iframeElem = window.frames["print-iframe"];
//    iframeElem.document.write('<head><link href="/css/print.css" rel="stylesheet" type="text/css"></head>');
//    //iframeElem.document.write('<body onload="window.print()">'+content+'</body>');
//    iframeElem.document.write('<body>'+content+'</body>');
//    iframeElem.document.close();
//    iframeElem.focus();
//    iframeElem.print();
//
////    iframe.html('<html><head></head><body></body></html>');
////
////    $('head').append("<script type='text/javascript'>console.log('loaded');</script>");
////    
////    iframe.find('head').append("<script type='text/javascript'>console.log('loaded');</script>");
////    
////    iframe.find('head').html('<link href="/css/print.css" rel="stylesheet" type="text/css">');
////
////    iframe.find('body').html('<body onload="window.print()">'+content+'</body>');
//
//    console.log('done');
//
////    $(newWin).find('head').append('<link href="/css/print.css" rel="stylesheet" type="text/css">');
////    
//////    var fileref = newWin.document.createElement("link");
//////    fileref.setAttribute("rel",  "stylesheet");
//////    fileref.setAttribute("type", "text/css");
//////    fileref.setAttribute("href", g_root_url+'css/print.css');
//////    Make DOM element like so:
//////    var link = newWin.document.createElement('link');
//////    link.type = 'text/css';
//////    link.rel  = 'stylesheet';
//////    link.href = g_root_url+'css/print.css';
////
//////    document.getElementsByTagName('head')[0].appendChild('<link href="/css/print.css" rel="stylesheet" type="text/css">');
//////    document.getElementsByTagName('head')[0].appendChild(link);
////
////    var h = document.getElementsByTagName('head').item(0);
////
////    //h.innerHTML += '<style>a{font-size:100px;}</style>';
////    h.innerHTML += '<link href="/css/print.css" rel="stylesheet" type="text/css">';
////
//
//
//
//    console.log('done');
//
////    var frm = document.getElementById('#print-iframe').contentWindow;
////    frm.focus();// focus on contentWindow is needed on some ie versions
////    frm.print();
////    return false;
//}
//
//////////////////////////////////////////////////////////////////////////////////
//
//function print_content_jquery(content){
//
//    console.log('print_content_jquery');
//
//    //var iframe = $('body').find('iframe[id=print-iframe]');
//    //var iframe = $('body').find("#print-iframe");
//    var iframe = $('body').find('iframe#print-iframe');
//    
//    if( iframe.length == 0 ){
//        $('body').append('<iframe id="print-iframe" name="print-iframe" style="display:none"></iframe>');
//        iframe = $('body').find('iframe#print-iframe');
//    }else{
//        iframe.contents().find("head").html('');
//        iframe.contents().find("body").html('');
//    }
//
//    iframe.contents().find('head').append('<link href="/css/print.css" rel="stylesheet" type="text/css">');
//
//    iframe.contents().find('body').append('<body>'+content+'</body>');
//    //iframe.contents().find('body').append('<body onload="window.print();">'+content+'</body>');
//
//    iframe.contents().find('head').append("<script type='text/javascript'>console.log('start printing');</script>");
//    iframe.contents().find('head').append("<script type='text/javascript'>window.print();</script>");
//    iframe.contents().find('head').append("<script type='text/javascript'>console.log('end printing');</script>");
//
//    //iframe.get(0).contentWindow.focus();
//    //iframe.get(0).contentWindow.print();
//
//    //iframe.contents().focus();
//    //iframe.contents().print();
//
//    console.log('done');
//
//    return false;
//}
//
//////////////////////////////////////////////////////////////////////////////////
