
/*! slides */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, SlideAuth, USER_TYPE_MASTER, CMSExtraUtil, DisplayUtil */

function ManageSlide() {}

////////////////////////////////////////////////////////////////////////////////

ManageSlide.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=slides]").addClass('active');


        content_div.html( '' );

        ManageSlideList.init();

    } catch(err) {
        console.error('Error in : ManageSlide - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageSlideList() {}
ManageSlideList.index = 0;
ManageSlideList.count = 10;

ManageSlideList.init         = function () {
    
    try{

        var content_div = $("#body").find("#content");

        content_div.html( '' );

        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Slides_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManageSlideForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManageSlideForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageSlideForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageSlideForm.export_form(); return false;">' +
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
        
        ManageSlideList.form_div = form_div;
        ManageSlideList.list_div = list_div;

        ManageSlideList.load();

        //if( SlideAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageSlide - init : [' + err +']');
    }
};

ManageSlideList.load         = function (index, count) {
    
    try{

        var list_div = ManageSlideList.list_div;
        var form_div = ManageSlideList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeOut(1000);

        index = ( index === undefined ) ? ManageSlideList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageSlideList.count : Utils.get_int(count);

        ManageSlideList.index = index;
        ManageSlideList.count = count;

        var data = "action=slides"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var slides       = outputArray["slides"];
                    var slides_count = outputArray["slides_count"];

                    ManageSlideList.display_list(slides, slides_count, CMSUtil.PAGINATION_LIST);

                    //ManageSlideList.display_chart(slides);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageSlideList - load :['+err+']');
    }

};

ManageSlideList.search       = function (index, count) {
    
    try{

        var list_div = ManageSlideList.list_div;

        ManageSlideList.index = Utils.get_int( index );
        ManageSlideList.count = Utils.get_int( count );
        ManageSlideList.count = ( ManageSlideList.count > 0 ) ? ManageSlideList.count : 10;

        var data = ManageSlideList.search_object;

        data["index"] = ManageSlideList.index;
        data["count"] = ManageSlideList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var slides       = outputArray["slides"];
                    var slides_count = outputArray["slides_count"];

                    ManageSlideList.display_list(slides, slides_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageSlideList.display_chart(slides);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageSlideList - search :['+err+']');
    }

};

ManageSlideList.display_list = function (array, result_count, source){

    try{

        ManageSlideList.array = array;

        var list_div = ManageSlideList.list_div;


        var labels   = CDictionary.get_labels([
                        'SlideList_SlideId_lbl', 
                        'SlideList_File_lbl', 
                        'SlideList_Type_lbl', 
                        'SlideList_Order_lbl', 
                        'SlideList_Active_lbl' ]);

        var fields   = [ "ManageSlideOutput.get_id(slide_id)", 
                         "ManageSlideOutput.get_file(icon)", 
                         "ManageSlideOutput.get_type(type)", 
                         "order",
                         "active" ];

        var id_label = "slide_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageSlideForm.edit', 'ManageSlideForm.remove', 'ManageSlideForm.view');//'ManageSlideForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageSlideList.search':'ManageSlideList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageSlideList.index, ManageSlideList.count, (ManageSlideList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageSlideList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageSlideForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageSlideForm.import_form = function(){

    try{

        var content_div = $("#body").find("#content");

        content_div.find("#form_cell_new").html('');
        content_div.find("#form_cell_search").html('');


        var cont_div_cell = content_div.find("#form_cell_import");
        cont_div_cell.html( '' );

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );

        //var cont_div = $("#import_form_slides");


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_slides",
            complete_callback : ManageSlideForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_slides_sample";
            //window.location = g_root_url+'uploads/samples/slides.xls';
        });

    }catch(err){
        console.error('Error in : ManageSlideForm - import_form :['+err+']');
    }
};

ManageSlideForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManageSlideList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_slides"
                  + "&slide_id="   + Utils.get_int( data.slide_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManageSlideForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageSlideForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'slide';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",      type : "hidden",     label : "", ignore_preview : true },
                { name : "title_en",      type : "hidden",     label : "", ignore_preview : true },
                { name : "desc_ar",       type : "hidden",     label : "", ignore_preview : true },
                { name : "desc_en",       type : "hidden",     label : "", ignore_preview : true },

                //{ name : "title_ar",      type : "text",       label : CDictionary.get_text('SlideForm_TitleAr_lbl')+":"           },      
                //{ name : "title_en",      type : "text",       label : CDictionary.get_text('SlideForm_TitleEn_lbl')+":"           },      
                //{ name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('SlideForm_DescAr_lbl')+":"            },      
                //{ name : "desc_en",       type : "textarea",   label : CDictionary.get_text('SlideForm_DescEn_lbl')+":"            },      
                //{ type : "separator",      label : "" },

                { name : "type",          type : "select",     label : CDictionary.get_text('SlideForm_Type_lbl')+":"              },      
                { name : "file",          type : "div",        label : CDictionary.get_text('SlideForm_File_lbl')+":"              },      
                
                { type : "separator",      label : "" },
                
                { name : "link_ar",       type : "text",       label : CDictionary.get_text('SlideForm_LinkAr_lbl')+":"            },      
                { name : "link_en",       type : "text",       label : CDictionary.get_text('SlideForm_LinkEn_lbl')+":"            },      

                { type : "separator",      label : "" },

                { name : "order",         type : "text",       label : CDictionary.get_text('SlideForm_Order_lbl')+":"             },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('SlideForm_Active_lbl')+":", value:"1" },

                { type : "separator",      label : "" },

                //{ name : "parent_type",   type : "select",     label : CDictionary.get_text('SlideForm_ParentType_lbl')+":"             },
                //{ name : "parent_id",     type : "select",     label : CDictionary.get_text('SlideForm_ParentId_lbl')+":"             },
                { name : "parent_type",   type : "hidden",     label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",     label : "", ignore_preview : true },

                { name : "slide_id",     type : "hidden",     label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageSlideForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageSlideForm.search = function(){

    try{

        var list_div = ManageSlideList.list_div;
        var form_div = ManageSlideList.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        var cont_form_div = TplUtil.get_hidden_div('slide_search_tpl', true);
        
        form_div.append( cont_form_div );
        
        form_div.find( 'select[name=country]' ).html( ManageSlideForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManageSlideForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_slides",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManageSlideList.search_object = data;

                ManageSlideList.search(0, ManageSlideList.count);

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

ManageSlideForm.add    = function(){
    
    try{

        var list_div = ManageSlideList.list_div;
        var form_div = ManageSlideList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('slide_form_tpl', true);
        var cont_form_div = ManageSlideForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_slide.tpl",
            form_action         : g_request_url+"?action=add_slide",
            complete_callback   : ManageSlideForm.callback,
            cancel_callback     : ManageSlideForm.cancel,
            prepare_func        : ManageSlideForm.prepare,
            validate_func       : ManageSlideForm.validate,
            validate_notes_func : ManageSlideForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageSlideForm - add :['+err+']');
    }
};

ManageSlideForm.edit   = function(slide_id) {

    try{
        
        slide_id = Utils.get_int(slide_id);

        var list_div = ManageSlideList.list_div;
        var form_div = ManageSlideList.form_div;
        
        $(list_div).fadeOut(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        //var cont_form_div = TplUtil.get_hidden_div('slide_form_tpl', true);
        var cont_form_div = ManageSlideForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : form_div,
            form_div            : cont_form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_slide.tpl",
            form_action         : g_request_url+"?action=update_slide",
            complete_callback   : ManageSlideForm.callback,
            cancel_callback     : ManageSlideForm.cancel,
            prepare_func        : ManageSlideForm.prepare,
            post_func           : ManageSlideForm.post_edit, 
            post_args           : slide_id,
            validate_func       : ManageSlideForm.validate,
            validate_notes_func : ManageSlideForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageSlideForm - edit :['+err+']');
    }
};

ManageSlideForm.view   = function(slide_id) {

    try{

        slide_id = Utils.get_int(slide_id);

        var slide = ManageSlideForm.get_object(slide_id);
        
        //var preview_div = TplUtil.get_hidden_div('slide_preview_tpl');
        var preview_div = ManageSlideForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=slide_id]'     ).html( slide.slide_id   );
        preview_div.find( 'div[name=title_ar]'    ).html( slide.title_ar    );
        preview_div.find( 'div[name=title_en]'    ).html( slide.title_en    );
        preview_div.find( 'div[name=desc_ar]'     ).html( slide.desc_ar     );
        preview_div.find( 'div[name=desc_en]'     ).html( slide.desc_en     );
        preview_div.find( 'div[name=file]'        ).html( ManageSlideOutput.get_file( slide.file, slide.type ) );
        preview_div.find( 'div[name=type]'        ).html( ManageSlideOutput.get_type( slide.type ) );
        preview_div.find( 'div[name=link_ar]'     ).html( slide.link_ar     );
        preview_div.find( 'div[name=link_en]'     ).html( slide.link_en     );
        preview_div.find( 'div[name=order]'       ).html( slide.order       );
        preview_div.find( 'div[name=active]'      ).html( (slide.active>0)?gYes:gNo );
        preview_div.find( 'div[name=parent_type]' ).html( slide.parent_type );
        preview_div.find( 'div[name=parent_id]'   ).html( slide.parent_id   );

        //var image_src = g_root_url+'uploads/slides/'+slide.image;

        //if( RequestUtil.image_exists( image_src ) ){
        //    preview_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //}

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Slide Info'  );

    }catch(err){
        console.error('Error in : ManageSlideForm - view :['+err+']');
    }
};

ManageSlideForm.remove = function(slide_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageSlideList.list_div;

            var data = "action=remove_slide"
                        + "&slide_id=" + slide_id;

            RequestUtil.quick_post_request(list_div, data, ManageSlideForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageSlideForm - delete :['+err+']');
    }
    
};

ManageSlideForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageSlideForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageSlideForm.prepare        = function(form_div){

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

            ManageSlideForm.change_file_type( form_div, $(this).val() );

        });

        form_div.find( 'select[name=type]' ).find( 'option[value='+TYPE_IMAGE+']' ).attr( "selected", true );

        ManageSlideForm.change_file_type( form_div, TYPE_IMAGE );
    
    }catch(err){
        console.error('Error in : ManageSlideForm - prepare form :['+err+']');
    }
};

ManageSlideForm.post_edit      = function(slide_id){

    try{

        var slide = ManageSlideForm.get_object(slide_id);

        var form_div = ManageSlideList.form_div;

        //var form_div = list_div.find(".form_cell").find("#form_cell_"+slide_id);
 
        form_div = form_div.find('form');        

        form_div.find( 'input[name=slide_id]'     ).val( slide.slide_id );
        form_div.find( 'input[name=title_ar]'     ).val( slide.title_ar  );
        form_div.find( 'input[name=title_en]'     ).val( slide.title_en  );
        form_div.find( 'textarea[name=desc_ar]'   ).val( slide.desc_ar   );
        form_div.find( 'textarea[name=desc_en]'   ).val( slide.desc_en   );
        form_div.find( 'input[name=link_ar]'      ).val( slide.link_ar   );
        form_div.find( 'input[name=link_en]'      ).val( slide.link_en   );
        form_div.find( 'select[name=type]'        ).find( 'option[value='+slide.type+']' ).attr( "selected", true );
        form_div.find( 'input[name=order]'        ).val( slide.order    );
        form_div.find( 'input[name=active]'       ).attr( "checked", (slide.active>0  )?"checked":"" );

        form_div.find( 'select[name=parent_type]' ).find( 'option[value='+slide.parent_type+']' ).attr( "selected", true );
        form_div.find( 'select[name=parent_id]'   ).find( 'option[value='+slide.parent_id+']'   ).attr( "selected", true );

        form_div.find( 'input[name=parent_type]' ).val( slide.parent_type );
        form_div.find( 'input[name=parent_id]'   ).val( slide.parent_id   );

        ManageSlideForm.change_file_type( form_div, slide.type );

        ManageSlideForm.set_file_value( form_div, slide.type, slide.file );

        var icon_src = g_root_url+'uploads/slides/'+slide.icon;

        if( RequestUtil.image_exists( icon_src ) ){
            form_div.find('input[name=icon]').parent().find('.preview').html( '<img src="'+icon_src+'" />' );
            //form_div.find( 'div[data-preview=image]' ).html( '<img src="'+image_src+'" />' );
        }

        //var image_src = g_root_url+'uploads/slides/'+slide.image;
        //
        //$.get(image_src).done(function() { 
        //    form_div.find( 'div[name=image]' ).html( '<img src="'+image_src+'" />' );
        //});

    }catch(err){
        console.error('Error in : ManageSlideForm - post edit :['+err+']');
    }
};

ManageSlideForm.validate       = function(form_div){
    
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
        console.error('Error in : ManageSlideForm - validate form :['+err+']');
    }

    return errors;
};

ManageSlideForm.validate_notes = function(form_div){

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
        console.error('Error in : ManageSlideForm - show validate notes :['+err+']');
    }

};

ManageSlideForm.callback       = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }

        ManageSlideList.load();

        $('body').trigger( "slides_updated" );

    }catch(err){
        console.error('Error in : ManageSlideForm - callback :['+err+']');
    }
};

ManageSlideForm.cancel         = function(){

    try{

        ManageSlideList.load();

    }catch(err){
        console.error('Error in : ManageSlideForm - cancel :['+err+']');
    }
};

ManageSlideForm.get_object     = function(slide_id) {

    var object = null;

    try{
        
        var object_array = ManageSlideList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].slide_id == slide_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageSlideForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

ManageSlideForm.change_file_type = function(form_div, typeval){

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

ManageSlideForm.set_file_value   = function(form_div, typeval, fileval){

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

function ManageSlideOutput() {}

ManageSlideOutput.get_id   = function(slide_id){
    return number_pad(slide_id, 6);
};

ManageSlideOutput.get_icon = function(icon){
    
    var show_icon = '';
    
    var icon_src = g_root_url+'uploads/slides/'+icon;
    
    show_icon += '<img src="' + icon_src + '" alt="" width="80px" height="80px" />';
    
    return show_icon;
};

ManageSlideOutput.get_type = function(type){
    
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

ManageSlideOutput.get_file = function(file, type){
    
    var show_file = '';
    
    type = Utils.get_int(type);
    
    var folder = ''+g_root_url+'uploads/slides';
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
