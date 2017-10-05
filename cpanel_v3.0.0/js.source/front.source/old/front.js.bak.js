
/* 
 * front.js
 */

////////////////////////////////////////////////////////////////////////////////
/// constants ///
////////////////////////////////////////////////////////////////////////////////

var MODE_DESKTOP = 1;
var MODE_TABLET  = 2;
var MODE_MOBILE  = 3;

var g_display_mode = MODE_DESKTOP;
var g_prev_mode    = 0;

////////////////////////////////////////////////////////////////////////////////
/// front ///
////////////////////////////////////////////////////////////////////////////////

var gIntervalID  = 0;
var gBannerIndex = -1;

$(document).ready(function(){

    //alert('front js loaded');

    $('body').bind("dictionaryLoaded",  _init_preloads_with_label);

    check_browser();
    
    load_dictionary();

    _init_preloads();

    _window_resized();
    
    _init_go_to_top();

    //_replace_broken_images();

    //include_js( "./js/front/include_js.js" );   
});

$(window).resize(function() {

    _window_resized();

});
//}).trigger("resize");

////////////////////////////////////////////////////////////////////////////////

function is_mode_changed(){
    
    //console.log('g_display_mode : '+g_display_mode);
    //console.log('g_prev_mode    : '+g_prev_mode);
    
    return ( g_display_mode != g_prev_mode );
}

////////////////////////////////////////////////////////////////////////////////

function _window_resized(){

    //alert('_window_resized');
    //console.log('_window_resized');
    
    //sync();
    g_display_mode = get_display_mode();

    if( is_mode_changed() ){

        //console.log('mode changed');
        
        g_prev_mode = g_display_mode;

        _prepare_body_click();
        
        _prepare_top_menu();

        //_prepare_side_menu();
    }

}

////////////////////////////////////////////////////////////////////////////////

