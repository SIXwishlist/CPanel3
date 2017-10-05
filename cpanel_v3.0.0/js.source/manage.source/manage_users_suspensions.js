
/* global CMSUtil, Utils, CDictionary, RequestUtil, CPopup, MainGlobals */

/*! users suspensions */

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageUserSuspendList() {}

ManageUserSuspendList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append(
            '<div class="top_label_main" onclick="ManageUserSuspendList.init(); return false;">' + CDictionary.get_text('SuspendedUsers_lbl') + '</div>' +
            '<br /><br />' +
            '<br />' +
            '<div id="module-path" class="clearfix">' + 
                '<a class="" href="#reviewed-users" onclick="ManageUserSuspendList.init();">' + CDictionary.get_text('SuspendedUsers_lbl') + '</a>' +
            '</div>' +
            '<div id="list"></div>' 
        );
        
        var list_div = $("#body").find("#content").find("#list");
        
        ManageUserSuspendList.list_div = list_div;

        ManageUserSuspendList.load();

    
    } catch(err) {
        console.error('Error in : ManageAdmission - init : [' + err +']');
    }
};

ManageUserSuspendList.load         = function (index, count) {
    
    try{

        var list_div = ManageUserSuspendList.list_div;

        index = Utils.get_int(index);
        count = Utils.get_int(count);
        count = ( count > 0 ) ? count : 10;

        ManageUserSuspendList.index = index;
        ManageUserSuspendList.count = count;

        var data = "action=suspended_users"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var users       = outputArray["users"];
                    var users_count = outputArray["users_count"];

                    ManageUserSuspendList.display_list(users, users_count, CMSUtil.PAGINATION_LIST);

                    //ManageUserSuspendList.display_chart(users);

                }

            } catch (err) {
                console.log('error in load admission users request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageUserSuspendList - load :['+err+']');
    }

};

