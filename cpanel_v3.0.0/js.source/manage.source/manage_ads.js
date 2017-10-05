
/*! ads */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, AdAuth, USER_TYPE_MASTER, CMSExtraUtil, DisplayUtil */

function ManageAd() {}

////////////////////////////////////////////////////////////////////////////////

ManageAd.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=ads]").addClass('active');


        content_div.html( '' );

        ManageAdList.init();

    } catch(err) {
        console.error('Error in : ManageAd - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageAdList() {}
ManageAdList.index = 0;
ManageAdList.count = 10;

ManageAdList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Ads_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManageAdForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManageAdForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageAdForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageAdForm.export_form(); return false;">' +
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
        
        ManageAdList.form_div = form_div;
        ManageAdList.list_div = list_div;

        ManageAdList.load();

        //if( AdAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageAd - init : [' + err +']');
    }
};

ManageAdList.load         = function (index, count) {
    
    try{

        var list_div = ManageAdList.list_div;
        var form_div = ManageAdList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        index = ( index === undefined ) ? ManageAdList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageAdList.count : Utils.get_int(count);

        ManageAdList.index = index;
        ManageAdList.count = count;

        var data = "action=ads"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var ads       = outputArray["ads"];
                    var ads_count = outputArray["ads_count"];

                    ManageAdList.display_list(ads, ads_count, CMSUtil.PAGINATION_LIST);

                    //ManageAdList.display_chart(ads);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageAdList - load :['+err+']');
    }

};

ManageAdList.search       = function (index, count) {
    
    try{

        var list_div = ManageAdList.list_div;

        ManageAdList.index = Utils.get_int( index );
        ManageAdList.count = Utils.get_int( count );
        ManageAdList.count = ( ManageAdList.count > 0 ) ? ManageAdList.count : 10;

        var data = ManageAdList.search_object;

        data["index"] = ManageAdList.index;
        data["count"] = ManageAdList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var ads       = outputArray["ads"];
                    var ads_count = outputArray["ads_count"];

                    ManageAdList.display_list(ads, ads_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageAdList.display_chart(ads);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageAdList - search :['+err+']');
    }

};

ManageAdList.display_list = function (array, result_count, source){

    try{

        ManageAdList.array = array;

        var list_div = ManageAdList.list_div;


        var labels   = CDictionary.get_labels([
                        'AdList_AdId_lbl', 
                        'AdList_File_lbl', 
                        'AdList_Type_lbl', 
                        'AdList_Order_lbl', 
                        'AdList_Active_lbl' ]);

        var fields   = [ "ManageAdOutput.get_id(ad_id)", 
                         "ManageAdOutput.get_file(icon)", 
                         "ManageAdOutput.get_type(type)", 
                         "order",
                         "active" ];

        var id_label = "ad_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageAdForm.edit', 'ManageAdForm.remove', 'ManageAdForm.view');//'ManageAdForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageAdList.search':'ManageAdList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageAdList.index, ManageAdList.count, (ManageAdList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageAdList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageAdForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageAdForm.import_form = function(){

    try{

        var content_div = $("#body").find("#content");

        content_div.find("#form_cell_new").html('');
        content_div.find("#form_cell_search").html('');


        var cont_div_cell = content_div.find("#form_cell_import");
        cont_div_cell.html( '' );

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );

        //var cont_div = $("#import_form_ads");


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_ads",
            complete_callback : ManageAdForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_ads_sample";
            //window.location = g_root_url+'uploads/samples/ads.xls';
        });

    }catch(err){
        console.error('Error in : ManageAdForm - import_form :['+err+']');
    }
};

ManageAdForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManageAdList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_ads"
                  + "&ad_id="   + Utils.get_int( data.ad_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManageAdForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageAdForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'ad';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "file",      type : "div",        label : CDictionary.get_text('AdForm_File_lbl')+":"              },      
                { name : "type",      type : "select",     label : CDictionary.get_text('AdForm_Type_lbl')+":"              },      
                { name : "link",      type : "text",       label : CDictionary.get_text('AdForm_Link_lbl')+":"              },      
                //{ name : "width",   type : "text",       label : CDictionary.get_text('AdForm_Width_lbl')+":"             },      
                //{ name : "height",  type : "text",       label : CDictionary.get_text('AdForm_Height_lbl')+":"            },      
                { name : "order",     type : "text",       label : CDictionary.get_text('AdForm_Order_lbl')+":"             },
                { name : "active",    type : "checkbox",   label : CDictionary.get_text('AdForm_Active_lbl')+":", value:"1" },

                { type : "seprator",  label : "" },

                { name : "ad_id",     type : "hidden",     label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageAdForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageAdForm.search = function(){

    try{

        var list_div = ManageAdList.list_div;
        var form_div = ManageAdList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        var cont_form_div = TplUtil.get_hidden_div('ad_search_tpl', true);
        
        form_div.append( cont_form_div );
        
        form_div.find( 'select[name=country]' ).html( ManageAdForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManageAdForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_ads",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManageAdList.search_object = data;

                ManageAdList.search(0, ManageAdList.count);

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

ManageAdForm.add    = function(){
    
    try{

        var list_div = ManageAdList.list_div;
        var form_div = ManageAdList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('ad_form_tpl', true);
        var cont_form_div = ManageAdForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_ad.tpl",
            form_action         : g_request_url+"?action=add_ad",
            complete_callback   : ManageAdForm.callback,
            cancel_callback     : ManageAdForm.cancel,
            prepare_func        : ManageAdForm.prepare,
            validate_func       : ManageAdForm.validate,
            validate_notes_func : ManageAdForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageAdForm - add :['+err+']');
    }
};

ManageAdForm.edit   = function(ad_id) {

    try{
        
        ad_id = Utils.get_int(ad_id);

        var list_div = ManageAdList.list_div;
        var form_div = ManageAdList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('ad_form_tpl', true);
        var cont_form_div = ManageAdForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_ad.tpl",
            form_action         : g_request_url+"?action=update_ad",
            complete_callback   : ManageAdForm.callback,
            cancel_callback     : ManageAdForm.cancel,
            prepare_func        : ManageAdForm.prepare,
            post_func           : ManageAdForm.post_edit, 
            post_args           : ad_id,
            validate_func       : ManageAdForm.validate,
            validate_notes_func : ManageAdForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageAdForm - edit :['+err+']');
    }
};

ManageAdForm.view   = function(ad_id) {

    try{

        ad_id = Utils.get_int(ad_id);

        var ad = ManageAdForm.get_object(ad_id);
        
        //var preview_div = TplUtil.get_hidden_div('ad_preview_tpl');
        var preview_div = ManageAdForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=ad_id]'    ).html( ad.ad_id   );
        preview_div.find( 'div[name=file]'     ).html( ManageAdOutput.get_file(ad.file, ad.type) );
        preview_div.find( 'div[name=type]'     ).html( ManageAdOutput.get_type(ad.type) );
        preview_div.find( 'div[name=width]'    ).html( ad.width    );
        preview_div.find( 'div[name=height]'   ).html( ad.height   );
        preview_div.find( 'div[name=link]'     ).html( ad.link     );
        preview_div.find( 'div[name=order]'    ).html( ad.order    );
        preview_div.find( 'div[name=active]'   ).html( (ad.active>0)?gYes:gNo );

        //var image_src = g_root_url+'uploads/ads/'+ad.image;

        //if( RequestUtil.image_exists( image_src ) ){
        //    preview_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Ad Info'  );

    }catch(err){
        console.error('Error in : ManageAdForm - view :['+err+']');
    }
};

ManageAdForm.remove = function(ad_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageAdList.list_div;

            var data = "action=remove_ad"
                        + "&ad_id=" + ad_id;

            RequestUtil.quick_post_request(list_div, data, ManageAdForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageAdForm - delete :['+err+']');
    }
    
};

ManageAdForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageAdForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageAdForm.prepare        = function(form_div){

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

            ManageAdForm.change_file_type( form_div, $(this).val() );

        });

        form_div.find( 'select[name=type]' ).find( 'option[value='+TYPE_IMAGE+']' ).attr( "selected", true );

        ManageAdForm.change_file_type( form_div, TYPE_IMAGE );
    
    }catch(err){
        console.error('Error in : ManageAdForm - prepare form :['+err+']');
    }
};

