/*! front */

/* global Utils, CPopup, LoginForm, lang, BackgroundRequests, CDictionary, DisplayUtil, TYPE_IMAGE, TYPE_YOUTUBE, TYPE_VIMEO, TYPE_VIDEO, g_root_url, UserAuth */

$(document).ready(function(){

    try{

        //alert('front js loaded');

        //Utils.check_browser();

        $('body').bind(CDictionary.DICTIONARY_LOADED, FrontDisplay.init);

        $('body').bind(DisplayMode.MODE_CHANGED_EVENT, FrontDisplay.mode_changed);


        CDictionary.init();
        CDictionary.load();
        CDictionary.set_lang(lang);

        CPopup.set_options({"theme":"window"});


        DisplayMode.window_resized();

        //include_js( "./js/front/include_js.js" );

    } catch (err) {
        console.error('Error in : FrontDisplay - document - ready [' + err + ']');
    }

});

$(window).resize(function() {

    try{

        DisplayMode.window_resized();

    } catch (err) {
        console.error('Error in : $(window) - resize [' + err + ']');
    }

});//.trigger("resize");

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function FrontDisplay(){}

FrontDisplay.init = function(){

    try{

        UserAuth.check_login();

        GoToTop.init();

        FrontDisplay.init_language();

        //FrontDisplay.init_sound_switch();

        //FrontDisplay.init_marquee();

        SearchView.init_search();

        //PopupView.check_popup();

        SlidesView.init();

        //NewsView.init();

        //FrontDisplay.replace_broken_images();

        //$('body').trigger( BackgroundRequests.BACKGROUND_REQUEST );

    } catch (err) {
        console.error('Error in : FrontDisplay - init [' + err + ']');
    }
};

////////////////////////////////////////////////////////////////////////////////

function DisplayMode(){}

DisplayMode.MODE_DESKTOP     = 1;
DisplayMode.MODE_TABLET      = 2;
DisplayMode.MODE_WIDE_MOBILE = 3;
DisplayMode.MODE_MOBILE      = 4;

DisplayMode.MODE_CHANGED_EVENT = "MODE_CHANGED";

DisplayMode.window_resized  = function (){

    //alert('DisplayMode.window_resized');
    //console.log('DisplayMode.window_resized');
    //sync();

    DisplayMode.current_mode = DisplayMode.get_mode();

    if( DisplayMode.is_mode_changed() ){

        //console.log('mode changed');
        
        DisplayMode.prev_mode = DisplayMode.current_mode;
        
        $('body').trigger(DisplayMode.MODE_CHANGED_EVENT);

    }

};

DisplayMode.get_mode        = function (){

    var display_mode = DisplayMode.MODE_DESKTOP; 

    var w  = $(window).width();
    
    if ( w <= 479 ) {
        display_mode = DisplayMode.MODE_MOBILE;
    } else if ( w <= 767 ) {
        display_mode = DisplayMode.MODE_WIDE_MOBILE;
    } else if ( w <= 1023 ) {
        display_mode = DisplayMode.MODE_TABLET;
    } else {
        display_mode = DisplayMode.MODE_DESKTOP;
    }

    //console.log('w            = '+w);
    //console.log('display_mode = '+display_mode);

    //var ww = $(window).width();
    //var dw = $(document).width();
    //var wi = $(window).innerWidth();
    //console.log('ww           = '+dw);
    //console.log('dw           = '+wi);
    //console.log('wi           = '+wi);

    return display_mode;
};

