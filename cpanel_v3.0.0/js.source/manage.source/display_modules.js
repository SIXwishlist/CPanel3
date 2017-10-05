
/*! manage display modules */

/* global CDictionary, g_root_url, CPopup, AdminAuth, BackgroundRequests, MainGlobals, ADMIN_RULE_MASTER, ADMIN_RULE_CHECKER, ADMIN_RULE_ENTRY, CMSUtil, USER_TYPE_MASTER, USER_TYPE_MANAGER, AuthUtil, ManageOrganization, ManageOrganizationProfile */

$(document).ready(function(){

    try{
        
        //$('body').bind( BackgroundRequests.NOTIFICATIONS_LOADED, ManageModules.show_notifications );

    } catch (err) {
        console.error('error in main modules :[' + err + ']');
    }

});


function ManageModules(){}

ManageModules.show_main_modules   = function(){
    
    try{

        if( AdminAuth.admin_id <= 0 ){
            return AdminAuth.show_form();
        }

        ManageModules.show_master_modules();

        //switch(AdminAuth.rule_id){
        //
        //    case USER_TYPE_MASTER       :
        //        ManageModules.show_master_modules();
        //    break;
        //
        //    case USER_TYPE_ORG :
        //        ManageModules.show_organization_modules();
        //        break;
        //
        //    case USER_TYPE_ENTRY        :
        //        ManageModules.show_entry_modules();
        //        break;
        //
        //    case USER_TYPE_CHECKER      :
        //        ManageModules.show_checker_modules();
        //        break;
        //
        //}

        //BackgroundRequests.load_main_notifications();

    } catch (err) {
        console.error('error in : ManageModules - show home :[' + err + ']');
    }
};

ManageModules.show_master_modules = function(){

    try{
        
        var menu_div    = $("#body").find("#menu");
        var title_div   = $("#body").find("#title");
        var content_div = $("#body").find("#content");

        menu_div.html('');
        title_div.html('');
        content_div.html('');
        
        menu_div.show();

        var rule_id   = AdminAuth.rule_id;

        var menu_html = ManageModules.get_menu_by_rule( rule_id );

        var title_html = '<h1>' + CDictionary.get_text('Welcome_lbl') +' '+ AdminAuth.name +'</h1>';

        menu_div.html( menu_html );
        title_div.html( title_html );
        content_div.html( '' );


        var content_html = '<br /><br /><br />' +
                    '<div id="main-links">' +  
                    
                        '<div data-group="admin" ><a href="javascript:ManageSectionChilds.init();"          ><img src="'+g_root_url+'images/manage/main-icons/website-content.png" > ' + CDictionary.get_text('WebsiteContent_lbl')      + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageCategoryChilds.init();"         ><img src="'+g_root_url+'images/manage/main-icons/categories.png"      > ' + CDictionary.get_text('Categories_lbl')          + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageSlide.init();"                  ><img src="'+g_root_url+'images/manage/main-icons/slides.png"          > ' + CDictionary.get_text('Slides_lbl')              + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageAd.init();"                     ><img src="'+g_root_url+'images/manage/main-icons/adverts.png"         > ' + CDictionary.get_text('Ads_lbl')                 + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageUser.init();"                   ><img src="'+g_root_url+'images/manage/main-icons/users.png"           > ' + CDictionary.get_text('Users_lbl')               + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManagePayment.init();"                ><img src="'+g_root_url+'images/manage/main-icons/payments.png"        > ' + CDictionary.get_text('Payments_lbl')            + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageWishedItem.init();"             ><img src="'+g_root_url+'images/manage/main-icons/wishlist.png"        > ' + CDictionary.get_text('WishedItems_lbl')         + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageCache.rebuild_section_tree();"  ><img src="'+g_root_url+'images/manage/main-icons/build-tree.png"      > ' + CDictionary.get_text('RebuildSectionTree_lbl')  + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageCache.rebuild_category_tree();" ><img src="'+g_root_url+'images/manage/main-icons/build-tree.png"      > ' + CDictionary.get_text('RebuildCategoryTree_lbl') + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageCache.clear_cache();"           ><img src="'+g_root_url+'images/manage/main-icons/clear-cache.png"     > ' + CDictionary.get_text('ClearCache_lbl')          + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:ManageModules.open_upload_popup();"   ><img src="'+g_root_url+'images/manage/main-icons/media-center.png"    > ' + CDictionary.get_text('MediaCenter_lbl')         + '</a></div>' + 
                        
                        '<div data-group="admin" ><a href="javascript:ManageStatistics.init();"             ><img src="'+g_root_url+'images/manage/main-icons/stats.png"           > ' + CDictionary.get_text('Statistics_lbl')          + '</a></div>' + 
                        //'<div data-group="admin" ><a href="javascript:ManageStatistics.show_hits();"        ><img src="'+g_root_url+'images/manage/main-icons/hits.png"            > ' + CDictionary.get_text('Hits_lbl')                + '</a></div>' + 
                
                        '<div data-group="admin" ><a href="javascript:ManageAdmin.init();"                  ><img src="'+g_root_url+'images/manage/main-icons/admins.png"          > ' + CDictionary.get_text('Admins_lbl')              + '</a></div>' + 
                        '<div data-group="admin" ><a href="javascript:AdminAuth.logout()"                   ><img src="'+g_root_url+'images/manage/main-icons/logout.png"          > ' + CDictionary.get_text('Logout_lbl')              + '</a></div>' + 
                    '</div>';

        content_div.html( content_html );

    } catch (err) {
        console.error('Error in : ManageModules - show master modules :[' + err + ']');
    }
};