function _init_go_to_top(){

    $(window).scroll(function(event){
        
        var st = $(this).scrollTop();
       
        if( st >= 125 ){
            
            $(".go-to-top").show("fade", 300);
            
            _stick_top_menu();
            
        }else{
            
            $(".go-to-top").hide("fade", 300);
            
            _release_top_menu();
            
        }
        
    });
    
    var st = $(window).scrollTop();
       
    if( st >= 125 ){
            
        $(".go-to-top").show("fade", 300);

        _stick_top_menu();

    }else{

        $(".go-to-top").hide("fade", 300);

        _release_top_menu();

    }


    $(".go-to-top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });


    //var lastScrollTop = 0;
    //
    //$(window).scroll(function(event){
    //
    //   if (st > lastScrollTop){
    //       // downscroll code
    //       console.log('scrolling down..');
    //   } else {
    //      // upscroll code
    //       console.log('scrolling up..');
    //   }
    //
    //   lastScrollTop = st;
    //});
}

////////////////////////////////////////////////////////////////////////////////

function _stick_top_menu(){

    $("#Head").find("#TopMenu").addClass('is_sticked');

}

function _release_top_menu(){
    
    $("#Head").find("#TopMenu").removeClass('is_sticked');

}

////////////////////////////////////////////////////////////////////////////////

function _replace_broken_images(){

    //alert('_window_resized');
    //console.log('_window_resized');
    
    $(body).find('img').each(function(i) {

        var img_div = $(this);

        img_div.attr("onError", "this.onerror=null;this.src='/images/noimage.gif';");

        //img_div.attr("onerror", "imgError(this);");

        //img_error(img_div, "/images/noimage.gif");

    });


}

////////////////////////////////////////////////////////////////////////////////

function doTranslate() {
    document.getElementById("google_translate_element").innerHTML = "Please wait";
    var element = document.createElement("script");
    element.type = "text/javascript";
    element.src = "http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.body.appendChild(element);
}

function googleTranslateElementInit() {
    document.getElementById("google_translate_element").innerHTML = "";
    new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ca,cy,da,de,en,es,et,eu,fa,fi,fr,hi,hr,hu,hy,is,it,ja,la,nl,no,pl,pt,ru,sv,tl,tr,zh-CN,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-1246013-1'}, 'google_translate_element');
    //new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}

////////////////////////////////////////////////////////////////////////////////

function _init_preloads(e){

    _init_language();

    //_init_sound_switch();
    
    //_init_language_switch();

    _init_marquee();
    
    _check_popup();

}

function _init_preloads_with_label(){

    _init_search();

    _init_banner();
    
    _init_news();

}
////////////////////////////////////////////////////////////////////////////////

function _init_sound_switch(){
    
    var sound_on_div  = $("#Sound").find("#sound-on"); 
    
    sound_on_div.click(function(){
       console.log('sound on') ;
    });
    
    var sound_off_div = $("#Sound").find("#sound-off"); 
    
    sound_off_div.click(function(){
       console.log('sound off') ;
    });

}
////////////////////////////////////////////////////////////////////////////////

function get_display_mode(){

    var display_mode = MODE_DESKTOP; 

    var w  = $(window).width();

    //if ( w > '1280') {
    if ( w > '1024') {
        display_mode = MODE_DESKTOP;
    } else if ( w >= '768' ) {
        display_mode = MODE_TABLET;
    } else if ( w >= '320' ) {
        display_mode = MODE_MOBILE;
    }else {
        //old devices
        display_mode = MODE_MOBILE;
    }

    //console.log('w            = '+w);
    //console.log('display_mode = '+display_mode);

    return display_mode;
}

////////////////////////////////////////////////////////////////////////////////

function _init_font_size_control(){

    //console.log('_init_font_size_control');

    var zoom_div = $("#Body").find("#zoom");

    var zoom_in  = zoom_div.find("#zoom_in");
    var zoom_out = zoom_div.find("#zoom_out");

    zoom_in.click(function(){
       increase_font(); 
    });

    zoom_out.click(function(){
       decrease_font(); 
    });

}

function decrease_font(){
    
    //console.log('decrease_font');

    var font_div = $(".detail_content");
    
    var font_size = parseInt( font_div.css("font-size") );
    
    font_size -= 1;
    
    font_size = ( font_size < 8 ) ? 8 : font_size;

    font_div.css("font-size", font_size+"px");

}
function increase_font(){
    
    //console.log('increase_font');

    var font_div = $(".detail_content");

    var font_size = parseInt( font_div.css("font-size") );
    
    font_size += 1;
    
    font_size = ( font_size > 40 ) ? 40 : font_size;

    font_div.css("font-size", font_size+"px");

}

////////////////////////////////////////////////////////////////////////////////

function _init_marquee(){

    //$(document).ready(function(){
    //    
    //    var direction = (lang=="ar")?'right':'left';
    //    //alert( direction );
    //
    //    $('.marquee').marquee({
    //        pauseOnHover: true,
    //        duration: 5000,
    //        gap: 0,
    //        direction: direction
    //    });
    //});

    //$('.marquee').marquee('pointer').mouseover(function() {
    //    $(this).trigger('stop');
    //}).mouseout(function() {
    //    $(this).trigger('start');
    //}).mousemove(function(event) {
    //    if ($(this).data('drag') == true) {
    //        this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
    //    }
    //}).mousedown(function(event) {
    //    $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
    //}).mouseup(function() {
    //    $(this).data('drag', false);
    //});
    
}

function _init_language(){
    
    $("#Lang").find("*").hide();

    if( lang == "ar" ){
        $("#Lang").find("#English").show();
    }else{
        $("#Lang").find("#Arabic").show();
    }
}

function _init_language_switch(){

    //alert( 'init_language_switch()' );

    $( "#Arabic" ).css("cursor", "pointer");
    $( "#Arabic" ).click(function (e){
        e.preventDefault();
        switch_to_lang("ar");
        return false;
    });

    $( "#English" ).css("cursor", "pointer");
    $( "#English" ).click(function (e){
        e.preventDefault();
        switch_to_lang("en");
        return false;
    });

}

////////////////////////////////////////////////////////////////////////////////

function _prepare_body_click(){

    $("body").unbind("click");

    if( g_display_mode == MODE_DESKTOP ){
        _prepare_body_click_desktop();
    }else if( g_display_mode == MODE_TABLET ){
        _prepare_body_click_tablet();
    }else if( g_display_mode == MODE_MOBILE ){
        _prepare_body_click_mobile();
    }

}

function _prepare_body_click_desktop(){
}
function _prepare_body_click_tablet(){

    $("body").click(function(e) {
        if (e.target.id == "TopMenu" || $(e.target).parents("#TopMenu").size()) { 
            //alert("Inside div");
        } else { 
            //alert("Outside div");
            var submenu = $('#TopMenu').find('.submenu');
            submenu.removeClass('open');
            submenu.hide("slide", {direction: "up"}, 300);
        }
    });

}
function _prepare_body_click_mobile(){

    $("body").click(function(e) {

        //alert(e.target.id);

        if (e.target.id == "TopMenu" || $(e.target).parents("#TopMenu").size()) { 
            //alert("Inside div");
        } else { 
            //alert("Outside div");

            var menu   = $('#TopMenu').find('.main');
            var button = $('#TopMenu').find('#button');
    
            button.removeClass("open");
            menu.find(".submenu");
            menu.hide("slide", {direction: "up"}, 300);
        }
            
        return true;
    });

}

////////////////////////////////////////////////////////////////////////////////

function _prepare_top_menu(){
    
    //console.log('_prepare_top_menu()');
    
    $('#TopMenu').find("*").off();
    
    g_display_mode = get_display_mode();

    if( g_display_mode == MODE_DESKTOP ){
        _prepare_top_menu_desktop();
    }else if( g_display_mode == MODE_TABLET ){
        _prepare_top_menu_tablet();
    }else if( g_display_mode == MODE_MOBILE ){
        _prepare_top_menu_mobile();
    }

}

function _prepare_top_menu_desktop() {

    //console.log('_prepare_top_menu_desktop()');

    $('#TopMenu').find( '.main'    ).show();
    $('#TopMenu').find( '.submenu' ).hide().removeClass('open');


    $('#TopMenu').find('ul.main > li ').mouseenter(function() {
        var submenu = $(this).children('.submenu');
        _open_top_sub_menu( submenu );
    });
    
    $('#TopMenu').find('ul.main > li ').mouseleave(function() {
        var submenu = $(this).children('.submenu');
        _close_top_sub_menu( submenu );
    });

    //$('#TopMenu').find('ul.main > li ').mouseover(function() {
    //    var submenu = $(this).children('.submenu');
    //    _open_top_sub_menu( submenu );
    //});
    
    //$('#TopMenu').find('ul.main > li > .submenu ').mouseover(function() {
    //    $(this).show();
    //});
    $('#TopMenu').find('ul.main > li > .submenu ').mouseenter(function() {
        $(this).show();
    });
    
    $('#TopMenu').find('ul.main > li > .submenu ').mouseleave(function() {
        var submenu = $(this);
        _close_top_sub_menu( submenu );
    });
}
function _prepare_top_menu_tablet() {
    
    //alert( '_prepare_top_menu_tablet' );

    $('#TopMenu').find( '.main'    ).show();
    $('#TopMenu').find( '.submenu' ).hide().removeClass("open");

    $('#TopMenu').find('ul.main > li > a').click(function() {

        var submenu = $(this).parent().children('.submenu');
        _open_top_sub_menu( submenu );

        //if( $(this).parent().find('.submenu').length > 0 ){
        if( $(this).parent().children('div').length > 0 ){
            return false;
        }else{
            return true;
        }

        return false;
    });

}
function _prepare_top_menu_mobile() {

    //console.log('_prepare_top_menu_mobile()');

    $('#TopMenu').find( '.main'    ).hide();
    $('#TopMenu').find( '.submenu' ).hide().removeClass("open");

    $('#TopMenu').find( '#button' ).removeClass("open");


    $('#TopMenu').find('#button').click(function() {

        //alert('al hamdo le Allah');

        var menu = $("#TopMenu").find('ul.main');

        if( $(this).hasClass('open')){
            $(this).removeClass('open');
            menu.hide("slide", {direction: "up"}, 300);
        }else{
            $(this).addClass('open');
            menu.show("slide", {direction: "up"}, 300);
            menu.find(".submenu");
        }
        
    });

}

function _open_top_sub_menu(submenu) {

    if( submenu.hasClass('open') ){
        return;
    }

    //(menu) (li)    main (ul)
    submenu.parent().parent().find('.submenu').hide();

    submenu.addClass('open');

    if( submenu.length == 0 ) return;

    var win_width  = parseFloat( $(window).width() );
    var menu_width = parseFloat( submenu.width() );
    var menu_left  = parseFloat( submenu.parent().offset().left );
    var menu_right = menu_left + parseFloat( submenu.parent().width() );

    var shift      = 0;

    if( lang != "ar" ){
        if( (menu_width+menu_left) > win_width ){
            shift    = ( ( menu_width + menu_left ) - (win_width-12) ) * -1;
            submenu.css( "left", shift+"px" );
            //submenu.css( { left: shift+"px" } );
        }
    }

    if( lang == "ar" ){
        if( (menu_right-menu_width) < 22 ){
            //console.log( "menu_width : " + menu_width );
            //var shift    = ( menu_width + menu_right ) - (win_width-22);
            shift    = (menu_width - menu_right + 12) * -1;
            submenu.css("right", shift+"px");
        }
    }

    submenu.show("slide", {direction: "up"}, 300);    
}

function _close_top_sub_menu(submenu) {

    submenu.hide().removeClass('open');

}

////////////////////////////////////////////////////////////////////////////////

function _prepare_side_menu(){
    
    //console.log('_prepare_side_menu()');

    $('#SideMenu').find("*").off();

    g_display_mode = get_display_mode();

    if( g_display_mode == MODE_DESKTOP ){
        _prepare_side_menu_desktop();
    }else if( g_display_mode == MODE_TABLET ){
        _prepare_side_menu_desktop();
    }else if( g_display_mode == MODE_MOBILE ){
        _prepare_side_menu_mobile();
    }

}

function _prepare_side_menu_desktop(){
    
    //console.log('_prepare_side_menu_mobile()');

    //#SideMenu ul.side_menu li.title a
    
    $('#SideMenu').find('ul.side_menu > li').show();
    $('#SideMenu').find('ul.side_menu > li.title').show();
    
}

function _prepare_side_menu_mobile(){
    
    //console.log('_prepare_side_menu_mobile()');

    //#SideMenu ul.side_menu li.title a
    
    $('#SideMenu').find('ul.side_menu > li').hide();
    $('#SideMenu').find('ul.side_menu > li.title').show();
    
     $('#SideMenu').find('ul.side_menu > li.title').click(function() {

        if( $(this).hasClass('open')){
            $(this).removeClass('open');
            $(this).parent().find('li').hide();
            $(this).show();

        }else{
            $(this).addClass('open');
            $(this).parent().find('li').show();
        }

        return false;
    });
    
}

////////////////////////////////////////////////////////////////////////////////

function _init_banner(){
    //alert('init banner');
    var navDivs = $("#navs").find(".nav");

    if( navDivs.length > 1 ){

        display_next_banner();

        //setTimeout(display_next_banner, 12000);//12 seconds // one time
        gIntervalID = window.setInterval('display_next_banner()', 12000);//12 seconds // repeated
    }
}

function display_banner_by_id(banner_id){

    var bannerNavDiv = $("#navs").find(".nav").filter('[id='+banner_id+']');

    gBannerIndex = bannerNavDiv.data("index");

    window.clearInterval(gIntervalID);

    gIntervalID = window.setInterval('display_next_banner()', 12000);//12 seconds // repeated

    display_banner( bannerNavDiv );
}

function display_banner_by_index(bannerIndex){

    var bannerNavDiv = $("#navs").find(".nav").filter('[data-index='+bannerIndex+']');

    display_banner( bannerNavDiv );
}

function display_banner(banner_nav_div){

    //alert('display_banner');

    var type  = banner_nav_div.data("type");//472*265
    var file  = banner_nav_div.data("file");

    var title = banner_nav_div.data("title");
    var desc  = banner_nav_div.data("desc");
    
    var link  = banner_nav_div.data("link");

    $("#Slide").find("#navs").find(".nav").removeClass("nav_on");
    banner_nav_div.addClass("nav_on");

    type = parseInt(type);
    
    var width  = 472;
    var height = 265;

    //console.log('type = '+type+', file = '+file+', width = '+width+', height = '+height );
    
    var pattern = '<a href="%s" title="'+title+'">%s</a>';

    //console.log('pattern = '+pattern+'' );

    var embed = get_banner_embed_output_html( file, type, width, height);
    
    //console.log('embed no click  = '+embed );
    //
    //console.log('title  = '+title );
    //console.log('desc   = '+desc  );
    //console.log('embed  = '+embed );

    embed  = sprintf( pattern, link, embed );

    title  = sprintf( pattern, link, title );
    desc   = sprintf( pattern, link, desc  );
    
    $("#Slide").find("#image").hide().html( embed ).fadeTo("slow",1.0);

    $("#Slide").find("#title").hide().html( title ).fadeTo("slow",1.0);
    $("#Slide").find("#desc" ).hide().html(  desc ).fadeTo("slow",1.0);

    //$("#Banner").find("#Desc").hide().html( more );

    return false;
}

function display_next_banner(){

    //alert('display_next_banner()');

    gBannerIndex++;
    
    var navDivs = $("#navs").find(".nav");

    var lastIndex = navDivs.length-1;
    gBannerIndex = ( gBannerIndex > lastIndex ) ? 0 : gBannerIndex;
    gBannerIndex = ( gBannerIndex < 0 ) ? lastIndex : gBannerIndex;

    display_banner_by_index(gBannerIndex);
}

function get_banner_embed_output_html( file, type, width, height){

    var output = '';

    switch( type ){
        
        case TYPE_IMAGE:
            //console.log('image');
            output = get_image_embed(g_root_url+"uploads/banners/"+file, -1, -1);
            break;

        case TYPE_YOUTUBE:
            //console.log('youtube');
            output = get_youtube_video_embed(file, width, height);
            break;

        case TYPE_VIMEO:
            //console.log('vimeo');
            output = get_vimeo_video_embed(file, width, height);
            break;

        case TYPE_VIDEO:
            //console.log('jwplayer');
            output = get_jsplayer_video_embed(file, width, height, 'uploads/banners', '', false);
            break;

        default:
            break;
    }

    return output;
}

////////////////////////////////////////////////////////////////////////////////

function _init_news() {

    if ($("#NewsBox").length <= 0) { return }

    var item_divs = $("#NewsBox").find("#items").find(".item");

    item_divs.each(function (i) {
        $(this).on("click", function () {
    
            window.clearInterval(gNewsIntervalID);
            gNewsIntervalID = window.setInterval("display_next_news()", 5000);
    
            var index  = $(this).data("index");
            gNewsIndex = index;

            display_news_by_index(index);
        })
    });

    if (item_divs.length > 1) {
        gNewsIndex = -1;
        display_next_news();
        gNewsIntervalID = window.setInterval("display_next_news()", 5000);
    }
}

function display_next_news() {

    gNewsIndex++;

    var item_divs  = $("#NewsBox").find("#items").find(".item");
    var last_index = item_divs.length - 1;

    gNewsIndex = (gNewsIndex > last_index) ? 0 : gNewsIndex;
    gNewsIndex = (gNewsIndex < 0) ? last_index : gNewsIndex;

    display_news_by_index(gNewsIndex)
}

function display_news_by_id(news_id) {

    var news_div = $("#NewsBox").find("#items").find(".item").filter("[id=" + news_id + "]");

    gNewsIndex   = news_div.data("index");

    display_news(news_div);
}

function display_news_by_index(index) {

    var news_div = $("#NewsBox").find("#items").find(".item").filter("[data-index=" + index + "]");
    display_news(news_div);
}

function display_news(news_div) {

    var title  = news_div.data("title");
    var desc   = news_div.data("desc");
    var image  = news_div.data("image");
    var folder = news_div.data("folder");
    var href   = news_div.data("href");
    var index  = news_div.data("index");

    gNewsIndex = index;

    $("#NewsBox").find("#items").find(".item").removeClass("item_selected");
    news_div.addClass("item_selected");

    var image_html = '<a href="' + href + '" title="' + title + '"><img src="' + g_root_url + "uploads/" + folder + "/" + image + '" /></a>';
    var title_html = '<a href="' + href + '" title="' + title + '">' + title + "</a>";

    var more_html  = '<a href="' + href + '" title="' + title + '">' + get_dictionary_text('More_lbl') + '</a>';
    //var more_html  = '';

    $("#NewsBox").find("#image").hide().html( image_html ).fadeTo("slow", 1);
    $("#NewsBox").find("#title").hide().html( title_html ).fadeTo("slow", 1);
    $("#NewsBox").find("#desc" ).hide().html( desc       ).fadeTo("slow", 1);
    $("#NewsBox").find("#more" ).hide().html( more_html  ).fadeTo("slow", 1);

    return false;
}

////////////////////////////////////////////////////////////////////////////////

function _init_search(){

    var done = false;

    var searchTextField = $('input[name=search_item]');
    var searchButton    = $('a[id=search-button]');

    var searchText = get_dictionary_text("Search_lbl");
    searchTextField.val( searchText );

    searchButton.click( function() {

        var search_label = get_dictionary_text("Search_lbl");
        var search_item  = searchTextField.val();

        if( search_item != search_label && search_item != "" ){
            load_search( search_item );
        }
    });

    searchTextField.focus(function() {

        var searchText = get_dictionary_text("Search_lbl");

        if( $(this).val() == searchText ){
            $(this).val("");
        }

        $(this).keydown(function(e){
            if(e.keyCode == 13) {
                if(!done){
                    done = true;
                    load_search( searchTextField.val() );
                }
            }
        });

    });

    searchTextField.focusout(function() {
        if( $(this).val() == null || $(this).val() == "" ){
            var searchText = get_dictionary_text("Search_lbl");
            $(this).val(searchText);
        }
    });


    var input               = $( 'input[name=search_item]' );
    //var input               = "search_item";
    var qsearch_server_side = g_root_url+"ajax.php?page=qsearch";

    apply_auto_complete(input, qsearch_server_side, load_search);

    //apply_auto_complete("search_item", g_root_url+"ajax.php?page=qsearch", load_search);

}

function load_search(search_item){

    ////alert( 'load_search('+search_item+')' );
    ////(en|ar)/(.*)/(.*)/5

    var url = '';
    
    if( g_use_meaningful_url ){

        url = g_root_url+lang+'/'+get_dictionary_text('Search_lbl')+'/'+search_item+'/5';

        ////alert( 'url = '+url );
        url = url.toLowerCase().replace(/\s+/g,"-").replace(/&+/g,"").replace("?","");

    }else{
        url = '?page=search&search_item='+search_item+'';
    }

    window.location.href = url;

    //window.location.href = '?page=search&search_item='+search_item+'';

    return false;
}

////////////////////////////////////////////////////////////////////////////////

function _check_popup() {

    //console.log('_check_popup()');

    var shown = get_cookie("popup-shown");

    //console.log('shown = '+shown);

    shown = ( shown == null ) ? 0 : shown;

    //console.log('shown = '+shown);

    if ( shown <= 0  ) {
        //console.log('shown = '+shown);
        _load_popup();
    }

} 

function _load_popup(){

    //console.log('_load_popup()');
    
    var seqId = Math.floor(Math.random()*1000);

    var serverUrl = g_root_url+"ajax.php";

    var data      = "page=popup"
                  + "&seqId="+seqId;

    var xmlHttp = $.get(serverUrl, data, _on_load_popup, "json");

}

function _on_load_popup(outputArray){
    
    //console.log('_on_load_popup()');

    var popup = outputArray.popup;

    if( popup != null && popup.active > 0 ){

        //console.log('popup active');

        display_popup(popup.content, '');

        set_cookie( "popup-shown", 1, 7 ); 
        
        setTimeout( close_popup, 15000 );
    }

}

////////////////////////////////////////////////////////////////////////////////
                                                                           

