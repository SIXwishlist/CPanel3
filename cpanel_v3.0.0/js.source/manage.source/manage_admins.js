
/*! admins */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, AdminAuth, USER_TYPE_MASTER, CMSExtraUtil */

function ManageAdmin() {}

////////////////////////////////////////////////////////////////////////////////

ManageAdmin.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=admins]").addClass('active');


        content_div.html( '' );

        ManageAdminList.init();

    } catch(err) {
        console.error('Error in : ManageAdmin - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageAdminList() {}
ManageAdminList.index = 0;
ManageAdminList.count = 10;

ManageAdminList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Admins_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManageAdminForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageAdminForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageAdminForm.export_form(); return false;">' +
                //    '<i class="fa fa-share-square-o" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Export_lbl') + 
                //'</div>' + 
            '</div>' 
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
        
        ManageAdminList.form_div = form_div;
        ManageAdminList.list_div = list_div;

        ManageAdminList.load();

        CMSExtraUtil.show_list(ManageAdminList);
        //if( AdminAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageAdmin - init : [' + err +']');
    }
};

ManageAdminList.load         = function (index, count) {
    
    try{

        index = ( index === undefined ) ? ManageAdminList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageAdminList.count : Utils.get_int(count);

        ManageAdminList.index = index;
        ManageAdminList.count = count;

        var data = "action=admins"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var admins       = outputArray["admins"];
                    var admins_count = outputArray["admins_count"];

                    ManageAdminList.display_list(admins, admins_count, CMSUtil.PAGINATION_LIST);

                    //ManageAdminList.display_chart(admins);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageAdminList - load :['+err+']');
    }

};

ManageAdminList.search       = function (index, count) {
    
    try{

        var list_div = ManageAdminList.list_div;

        ManageAdminList.index = Utils.get_int( index );
        ManageAdminList.count = Utils.get_int( count );
        ManageAdminList.count = ( ManageAdminList.count > 0 ) ? ManageAdminList.count : 10;

        var data = ManageAdminList.search_object;

        data["index"] = ManageAdminList.index;
        data["count"] = ManageAdminList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var admins       = outputArray["admins"];
                    var admins_count = outputArray["admins_count"];

                    ManageAdminList.display_list(admins, admins_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageAdminList.display_chart(admins);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageAdminList - load :['+err+']');
    }

};

ManageAdminList.display_list = function (array, result_count, source){

    try{

        ManageAdminList.array = array;

        var list_div = ManageAdminList.list_div;


        var labels   = CDictionary.get_labels([
                        'AdminList_AdminId_lbl', 
                        'AdminList_Name_lbl', 
                        'AdminList_Password_lbl', 
                        'AdminList_Email_lbl', 
                        'AdminList_RuleId_lbl' ]);

        var fields   = [ "ManageAdminOutput.get_id(admin_id)", 
                         "name", 
                         "password", 
                         "email",
                         "rule_id" ];

        var id_label = "admin_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageAdminForm.edit', 'ManageAdminForm.remove', 'ManageAdminForm.view');//'ManageAdminForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageAdminList.search':'ManageAdminList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageAdminList.index, ManageAdminList.count, (ManageAdminList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageAdminList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageAdminForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageAdminForm.import_form = function(){

    try{

        var content_div = $("#body").find("#content");

        content_div.find("#form_cell_new").html('');
        content_div.find("#form_cell_search").html('');


        var cont_div_cell = content_div.find("#form_cell_import");
        cont_div_cell.html( '' );

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );

        //var cont_div = $("#import_form_admins");


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_admins",
            complete_callback : ManageAdminForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_admins_sample";
            //window.location = g_root_url+'uploads/samples/admins.xls';
        });

    }catch(err){
        console.error('Error in : ManageAdminForm - import_form :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageAdminForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManageAdminList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_admins"
                  + "&admin_id="   + Utils.get_int( data.admin_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManageAdminForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageAdminForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'admin';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "name",       type : "text",     label : CDictionary.get_text('AdminForm_Name_lbl')+":"       },
                { name : "password",   type : "password", label : CDictionary.get_text('AdminForm_Password_lbl')+":"       },

                { type : "separator",  label : "" },

                { name : "email",      type : "text",     label : CDictionary.get_text('AdminForm_Email_lbl')+":"       },

                { type : "separator",  label : "" },

                { name : "rule_id",    type : "select",   label : CDictionary.get_text('AdminForm_RuleId_lbl')+":"     },

                { type : "separator",  label : "" },

                { name : "admin_id",   type : "hidden",  label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'application/x-www-form-urlencoded',//'multiadmin/form-data',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageAdminForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageAdminForm.search = function(){

    try{

        var list_div = ManageAdminList.list_div;
        var form_div = ManageAdminList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        var cont_form_div = TplUtil.get_hidden_div('admin_search_tpl', true);
        
        form_div.append( cont_form_div );
        
        form_div.find( 'select[name=country]' ).html( ManageAdminForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManageAdminForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_admins",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManageAdminList.search_object = data;

                ManageAdminList.search(0, ManageAdminList.count);

                return false;

            } catch(err) {
                console.error('Error in : ManageOrganizationForm - search - submit : [' + err +']');
            }
        });

        form_div.find( 'input[type=reset]'  ).click( function(event){

            try{
                //alert("reset");

                event.preventDefault();

                form_div.find('form').get(0).reset();

                form_div.hide().fadeOut( 1000 );

            } catch(err) {
                console.error('Error in : ManageOrganizationForm - search - reset : [' + err +']');
            }
        });

    }catch(err){
        console.error('Error in : ManageOrganizationForm - add :['+err+']');
    }
};

