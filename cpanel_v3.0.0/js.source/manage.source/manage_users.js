
/*! users */

/* global CDictionary, g_request_url, CMSUtil, CPopup, Validate, g_root_url, Utils, RequestUtil, MainGlobals, BackgroundRequests, CMSExtraUtil */

var USER_STATUS_NOT_VERIFIED   = 0;
var USER_STATUS_EMAIL_VERIFIED = 1;
var USER_STATUS_PHONE_VERIFIED = 2;


var USER_RULE_NORMAL       = 1;
var USER_RULE_PRIVATE      = 2;
var USER_RULE_SUSPENDED    = 3;
var USER_RULE_BLOCKED      = 4;


var USER_GENDER_MALE       = 1;
var USER_GENDER_FEMALE     = 2;


function ManageUser() {}

////////////////////////////////////////////////////////////////////////////////

ManageUser.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=users]").addClass('active');


        content_div.html( '' );

        ManageUserList.init();
        
        BackgroundRequests.load_countries();

    } catch(err) {
        console.error('Error in : ManageUser - init : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageUserList() {}
ManageUserList.index = 0;
ManageUserList.count = 10;

ManageUserList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            //'<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Users_lbl') + '</div>' + 
                '<div class="top_label new_label" onclick="ManageUserForm.add(); return false;">' +
                    //'<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_label search_label" onclick="ManageUserForm.search(); return false;">' +
                    //'<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageUserForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageUserForm.export_form(); return false;">' +
                //    '<i class="fa fa-share-square-o" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Export_lbl') + 
                //'</div>' + 
            //'</div>' 
                '<div class="clearfix" style="margin-top: 30px; clear: both;"><br /></div>'
        );

        content_div.append( '<div class="clearfix"></div>' );

        //content_div.append( 
        //    '<div id="form_cell_new"></div>'    +
        //    '<div id="form_cell_search"></div>' +
        //    '<div id="form_cell_import"></div>' +
        //    '<div id="form_cell_export"></div>'
        //);

        content_div.append(
            '<div id="show-area" class="clearfix">' +
                //'<div class="clearfix"><br /></div>' + 
                '<div id="form"></div>' +
                '<div id="list"></div>' +
            '</div>' 
        );
        
        var form_div = $("#body").find("#content").find("#form");
        var list_div = $("#body").find("#content").find("#list");
        
        ManageUserList.form_div = form_div;
        ManageUserList.list_div = list_div;

        ManageUserList.load();

        //if( UserAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageUser - init : [' + err +']');
    }

};

ManageUserList.load         = function (index, count) {
    
    try{
        
        var list_div = ManageUserList.list_div;
        var form_div = ManageUserList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        index = ( index === undefined ) ? ManageUserList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageUserList.count : Utils.get_int(count);

        ManageUserList.index = index;
        ManageUserList.count = count;

        var data = "action=users"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var users       = outputArray["users"];
                    var users_count = outputArray["users_count"];

                    ManageUserList.display_list(users, users_count, CMSUtil.PAGINATION_LIST);

                    //ManageUserList.display_chart(users);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });


    } catch(err) {
        console.error('Error in : ManageUserList - load :['+err+']');
    }

};

