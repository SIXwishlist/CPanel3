
/*! notifications */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, AdminAuth, USER_TYPE_MASTER, Globals, CMSExtraUtil */

function ManageNotification() {}

////////////////////////////////////////////////////////////////////////////////

ManageNotification.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=notifications]").addClass('active');


        content_div.html( '' );

        ManageNotificationList.init();

    } catch(err) {
        console.error('Error in : ManageNotification - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageNotificationList() {}
ManageNotificationList.index = 0;
ManageNotificationList.count = 10;

ManageNotificationList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Notifications_lbl') + '</div>' + 
            '</div>' 
        );

        content_div.append( '<div class="clearfix"></div>' );

        content_div.append(
            '<div id="show-area" class="clearfix">' +
                '<div id="form"></div>' +
                '<div id="list"></div>' +
            '</div>' 
        );

        var form_div = $("#body").find("#content").find("#form");
        var list_div = $("#body").find("#content").find("#list");

        ManageNotificationList.form_div = form_div;
        ManageNotificationList.list_div = list_div;

        ManageNotificationList.load();

    } catch(err) {
        console.error('Error in : ManageNotification - init : [' + err +']');
    }
};

ManageNotificationList.load         = function (index, count) {
    
    try{

        var list_div = ManageNotificationList.list_div;
        var form_div = ManageNotificationList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        index = ( index === undefined ) ? ManageNotificationList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageNotificationList.count : Utils.get_int(count);

        ManageNotificationList.index = index;
        ManageNotificationList.count = count;

        //var org_id = Globals.user.org_id;
        var org_id = AdminAuth.org_id;

        var data = "action=notifications"
                + "&org_id="+org_id;
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var notifications       = outputArray["notifications"];
                    var notifications_count = outputArray["notifications_count"];

                    ManageNotificationList.display_list(notifications, notifications_count, CMSUtil.PAGINATION_LIST);

                    //ManageNotificationList.display_chart(notifications);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageNotificationList - load :['+err+']');
    }

};

ManageNotificationList.display_list = function (notifications, notifications_count, source){

    try{

        ManageNotificationList.array = notifications;

        var list_div = ManageNotificationList.list_div;

        var notifications_html = '';

        notifications_html += '<div id="notifications">';

        for(var i=0; i<notifications.length; i++){

            var notification = notifications[i];

            //not_id 	action 	desc 	time 	status 	user_id 	target_id

            notifications_html += '<div class="notification">' +
                                   '<div class="desc">'+notification.desc+'</div>' + 
                                   '<div class="action">'+notification.action+'</div>' + 
                                   '<div class="status">'+notification.status+'</div>' +
                                   '<div class="time">'+notification.time+'</div>' +
                                   '<div class="user">'+notification.user+'</div>' + 
                                   '<div class="target">'+notification.organization+'</div>' + 
                              '</div>';

        }

        notifications_html += '</div>';

        list_div.html(notifications_html);

    }catch(err){
        console.error('Error in : ManageNotificationList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

ManageNotificationList.print  = function(notification_div){
  
    try{

        CMSExtraUtil.print_div_popup(notification_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageNotificationForm - print :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
