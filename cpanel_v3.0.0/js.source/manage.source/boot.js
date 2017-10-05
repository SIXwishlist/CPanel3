
/*! boot */

/* global AdminAuth, CDictionary, CPopup, UrlUtil */

$(document).ready(function(){

    //alert('auth.js Loaded');

    try{

        $('body').bind(AdminAuth.LOGIN_LOADED, HomeDisplay.update_top_menu);

        //$('body').bind(CDictionary.DICTIONARY_LOADED, AdminAuth.check_login);
        $('body').bind("dictionary-loaded", AdminAuth.check_login);

        CDictionary.init();
        CDictionary.load();
        CDictionary.set_lang(lang);

        CPopup.set_options({"theme":"default"});
        //CPopup.set_style("white-style");

    } catch (err) {
        console.error('Error in boot main :[' + err + ']');
    }

});


function HomeDisplay(){}

HomeDisplay.update_top_menu   = function(){

    try{

        var top_menu_div = $("#head").find("#top-menu");
        
        var top_menu_html = '';

        var admin_id = AdminAuth.admin_id;

        if( admin_id > 0 ){

            //top_menu_html = '<a href="'+UrlUtil.get_home_url()+'" onclick="">' + 
            top_menu_html = '<a href="'+UrlUtil.get_manage_url()+'" onclick="">' + 
                          '<i class="fa fa-home" aria-hidden="true"></i> ' +
                          CDictionary.get_text('Home_lbl') + 
                      '</a>' + 
                      //'<a href="'+UrlUtil.get_about_url()+'" onclick="">' + 
                      //    '<i class="fa fa-cog" aria-hidden="true"></i> ' +
                      //    CDictionary.get_text('About_lbl') + 
                      //'</a>' + 
                      '<a href="'+UrlUtil.get_manage_url()+'" onclick="">' + 
                          '<i class="fa fa-user" aria-hidden="true"></i> ' +
                          CDictionary.get_text('Account_lbl') + 
                      '</a>' + 
                      '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">' + 
                          '<i class="fa fa-sign-out" aria-hidden="true"></i> '+ 
                          CDictionary.get_text('Logout_lbl') + 
                      '</a>';

        }else{
            
            //top_menu_html = '<a href="'+UrlUtil.get_home_url()+'" onclick="">' + 
            top_menu_html = '<a href="'+UrlUtil.get_manage_url()+'" onclick="">' + 
                          '<i class="fa fa-home" aria-hidden="true"></i> ' +
                          CDictionary.get_text('Home_lbl') + 
                      '</a>' + 
                      //'<a href="'+UrlUtil.get_about_url()+'" onclick="">' + 
                      //    '<i class="fa fa-cog" aria-hidden="true"></i> ' +
                      //    CDictionary.get_text('About_lbl') + 
                      //'</a>' + 
                      '<a href="'+UrlUtil.get_manage_url()+'" onclick="">' + 
                          '<i class="fa fa-user" aria-hidden="true"></i> ' +
                          CDictionary.get_text('Login_lbl') + 
                      '</a>';// + 
                      //'<a href="'+UrlUtil.get_signup_url()+'" onclick="">' + 
                      //    '<i class="fa fa-user" aria-hidden="true"></i> ' +
                      //    CDictionary.get_text('SignUp_lbl') + 
                      //'</a>';
        }

        top_menu_div.html(top_menu_html);

    } catch (err) {
        console.error('Error in : HomeDisplay - update menu :[' + err + ']');
    }

};