ManageAdminForm.add    = function(){
    
    try{

        var list_div = ManageAdminList.list_div;
        var form_div = ManageAdminList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('admin_form_tpl', true);
        var cont_form_div = ManageAdminForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_admin.tpl",
            form_action         : g_request_url+"?action=add_admin",
            complete_callback   : ManageAdminForm.callback,
            cancel_callback     : ManageAdminForm.cancel,
            prepare_func        : ManageAdminForm.prepare_form,
            validate_func       : ManageAdminForm.validate_form,
            validate_notes_func : ManageAdminForm.show_validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageAdminForm - add :['+err+']');
    }
};

ManageAdminForm.edit   = function(admin_id) {

    try{
        
        admin_id = Utils.get_int(admin_id);

        var list_div = ManageAdminList.list_div;
        var form_div = ManageAdminList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('admin_form_tpl', true);
        var cont_form_div = ManageAdminForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_admin.tpl",
            form_action         : g_request_url+"?action=update_admin",
            complete_callback   : ManageAdminForm.callback,
            cancel_callback     : ManageAdminForm.cancel,
            prepare_func        : ManageAdminForm.prepare_form,
            post_func           : ManageAdminForm.post_edit, 
            post_args           : admin_id,
            validate_func       : ManageAdminForm.validate_form,
            validate_notes_func : ManageAdminForm.show_validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageAdminForm - edit :['+err+']');
    }
};

ManageAdminForm.view   = function(admin_id) {

    try{

        admin_id = Utils.get_int(admin_id);

        var admin = ManageAdminForm.get_object(admin_id);
        
        //var preview_div = TplUtil.get_hidden_div('admin_preview_tpl');
        var preview_div = ManageAdminForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=admin_id]'   ).html( admin.admin_id   );
        preview_div.find( 'div[name=name]'       ).html( admin.name       );
        preview_div.find( 'div[name=password]'   ).html( admin.password   );
        preview_div.find( 'div[name=email]'      ).html( admin.email      );
        preview_div.find( 'div[name=rule_id]'    ).html( admin.rule_id    );

        //var image_src = g_root_url+'uploads/admins/'+admin.image;

        //if( RequestUtil.image_exists( image_src ) ){
        //    preview_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Admin Info'  );

    }catch(err){
        console.error('Error in : ManageAdminForm - view :['+err+']');
    }
};

ManageAdminForm.remove = function(admin_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageAdminList.list_div;

            var data = "action=remove_admin"
                        + "&admin_id=" + admin_id;

            RequestUtil.quick_post_request(list_div, data, ManageAdminForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageAdminForm - delete :['+err+']');
    }
    
};

ManageAdminForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageAdminForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageAdminForm.prepare_form        = function(form_div){

    try{

        form_div.find( 'select[name=country]' ).html( ManageAdminForm.get_country_select() );
    
    }catch(err){
        console.error('Error in : ManageAdminForm - prepare form :['+err+']');
    }
};