ManageUserList.search       = function (index, count) {
    
    try{

        var list_div = ManageUserList.list_div;

        ManageUserList.index = Utils.get_int( index );
        ManageUserList.count = Utils.get_int( count );
        ManageUserList.count = ( ManageUserList.count > 0 ) ? ManageUserList.count : 10;

        var data = ManageUserList.search_object;

        data["index"] = ManageUserList.index;
        data["count"] = ManageUserList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var users       = outputArray["users"];
                    var users_count = outputArray["users_count"];

                    ManageUserList.display_list(users, users_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageUserList.display_chart(users);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageUserList - load :['+err+']');
    }

};

ManageUserList.display_list = function (array, result_count, source){

    try{

        ManageUserList.array = array;

        var list_div = ManageUserList.list_div;

        var labels   = CDictionary.get_labels([
                        'UserList_UserId_lbl', 
                        'UserList_Username_lbl', 
                        'UserList_Password_lbl', 
                        'UserList_Email_lbl', 
                        'UserList_Phone_lbl', 
                        'UserList_Payments_lbl', 
                        'UserList_Wishlist_lbl', 
                        'UserList_Status_lbl',
                        'UserList_Rule_lbl' ]);

        var fields   = [ "ManageUserOutput.get_id(user_id)", 
                         "name", 
                         "password", 
                         "email", 
                         "phone", 
                         "ManageUserOutput.get_payments(user_id)", 
                         "ManageUserOutput.get_wishlist(user_id)", 
                         "ManageUserOutput.get_status(status)", 
                         "ManageUserOutput.get_rule(rule)" ];

        var id_label = "user_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageUserForm.edit', 'ManageUserForm.remove', 'ManageUserForm.view');//'ManageUserForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageUserList.search':'ManageUserList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageUserList.index, ManageUserList.count, (ManageUserList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageUserList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageUserForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageUserForm.get_search_form_properties = function(){
    
    var form_object = {};

    try{

        var name = 'user_search';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "user_id",   type : "text",       label : CDictionary.get_text('UserForm_UserId_lbl')+":"           },
                { name : "name",      type : "text",       label : CDictionary.get_text('UserForm_Name_lbl')+":"              },

                { type : "seprator",  label : "" },

                { name : "email",     type : "text",       label : CDictionary.get_text('UserForm_Email_lbl')+":"            },
                { name : "phone",     type : "text",       label : CDictionary.get_text('UserForm_Phone_lbl')+":"            },
                
                { type : "seprator",  label : "" },
                
                { name : "country",   type : "select",     label : CDictionary.get_text('UserForm_Country_lbl')+":"           },

                { type : "seprator",  label : "" },

                { name : "status",    type : "select",     label : CDictionary.get_text('UserForm_Status_lbl')+":"            },
                { name : "rule_id",   type : "select",     label : CDictionary.get_text('UserForm_RuleId_lbl')+":"            }

            ],

            action  : '',
            method  : 'post',
            enctype : 'application/x-www-form-urlencoded',

            style   : 'style2'

        };

        form_object = new CForm(form_properties);

    } catch(err) {
        console.error('Error in : ManageUserForm - get search form properties :['+err+']');
    }

    return form_object;
};

