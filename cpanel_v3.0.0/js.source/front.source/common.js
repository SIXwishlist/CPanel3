
////////////////////////////////////////////////////////////////////////////////
/// common ///
////////////////////////////////////////////////////////////////////////////////

$(document).ready(function(){
    
});

function reloadCaptcha(){
    var captchaImg = $("img[id=captcha]");
    var seqId = Math.floor(Math.random()*1000);
    captchaImg.replaceWith('<img id="captcha" src="'+g_root_url+'captcha.php?seqId='+seqId+'" alt="captcha text" />');
}

function showNotePopup(title, message){

    var popup = null;

    display_popup(message, title);

    popup = get_popup_object();
    
    return popup;
}
    
function getShareLinks(url){
    
    var socialNetworkIcons = '';
    socialNetworkIcons += '<br /><br />';   

    var encUrl = escape( url );

    socialNetworkIcons += '<a target="_blank" href="http://www.facebook.com/sharer.php?u='+encUrl+'">' +
                              '<img   width="80" src="images/social_network/facebook_button.png" />' +
                          '</a>';

    socialNetworkIcons += ' &nbsp; ';

    socialNetworkIcons += '<a target="_blank" href="http://www.twitter.com/share?u='+encUrl+'">' +
                              '<img   width="80" src="images/social_network/twitter_button.png">' +
                          '</a>';

    socialNetworkIcons += ' &nbsp; ';

    socialNetworkIcons += '<a class="copy_link" href="#copy">' +
                              '<img class="copy_link"   width="32" height="32" src="images/social_network/hyperlink_button.png" />' +
                          '</a>';

    socialNetworkIcons += ' &nbsp; ';

    socialNetworkIcons += '<a target="_blank" href="mailto:?subject=check this link&body='+encUrl+'">' +
                              '<img   width="32" height="32" src="images/social_network/send.png" />' +
                          '</a>';

    return socialNetworkIcons;

}
    
function getDownloadIcon(url){
    
    var downloadIcons = '';

    downloadIcons += '<a target="_blank" href="'+url+'">' +
                          '<img   width="32" height="32" src="images/social_network/box_download.png" />' +
                      '</a>';

    return downloadIcons;

}

function getLikeButton(url){

    var output = '';

    var encodedUrl = escape( url );
    
    //for ex. :
    //http://Fwww.facebook.com/history.info
    //<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fhistory.info&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80" scrolling="no"  style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>
    
    output += '<br />'
        + '<iframe src="//www.facebook.com/plugins/like.php?'+encodedUrl+'&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80" scrolling="no"  style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>';
    
    //href=http%3A%2F%2Fwww.facebook.com%2Fahmad.alselwadi
    //http://www.facebook.com/plugins/like.php?'+encodedUrl+'&send=false&layout=standard&width=450&show_faces=true&action=like&colorscheme=light&font&height=80
    
    //<div id="fb-root"></div>
    //<script>(function(d, s, id) {
    //  var js, fjs = d.getElementsByTagName(s)[0];
    //  if (d.getElementById(id)) return;
    //  js = d.createElement(s); js.id = id;
    //  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    //  fjs.parentNode.insertBefore(js, fjs);
    //}(document, 'script', 'facebook-jssdk'));</script>
    
    //<div class="fb-like" data-href="http://www.facebook.com/ahmad.alselwadi" data-send="false" data-width="450" data-show-faces="true"></div>
    
    return output;
}

function activateCopyToClipboard(div, text){
    //alert('@1');
    //alert(div.html());
    div.zclip({
        path: './swf/zero_clipboard/ZeroClipboard.swf',
        copy: text
    });
    //alert('@2');
}

function getPrintDiv( printObject ){

    var printDiv = $('<a></a>');//var printDiv = document.createElement("a");

    printDiv.attr("href", "#print");
    printDiv.html( ' &nbsp; <img   width="32" height="32" src="images/social_network/print.png" />' );

    printDiv.click( function() {
        printPopup( printObject );
    });

    //var printIcon = '';
    //printIcon += ' &nbsp; ';
    //printIcon += '<a href="#print" onclick="printPopup('+printObject+'); return;">' +
    //                 '<img   width="32" height="32" src="images/social_network/print.png" />' +
    //             '</a>';

    return printDiv;
}

function printPopup( printObject ){
    
    //alert( 'printPopup' );
    
    //alert( printObject["title"] );
    //alert( printObject["image"] );
    //alert( printObject["text"]  );
    
    //alert( printObject.title );
    //alert( printObject.image );
    //alert( printObject.text  );

    var title = printObject.title;
    var image = printObject.image;
    var text  = printObject.text;

    var newwindow = window.open('',title+'','');

    var tmp = newwindow.document;

    tmp.write('<html>');
        tmp.write('<head>');
            tmp.write('<title>'+title+'</title>');
        tmp.write('</head>');
        tmp.write('<body>');
            tmp.write('<center>');
                tmp.write('<b>'+title+'</b>');
                tmp.write('<br />');
                if( image != null && image != "" ){
                    tmp.write('<p>'+image+'</p>');
                    tmp.write('<br />');
                }
                tmp.write('<p>'+text+'</p>');
                tmp.write('<br /><br />');
                //tmp.write('<a href="javascript:window.print()">print</a>');
                tmp.write('<a href="javascript:window.print()">'+get_dictionary_text('Print_lbl')+'</a>');
                tmp.write('<br />');
            tmp.write('</center>');
        tmp.write('</body>');
    tmp.write('</html>');

    tmp.close();

    if (window.focus) {newwindow.focus()}

    return false;
}