ManageModules.get_menu_by_rule    = function(rule){
    
    var menu_html = '';

    menu_html = '<a data-module="home" href="#" onclick="ManageModules.show_main_modules(); AuthUtil.highlight_button(this); return false;">'
                          //+ '<i class="ion-android-home"></i> '
                          + '<i class="fa fa-home" aria-hidden="true"></i> '
                          + CDictionary.get_text('Home_lbl')
                      + '</a>'
                        //+ '<span> | </span>'
                      //+ '<a data-module="settings" href="#" onclick="ManageSettingsForm.init();         AuthUtil.highlight_button(this); return false;">'
                      //  //+ '<i class="ion-ios-home"></i> '
                      //  + '<i class="fa fa-cog" aria-hidden="true"></i> '
                      //  + CDictionary.get_text('Settings_lbl')
                      //+ '</a>'
                        //+ '<span> | </span>'
                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
                          //+ '<i class="ion-log-out"></i> '
                          + CDictionary.get_text('Logout_lbl')
                      + '</a>';



//    switch (rule){
//
//        case USER_TYPE_MASTER:
//            menu_html = '<a data-module="organizations" href="#" onclick="ManageOrganization.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-building" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Organizations_lbl')
//                      + '</a>'
//                      + '<a data-module="certificates" href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="um-faicon-file-text-o"></i> '
//                          + '<i class="fa fa-file-text-o" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Certificates_lbl')
//                      + '</a>'
//                      + '<a data-module="users" href="#" onclick="ManageUser.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-user" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Users_lbl')
//                      + '</a>'
//                      //+ '<span> | </span>'
//                      + '<a data-module="settings" href="#" onclick="ManageSettingsForm.init();         AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-ios-home"></i> '
//                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Settings_lbl')
//                      + '</a>'
//                      //+ '<span> | </span>'
//                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
//                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
//                          //+ '<i class="ion-log-out"></i> '
//                          + CDictionary.get_text('Logout_lbl')
//                      + '</a>';
//            break;
//
//        case USER_TYPE_ORG:
//            menu_html = '<a data-module="organizations" href="#" onclick="ManageOrganizationProfile.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-building" aria-hidden="true"></i> '
//                          + CDictionary.get_text('MyOrganization_lbl')
//                      + '</a>'
//                      + '<a data-module="certificates" href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="um-faicon-file-text-o"></i> '
//                          + '<i class="fa fa-file-text-o" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Certificates_lbl')
//                      + '</a>'
//                      + '<a data-module="users" href="#" onclick="ManageUser.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-user" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Users_lbl')
//                      + '</a>'
//                      + '<a data-module="activities" href="#" onclick="ManageActivity.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-sellsy" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Activities_lbl')
//                      + '</a>'
//                      + '<a data-module="notifications" href="#" onclick="ManageNotification.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-sellsy" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Notifications_lbl')
//                      + '</a>'
//                      + '<a data-module="settings" href="#" onclick="ManageSettingsForm.init();         AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-ios-home"></i> '
//                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Settings_lbl')
//                      + '</a>'
//                      //+ '<span> | </span>'
//                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
//                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
//                          //+ '<i class="ion-log-out"></i> '
//                          + CDictionary.get_text('Logout_lbl')
//                      + '</a>';
//            break;
//
//        case USER_TYPE_CHECKER:
//            menu_html = '<a data-module="organizations" href="#" onclick="ManageOrganizationProfile.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-building" aria-hidden="true"></i> '
//                          + CDictionary.get_text('MyOrganization_lbl')
//                      + '</a>'
//                      + '<a data-module="certificates" href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="um-faicon-file-text-o"></i> '
//                          + '<i class="fa fa-file-text-o" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Certificates_lbl')
//                      + '</a>'
//                      + '<a data-module="settings" href="#" onclick="ManageSettingsForm.init();         AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-ios-home"></i> '
//                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Settings_lbl')
//                      + '</a>'
//                      //+ '<span> | </span>'
//                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
//                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
//                          //+ '<i class="ion-log-out"></i> '
//                          + CDictionary.get_text('Logout_lbl')
//                      + '</a>';
//            break;
//
//        case USER_TYPE_ENTRY:
//            menu_html = '<a data-module="organizations" href="#" onclick="ManageOrganizationProfile.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-android-home"></i> '
//                          + '<i class="fa fa-building" aria-hidden="true"></i> '
//                          + CDictionary.get_text('MyOrganization_lbl')
//                      + '</a>'
//                      + '<a data-module="certificates" href="#" onclick="ManageCertificate.init(); AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="um-faicon-file-text-o"></i> '
//                          + '<i class="fa fa-file-text-o" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Certificates_lbl')
//                      + '</a>'
//                      + '<a data-module="settings" href="#" onclick="ManageSettingsForm.init();         AuthUtil.highlight_button(this); return false;">'
//                          //+ '<i class="ion-ios-home"></i> '
//                          + '<i class="fa fa-cog" aria-hidden="true"></i> '
//                          + CDictionary.get_text('Settings_lbl')
//                      + '</a>'
//                      //+ '<span> | </span>'
//                      + '<a href="#" onclick="AdminAuth.logout();        AuthUtil.highlight_button(this); return false;">'
//                          + '<i class="fa fa-sign-out" aria-hidden="true"></i> '
//                          //+ '<i class="ion-log-out"></i> '
//                          + CDictionary.get_text('Logout_lbl')
//                      + '</a>';
//            break;
//            
//        default:
//            //menu_html = '<a href="#" onclick="AdminAuth.logout(); return false;">Log Out</a>';
//            menu_html = '<a href="#" onclick="AdminAuth.logout(); AuthUtil.highlight_button(this); return false;">'
//                          + '<i class="ion-log-out"></i> '
//                          + CDictionary.get_text('Logout_lbl')
//                      + '</a>';
//            break;
//    }

    return  menu_html;
};

ManageModules.open_upload_popup   = function(){
    
    //http://localhost/helping/js/kcfinder/browse.php?type=images&CKEditor=editor1&CKEditorFuncNum=1&langCode=en
    
    var width  = 950;
    var height = 550;
    
    //var host = "http://"+window.location.hostname;
    var kcfinder_url  = g_root_url+'js/kcfinder/browse.php';
    
    var kcfinder_iframe = '<iframe width="'+width+'" height="'+height+'" src="'+kcfinder_url+'" frameborder="0" allowfullscreen="1"></iframe>';
    
    var content = '<div style="width: '+width+'px; height: '+height+'px; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; overflow: hidden;">'+kcfinder_iframe+'</div>';
    
    CPopup.display( content );

};