ManageUserForm.search_form = function(){

    try{

        var content_div = $("#body").find("#content");

        content_div.find("#form_cell_new").html('');
        content_div.find("#form_cell_import").html('');

        var cont_div_cell = content_div.find("#form_cell_search");
        cont_div_cell.html( '' );

        var form_object = ManageUserForm.get_search_form_properties(); 

        var form_div    = form_object.get_form_div();

        form_div.find( 'select[name=country]'   ).html( ManageUserForm.get_country_select() );

        form_div.find( 'select[name=status]'    ).html( ManageUserForm.get_status_select() );
        form_div.find( 'select[name=rule_id]'   ).html( ManageUserForm.get_rule_select() );

        cont_div_cell.html(form_div);

        form_div.find( 'input[type=submit]' ).click( function(event) {

            event.preventDefault();
            
            var data = {
                action     : "search_users",

                user_id    : form_div.find( 'input[name=user_id]'   ).val() ,
                name       : form_div.find( 'input[name=name]'      ).val() ,
                email      : form_div.find( 'input[name=email]'     ).val() ,
                phone      : form_div.find( 'input[name=phone]'     ).val() ,
                country    : form_div.find( 'select[name=country]'  ).val() ,
                status     : form_div.find( 'select[name=status]'   ).val() ,
                rule_id    : form_div.find( 'select[name=rule_id]'  ).val()
            };

            ManageUserList.search_object = data;

            ManageUserList.search(0, ManageUserList.count);

            return false;

        });

        form_div.find( 'input[type=reset]'  ).click( function(event){

            try{
                //alert("reset");

                event.preventDefault();

                form_div.get(0).reset();

                form_div.removeClass('open');

                form_div.hide("slide", {direction: "up"}, 1000, function(){
                    form_div.user().html('');
                });

            } catch(err) {
                console.error('Error in : ManageUserForm - search_form - reset : [' + err +']');
            }
        });

    } catch(err) {
        console.error('Error in : ManageUserForm - search :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

ManageUserForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'user';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "name",       type : "text",     label : CDictionary.get_text('UserForm_Username_lbl') +":" },
                { name : "password",   type : "password", label : CDictionary.get_text('UserForm_Password_lbl') +":" },
                { name : "email",      type : "text",     label : CDictionary.get_text('UserForm_Email_lbl')    +":" },
                
                { type : "seprator",   label : "" },

                { name : "phone",      type : "text",     label : CDictionary.get_text('UserForm_Phone_lbl')    +":" },
                { name : "key",        type : "text",     label : CDictionary.get_text('UserForm_Key_lbl')      +":" },
                { name : "code",       type : "text",     label : CDictionary.get_text('UserForm_Code_lbl')     +":" },

                { type : "seprator",   label : "" },

                { name : "icon",       type : "image",    label : CDictionary.get_text('UserForm_Icon_lbl')     +":" },
                { name : "birth_date", type : "date",     format : "yy-mm-dd", label : CDictionary.get_text('UserForm_BirthDate_lbl')     +":" },
                
                { type : "clear",   label : "" },

                { name : "",  type : "label",  label : CDictionary.get_text('UserForm_Gender_Select_lbl'),  grid : 'cell-x' },
                { name : "gender",     postfix:"radio_m", type : "radio", label : CDictionary.get_text('UserForm_Gender_Male_lbl')   , grid : 'cell-x', value : '1' },
                { name : "gender",     postfix:"radio_f", type : "radio", label : CDictionary.get_text('UserForm_Gender_Female_lbl') , grid : 'cell-x', value : '2' },
                                
                { type : "clear",   label : "" },

                { type : "seprator",   label : "" },
                                
                { name : "created",    type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_Created_lbl')  +":" },
                { name : "updated",    type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_Updated_lbl')  +":" },
                
                { type : "seprator",   label : "" },

                { name : "status",     type : "select",   label : CDictionary.get_text('UserForm_Status_lbl')   +":" },
                { name : "rule_id",    type : "select",   label : CDictionary.get_text('UserForm_RuleId_lbl')   +":" },

                { name : "suspend_date", type : "datetime",   format: "yy-mm-dd hh:mm:ss",   label : CDictionary.get_text('UserForm_SuspendDate_lbl')  +":" },
                                
                { type : "seprator",   label : "" },
                
                { name : "country",    type : "select",   label : CDictionary.get_text('UserForm_Country_lbl')   +":" },

                { name : "user_id", type : "hidden", label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',
            //enctype : 'application/x-www-form-urlencoded',//

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageUserForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageUserForm.add    = function(){
    
    try{

        var list_div = ManageUserList.list_div;
        var form_div = ManageUserList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('user_form_tpl', true);
        var cont_form_div = ManageUserForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_user.tpl",
            form_action         : g_request_url+"?action=add_user",
            complete_callback   : ManageUserForm.callback,
            cancel_callback     : ManageUserForm.cancel,
            prepare_func        : ManageUserForm.prepare,
            validate_func       : ManageUserForm.validate,
            validate_notes_func : ManageUserForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageUserForm - add :['+err+']');
    }
};

ManageUserForm.edit   = function(user_id) {

    try{

        user_id = Utils.get_int(user_id);

        var list_div = ManageUserList.list_div;
        var form_div = ManageUserList.form_div;

        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);

        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('user_form_tpl', true);
        var cont_form_div = ManageUserForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_user.tpl",
            form_action         : g_request_url+"?action=update_user",
            complete_callback   : ManageUserForm.callback,
            cancel_callback     : ManageUserForm.cancel,
            prepare_func        : ManageUserForm.prepare,
            post_func           : ManageUserForm.post_edit, 
            post_args           : user_id,
            validate_func       : ManageUserForm.validate,
            validate_notes_func : ManageUserForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageUserForm - edit :['+err+']');
    }
};

ManageUserForm.view   = function(user_id) {

    try{

        user_id = Utils.get_int(user_id);

        var user = ManageUserForm.get_object(user_id);

        var preview_object = ManageUserForm.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();

        preview_div.find( 'div[name=user_id]'    ).html( user.user_id      );
        
        preview_div.find( 'div[name=name]'       ).html( user.name     );
        preview_div.find( 'div[name=password]'   ).html( user.password );
        preview_div.find( 'div[name=email]'      ).html( user.email    );
        preview_div.find( 'div[name=phone]'      ).html( user.phone );
        preview_div.find( 'div[name=key]'        ).html( user.key   );
        preview_div.find( 'div[name=code]'       ).html( user.code  );

        
        preview_div.find( 'div[name=fname]'      ).html( user.fname    );
        preview_div.find( 'div[name=lname]'      ).html( user.lname    );
        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/users/'+user.icon+'"  />' );
        preview_div.find( 'div[name=birth_date]' ).html( user.birth_date );
        
        preview_div.find( 'div[name=gender]'     ).html( ManageUserOutput.get_gender(user.gender) );
        preview_div.find( 'div[name=country]'    ).html( ManageUserOutput.get_country(user.country) );
        
        preview_div.find( 'div[name=created]'    ).html( user.created );
        preview_div.find( 'div[name=updated]'    ).html( user.updated );

        preview_div.find( 'div[name=suspend_date]' ).html( user.suspend_date );

        preview_div.find( 'div[name=status]'     ).html( ManageUserOutput.get_status( user.status ) );
        preview_div.find( 'div[name=rule_id]'    ).html( ManageUserOutput.get_rule( user.rule_id ) );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'User Info'  );

    }catch(err){
        console.error('Error in : ManageUserForm - view :['+err+']');
    }
};

ManageUserForm.remove = function(user_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageUserList.list_div;

            var data = "action=remove_user"
                        + "&user_id=" + user_id;

            RequestUtil.quick_post_request(list_div, data, ManageUserForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageUserForm - delete :['+err+']');
    }
    
};

ManageUserForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageUserForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageUserForm.prepare             = function(form_div){

    try{

        form_div.find( 'select[name=status]' ).html( ManageUserForm.get_status_select() );
        
        form_div.find( 'select[name=rule_id]' ).html( ManageUserForm.get_rule_select() );
        
        form_div.find( 'select[name=gender]' ).html( ManageUserForm.get_gender_select() );

        form_div.find( 'select[name=country]' ).html( ManageUserForm.get_country_select() );
        
        form_div.find( 'input[name=suspend_date]').prop('disabled', true);
        
        form_div.find( 'select[name=rule_id]' ).change(function(){

            if( $(this).val() == USER_RULE_SUSPENDED ){
                form_div.find( 'input[name=suspend_date]').prop('disabled', false);
            }else{
                form_div.find( 'input[name=suspend_date]').prop('disabled', true);
            }

        });

    }catch(err){
        console.error('Error in : ManageUserForm - prepare form :['+err+']');
    }
};

ManageUserForm.post_edit           = function(user_id){

    try{

        var user = ManageUserForm.get_object(user_id);

        var form_div = ManageUserList.form_div;
 
        form_div = form_div.find('form');

        
        form_div.find( 'input[name=user_id]'    ).val( user.user_id  );

        form_div.find( 'input[name=name]'       ).val( user.name );
        form_div.find( 'input[name=password]'   ).val( user.password );
        form_div.find( 'input[name=email]'      ).val( user.email    );
        form_div.find( 'input[name=phone]'      ).val( user.phone    );
        form_div.find( 'input[name=key]'        ).val( user.key      );
        form_div.find( 'input[name=code]'       ).val( user.code     );


        //form_div.find( 'input[name=icon]'     ).val( user.icon    );
        form_div.find( 'input[name=birth_date]' ).val( user.birth_date );
        //form_div.find( 'select[name=gender]'    ).find( 'option[value='+user.gender+']'  ).attr( "selected", true );
        
        //form_div.find( 'input[name=gender][value='+user.gender+']' ).attr('checked', true);
        form_div.find( 'input[name=gender]'     ).filter('[value='+user.gender+']').attr('checked', true);
        
        form_div.find( 'select[name=country]'   ).find( 'option[value='+user.country+']' ).attr( "selected", true );
                
        form_div.find( 'input[name=created]'    ).val( user.created );
        form_div.find( 'input[name=updated]'    ).val( user.updated  );

        form_div.find( 'input[name=suspend_date]' ).val( user.suspend_date );

        form_div.find( 'select[name=status]'    ).find( 'option[value='+user.status+']'  ).attr( "selected", true );
        form_div.find( 'select[name=rule_id]'   ).find( 'option[value='+user.rule_id+']' ).attr( "selected", true );

        var icon_src = g_root_url+'uploads/users/'+user.icon;

        if( RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }

    }catch(err){
        console.error('Error in : ManageUserForm - post edit :['+err+']');
    }
};

ManageUserForm.validate            = function(form_div){
    
    var errors = 0;
    
    try{

        form_div.find('.error').html('');
        form_div.find("*").removeClass("required");
        
        var element = null;

        element = form_div.find('input[name=email]');
        if(   ! Validate.required( element.val() )   ){
            element.addClass('required');
            errors++;
        }

        element = form_div.find('select[name=status]');
        if(   ! Validate.required( element.val() )   ){
            element.addClass('required');
            errors++;
        }

        element = form_div.find('select[name=rule_id]');
        if(   ! Validate.required( element.val() )   ){
            element.addClass('required');
            errors++;
        }

        element = form_div.find('select[name=country]');
        if(   ! Validate.required( element.val() )   ){
            element.addClass('required');
            errors++;
        }


        if( errors > 0 ){
            console.log( 'validate found : '+ errors + ' errors' );
        }
    
    }catch(err){
        console.error('Error in : ManageUserForm - validate form :['+err+']');
    }

    return errors;
};

ManageUserForm.validate_notes      = function(form_div){

    try{

        form_div.find('.error').html('').remove();

        var errors = 0;
        var error_html = '';
        
        var element = null;

        element = form_div.find('input[name=email]');
        if(   ! Validate.required( element.val() )   ){
            error_html += 'Email required ! <br />';
            errors++;
        }

        form_div.append('<div class="clearfix"></div>');
        form_div.append('<div class="error alert-error">'+error_html+'</div>');
    
    }catch(err){
        console.error('Error in : ManageUserForm - show validate notes :['+err+']');
    }
    
};

ManageUserForm.callback            = function(outputArray){

    try{

var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }

        ManageUserList.load();

        $('body').trigger( "users_updated" );

    }catch(err){
        console.error('Error in : ManageUserForm - callback :['+err+']');
    }
};

ManageUserForm.cancel              = function(){

    try{

        ManageUserList.load();

    }catch(err){
        console.error('Error in : ManageUserForm - cancel :['+err+']');
    }
};

ManageUserForm.get_object          = function(user_id) {

    var user = null;

    try{
        
        var user_array = ManageUserList.array;

        for(var i=0; i<user_array.length; i++){

            if( user_array[i].user_id == user_id ){
                
                user = user_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageUserForm - get object :['+err+']');
    }

    return user;
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

ManageUserForm.get_status_select  = function(){

    var select_html = '';

    select_html += '<option value="">'+CDictionary.get_text('UserForm_Status_Select_lbl')+'</option>' +
                   '<option value="'+ USER_STATUS_NOT_VERIFIED   +'">' + CDictionary.get_text('UserForm_Status_NotVerified_lbl')   +'</option>' +
                   '<option value="'+ USER_STATUS_EMAIL_VERIFIED +'">' + CDictionary.get_text('UserForm_Status_EmailVerified_lbl') +'</option>' +
                   '<option value="'+ USER_STATUS_PHONE_VERIFIED +'">' + CDictionary.get_text('UserForm_Status_PhoneVerified_lbl') +'</option>' ;
    
    return select_html;
};

ManageUserForm.get_rule_select    = function(){

    var select_html = '';

    select_html += '<option value="">'+CDictionary.get_text('UserForm_Rule_Select_lbl')+'</option>' +
                   '<option value="'+ USER_RULE_NORMAL    +'">' + CDictionary.get_text('UserForm_Rule_Normal_lbl')    +'</option>' +
                   '<option value="'+ USER_RULE_PRIVATE   +'">' + CDictionary.get_text('UserForm_Rule_Private_lbl')   +'</option>' +
                   '<option value="'+ USER_RULE_SUSPENDED +'">' + CDictionary.get_text('UserForm_Rule_Suspended_lbl') +'</option>' +
                   '<option value="'+ USER_RULE_BLOCKED   +'">' + CDictionary.get_text('UserForm_Rule_Blocked_lbl')   +'</option>' ;
    
    return select_html;
};

ManageUserForm.get_gender_select  = function(){

    var select_html = '';

    select_html += '<option value="">'+CDictionary.get_text('UserForm_Gender_Select_lbl')+'</option>' +
                   '<option value="'+ USER_GENDER_MALE         +'">' + CDictionary.get_text('UserForm_Gender_Male_lbl')        +'</option>' +
                   '<option value="'+ USER_GENDER_FEMALE       +'">' + CDictionary.get_text('UserForm_Gender_Female_lbl')      +'</option>' ;
    
    return select_html;
};

ManageUserForm.get_country_select = function(){
    
    var select_html = '';

    select_html += '<option value="">' + CDictionary.get_text('UserForm_Country_Select_lbl') + '</option>';

    var countries = MainGlobals.countries;

    for(var i=0; i<countries.length; i++){
        
        var country = countries[i];
        
        var name = CDictionary.get_text_by_lang(country, "name");

        select_html += '<option value="'+ country.country_id +'">' + name + '</option>';

    }
    
    return select_html;

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageUserOutput() {}

ManageUserOutput.get_id        = function( user_id ){
    return number_pad(user_id, 6);
};

ManageUserOutput.get_payments  = function( user_id ){
    
    var link = '';

    var title = CDictionary.get_text('Payments_lbl');

    link = '<a href="#" onclick="ManagePayment.init('+user_id+');">'+ title +'</a>';

    return link;

};

ManageUserOutput.get_wishlist  = function( user_id ){
    
    var link = '';

    var title = CDictionary.get_text('Wishlist_lbl');

    link = '<a href="#" onclick="ManageWishlist.init('+user_id+');">'+ title +'</a>';

    return link;

};


ManageUserOutput.get_status  = function( status ){
    
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

ManageUserOutput.get_rule    = function( rule_id ){
    
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

ManageUserOutput.get_gender  = function( gender ){

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

ManageUserOutput.get_country = function( country_name ){

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
