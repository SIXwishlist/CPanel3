
/*! shots */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, ShotAuth, USER_TYPE_MASTER, CMSExtraUtil, DisplayUtil */

function ManageShot() {}

////////////////////////////////////////////////////////////////////////////////

ManageShot.init = function (parent_id) {

    try{


        ManageShot.parent_id = Utils.get_int( parent_id );

        ManageShot.parent_id = ( ManageShot.parent_id  > 0 ) ? ManageShot.parent_id : 0;


        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=shots]").addClass('active');


        content_div.html( '' );

        ManageShotList.init();

    } catch(err) {
        console.error('Error in : ManageShot - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageShotList() {}
ManageShotList.index = 0;
ManageShotList.count = 10;

ManageShotList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Shots_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManageShotForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManageShotForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageShotForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageShotForm.export_form(); return false;">' +
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
        
        ManageShotList.form_div = form_div;
        ManageShotList.list_div = list_div;

        ManageShotList.load();

        //if( ShotAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageShot - init : [' + err +']');
    }
};

ManageShotList.load         = function (index, count) {
    
    try{

        var list_div = ManageShotList.list_div;
        var form_div = ManageShotList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        index = ( index === undefined ) ? ManageShotList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageShotList.count : Utils.get_int(count);

        ManageShotList.index = index;
        ManageShotList.count = count;
        
        var parent_id = ManageShot.parent_id;

        var data = "action=shots"
                + "&parent_id="+parent_id
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var shots       = outputArray["shots"];
                    var shots_count = outputArray["shots_count"];

                    ManageShotList.display_list(shots, shots_count, CMSUtil.PAGINATION_LIST);

                    //ManageShotList.display_chart(shots);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageShotList - load :['+err+']');
    }

};

ManageShotList.search       = function (index, count) {
    
    try{

        var list_div = ManageShotList.list_div;

        ManageShotList.index = Utils.get_int( index );
        ManageShotList.count = Utils.get_int( count );
        ManageShotList.count = ( ManageShotList.count > 0 ) ? ManageShotList.count : 10;

        var data = ManageShotList.search_object;

        data["index"] = ManageShotList.index;
        data["count"] = ManageShotList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var shots       = outputArray["shots"];
                    var shots_count = outputArray["shots_count"];

                    ManageShotList.display_list(shots, shots_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageShotList.display_chart(shots);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageShotList - search :['+err+']');
    }

};

ManageShotList.display_list = function (array, result_count, source){

    try{

        ManageShotList.array = array;

        var list_div = ManageShotList.list_div;


        var labels   = CDictionary.get_labels([
                        'ShotList_ShotId_lbl', 
                        'ShotList_Icon_lbl', 
                        'ShotList_Type_lbl', 
                        'ShotList_Order_lbl', 
                        'ShotList_Active_lbl' ]);

        var fields   = [ "ManageShotOutput.get_id(shot_id)", 
                         "ManageShotOutput.get_icon(icon)", 
                         "ManageShotOutput.get_type(type)", 
                         "order",
                         "active" ];

        var id_label = "shot_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageShotForm.edit', 'ManageShotForm.remove', 'ManageShotForm.view');//'ManageShotForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageShotList.search':'ManageShotList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageShotList.index, ManageShotList.count, (ManageShotList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageShotList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageShotForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageShotForm.import_form = function(){

    try{

        var content_div = $("#body").find("#content");

        content_div.find("#form_cell_new").html('');
        content_div.find("#form_cell_search").html('');


        var cont_div_cell = content_div.find("#form_cell_import");
        cont_div_cell.html( '' );

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );

        //var cont_div = $("#import_form_shots");


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_shots",
            complete_callback : ManageShotForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_shots_sample";
            //window.location = g_root_url+'uploads/samples/shots.xls';
        });

    }catch(err){
        console.error('Error in : ManageShotForm - import_form :['+err+']');
    }
};

ManageShotForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManageShotList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_shots"
                  + "&shot_id="   + Utils.get_int( data.shot_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManageShotForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageShotForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'shot';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "icon",          type : "image",      label : CDictionary.get_text('ShotForm_Icon_lbl')+":"                  },
                { name : "type",          type : "select",     label : CDictionary.get_text('ShotForm_Type_lbl')+":"                  },
                { name : "file",          type : "div",        label : CDictionary.get_text('ShotForm_File_lbl')+":"                  },

                { type : "seprator",      label : "" },

                { name : "order",         type : "text",       label : CDictionary.get_text('ShotForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('ShotForm_Active_lbl'),     value:"1" },
                //{ name : "active",        type : "checkbox",   label : CDictionary.get_text('ShotForm_Active_lbl')+":",     value:"1" },

                { type : "seprator",      label : "" },

                { name : "parent_type",   type : "hidden",   label : "", ignore_preview : true, value:"1" },
                { name : "parent_id",     type : "hidden",   label : "", ignore_preview : true },
                { name : "shot_id",       type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageShotForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageShotForm.search = function(){

    try{

        var list_div = ManageShotList.list_div;
        var form_div = ManageShotList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        var cont_form_div = TplUtil.get_hidden_div('shot_search_tpl', true);
        
        form_div.append( cont_form_div );
        
        form_div.find( 'select[name=country]' ).html( ManageShotForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManageShotForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_shots",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManageShotList.search_object = data;

                ManageShotList.search(0, ManageShotList.count);

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

ManageShotForm.add    = function(){
    
    try{

        var list_div = ManageShotList.list_div;
        var form_div = ManageShotList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('shot_form_tpl', true);
        var cont_form_div = ManageShotForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_shot.tpl",
            form_action         : g_request_url+"?action=add_shot",
            complete_callback   : ManageShotForm.callback,
            cancel_callback     : ManageShotForm.cancel,
            prepare_func        : ManageShotForm.prepare,
            validate_func       : ManageShotForm.validate,
            validate_notes_func : ManageShotForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageShotForm - add :['+err+']');
    }
};

ManageShotForm.edit   = function(shot_id) {

    try{
        
        shot_id = Utils.get_int(shot_id);

        var list_div = ManageShotList.list_div;
        var form_div = ManageShotList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('shot_form_tpl', true);
        var cont_form_div = ManageShotForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_shot.tpl",
            form_action         : g_request_url+"?action=update_shot",
            complete_callback   : ManageShotForm.callback,
            cancel_callback     : ManageShotForm.cancel,
            prepare_func        : ManageShotForm.prepare,
            post_func           : ManageShotForm.post_edit, 
            post_args           : shot_id,
            validate_func       : ManageShotForm.validate,
            validate_notes_func : ManageShotForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageShotForm - edit :['+err+']');
    }
};

ManageShotForm.view   = function(shot_id) {

    try{

        shot_id = Utils.get_int(shot_id);

        var shot = ManageShotForm.get_object(shot_id);
        
        //var preview_div = TplUtil.get_hidden_div('shot_preview_tpl');
        var preview_div = ManageShotForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=shot_id]'     ).html( shot.shot_id   );
        //preview_div.find( 'div[name=shot_id]'   ).html( shot.shot_id   );
        preview_div.find( 'div[name=icon]'        ).html( '<img src="'+g_root_url+'uploads/shots/'+shot.icon+'" />'  );
        preview_div.find( 'div[name=file]'        ).html( ManageShotOutput.get_file( shot.file, shot.type ) );
        preview_div.find( 'div[name=type]'        ).html( ManageShotOutput.get_type( shot.type ) );
        preview_div.find( 'div[name=order]'       ).html( shot.order      );
        preview_div.find( 'div[name=active]'      ).html( (shot.active>0  )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]' ).html( shot.parent_id  );

        //var image_src = g_root_url+'uploads/shots/'+shot.image;

        //if( RequestUtil.image_exists( image_src ) ){
        //    preview_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Shot Info'  );

    }catch(err){
        console.error('Error in : ManageShotForm - view :['+err+']');
    }
};

ManageShotForm.remove = function(shot_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageShotList.list_div;

            var data = "action=remove_shot"
                        + "&shot_id=" + shot_id;

            RequestUtil.quick_post_request(list_div, data, ManageShotForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageShotForm - delete :['+err+']');
    }
    
};

ManageShotForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageShotForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageShotForm.prepare        = function(form_div){

    try{

        form_div.find( 'select[name=type]' ).html( 
            '<option value="1">Download</option>'    +
            '<option value="2">Image</option>'    +
            '<option value="3">Flash</option>'    +
            '<option value="5">Video</option>'    +
            '<option value="6">Youtube</option>'  +
            '<option value="7">Vimeo</option>' 
        );

        form_div.find( 'select[name=type]' ).change(function () {

            ManageShotForm.change_file_type( form_div, $(this).val() );

        });

        form_div.find( 'select[name=type]' ).find( 'option[value='+TYPE_IMAGE+']' ).attr( "selected", true );

        ManageShotForm.change_file_type( form_div, TYPE_IMAGE );

        form_div.find( 'input[name=parent_id]' ).val( ManageShot.parent_id );
    
    }catch(err){
        console.error('Error in : ManageShotForm - prepare form :['+err+']');
    }
};

ManageShotForm.post_edit      = function(shot_id){

    try{

        var shot = ManageShotForm.get_object(shot_id);

        var form_div = ManageShotList.form_div;

        //var form_div = list_div.find(".form_cell").find("#form_cell_"+shot_id);
 
        form_div = form_div.find('form');


        form_div.find( 'input[name=shot_id]'      ).val( shot.shot_id );
        //form_div.find( 'input[name=icon]'       ).val( shot.icon        );
        //form_div.find( 'input[name=file]'       ).val( shot.image       );
        form_div.find( 'select[name=type]'        ).find( 'option[value='+shot.type+']' ).attr( "selected", "selected" );
        form_div.find( 'input[name=order]'        ).val( shot.order       );
        form_div.find( 'input[name=active]'       ).attr( "checked", (shot.active>0)   ? true:false );
        form_div.find( 'input[name=parent_id]'    ).val( shot.parent_id   );

        ManageShotForm.change_file_type( form_div, shot.type );

        ManageShotForm.set_file_value( form_div, shot.type, shot.file );

        var icon_src = g_root_url+'uploads/shots/'+shot.icon;

        if( RequestUtil.image_exists( icon_src ) ){
            form_div.find('input[name=icon]').parent().find('.preview').html( '<img src="'+icon_src+'" />' );
            //form_div.find( 'div[data-preview=image]' ).html( '<img src="'+image_src+'" />' );
        }

        //var image_src = g_root_url+'uploads/shots/'+shot.image;
        //
        //$.get(image_src).done(function() { 
        //    form_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //});

    }catch(err){
        console.error('Error in : ManageShotForm - post edit :['+err+']');
    }
};

ManageShotForm.validate       = function(form_div){
    
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
        console.error('Error in : ManageShotForm - validate form :['+err+']');
    }

    return errors;
};

ManageShotForm.validate_notes = function(form_div){

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
        console.error('Error in : ManageShotForm - show validate notes :['+err+']');
    }

};

ManageShotForm.callback       = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }

        ManageShotList.load();

        $('body').trigger( "shots_updated" );

    }catch(err){
        console.error('Error in : ManageShotForm - callback :['+err+']');
    }
};

