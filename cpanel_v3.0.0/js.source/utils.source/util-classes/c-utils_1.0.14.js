
/*! Utils_1.0.14.js */

function Utils() {
}

////////////////////////////////////////////////////////////////////////////////

Utils.set_cookie = function(cname, cvalue, lifespan_in_days, valid_domain) {

    try{
        //var d = new Date();
        //d.setTime(d.getTime() + (exdays*24*60*60*1000));
        //var expires = "expires="+d.toUTCString();

        var domain_string = valid_domain ? ("; domain=" + valid_domain) : '' ;

        document.cookie = cname + "=" + cvalue+ "; " +
             "; max-age=" + 60 * 60 * 24 * lifespan_in_days +
             "; path=/" + domain_string ;

        //encodeURIComponent

    } catch(err) {
        console.error('Utils - Error in - set cookie : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////

Utils.get_cookie = function(cname) {
    
    try{

        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }

    } catch(err) {
        console.error('Utils - Error in - get cookie : [' + err +']');
    }

    return "";
    
    //decodeURIComponent
};

////////////////////////////////////////////////////////////////////////////////

Utils.img_error = function(image, default_path) {

    try{

        image.onerror = "";
        //image.src = "/images/noimage.gif";
        image.src = default_path;

    } catch(err) {
        console.error('Utils - Error in - img error : [' + err +']');
    }

    return true;
};

////////////////////////////////////////////////////////////////////////////////

Utils.apply_auto_complete = function(input_elem, server_side, enter_action){
    
    try{
        
        $(function() {
            function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }
            // input element
            input_elem
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( server_side, {
                        term: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 1 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );

                    //this.value = terms.join( ", " );
                    this.value = terms.join( "" );

                    return false;
                }
            }).keydown(function(e){
                if(e.keyCode == 13) {
                    //alert( $(this).val() );
                    enter_action( $(this).val() );
                }
            });
        });

    } catch(err) {
        console.error('Utils - Error in - apply auto complete : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////

Utils.change_location_url = function( url, title, object ){

    try{
        var oldTitle = window.document.title;
        var oldUrl   = window.document.href;

        url    = ( url    == null ) ? oldUrl   : url;
        title  = ( title  == null ) ? oldTitle : title;
        object = ( object == null ) ? {} : object;

        window.document.title = title;
        window.history.pushState(object, title, url);

    //
    //window.history.pushState({ id: 35 }, 'Viewing item #35', '/item/35');
    //
    //window.onpopstate = function (e) {
    //  var id = e.state.id;
    //  load_item(id);//any function here
    //};
    //

    } catch(err) {
        console.error('Utils - Error in - change location url : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////

Utils.trim_text = function(str, tLength){
    
    try{

        if( tLength == null ){
            tLength = 23;
        }

        if( str.length > tLength ){
            str = str.substr(0, tLength-3)+"...";
        }

    } catch(err) {
        throw 'Utils - Error in - trim_text : [' + err +']';
    }
    
    return str;
};

Utils.get_string = function(string){

    var str = '';

    try{

        if( string == null ){
            str = '';
        }else{
            str = string;
        }

    } catch(err) {
        throw 'Utils - Error in - get string : [' + err +']';
    }

    return str;
};

Utils.get_number_string = function(string){

    var str = '0';
    
    try{

        str  = ( string == 'true' || string == true ) ? '1' : string;

        str  = ( isNaN(string)    || string == ''   ) ? '0' : string;

    } catch(err) {
        throw 'Utils - Error in - get number string : [' + err +']';
    }

    return str;
};

Utils.get_int = function(string){

    var num = 0;

    try{
        
        var str = Utils.get_number_string(string);

        num = parseInt(str);

    } catch(err) {
        throw 'Utils - Error in - get int : [' + err +']';
    }

    return num;
};

Utils.get_float = function(string){

    var num = 0;

    try{
        var str = Utils.get_number_string(string);

        num = parseFloat(str);

    } catch(err) {
        throw 'Utils - Error in - get float : [' + err +']';
    }

    return num;
};

Utils.get_double = function(string){

    var num = 0;

    try{

        var str = Utils.get_number_string(string);

        num = parseFloat(str);
 
    } catch(err) {
        throw 'Utils - Error in - get double : [' + err +']';
    }
    
    return num;
};

Utils.get_more_result_html_output = function(linkFunction, result_count, index, count, groupCount){

    var output = '';

    try{

        //alert( linkFunction+", "+result_count+", "+index+", "+count+", "+groupCount );

        output += '<div id="MoreResults">'
                    + '<span class="more_result">' + result_count + ' ' + get_dictionary_text('Results_lbl') + '</span>'
                    + '<br />'
                    + '<div class="more_result">';

            if(index > 0){
                output += '<span>'
                            + '<a href="javascript:'+linkFunction+'('+ (index-count)+');">' + get_dictionary_text('Back_lbl') + '</a>'
                        + '</span>';
                            //+ '<a href="'+srcLink+'&index='+(index-count)+'">' + get_dictionary_text('Back_lbl'') + '</a>'
            }

            if( (index>0) && (index+count<result_count) ){
                output += '<span> | </span>';
            }


            if( index+count < result_count){
                output += '<span>'
                            + '<a href="javascript:'+linkFunction+'('+ (index+count)+');">' + get_dictionary_text('Next_lbl') + '</a>'
                        + '</span>';
                            //+ '<a href="'+srcLink+'&index='+(index+count)+'">' + get_dictionary_text('Next_lbl'') + '</a>'
            }
            output += '</div>'
                    + '<br />';


        var start = parseInt( index / groupCount ) * groupCount;

            output += '<div class="more_result">';

                if(start >= groupCount){
                output += '<span>'
                            + '<a href="javascript:'+linkFunction+'('+ (start-groupCount)+');"> &laquo; </a>'
                        + '</span>';
                            //+ '<a href="'+ srcLink +'&index='+ (start-groupCount) +'"> &laquo; </a> |'
                }

                for( i=start; i<result_count && i<start+groupCount; i+=count ){
                output += '<span>'
                            + '<a href="javascript:'+linkFunction+'('+ i +');"> '+((i/count)+1)+' </a>'
                        + '</span>';
                            //+ '<a href="'+srcLink+'&index='+(i)+'">' + (i/count)+1 + '</a>'
                            //+ (  (i<result_count-count) ? "|" : ""  )
                }

                if( start+groupCount <= result_count){
                output += '<span>'
                            + '<a href="javascript:'+linkFunction+'('+ (start+groupCount)+');"> &raquo; </a>'
                        + '</span>';
                }

            output += '</div>'
             + '</div>';

    } catch(err) {
        throw 'Utils - Error in - get more result html output : [' + err +']';
    }

    return output;
};

Utils.get_pagination_html_output = function(linkFunction, result_count, index, count, groupCount){

    try{
        //alert( linkFunction+", "+result_count+", "+index+", "+count+", "+groupCount );

        var start = parseInt( index / groupCount ) * groupCount;

        var output = '';
        output += '<div id="Pagination" class="pagination">';

            output += '<div id="Links">';

                if(start >= groupCount){
                    output += '<a href="javascript:'+linkFunction+'('+ (start-groupCount)+');"> &laquo; </a>'
                }
    //            if(index > 0){
    //                output += '<a href="javascript:'+linkFunction+'('+ (index-count)+');">' + get_dictionary_text('Back_lbl'') + '</a>';
    //            }
                for(var i=start; i<result_count && i<start+groupCount; i+=count ){
                    output += '<a href="javascript:'+linkFunction+'('+ i +');"> '+(parseInt(i/count)+1)+' </a>';
                }
    //            if( index+count < result_count){
    //                output += '<a href="javascript:'+linkFunction+'('+ (index+count)+');">' + get_dictionary_text('Next_lbl'') + '</a>';
    //            }
                if( start+groupCount <= result_count){
                    output += '<a href="javascript:'+linkFunction+'('+ (start+groupCount)+');"> &raquo; </a>';
                }

            output += '</div>';

            output += '<div id="Text"> &nbsp; ' + result_count + ' ' + get_dictionary_text('Results_lbl') + '</div>';

        output += '</div>';

    } catch(err) {
        throw 'Utils - Error in - get pagination html output : [' + err +']';
    }
    
    return output;
};

////////////////////////////////////////////////////////////////////////////////

Utils.check_browser = function(){

    try{

        if ( $.browser.msie ){
            if ( parseInt($.browser.version, 10) < 9) {
                alert( "You should upgrade your copy of Internet Explorer to 9 or upper.");
                window.open("http://windows.microsoft.com/en-US/internet-explorer/downloads/ie-9/worldwide-languages");
            } else {
                //alert( "You're using a recent copy of Internet Explorer.");
            }
        } else {
            //alert( "You're not using Internet Explorer.");
        }

    } catch(err) {
        throw 'Utils - Error in - check browser : [' + err +']';
    }
};

////////////////////////////////////////////////////////////////////////////////

Utils.escapeHTML = function(html) {

    try{

        return html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    } catch(err) {
        throw 'Utils - Error in - escapeHTML : [' + err +']';
    }
};

////////////////////////////////////////////////////////////////////////////////

Utils.convert_date_to_string = function(dateObject, withTime){
    
    var stringDate = '';

    try{
        //alert( 'todayDate.toString() : ' + todayDate.toString() );

        var stringDate = dateObject.getFullYear()+'-'+(dateObject.getMonth()+1)+'-'+dateObject.getDate();

        if( withTime != null && withTime ){
            stringDate += ' ' + dateObject.getHours()+':'+dateObject.getMinutes()+':'+dateObject.getSeconds();
        }

    } catch(err) {
        throw 'Utils - Error in - convert date to string : [' + err +']';
    }

    return stringDate;
};

Utils.convert_string_to_date = function(stringDate){
    
    var dateObject = null;

    try{

        if( ! Utils.valid_string_date(stringDate) ){
            return dateObject;
        }

        var dateArray = stringDate.split("-");

        var year  = dateArray[0];
        var month = dateArray[1];
        var day   = dateArray[2];

        dateObject = new Date(year, month-1, day, 0, 0, 0);

    } catch(err) {
        throw 'Utils - Error in - convert string to date : [' + err +']';
    }

    return dateObject;
};

Utils.convert_string_to_date_time = function(stringDate){
    
    var dateObject = null;

    try{

        if( ! Utils.valid_string_date(stringDate) ){
            return dateObject;
        }

        var stringDateArray = stringDate.split(" ");

        var dateArray = stringDateArray[0].split("-");
        var timeArray = stringDateArray[1].split(":");

        var year  = dateArray[0];
        var month = dateArray[1];
        var day   = dateArray[2];

        var hour   = timeArray[0];
        var minute = timeArray[1];
        var second = timeArray[2];

        dateObject = new Date(year, month-1, day, hour, minute, second);

    } catch(err) {
        throw 'Utils - Error in - convert string to date time : [' + err +']';
    }

    return dateObject;
};

Utils.valid_string_date = function(stringDate){
    
    var valid = false;

    try{
        
        //valid = checkStringDate(stringDate);
        //return valid;
    
    } catch(err) {
        throw 'Utils - Error in - valid string date : [' + err +']';
    }

    return true;
};

////////////////////////////////////////////////////////////////////////////////

Utils.get_youtube_id = function(ytUrl){

    var ytID = '';

    try{
        
        ytID = ytUrl.match("[\?&]v=([^&#]*)")[1];

    } catch(err) {
        throw 'Utils - Error in - get youtube id : [' + err +']';
    }

    return ytID;
};

Utils.navigate_to_url = function(url){

    try{

        if( url == null ) return;

        window.location = url;

    } catch(err) {
        throw 'Utils - Error in - navigate to url : [' + err +']';
    }
};

Utils.scroll_to_element = function(element, duration){

    try{

        if( element == null ) return;
        if( duration == null ) duration = 300;

        $('html, body').animate({
            scrollTop: element.offset().top
        }, duration);

    } catch(err) {
        throw 'Utils - Error in - scroll to element : [' + err +']';
    }
};

Utils.scroll_to_anchor = function(name){
    
    try{

        window.scrollTo(0, $('a[name='+name+']').position().top);
    
    } catch(err) {
        throw 'Utils - Error in - scroll to anchor : [' + err +']';
    }
};

Utils.reset_form = function($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
};

////////////////////////////////////////////////////////////////////////////////

Utils.fix_text_area_max_length = function(){

    $('textarea[maxlength]').keydown(function(){
        var max = parseInt($(this).attr('maxlength'));
        if($(this).val().length > max){
            alert( get_dictionary_text('MaxLengthExceeded_lbl') );
            $(this).val($(this).val().substr(0, $(this).attr('maxlength')));
        }

        $(this).parent().find('.charsRemaining').html('You have ' + (max - $(this).val().length) + ' characters remaining');
    });

};

Utils.switch_to_lang = function(lang){

    try{

        ///alert( window.location );
        //alert( window.location.href );
        //alert( window.location.search );

        //var url    = window.location.href + '';//for casting to string
        var url    = window.location.href;
        //var url    = window.location.search;

        //alert(url);

        var url_items = url.split("/");
        var lang_arr  = ["ar", "en", "fr"];

        for(var i=0;i<url_items.length;i++){
            if( lang_arr.contains( url_items[i] ) ){
                url_items[i] = lang;
            }
        }

        var new_url = url_items.join("/");

        //alert(new_url);

        //window.open(new_url, '_self', false);

        window.location.href = new_url;
        //window.location   = new_url;
        //window.location.href   = new_url;
        //window.location.search = new_url;

    } catch(err) {
        throw 'Utils - Error in - switch to lang : [' + err +']';
    }
};

Utils.set_page_direction = function(dir) {

    if( dir == null ){
        dir = (lang == "ar") ? "rtl" : "ltr";
    }

};

/*
Utils.get_base_url = function() {

    var url     = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));

    if (baseURL.indexOf('http://localhost') != -1) {
        // Base Url for localhost
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    } else {
        // Root Url for domain name
        return baseURL + "/";
    }
}
*/

Utils.get_base_url = function() {

    if( !location.href ){
        return "";
    }

    var fullUrl = location.href;

    var baseUrl  = fullUrl.substring(0, fullUrl.lastIndexOf("/"));

    return baseUrl;
};

Utils.get_variable = function(item) {

    try{

        if( !window.location.search ){
            return "";
        }

        var queryString = window.location.search.substring(1);

        var vars = queryString.split("&");

        for (var i=0;i<vars.length;i++) {

            var vals = vars[i].split("=");

            if (vals[0] == item) {
                return vals[1];
            }
        }

    } catch(err) {
        throw 'Utils - Error in - get variable : [' + err +']';
    }

    return "";
};

Utils.encode_utf8 = function(s) {
    if (window.encodeURIComponent) {//check fn present in old browser
        return unescape(encodeURIComponent(s));
    } else {
        return escape(s);
    }
};

Utils.decode_utf8 = function(s) {
    if (window.decodeURIComponent) {//check fn present in old browser
        return decodeURIComponent(escape(s));
    } else {
        return unescape(s);
    }
};

Utils.get_brief_text = function(str, tLength){

    if( tLength == null || tLength <= 0 ){
        tLength = 23;
    }

    if( str.length > tLength ){
        str = str.substr(0, tLength-3)+"...";
    }

    return str;
};

Utils.unescape_text = function(str){

    str = Utils.replace_all (str, "+", " ");

    str = unescape( str );

    return str;
};

Utils.trim = function(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g,"");
};

Utils.replace_all = function(str, find, replace){

    while( str.indexOf(find) != -1 ){
        str = str.replace(find, replace);
    }

    return str;
};

Utils.capitalize = function(string){
    string = ( string == null ) ? "" : string;
    return string.charAt(0).toUpperCase() + string.slice(1);
};

Utils.is_arabic = function(string) {

    var arabic = /[\u0600-\u06FF]/;
    //var string = 'عربية‎'; // some Arabic string from Wikipedia

    //alert(arabic.test(string)); // displays true

    return arabic.test(string);
};

////////////////////////////////////////////////////////////////////////////////

String.prototype.capitalize  = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.trim        = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
};

String.prototype.replace_all = function(find, replace){

    var temp = this;

    var index = temp.indexOf( find );

    while(index != -1){

        temp = temp.replace( find, replace );
        index = temp.indexOf( find );

    }

    return temp;
};

////////////////////////////////////////////////////////////////////////////////

Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
};

////////////////////////////////////////////////////////////////////////////////

Date.prototype.get_formatted = function() {
   var yyyy = this.getFullYear().toString();
   var mm   = (this.getMonth()+1).toString(); // getMonth() is zero-based
   var dd   = this.getDate().toString();
   return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]); // padding
};

////////////////////////////////////////////////////////////////////////////////

UTF8 = {
    encode: function(s){
        for(var c, i = -1, l = (s = s.split("")).length, o = String.fromCharCode; ++i < l;
                s[i] = (c = s[i].charCodeAt(0)) >= 127 ? o(0xc0 | (c >>> 6)) + o(0x80 | (c & 0x3f)) : s[i]
        );
        return s.join("");
    },
    decode: function(s){
        for(var a, b, i = -1, l = (s = s.split("")).length, o = String.fromCharCode, c = "charCodeAt"; ++i < l;
                ((a = s[i][c](0)) & 0x80) &&
                (s[i] = (a & 0xfc) == 0xc0 && ((b = s[i + 1][c](0)) & 0xc0) == 0x80 ?
                o(((a & 0x03) << 6) + (b & 0x3f)) : o(128), s[++i] = "")
        );
        return s.join("");
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