ManageAdForm.post_edit      = function(ad_id){

    try{

        var ad = ManageAdForm.get_object(ad_id);

        var form_div = ManageAdList.form_div;

        //var form_div = list_div.find(".form_cell").find("#form_cell_"+ad_id);
 
        form_div = form_div.find('form');        

        form_div.find( 'input[name=ad_id]'  ).val( ad.ad_id    );
        form_div.find( 'input[name=link]'   ).val( ad.link     );
        form_div.find( 'select[name=type]'  ).find( 'option[value='+ad.type+']' ).attr( "selected", true );
        form_div.find( 'input[name=width]'  ).val( ad.width    );
        form_div.find( 'input[name=height]' ).val( ad.height   );
        form_div.find( 'input[name=order]'  ).val( ad.order    );
        form_div.find( 'input[name=active]' ).attr( "checked", ( ad.active > 0 ) ? true : false );

        ManageAdForm.change_file_type( form_div, ad.type );

        ManageAdForm.set_file_value( form_div, ad.type, ad.file );

        //var icon_src = g_root_url+'uploads/ads/'+ad.icon;

        //if( RequestUtil.image_exists( icon_src ) ){
        //    form_div.find('input[name=icon]').parent().find('.preview').html( '<img src="'+icon_src+'" />' );
        //    //form_div.find( 'div[data-preview=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        //var image_src = g_root_url+'uploads/ads/'+ad.image;
        //
        //$.get(image_src).done(function() { 
        //    form_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //});

    }catch(err){
        console.error('Error in : ManageAdForm - post edit :['+err+']');
    }
};

ManageAdForm.validate       = function(form_div){
    
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
        console.error('Error in : ManageAdForm - validate form :['+err+']');
    }

    return errors;
};

ManageAdForm.validate_notes = function(form_div){

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
        console.error('Error in : ManageAdForm - show validate notes :['+err+']');
    }

};

ManageAdForm.callback       = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }

        ManageAdList.load();

        $('body').trigger( "ads_updated" );

    }catch(err){
        console.error('Error in : ManageAdForm - callback :['+err+']');
    }
};

ManageAdForm.cancel         = function(){

    try{

        ManageAdList.load();

    }catch(err){
        console.error('Error in : ManageAdForm - cancel :['+err+']');
    }
};

ManageAdForm.get_object     = function(ad_id) {

    var object = null;

    try{
        
        var object_array = ManageAdList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].ad_id == ad_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageAdForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

ManageAdForm.change_file_type = function(form_div, typeval){

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

ManageAdForm.set_file_value   = function(form_div, typeval, fileval){

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

function ManageAdOutput() {}

ManageAdOutput.get_id   = function(ad_id){
    return number_pad(ad_id, 6);
};

ManageAdOutput.get_icon = function(icon){
    
    var show_icon = '';
    
    var icon_src = g_root_url+'uploads/ads/'+icon;
    
    show_icon += '<img src="' + icon_src + '" alt="" width="80px" height="80px" />';
    
    return show_icon;
};

ManageAdOutput.get_type = function(type){
    
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

ManageAdOutput.get_file = function(file, type){
    
    var show_file = '';
    
    type = Utils.get_int(type);
    
    var folder = ''+g_root_url+'uploads/ads';
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