ManageShotForm.cancel         = function(){

    try{

        ManageShotList.load();

    }catch(err){
        console.error('Error in : ManageShotForm - cancel :['+err+']');
    }
};

ManageShotForm.get_object     = function(shot_id) {

    var object = null;

    try{
        
        var object_array = ManageShotList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].shot_id == shot_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageShotForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

ManageShotForm.change_file_type = function(form_div, typeval){

    var type = Utils.get_int( typeval );

    switch( type ){

        case TYPE_DOWNLOAD:
            form_div.find("div[name=file]").html(
                '<input type="file" name="file" value="" />'
            );
            break;

        case TYPE_IMAGE:
            form_div.find("div[name=file]").html(
                '<input type="file" name="file" value="" accept="image/x-png, image/gif, image/jpeg" />'
            );
            break;

        case TYPE_FLASH:
            form_div.find("div[name=file]").html(
                '<input type="file" name="file" value="" accept="application/x-shockwave-flash swf" />'
            );
            break;

        case TYPE_VIDEO:
            form_div.find("div[name=file]").html(
                '<input type="file" name="file1" value="" accept="video/mp4"  /> mp4  <br />' +
                '<input type="file" name="file2" value="" accept="video/ogg"  /> ogv  <br />' + 
                '<input type="file" name="file3" value="" accept="video/webm" /> webm <br />' 
            );
            break;

        case TYPE_YOUTUBE:
            form_div.find("div[name=file]").html(
                '<input type="text" name="file" value="" size="40" />'
            );
            break;

        case TYPE_VIMEO:
            form_div.find("div[name=file]").html(
                '<input type="text" name="file" value="" size="40" />'
            );
            break;

        default:
            break;

    }
};

ManageShotForm.set_file_value   = function(form_div, typeval, fileval){

    var type = Utils.get_int( typeval );

    switch( type ){

        case TYPE_DOWNLOAD:
            form_div.find("div[name=file]").append(fileval);
            break;

        case TYPE_IMAGE:
            form_div.find("div[name=file]").append(fileval);
            break;

        case TYPE_FLASH:
            form_div.find("div[name=file]").append(fileval);
            break;

        case TYPE_VIDEO:
            form_div.find("div[name=file]").append(fileval);
            break;

        case TYPE_YOUTUBE:
            form_div.find("input[name=file]").val('http://www.youtube.com/watch?v='+fileval);
            break;

        case TYPE_VIMEO:
            form_div.find("input[name=file]").val('http://vimeo.com/'+fileval);
            break;

        default:
            break;

    }
};

////////////////////////////////////////////////////////////////////////////////

function ManageShotOutput() {}

ManageShotOutput.get_id   = function(shot_id){
    return number_pad(shot_id, 6);
};

ManageShotOutput.get_icon = function(icon){
    
    var show_icon = '';
    
    var icon_src = g_root_url+'uploads/shots/'+icon;
    
    show_icon += '<img src="' + icon_src + '" alt="" width="80px" height="80px" />';
    
    return show_icon;
};

ManageShotOutput.get_type = function(type){
    
    var show_type = '';
    
    type = Utils.get_int(type);

    switch(type){
        case TYPE_DOWNLOAD:
            show_type = CDictionary.get_text('File_Type_Download_lbl');
            break;

        case TYPE_IMAGE:
            show_type = CDictionary.get_text('File_Type_Image_lbl');
            break;

        case TYPE_FLASH:
            show_type = CDictionary.get_text('File_Type_Flash_lbl');
            break;

        case TYPE_SOUND:
            show_type = CDictionary.get_text('File_Type_Sound_lbl');
            break;

        case TYPE_VIDEO:
            show_type = CDictionary.get_text('File_Type_Video_lbl');
            break;

        case TYPE_YOUTUBE:
            show_type = CDictionary.get_text('File_Type_Youtube_lbl');
            break;

        case TYPE_VIMEO:
            show_type = CDictionary.get_text('File_Type_Vimeo_lbl');
            break;

        case TYPE_EMBED_CODE:
            show_type = CDictionary.get_text('File_Type_Embed_Code_lbl');
            break;
            
    }

    return show_type;
};

ManageShotOutput.get_file = function(file, type){
    
    var show_file = '';
    
    type = Utils.get_int(type);
    
    var folder = ''+g_root_url+'uploads/shots';
    var width  = 350;
    var height = 300;

    switch( type ){

        case TYPE_DOWNLOAD:
            show_file = DisplayUtil.get_download_embed(folder+'/'+file);
            break;

        case TYPE_IMAGE:
            show_file = DisplayUtil.get_image_embed(folder+'/'+file, width, height);
            break;

        case TYPE_FLASH:
            show_file = DisplayUtil.get_flash_embed(folder+'/'+file, width, height);
            break;

        case TYPE_SOUND:
            show_file = DisplayUtil.get_jsplayer_sound_embed(file, width, height, folder);
            break;

        case TYPE_VIDEO:
            show_file = DisplayUtil.get_jsplayer_video_embed(file, width, height, folder);
            break;

        case TYPE_YOUTUBE:
            show_file = DisplayUtil.get_youtube_video_embed(file, width, height, false);
            break;

        case TYPE_VIMEO:
            show_file = DisplayUtil.get_vimeo_video_embed(file, width, height, false);
            break;

        case TYPE_EMBED_CODE:
            show_file = escapeHTML(file);
            break;

        default:
            break;

    }

    return show_file;
};