ManageAdminForm.post_edit           = function(admin_id){

    try{

        var admin = ManageAdminForm.get_object(admin_id);

        var form_div = ManageAdminList.form_div;

        //var form_div = list_div.find(".form_cell").find("#form_cell_"+admin_id);
 
        form_div = form_div.find('form');        


        form_div.find( 'input[name=admin_id]' ).val( admin.admin_id );
        form_div.find( 'input[name=name]'     ).val( admin.name     );
        form_div.find( 'input[name=password]' ).val( admin.password );
        form_div.find( 'input[name=email]'    ).val( admin.email    );

        form_div.find( 'input[name=rule_id]' ).find( 'option[value='+admin.rule_id+']'    ).attr( "selected", true );

        //var image_src = g_root_url+'uploads/admins/'+admin.image;

        //if( RequestUtil.image_exists( image_src ) ){
        //    form_div.find('input[name=image]').parent().find('.preview').html( '<img src="'+image_src+'" />' );
        //    //form_div.find( 'div[data-preview=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        //var image_src = g_root_url+'uploads/admins/'+admin.image;
        //
        //$.get(image_src).done(function() { 
        //    form_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //});

    }catch(err){
        console.error('Error in : ManageAdminForm - post edit :['+err+']');
    }
};

ManageAdminForm.validate_form       = function(form_div){
    
    var errors = 0;
    
    try{

        form_div.find('.error').html('');
        form_div.find("*").removeClass("required");
        
        var element = null;

        //element = form_div.find('input[name=name]');
        //if(   ! Validate.required( element.val() )   ){
        //    element.addClass('required');
        //    errors++;
        //}

        if( errors > 0 ){
            console.log( 'validate found : '+ errors + ' errors' );
        }
    
    }catch(err){
        console.error('Error in : ManageAdminForm - validate form :['+err+']');
    }

    return errors;
};

ManageAdminForm.show_validate_notes = function(form_div){

    try{

        form_div.find('.error').html('').remove();

        var error_html = '';
        
        var element = null;

        //element = form_div.find('input[name=name]');
        //if(   ! Validate.required( element.val() )   ){
        //    error_html += 'Title required !';
        //}

        form_div.append('<div class="clearfix"></div>');
        form_div.append('<div class="error alert-error">'+error_html+'</div>');

    }catch(err){
        console.error('Error in : ManageAdminForm - show validate notes :['+err+']');
    }

};

ManageAdminForm.callback            = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }

        ManageAdminList.load();

        $('body').trigger( "admins_updated" );

    }catch(err){
        console.error('Error in : ManageAdminForm - callback :['+err+']');
    }
};

ManageAdminForm.cancel              = function(){

    try{

        ManageAdminList.load();

    }catch(err){
        console.error('Error in : ManageAdminForm - cancel :['+err+']');
    }
};

ManageAdminForm.get_object          = function(admin_id) {

    var object = null;

    try{
        
        var object_array = ManageAdminList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].admin_id == admin_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageAdminForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

ManageAdminForm.get_rule_select  = function(){

    var select_html = '';

    select_html += '<option value="-1">Please select status</option>'+
                   '<option value="'+ORG_STATUS_HOLD       +'">'+ CDictionary.get_text('CertForm_Status_Hold_lbl')     +'</option>' +
                   '<option value="'+ORG_STATUS_TRIAL      +'">'+ CDictionary.get_text('CertForm_Status_Trial_lbl')    +'</option>' +
                   '<option value="'+ORG_STATUS_EXPIRED    +'">'+ CDictionary.get_text('CertForm_Status_Expired_lbl')  +'</option>' +
                   '<option value="'+ORG_STATUS_ACTIVE     +'">'+ CDictionary.get_text('CertForm_Status_Active_lbl')   +'</option>';

    return select_html;
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageAdminOutput() {}

ManageAdminOutput.get_id   = function(admin_id){
    return number_pad(admin_id, 6);
};

ManageAdminOutput.get_rule = function(status){
    
    var cert_rule = '';
    
    status = Utils.get_int(status);
    
    switch (status){
        
        case ORG_STATUS_HOLD:
            cert_rule = CDictionary.get_text('CertForm_Status_Hold_lbl');
            break;

        case ORG_STATUS_EXPIRED:
            cert_rule = CDictionary.get_text('CertForm_Status_Expired_lbl');
            break;

        case ORG_STATUS_TRIAL:
            cert_rule = CDictionary.get_text('CertForm_Status_Trial_lbl');
            break;

        case ORG_STATUS_ACTIVE:
            cert_rule = CDictionary.get_text('CertForm_Status_Active_lbl');
            break;

        default :
            break;
        
    }
    
    return cert_rule;

};