DisplayMode.is_mode_changed = function (){
    
    //console.log('DisplayMode.current_mode : '+DisplayMode.current_mode);
    //console.log('DisplayMode.prev_mode    : '+DisplayMode.prev_mode);
    
    return ( DisplayMode.current_mode != DisplayMode.prev_mode );
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

FrontDisplay.mode_changed   = function(){
    
    //console.log('mode changed!');    

    MenuView.prepare_body_click();

    MenuView.prepare_top_menu();

    var display_mode = DisplayMode.get_mode();

    if( display_mode == DisplayMode.MODE_MOBILE ){
        
        FrontDisplay.prepare_share_menu();

        FrontDisplay.prepare_contacts_menu();

        FrontDisplay.prepare_user_profile_tabs_menu();
        
    }

    //FrontDisplay.prepare_side_menu();
    
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

FrontDisplay.replace_broken_images = function(){

    //alert('_window_resized');
    //console.log('_window_resized');
    
    $('body').find('img').each(function(i) {

        var img_div = $(this);

        img_div.attr("onError", "this.onerror=null;this.src='/images/noimage.gif';");

        //img_div.attr("onerror", "imgError(this);");

        //Utils.img_error(img_div, "/images/noimage.gif");

    });


};

////////////////////////////////////////////////////////////////////////////////

FrontDisplay.doTranslate = function() {
    document.getElementById("google_translate_element").innerHTML = "Please wait";
    var element = document.createElement("script");
    element.type = "text/javascript";
    element.src = "http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.body.appendChild(element);
};

FrontDisplay.googleTranslateElementInit = function() {
    document.getElementById("google_translate_element").innerHTML = "";
    new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ca,cy,da,de,en,es,et,eu,fa,fi,fr,hi,hr,hu,hy,is,it,ja,la,nl,no,pl,pt,ru,sv,tl,tr,zh-CN,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-1246013-1'}, 'google_translate_element');
    //new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
};

////////////////////////////////////////////////////////////////////////////////

FrontDisplay.init_sound_switch = function(){
    
    var sound_on_div  = $("#Sound").find("#sound-on"); 
    
    sound_on_div.click(function(){
       console.log('sound on') ;
    });
    
    var sound_off_div = $("#Sound").find("#sound-off"); 
    
    sound_off_div.click(function(){
       console.log('sound off') ;
    });

};

////////////////////////////////////////////////////////////////////////////////

FrontDisplay.init_font_size_control = function(){

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

};

FrontDisplay.decrease_font = function(){
    
    //console.log('decrease_font');

    var font_div = $(".detail_content");
    
    var font_size = parseInt( font_div.css("font-size") );
    
    font_size -= 1;
    
    font_size = ( font_size < 8 ) ? 8 : font_size;

    font_div.css("font-size", font_size+"px");

};
FrontDisplay.increase_font = function(){
    
    //console.log('increase_font');

    var font_div = $(".detail_content");

    var font_size = parseInt( font_div.css("font-size") );
    
    font_size += 1;
    
    font_size = ( font_size > 40 ) ? 40 : font_size;

    font_div.css("font-size", font_size+"px");

};

////////////////////////////////////////////////////////////////////////////////

FrontDisplay.init_marquee = function(){

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
    
};

////////////////////////////////////////////////////////////////////////////////

FrontDisplay.init_language = function(){

    $("#languages").find("a").removeClass('active');

    //if( lang == "ar" ){
    //    $("#languages").find("#arabic").addClass('active');
    //}else{
    //    $("#languages").find("#english").addClass('active');
    //}
    
    $("#languages").find("*").hide();
    
    if( lang == "ar" ){
        $("#languages").find("#english").show();
    }else{
        $("#languages").find("#arabic").show();
    }

};

FrontDisplay.init_language_switch = function(){

    //alert( 'init_language_switch()' );

    $( "#Arabic" ).css("cursor", "pointer");
    $( "#Arabic" ).click(function (e){
        e.preventDefault();
        Utils.switch_to_lang("ar");
        return false;
    });

    $( "#English" ).css("cursor", "pointer");
    $( "#English" ).click(function (e){
        e.preventDefault();
        Utils.switch_to_lang("en");
        return false;
    });

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function GoToTop(){}

GoToTop.init            = function(){

    $(window).scroll(function(event){
        
        var st = $(this).scrollTop();
       
        if( st >= 125 ){
            
            $(".go-to-top").show("fade", 300);
            
            GoToTop.stick_top_bar();
            
        }else{
            
            $(".go-to-top").hide("fade", 300);
            
            GoToTop.release_top_bar();
            
        }
        
    });
    
    var st = $(window).scrollTop();
       
    if( st >= 125 ){
            
        $(".go-to-top").show("fade", 300);

        GoToTop.stick_top_bar();

    }else{

        $(".go-to-top").hide("fade", 300);

        GoToTop.release_top_bar();

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
};

GoToTop.stick_top_bar   = function(){

    $("#head").find("#top-menu").addClass('is_sticked');
    //$("#head").find("#stiky-bar").addClass('is_sticked');

};

GoToTop.release_top_bar = function(){
    
    //$("#head").find("#stiky-bar").removeClass('is_sticked');
    $("#head").find("#top-menu").removeClass('is_sticked');

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function MenuView(){}

MenuView.prepare_body_click = function(){

    $("body").unbind("click");
    
    var display_mode = DisplayMode.get_mode();
    
    switch ( display_mode ){

        case DisplayMode.MODE_DESKTOP:
            MenuView.prepare_body_click_desktop();
            break;

        case DisplayMode.MODE_TABLET:
            MenuView.prepare_body_click_tablet();
            break;

        case DisplayMode.MODE_WIDE_MOBILE:
        case DisplayMode.MODE_MOBILE:
            MenuView.prepare_body_click_mobile();
            break;
    }

};

MenuView.prepare_body_click_desktop = function(){
    $("body").click(function(e) {
        if (e.target.id == "top-menu" || $(e.target).parents("#top-menu").size()) { 
            //alert("Inside div");
        } else { 
            //alert("Outside div");
            var submenu = $('#top-menu').find('.submenu');
            submenu.removeClass('open');
            submenu.hide("slide", {direction: "up"}, 300);
        }
    });
};
MenuView.prepare_body_click_tablet  = function(){

    $("body").click(function(e) {
        if (e.target.id == "top-menu" || $(e.target).parents("#top-menu").size()) { 
            //alert("Inside div");
        } else { 
            //alert("Outside div");
            var submenu = $('#top-menu').find('.submenu');
            submenu.removeClass('open');
            submenu.hide("slide", {direction: "up"}, 300);
        }
    });

};
MenuView.prepare_body_click_mobile  = function(){

    $("body").click(function(e) {

        //alert(e.target.id);

        if (e.target.id == "top-menu" || $(e.target).parents("#top-menu").size()) { 
            //alert("Inside div");
        } else { 
            //alert("Outside div");

            var menu   = $('#top-menu').find('.main');
            var button = $('#top-menu').find('#button');
    
            button.removeClass("open");
            menu.find(".submenu");
            menu.hide("slide", {direction: "up"}, 300);
        }
            
        return true;
    });

};

////////////////////////////////////////////////////////////////////////////////

MenuView.prepare_top_menu = function(){
    
    //console.log('_prepare_top_menu()');
    
    $('#top-menu').find("*").off();
    
    var display_mode = DisplayMode.get_mode();

    switch ( display_mode ){

        case DisplayMode.MODE_DESKTOP:
            MenuView.prepare_top_menu_desktop();
            break;

        case DisplayMode.MODE_TABLET:
            MenuView.prepare_top_menu_tablet();
            break;

        case DisplayMode.MODE_WIDE_MOBILE:
        case DisplayMode.MODE_MOBILE:
            MenuView.prepare_top_menu_mobile();
            break;
    }

};

MenuView.prepare_top_menu_desktop = function() {

    //console.log('_prepare_top_menu_desktop()');

    $('#top-menu').find( '.main'    ).show();
    $('#top-menu').find( '.submenu' ).hide().removeClass('open');


    $('#top-menu').find('ul.main > li ').mouseenter(function() {
        var submenu = $(this).children('.submenu');
        MenuView.open_top_sub_menu( submenu );
    });
    
    $('#top-menu').find('ul.main > li ').mouseleave(function() {
        var submenu = $(this).children('.submenu');
        MenuView.close_top_sub_menu( submenu );
    });

    //$('#top-menu').find('ul.main > li ').mouseover(function() {
    //    var submenu = $(this).children('.submenu');
    //    MenuView.open_top_sub_menu( submenu );
    //});
    
    //$('#top-menu').find('ul.main > li > .submenu ').mouseover(function() {
    //    $(this).show();
    //});

    $('#top-menu').find('ul.main > li > .submenu ').mouseenter(function() {
        $(this).show();
    });
    
    $('#top-menu').find('ul.main > li > .submenu ').mouseleave(function() {
        var submenu = $(this);
        MenuView.close_top_sub_menu( submenu );
    });
};
MenuView.prepare_top_menu_tablet  = function() {
    
    //alert( '_prepare_top_menu_tablet' );

    $('#top-menu').find( '.main'    ).show();
    $('#top-menu').find( '.submenu' ).hide().removeClass("open");

    $('#top-menu').find('ul.main > li > a').click(function() {

        var submenu = $(this).parent().children('.submenu');
        MenuView.open_top_sub_menu( submenu );

        //if( $(this).parent().find('.submenu').length > 0 ){
        if( $(this).parent().children('div').length > 0 ){
            return false;
        }else{
            return true;
        }

        return false;
    });

};
MenuView.prepare_top_menu_mobile  = function() {

    //console.log('_prepare_top_menu_mobile()');
    
    var top_menu_div = $("#top-menu");

    $('#top-menu').find( '.main'    ).hide();
    $('#top-menu').find( '.submenu' ).hide().removeClass("open");

    $('#top-menu').find( '#button' ).removeClass("open");


    $('#top-menu').find('#button').click(function() {

        //alert('al hamdo le Allah');

        var menu = $("#top-menu").find('ul.main');

        if( $(this).hasClass('open')){
            $(this).removeClass('open');
            menu.hide("slide", {direction: "up"}, 300);
        }else{
            $(this).addClass('open');
            menu.show("slide", {direction: "up"}, 300);
            menu.find(".submenu");
        }
        
    });

};

MenuView.open_top_sub_menu = function(submenu) {

    if( submenu.hasClass('open') ){
        return;
    }

    //(menu) (li)    main (ul)
    submenu.parent().parent().find('.submenu').hide().removeClass('open');

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

    submenu.show("slide", {direction: "up"}, 300, function (){
        //console.log('hiding...');
        $('#top-menu').find('ul.main > li > .submenu:not(.open)').hide();
        //console.log('done');
    });
};

MenuView.close_top_sub_menu = function(submenu) {

    submenu.hide().removeClass('open');

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

FrontDisplay.prepare_share_menu = function() {

    try{

    } catch (err) {
        console.error('Error in : FrontDisplay - prepare share menu [' + err + ']');
    }
    
};

FrontDisplay.prepare_contacts_menu = function() {

    try{

    } catch (err) {
        console.error('Error in : FrontDisplay - prepare contacts menu [' + err + ']');
    }
};

FrontDisplay.prepare_user_profile_tabs_menu = function() {

    try{

    } catch (err) {
        console.error('Error in : FrontDisplay - prepare user profile tabs menu [' + err + ']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function SlidesView(){}

SlidesView.index       = -1;
SlidesView.interval_id = -1;

SlidesView.init             = function(){

    var slide_div = $("#slide");

    SlidesView.slide_div = slide_div;

    //alert('init slide');
    var nav_divs = slide_div.find("#navs").find(".nav");

    nav_divs.click(function(){
        
        var slide_id = Utils.get_int( $(this).attr("id") ); 
        
        //alert( 'ID :   ' + slide_id );
        SlidesView.display_by_id(slide_id);
        
    });

    if( nav_divs.length > 0 ){
        SlidesView.display_next();
    }

    if( nav_divs.length > 1 ){
        //setTimeout(SlidesView.display_next, 12000);//12 seconds // one time
        SlidesView.interval_id = window.setInterval('SlidesView.display_next()', 12000);//12 seconds // repeated
    }else{
        nav_divs.hide();
    }
};

SlidesView.display_by_id    = function(slide_id){

    var slide_div = SlidesView.slide_div;

    var slide_nav_div = slide_div.find("#navs").find(".nav").filter('[id='+slide_id+']');

    SlidesView.index = slide_nav_div.data("index");

    window.clearInterval(SlidesView.interval_id);

    SlidesView.interval_id = window.setInterval('SlidesView.display_next()', 12000);//12 seconds // repeated

    SlidesView.display( slide_nav_div );

};

SlidesView.display_by_index = function(slideIndex){

    var slide_div = SlidesView.slide_div;

    var slide_nav_div = slide_div.find("#navs").find(".nav").filter('[data-index='+slideIndex+']');

    SlidesView.display( slide_nav_div );
};

SlidesView.display          = function(slide_nav_div){

    //alert('SlidesView.display_slide');
    var slide_div = SlidesView.slide_div;

    var type  = slide_nav_div.data("type");//472*265
    var file  = slide_nav_div.data("file");

    var title = slide_nav_div.data("title");
    var desc  = slide_nav_div.data("desc");

    var link  = slide_nav_div.data("link");

    slide_div.find("#navs").find(".nav").removeClass("nav_on");
    slide_nav_div.addClass("nav_on");

    type = Utils.get_int(type);

    var width  = 472;
    var height = 265;

    //console.log('type = '+type+', file = '+file+', width = '+width+', height = '+height );
    
    var pattern = '<a href="%s" title="'+title+'">%s</a>';

    //console.log('pattern = '+pattern+'' );

    var embed = SlidesView.get_slide_embed_output_html( file, type, width, height);
    
    //console.log('embed no click  = '+embed );
    //
    //console.log('title  = '+title );
    //console.log('desc   = '+desc  );
    //console.log('embed  = '+embed );

    embed  = sprintf( pattern, link, embed );

    title  = sprintf( pattern, link, title );
    desc   = sprintf( pattern, link, desc  );
    
    slide_div.find("#image").hide().html( embed ).fadeTo("slow",1.0);

    slide_div.find("#title").hide().html( title ).fadeTo("slow",1.0);
    slide_div.find("#desc" ).hide().html(  desc ).fadeTo("slow",1.0);

    //$("#Banner").find("#Desc").hide().html( more );

    return false;
};

SlidesView.display_next     = function(){

    //alert('SlidesView.display_next()');

    var slide_div = SlidesView.slide_div;

    SlidesView.index++;
    
    var nav_divs = slide_div.find("#navs").find(".nav");

    var lastIndex = nav_divs.length-1;
    SlidesView.index = ( SlidesView.index > lastIndex ) ? 0 : SlidesView.index;
    SlidesView.index = ( SlidesView.index < 0 ) ? lastIndex : SlidesView.index;

    SlidesView.display_by_index(SlidesView.index);
};

SlidesView.get_slide_embed_output_html = function( file, type, width, height){

    var output = '';

    switch( type ){
        
        case TYPE_IMAGE:
            //console.log('image');
            output = DisplayUtil.get_image_embed(g_root_url+"uploads/slides/"+file, -1, -1);
            break;

        case TYPE_YOUTUBE:
            //console.log('youtube');
            output = DisplayUtil.get_youtube_video_embed(file, width, height);
            break;

        case TYPE_VIMEO:
            //console.log('vimeo');
            output = DisplayUtil.get_vimeo_video_embed(file, width, height);
            break;

        case TYPE_VIDEO:
            //console.log('jwplayer');
            output = DisplayUtil.get_jsplayer_video_embed(file, width, height, 'uploads/slides', '', false);
            break;

        default:
            break;
    }

    return output;
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function NewsView(){}

NewsView.interval_id = -1;
NewsView.index       = 0;

NewsView.init             = function() {

    var news_box_div = $("#NewsBox");

    NewsView.news_box_div = news_box_div;

    if (news_box_div.length <= 0) { return; }

    var item_divs = news_box_div.find("#items").find(".item");

    item_divs.each(function (i) {

        $(this).on("click", function () {
    
            window.clearInterval(NewsView.interval_id);
            NewsView.interval_id = window.setInterval("NewsView.display_next()", 5000);
    
            var index  = $(this).data("index");
            NewsView.index = index;

            NewsView.display_by_index(index);
        });

    });

    if (item_divs.length > 1) {
        NewsView.index = -1;
        NewsView.display_next();
        NewsView.interval_id = window.setInterval("NewsView.display_next()", 5000);
    }
};

NewsView.display_next     = function() {

    var news_box_div = NewsView.news_box_div;

    NewsView.index++;

    var item_divs  = news_box_div.find("#items").find(".item");
    var last_index = item_divs.length - 1;

    NewsView.index = (NewsView.index > last_index) ? 0 : NewsView.index;
    NewsView.index = (NewsView.index < 0) ? last_index : NewsView.index;

    NewsView.display_by_index(NewsView.index);
};

NewsView.display_by_id    = function(news_id) {

    var news_box_div = NewsView.news_box_div;

    var news_div = news_box_div.find("#items").find(".item").filter("[id=" + news_id + "]");

    NewsView.index = news_div.data("index");

    NewsView.display(news_div);

};

NewsView.display_by_index = function(index) {

    var news_box_div = NewsView.news_box_div;

    var news_div = news_box_div.find("#items").find(".item").filter("[data-index=" + index + "]");

    NewsView.display(news_div);
};

NewsView.display          = function(news_div) {

    var news_box_div = NewsView.news_box_div;

    var title  = news_div.data("title");
    var desc   = news_div.data("desc");
    var image  = news_div.data("image");
    var folder = news_div.data("folder");
    var href   = news_div.data("href");
    var index  = news_div.data("index");

    NewsView.index = index;

    news_box_div.find("#items").find(".item").removeClass("item_selected");
    news_div.addClass("item_selected");

    var image_html = '<a href="' + href + '" title="' + title + '"><img src="' + g_root_url + "uploads/" + folder + "/" + image + '" /></a>';
    var title_html = '<a href="' + href + '" title="' + title + '">' + title + "</a>";

    var more_html  = '<a href="' + href + '" title="' + title + '">' + CDictionary.get_text('More_lbl') + '</a>';
    //var more_html  = '';

    news_box_div.find("#image").hide().html( image_html ).fadeTo("slow", 1);
    news_box_div.find("#title").hide().html( title_html ).fadeTo("slow", 1);
    news_box_div.find("#desc" ).hide().html( desc       ).fadeTo("slow", 1);
    news_box_div.find("#more" ).hide().html( more_html  ).fadeTo("slow", 1);

    return false;
};

////////////////////////////////////////////////////////////////////////////////

function SearchView(){}

SearchView.init_search = function(){

    var done = false;

    var search_div = $('#search');

    var searchTextField = search_div.find("input");
    var searchButton    = search_div.find("a");

    var searchText = CDictionary.get_text("StartSearch_lbl");
    searchTextField.val( searchText );

    searchButton.click( function() {

        var search_label = CDictionary.get_text("StartSearch_lbl");
        var search_item  = searchTextField.val();

        if( search_item != search_label && search_item != "" ){
            SearchView.load_search( search_item );
        }
    });

    searchTextField.focus(function() {

        var searchText = CDictionary.get_text("StartSearch_lbl");

        if( $(this).val() == searchText ){
            $(this).val("");
        }

        $(this).keydown(function(e){
            if(e.keyCode == 13) {
                if(!done){
                    done = true;
                    SearchView.load_search( searchTextField.val() );
                }
            }
        });

    });

    searchTextField.focusout(function() {
        if( $(this).val() == null || $(this).val() == "" ){
            var searchText = CDictionary.get_text("StartSearch_lbl");
            $(this).val(searchText);
        }
    });


    var qsearch_server_side = g_root_url+"ajax.php?action=qsearch";

    Utils.apply_auto_complete(searchTextField, qsearch_server_side, SearchView.load_search);

};

SearchView.load_search = function(search_item){

    ////alert( 'load_search('+search_item+')' );
    ////(en|ar)/(.*)/(.*)/5

    var url = '';
    
    if( g_use_meaningful_url ){

        url = UrlUtil.get_search_href(search_item, lang);

        //url = g_root_url+lang+'/'+CDictionary.get_text('Search_lbl')+'/'+search_item+'/8';

        ////alert( 'url = '+url );
        //url = url.toLowerCase().replace(/\s+/g,"-").replace(/&+/g,"").replace("?","");

    }else{
        url = '?page=search&search_item='+search_item+'';
    }

    window.location.href = url;

    //window.location.href = '?page=search&search_item='+search_item+'';

    return false;
};

////////////////////////////////////////////////////////////////////////////////

function PopupView(){}

PopupView.check_popup   = function() {

    //console.log('_check_popup()');

    var shown = Utils.get_cookie("popup-shown");

    //console.log('shown = '+shown);

    shown = ( shown == null ) ? 0 : shown;

    //console.log('shown = '+shown);

    if ( shown <= 0  ) {
        //console.log('shown = '+shown);
        PopupView.load_popup();
    }

};

PopupView.load_popup    = function(){

    //console.log('_load_popup()');
    
    var seqId = Math.floor(Math.random()*1000);

    var serverUrl = g_root_url+"ajax.php";

    var data      = "page=popup"
                  + "&seqId="+seqId;

    var xmlHttp = $.get(serverUrl, data, PopupView.on_load_popup, "json");

};

PopupView.on_load_popup = function(outputArray){
    
    //console.log('_on_load_popup()');

    var popup = outputArray.popup;

    if( popup != null && popup.active > 0 ){

        //console.log('popup active');

        CPopup.display( $('<div></div>').append( popup.content ).html(), '' );

        Utils.set_cookie( "popup-shown", 1, 7 ); 
        
        setTimeout( CPopup.close, 15000 );

        //display_popup(popup.content, '');
        //
        //Utils.set_cookie( "popup-shown", 1, 7 ); 
        //
        //setTimeout( close_popup, 15000 );
    }

};

////////////////////////////////////////////////////////////////////////////////
                                                                           