ManageUserSuspendList.display_list = function (users, result_count, source){

    try{

        ManageUserSuspendForm.array = users;
        
        ManageUserSuspendForm.source = 1;

        var list_div = ManageUserSuspendList.list_div;

        var labels   = [ "User ID",                                 "Name", "Un Suspend" ];
        var fields   = [ "ManageUserSuspendOutput.get_id(user_id)", "name", "ManageUserSuspendOutput.get_unsuspend_link(user_id)" ];
        var id_label = "user_id";

        var array = users;

        CMSUtil.show_list(list_div, labels, fields, array, id_label, null, 'ManageUserSuspendForm.remove', 'ManageUserSuspendForm.view');//'ManageUserSuspendForm.view'

        var func = ( source == CMSUtil.PAGINATION_SEARCH ) ?'ManageUserSuspendList.search':'ManageUserSuspendList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageUserSuspendList.index, ManageUserSuspendList.count, (ManageUserSuspendList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageUserSuspendList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////

function ManageUserSuspendForm() {

    this.user_id = -1;
    this.array     = [];

}

ManageUserSuspendForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'user';

        var form_properties = { 

            name    : name,

            //user_id 	
            //username 	password 	email 	phone 	key 	code 	
            //fname 	lname 	icon 	birth_date 	gender 	country
            // 	created 	updated 	status 	rule_id

            params  : [
                
                { name : "name",       type : "text",     label : CDictionary.get_text('UserForm_Username_lbl') +":" },
                { name : "password",   type : "password", label : CDictionary.get_text('UserForm_Password_lbl') +":" },
                { name : "email",      type : "text",     label : CDictionary.get_text('UserForm_Email_lbl')    +":" },
                
                { type : "seprator",   label : "" },

                { name : "phone",      type : "text",     label : CDictionary.get_text('UserForm_Phone_lbl')    +":" },
                { name : "key",        type : "text",     label : CDictionary.get_text('UserForm_Key_lbl')      +":" },
                { name : "code",       type : "text",     label : CDictionary.get_text('UserForm_Code_lbl')     +":" },

                { type : "seprator",   label : "" },

                //{ name : "fname",      type : "text",     label : CDictionary.get_text('UserForm_FName_lbl')     +":" },
                //{ name : "lname",      type : "text",     label : CDictionary.get_text('UserForm_LName_lbl')     +":" },
                { name : "icon",       type : "image",    label : CDictionary.get_text('UserForm_Icon_lbl')     +":" },
                { name : "birth_date", type : "date",     format : "yy-mm-dd", label : CDictionary.get_text('UserForm_BirthDate_lbl')     +":" },
                { name : "gender",     type : "select",   label : CDictionary.get_text('UserForm_Gender_lbl')     +":" },

                { type : "seprator",   label : "" },

                { name : "country",    type : "select",   label : CDictionary.get_text('UserForm_Country_lbl')   +":" },

                { type : "seprator",   label : "" },
                                
                { name : "created",    type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_Created_lbl')  +":" },
                { name : "updated",    type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_Updated_lbl')  +":" },
                
                { type : "seprator",   label : "" },

                { name : "suspend_date", type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_SuspendDate_lbl')  +":" },
                
                //{ name : "verified",   type : "checkbox", label : CDictionary.get_text('UserForm_Verified_lbl') +":", value : "1" },
                { name : "status",     type : "select",   label : CDictionary.get_text('UserForm_Status_lbl')   +":" },
                { name : "rule_id",    type : "select",   label : CDictionary.get_text('UserForm_RuleId_lbl')   +":" },

                { name : "user_id", type : "hidden", label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style2'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageUserSuspendForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageUserSuspendForm.remove    = function(user_id){

    try{

        delete_popup( function(){

            var list_div = ManageUserSuspendForm.list_div;

            var data = "action=remove_user"
                        + "&user_id=" + user_id;

            RequestUtil.quick_post_request(list_div, data, ManageUserSuspendForm.callback);

        });

    }catch(err){
        console.log('Error in : ManageUserSuspendForm - remove :['+err+']');
    }
    
};

ManageUserSuspendForm.unsuspend = function(user_id){

    try{
        
        user_id = Utils.get_int(user_id);
                
        var title   = "Confirm Un Suspend User?";
        var message = "Are you sure you want to unsuspend this User ?";

        swal({
          title: title,  //"Confirm Delete ?",
          text:  message, //"Are you sure you want to delete ?",
          type:  "warning",
          showCancelButton: true,
          confirmButtonColor: "#dd2727",
          confirmButtonText: "Yes, Un Suspend it!",
          closeOnConfirm: true
        }, function(){

            var list_div = ManageUserSuspendList.list_div;

            var data = "action=unsuspend_user"
                        + "&user_id=" + user_id;

            RequestUtil.quick_post_request(list_div, data, ManageUserSuspendForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageUserSuspendForm - forbid :['+err+']');
    }
    
};

ManageUserSuspendForm.view      = function(user_id) {

    try{
        
        user_id = Utils.get_int(user_id);

        var user = ManageUserSuspendForm.get_object(user_id);

        var preview_object = ManageUserSuspendForm.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();

        preview_div.find( 'div[name=user_id]'    ).html( user.user_id  );

        preview_div.find( 'div[name=name]'       ).html( user.name     );
        preview_div.find( 'div[name=password]'   ).html( user.password );
        preview_div.find( 'div[name=email]'      ).html( user.email    );

        preview_div.find( 'div[name=phone]'      ).html( user.phone );
        preview_div.find( 'div[name=key]'        ).html( user.key   );
        preview_div.find( 'div[name=code]'       ).html( user.code  );
        
        //preview_div.find( 'div[name=fname]'      ).html( user.fname    );
        //preview_div.find( 'div[name=lname]'      ).html( user.lname    );
        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/users/'+user.icon+'"  />' );
        preview_div.find( 'div[name=birth_date]' ).html( user.birth_date );
        preview_div.find( 'div[name=gender]'     ).html( ManageUserSuspendOutput.get_gender(user.gender) );
        preview_div.find( 'div[name=country]'    ).html( ManageUserSuspendOutput.get_country(user.country) );
        
        preview_div.find( 'div[name=created]'    ).html( user.created );
        preview_div.find( 'div[name=updated]'    ).html( user.updated );

        preview_div.find( 'div[name=suspend_date]' ).html( user.suspend_date );

        preview_div.find( 'div[name=status]'     ).html( ManageUserSuspendOutput.get_status( user.status ) );
        preview_div.find( 'div[name=rule_id]'    ).html( ManageUserSuspendOutput.get_rule( user.rule_id ) );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'User Info'  );

    }catch(err){
        console.error('Error in : ManageUserSuspendForm - view :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageUserSuspendForm.get_object  = function(user_id) {

    var user = null;

    try{
        
        var user_array = ManageUserSuspendForm.array;

        for(var i=0; i<user_array.length; i++){

            if( user_array[i].user_id == user_id ){
                
                user = user_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageUserSuspendList - get user :['+err+']');
    }

    return user;
};

ManageUserSuspendForm.callback    = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            show_success("Action completed!");
        }else{
            show_error("Action not completed", "Problem with update, please make sure that you already login by reload page, ");
        }

        if( ManageUserSuspendForm.source == 1 ){
            ManageUserSuspendList.load();
        }else{
            ManageUserSuspendList.search();
        }

        $('body').trigger( "users_updated" );

    }catch(err){
        console.error('Error in : ManageUserSuspendForm - callback :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

function ManageUserSuspendOutput() {}

ManageUserSuspendOutput.get_id             = function( user_id ){
    return number_pad(user_id, 6);
};

ManageUserSuspendOutput.get_forbid_link    = function( user_id ){
    
    //alert( 'user_id : ' + user_id );

    var link_name = '<a href="#" onclick="ManageUserSuspendForm.forbid('+user_id+')">'+CDictionary.get_text('SuspendForm_Forbid_lbl')+'</a>';
    
    return link_name;

};

ManageUserSuspendOutput.get_unsuspend_link = function( user_id ){
    
    //alert( 'user_id : ' + user_id );

    var link_name = '<a href="#" onclick="ManageUserSuspendForm.unsuspend('+user_id+')">'+CDictionary.get_text('Unsuspend_lbl')+'</a>';
    
    return link_name;

};


ManageUserSuspendOutput.get_status  = function( status ){
    
    var user_status = '';
    
    status = Utils.get_int(status);
    
    switch (status){
        
        case USER_STATUS_NOT_VERIFIED:
            user_status = CDictionary.get_text('UserForm_Status_NotVerified_lbl');
            break;
        
        case USER_STATUS_EMAIL_VERIFIED:
            user_status = CDictionary.get_text('UserForm_Status_EmailVerified_lbl');
            break;

        case USER_STATUS_PHONE_VERIFIED:
            user_status = CDictionary.get_text('UserForm_Status_PhoneVerified_lbl');
            break;

        default:
            user_status = CDictionary.get_text('UserForm_Status_NotVerified_lbl');
            break;
    }
    
    return user_status;

};

ManageUserSuspendOutput.get_rule    = function( rule_id ){
    
    var user_rule = '';
    
    rule_id = Utils.get_int(rule_id);
    
    switch (rule_id){
        
        case USER_RULE_NORMAL:
            user_rule = CDictionary.get_text('UserForm_Rule_Normal_lbl');
            break;

        case USER_RULE_PRIVATE:
            user_rule = CDictionary.get_text('UserForm_Rule_Private_lbl');
            break;

        case USER_RULE_SUSPENDED:
            user_rule = CDictionary.get_text('UserForm_Rule_Suspended_lbl');
            break;

        case USER_RULE_BLOCKED:
            user_rule = CDictionary.get_text('UserForm_Rule_Blocked_lbl');
            break;
        
        default:
            user_rule = CDictionary.get_text('UserForm_Rule_Normal_lbl');
            break;
    }
    
    return user_rule;

};

ManageUserSuspendOutput.get_gender  = function( gender ){

    var user_gender = '';

    gender = Utils.get_int(gender);

    switch (gender){

        case USER_GENDER_MALE:
            user_gender = CDictionary.get_text('UserForm_Gender_Male_lbl');
            break;
        
        case USER_GENDER_FEMALE:
            user_gender = CDictionary.get_text('UserForm_Gender_Female_lbl');
            break;

        default:
            user_gender = CDictionary.get_text('UserForm_Gender_Male_lbl');
            break;
    }
    
    return user_gender;

};

ManageUserSuspendOutput.get_country = function( country_name ){

    var user_country = {};

    //country = Utils.get_int(country);

    var countries = MainGlobals.countries;

    for(var i=0; i<countries.length; i++){
        
        var country_object = countries[i];
        
        if( country_name == country_object.name ){
            user_country = CDictionary.get_text_by_lang(country_object, "name");
        }
    }
    
    return user_country;

};


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////