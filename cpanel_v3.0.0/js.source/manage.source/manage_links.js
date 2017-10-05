
/*! links */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageSectionChilds.callback, ManageSectionChilds, CMSExtraUtil, RequestUtil, ManageSectionChilds, ManageSectionChildsOutput */


function ManageLink(){}

ManageLink.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'link';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",      type : "text",       label : CDictionary.get_text('LinkForm_TitleAr_lbl')+":"               },
                { name : "title_en",      type : "text",       label : CDictionary.get_text('LinkForm_TitleEn_lbl')+":"               },
                
                { type : "separator",     label : "" },
                
                { name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('LinkForm_DescAr_lbl')+":"                },
                { name : "desc_en",       type : "textarea",   label : CDictionary.get_text('LinkForm_DescEn_lbl')+":"                },

                { type : "separator",     label : "" },
                
                { name : "icon",          type : "image",      label : CDictionary.get_text('LinkForm_Icon_lbl')+":"                  },
                
                { type : "separator",     label : "" },
                
                { name : "url_ar",        type : "text",       label : CDictionary.get_text('LinkForm_UrlAr_lbl')+":"               },
                { name : "url_en",        type : "text",       label : CDictionary.get_text('LinkForm_UrlEn_lbl')+":"               },
                
                { type : "separator",      label : "" },
                
                { name : "top_menu",      type : "checkbox",   label : CDictionary.get_text('LinkForm_TopMenu_lbl')+":",    value:"1" },
                { name : "main_menu",     type : "checkbox",   label : CDictionary.get_text('LinkForm_MainMenu_lbl')+":", value:"1" },
                //{ name : "side_menu",     type : "checkbox",   label : CDictionary.get_text('LinkForm_SideMenu_lbl')+":",   value:"1" },
                { name : "foot_menu",     type : "checkbox",   label : CDictionary.get_text('LinkForm_FootMenu_lbl')+":",   value:"1" },
                
                { type : "separator",      label : "" },
                
                { name : "new_window",    type : "checkbox",   label : CDictionary.get_text('LinkForm_NewWindow_lbl')+":",  value:"1" },
                
                { type : "separator",      label : "" },
                
                { name : "order",         type : "text",       label : CDictionary.get_text('LinkForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('LinkForm_Active_lbl')+":",     value:"1" },

                { type : "separator",      label : "" },

                { name : "editable",      type : "hidden",     label : "", ignore_preview : true },
                { name : "removable",     type : "hidden",     label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",     label : "", ignore_preview : true },
                { name : "link_id",       type : "hidden",     label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageLink - get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageLink.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_div      = ManageLink.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_link",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageLink.prepare,
            validate_func       : ManageLink.validate,
            validate_notes_func : ManageLink.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageLink - add :['+err+']');
    }
};

ManageLink.edit   = function(child_index){

    try{

        //var link    = ManageSectionChildsOutput.get_object(child_index);
        //var link_id = link.link_id;

        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageLink.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/link_form.tpl",
            form_action         : g_request_url+"?action=update_link",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageLink.prepare,
            post_func           : ManageLink.post_edit, 
            post_args           : child_index,
            validate_func       : ManageLink.validate,
            validate_notes_func : ManageLink.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageLink - edit :['+err+']');
    }
};

ManageLink.remove = function(child_index){

    try{

        var link    = ManageSectionChildsOutput.get_object(child_index);
        var link_id = link.link_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageSectionChilds.list_div;

            var data = "action=remove_link"
                        + "&link_id=" + link_id;

            RequestUtil.quick_post_request(list_div, data, ManageSectionChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageLink - delete :['+err+']');
    }
};

ManageLink.view   = function(child_index){

    try{

        var link    = ManageSectionChildsOutput.get_object(child_index);
        //var link_id = link.link_id;

        var preview_object = ManageLink.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();

        preview_div.find( 'div[name=link_id]'    ).html( link.link_id    );
        preview_div.find( 'div[name=title_ar]'   ).html( link.title_ar   );
        preview_div.find( 'div[name=title_en]'   ).html( link.title_en   );
        preview_div.find( 'div[name=keys_ar]'    ).html( link.keys_ar    );
        preview_div.find( 'div[name=keys_en]'    ).html( link.keys_en    );
        preview_div.find( 'div[name=desc_ar]'    ).html( link.desc_ar    );
        preview_div.find( 'div[name=desc_en]'    ).html( link.desc_en    );
        preview_div.find( 'div[name=content_ar]' ).html( link.content_ar );
        preview_div.find( 'div[name=content_en]' ).html( link.content_en );

        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/links/'+link.icon+'" />'  );
        preview_div.find( 'div[name=image]'      ).html( '<img src="'+g_root_url+'uploads/links/'+link.image+'" />' );

        preview_div.find( 'div[name=url_ar]'     ).html( link.url_ar     );
        preview_div.find( 'div[name=url_en]'     ).html( link.url_en     );

        preview_div.find( 'div[name=style]'      ).html( link.style      );
        preview_div.find( 'div[name=top_menu]'   ).html( (link.top_menu>0  )?gYes:gNo );
        preview_div.find( 'div[name=main_menu]'  ).html( (link.main_menu>0 )?gYes:gNo );
        //preview_div.find( 'div[name=side_menu]'  ).html( (link.side_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=foot_menu]'  ).html( (link.foot_menu>0 )?gYes:gNo );

        preview_div.find( 'div[name=new_window]' ).html( (link.new_window>0 )?gYes:gNo );

        preview_div.find( 'div[name=show_menu]'  ).html( (link.show_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_text]'  ).html( (link.show_text>0 )?gYes:gNo );

        preview_div.find( 'div[name=order]'      ).html( link.order     );
        preview_div.find( 'div[name=active]'     ).html( (link.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'  ).html( link.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Link Info'  );

    }catch(err){
        console.log('Error in : ManageLink - view :['+err+']');
    }

};

ManageLink.print  = function(link_div){
    
    try{

        CMSExtraUtil.print_div_popup(link_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageLink - print :['+err+']');
    }
};

/******************************************************************************/

ManageLink.prepare   = function(form_div){

    try{

        var style_html = ManageLink.get_style_select();
        form_div.find( 'select[name=style]' ).html( style_html );

        if( ManageSectionChilds.parent_id > 0 ){

            form_div.find( 'input[name=top_menu]'  ).attr( "disabled", true );
            form_div.find( 'input[name=main_menu]' ).attr( "disabled", true );
            form_div.find( 'input[name=side_menu]' ).attr( "disabled", true );
            form_div.find( 'input[name=foot_menu]' ).attr( "disabled", true );

            form_div.find( 'input[name=top_menu]'  ).hide();
            form_div.find( 'input[name=main_menu]' ).hide();
            form_div.find( 'input[name=side_menu]' ).hide();
            form_div.find( 'input[name=foot_menu]' ).hide();

            form_div.find( 'label[for^=top_menu_checkbox]'   ).hide();
            form_div.find( 'label[for^=main_menu_checkbox]'  ).hide();
            form_div.find( 'label[for^=side_menu_checkbox]'  ).hide();
            form_div.find( 'label[for^=foot_menu_checkbox]'  ).hide();

        }else{
            //form_div.find( 'input[name=icon]' ).parent().parent().parent().hide();
        }

        form_div.find( 'input[name=parent_id]' ).val( ManageSectionChilds.parent_id );

        //alert( (new Date().getFormatted()) );
        //form_div.find( 'input[name=date]' ).val( (new Date().getFormatted()) );


    }catch(err){
        console.log('Error in : ManageLink - prepare :['+err+']');
    }
};

ManageLink.post_edit = function(child_index){

    try{

        var link    = ManageSectionChildsOutput.get_object(child_index);
        //var link       = get_link_child(link_id);

        //var form_div = $("#body").find("#content").find("#link_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageSectionChilds.form_div;

        form_div.find( 'input[name=link_id]'   ).val( link.link_id  );

        form_div.find( 'input[name=title_ar]'     ).val( link.title_ar    );
        form_div.find( 'input[name=title_en]'     ).val( link.title_en    );

        form_div.find( 'textarea[name=keys_ar]'   ).val( link.keys_ar     );
        form_div.find( 'textarea[name=keys_en]'   ).val( link.keys_en     );
        form_div.find( 'textarea[name=desc_ar]'   ).val( link.desc_ar     );
        form_div.find( 'textarea[name=desc_en]'   ).val( link.desc_en     );

        //form_div.find( 'input[name=icon]'       ).val( link.icon        );
        //form_div.find( 'input[name=image]'      ).val( link.image       );

        form_div.find( 'input[name=url_ar]'       ).val( link.url_ar      );
        form_div.find( 'input[name=url_en]'       ).val( link.url_en      );

        form_div.find( 'select[name=style]'       ).find('option[value='+link.style+']').attr('selected', true);

        form_div.find( 'input[name=top_menu]'     ).attr( "checked", (link.top_menu>0 ) ? true:false );
        form_div.find( 'input[name=main_menu]'    ).attr( "checked", (link.main_menu>0) ? true:false );
        //form_div.find( 'input[name=side_menu]'    ).attr( "checked", (link.side_menu>0) ? true:false );
        form_div.find( 'input[name=foot_menu]'    ).attr( "checked", (link.foot_menu>0) ? true:false );

        form_div.find( 'input[name=editable]'     ).val( link.editable   );
        form_div.find( 'input[name=removable]'    ).val( link.removable  );

        form_div.find( 'input[name=show_menu]'    ).attr( "checked", (link.show_menu>0) ? true:false );
        form_div.find( 'input[name=show_text]'    ).attr( "checked", (link.show_text>0) ? true:false );

        form_div.find( 'input[name=order]'        ).val( link.order      );
        form_div.find( 'input[name=active]'       ).attr( "checked", (link.active>0)   ? true:false );

        form_div.find( 'input[name=parent_id]'    ).val( link.parent_id  );


        var icon_src  = g_root_url+'uploads/links/'+link.icon;
        var image_src = g_root_url+'uploads/links/'+link.image;

        if( (form_div.find('input[name=icon]').length  > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }
        if( (form_div.find('input[name=image]').length > 0 ) && RequestUtil.image_exists( image_src ) ){
            form_div.find('.image-upload[data-name=image]').find(".preview").html( '<img src="'+image_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageLink - post edit :['+err+']');
    }
};

/******************************************************************************/

ManageLink.validate       = function(form_div){
    
    var errors = 0;
    
    try{

        form_div.find('.error').html('').remove();
        
        //console.log(COLOR_PATTERN.test("#ffffff"));
//
//        var elem_val = form_div.find('input[name=color]').val();
//        
//        if(   ! Validate.required( elem_val )   ){
//            errors++;
//        }else if( ! COLOR_PATTERN.test( elem_val ) ) {
//            errors++;
//        }
//        
//        if(   ! Validate.required( form_div.find('input[name=date]').val() )   ){
//            errors++;
//        }
//        
//        if(   ! Validate.required( form_div.find('select[name=status]').val() )   ){
//            errors++;
//        }
//        
//        if(   ! Validate.required( form_div.find('select[name=student_id]').val() )   ){
//            errors++;
//        }
        
        if( errors > 0 ){
            console.log( 'validate found : '+ errors + ' errors' );
        }

    }catch(err){
        console.log('Error in : ManageLink - validate :['+err+']');
    }

    return errors;
};

ManageLink.validate_notes = function(form_div){

    try{

        form_div.find('.error').html('').remove();

        var element  = null;
        var elem_val = null;
//        
//        element  = form_div.find('input[name=color]');
//        elem_val = element.val();
//        
//        
//        if(   ! Validate.required( element.val() )   ){
//            element.after( CMSExtraUtil.get_error_div('Color required !') );
//        } else if( ! COLOR_PATTERN.test( elem_val ) ) {
//            element.after( CMSExtraUtil.get_error_div('Color value incorrect !') );
//        }
//
//        element = form_div.find('input[name=date]');
//        if(   ! Validate.required( element.val() )   ){
//            element.after( CMSExtraUtil.get_error_div('Date required !') );
//        }
//        
//        element = form_div.find('select[name=status]');
//        if(   ! Validate.required( element.val() )   ){
//            element.after( CMSExtraUtil.get_error_div('Please select status !') );
//        }
//
//        element = form_div.find('select[name=student_id]');
//        if(   ! Validate.required( element.val() )   ){
//            element.after( CMSExtraUtil.get_error_div('Please select student !') );
//        }

    }catch(err){
        console.log('Error in : ManageLink - validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageLink.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>';

    return select_html;
};
