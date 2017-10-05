
/*! sections */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageSectionChilds.callback, ManageSectionChilds, CMSExtraUtil, RequestUtil, ManageSectionChilds, ManageSectionChildsOutput */


function ManageSection(){}

ManageSection.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'section';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",    type : "text",      label : CDictionary.get_text('SectionForm_TitleAr_lbl')+":"     },
                { name : "title_en",    type : "text",      label : CDictionary.get_text('SectionForm_TitleEn_lbl')+":"     },

                { name : "desc_ar",     type : "textarea",  label : CDictionary.get_text('SectionForm_DescAr_lbl')+":"      },
                { name : "desc_en",     type : "textarea",  label : CDictionary.get_text('SectionForm_DescEn_lbl')+":"      },

                { name : "keys_ar",     type : "textarea",  label : CDictionary.get_text('SectionForm_KeysAr_lbl')+":"      },
                { name : "keys_en",     type : "textarea",  label : CDictionary.get_text('SectionForm_KeysEn_lbl')+":"      },

                { type : "separator",   label : "" },

                { name : "content_ar",  type : "editor",    label : CDictionary.get_text('SectionForm_ContentAr_lbl')+":"   },
                { name : "content_en",  type : "editor",    label : CDictionary.get_text('SectionForm_ContentEn_lbl')+":"   },

                { type : "separator",   label : "" },

                { name : "icon",        type : "file",      label : CDictionary.get_text('SectionForm_Icon_lbl')+":"        },
                //{ name : "image",       type : "file",      label : CDictionary.get_text('SectionForm_Image_lbl')+":"      },

                { type : "separator",   label : "" },

                { name : "format",      type : "select",    label : CDictionary.get_text('SectionForm_Format_lbl')+":"       },

                { type : "separator",   label : "" },
                { type : "clear",       label : "" },

                //{ name : "date",      type : "date",  format : "yy-mm-dd", label : CDictionary.get_text('SectionForm_Date_lbl')+":"      },
                //{ name : "color",     type : "color", label : CDictionary.get_text('SectionForm_Color_lbl')+":"      },

                { name : "top_menu",    type : "checkbox",  label : CDictionary.get_text('SectionForm_TopMenu_lbl')+":",  value:"1" },
                { name : "main_menu",   type : "checkbox",  label : CDictionary.get_text('SectionForm_MainMenu_lbl')+":", value:"1" },
                //{ name : "side_menu",     type : "checkbox",   label : CDictionary.get_text('SectionForm_SideMenu_lbl')+":",   value:"1" },
                { name : "foot_menu",   type : "checkbox",  label : CDictionary.get_text('SectionForm_FootMenu_lbl')+":", value:"1" },

                { type : "separator",   label : "" },

                { name : "show_menu",   type : "checkbox",  label : CDictionary.get_text('SectionForm_ShowMenu_lbl')+":", value:"1" },
                { name : "show_text",   type : "checkbox",  label : CDictionary.get_text('SectionForm_ShowText_lbl')+":", value:"1" },

                { type : "separator",    label : "" },
                { type : "clear",        label : "" },

                { name : "sitemap_exclude", type : "checkbox",  label : CDictionary.get_text('SectionForm_SitemapExclude_lbl')+":", value:"1" },
                { name : "special",         type : "checkbox",  label : CDictionary.get_text('SectionForm_Special_lbl')+":",        value:"1" },

                { type : "separator",   label : "" },
                { type : "clear",       label : "" },

                { name : "order",       type : "text",      label : CDictionary.get_text('SectionForm_Order_lbl')+":"             },
                { name : "active",      type : "checkbox",  label : CDictionary.get_text('SectionForm_Active_lbl')+":", value:"1" },

                { type : "separator",   label : "" },

                { name : "editable",    type : "hidden",    label : "", ignore_preview : true },
                { name : "removable",   type : "hidden",    label : "", ignore_preview : true },

                //{ name : "parent_id",   type : "hidden",    label : CDictionary.get_text('SectionForm_ParentId_lbl')+":" },
                { name : "parent_id",   type : "hidden",    label : "", ignore_preview : true },
                { name : "section_id",  type : "hidden",    label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageSection - get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageSection.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_div      = ManageSection.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_section",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageSection.prepare,
            validate_func       : ManageSection.validate,
            validate_notes_func : ManageSection.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageSection - add :['+err+']');
    }
};

ManageSection.edit   = function(child_index){

    try{

        //var section    = ManageSectionChildsOutput.get_object(child_index);
        //var section_id = section.section_id;

        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageSection.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/section_form.tpl",
            form_action         : g_request_url+"?action=update_section",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageSection.prepare,
            post_func           : ManageSection.post_edit, 
            post_args           : child_index,
            validate_func       : ManageSection.validate,
            validate_notes_func : ManageSection.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageSection - edit :['+err+']');
    }
};

ManageSection.remove = function(child_index){

    try{

        var section    = ManageSectionChildsOutput.get_object(child_index);
        var section_id = section.section_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageSectionChilds.list_div;

            var data = "action=remove_section"
                        + "&section_id=" + section_id;

            RequestUtil.quick_post_request(list_div, data, ManageSectionChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageSection - delete :['+err+']');
    }
};

ManageSection.view   = function(child_index){

    try{

        var section    = ManageSectionChildsOutput.get_object(child_index);
        //var section_id = section.section_id;

        var preview_object = ManageSection.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();


        preview_div.find( 'div[name=section_id]' ).html( section.section_id );
        //preview_div.find( 'div[name=section_id]'  ).html( section.section_id  );
        preview_div.find( 'div[name=title_ar]'   ).html( section.title_ar   );
        preview_div.find( 'div[name=title_en]'   ).html( section.title_en   );
        preview_div.find( 'div[name=keys_ar]'    ).html( section.keys_ar    );
        preview_div.find( 'div[name=keys_en]'    ).html( section.keys_en    );
        preview_div.find( 'div[name=desc_ar]'    ).html( section.desc_ar    );
        preview_div.find( 'div[name=desc_en]'    ).html( section.desc_en    );
        preview_div.find( 'div[name=content_ar]' ).html( section.content_ar );
        preview_div.find( 'div[name=content_en]' ).html( section.content_en );

        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/sections/'+section.icon+'" />'  );
        preview_div.find( 'div[name=image]'      ).html( '<img src="'+g_root_url+'uploads/sections/'+section.image+'" />' );

        preview_div.find( 'div[name=format]'     ).html( section.format       );
        preview_div.find( 'div[name=top_menu]'   ).html( (section.top_menu>0  )?gYes:gNo );
        preview_div.find( 'div[name=main_menu]'  ).html( (section.main_menu>0 )?gYes:gNo );
        //preview_div.find( 'div[name=side_menu]'  ).html( (section.side_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=foot_menu]'  ).html( (section.foot_menu>0 )?gYes:gNo );

        preview_div.find( 'div[name=show_menu]'  ).html( (section.show_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_text]'  ).html( (section.show_text>0 )?gYes:gNo );

        preview_div.find( 'div[name=sitemap_exclude]' ).html( (section.sitemap_exclude>0 )?gYes:gNo );
        preview_div.find( 'div[name=special]'         ).html( (section.special>0 )        ?gYes:gNo );

        preview_div.find( 'div[name=order]'      ).html( section.order     );
        preview_div.find( 'div[name=active]'     ).html( (section.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'  ).html( section.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Section Info'  );

    }catch(err){
        console.log('Error in : ManageSection - view :['+err+']');
    }

};

ManageSection.print  = function(section_div){
    
    try{

        CMSExtraUtil.print_div_popup(section_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageSection - print :['+err+']');
    }
};

/******************************************************************************/

ManageSection.prepare   = function(form_div){

    try{

        var format_html = ManageSection.get_format_select();

        form_div.find( 'select[name=format]' ).html( format_html );


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
        console.log('Error in : ManageSection - prepare :['+err+']');
    }
};

ManageSection.post_edit = function(child_index){

    try{

        var section    = ManageSectionChildsOutput.get_object(child_index);
        //var section       = get_section_child(section_id);

        //var form_div = $("#body").find("#content").find("#section_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageSectionChilds.form_div;

        form_div.find( 'input[name=section_id]'   ).val( section.section_id  );

        form_div.find( 'input[name=title_ar]'     ).val( section.title_ar    );
        form_div.find( 'input[name=title_en]'     ).val( section.title_en    );

        form_div.find( 'textarea[name=keys_ar]'   ).val( section.keys_ar     );
        form_div.find( 'textarea[name=keys_en]'   ).val( section.keys_en     );
        form_div.find( 'textarea[name=desc_ar]'   ).val( section.desc_ar     );
        form_div.find( 'textarea[name=desc_en]'   ).val( section.desc_en     );

        form_div.find( 'input[name=content_ar]'   ).val( section.content_ar  );
        form_div.find( 'input[name=content_en]'   ).val( section.content_en  );
        form_div.find( 'div[name=content_ar]'     ).html( section.content_ar );
        form_div.find( 'div[name=content_en]'     ).html( section.content_en );

        //form_div.find( 'input[name=icon]'       ).val( section.icon        );
        //form_div.find( 'input[name=image]'      ).val( section.image       );

        form_div.find( 'select[name=format]'      ).find('option[value='+section.format+']').attr('selected', true);

        form_div.find( 'input[name=top_menu]'     ).attr( "checked", (section.top_menu>0 ) ? true:false );
        form_div.find( 'input[name=side_menu]'    ).attr( "checked", (section.side_menu>0) ? true:false );
        form_div.find( 'input[name=main_menu]'    ).attr( "checked", (section.main_menu>0) ? true:false );
        form_div.find( 'input[name=foot_menu]'    ).attr( "checked", (section.foot_menu>0) ? true:false );

        form_div.find( 'input[name=editable]'     ).val( section.editable   );
        form_div.find( 'input[name=removable]'    ).val( section.removable  );

        form_div.find( 'input[name=show_menu]'    ).attr( "checked", (section.show_menu>0) ? true:false );
        form_div.find( 'input[name=show_text]'    ).attr( "checked", (section.show_text>0) ? true:false );

        form_div.find( 'input[name=sitemap_exclude]' ).attr( "checked", (section.sitemap_exclude>0) ? true:false );
        form_div.find( 'input[name=special]'         ).attr( "checked", (section.special>0)         ? true:false );

        form_div.find( 'input[name=order]'        ).val( section.order      );
        form_div.find( 'input[name=active]'       ).attr( "checked", (section.active>0)   ? true:false );

        form_div.find( 'input[name=parent_id]'    ).val( section.parent_id  );


        var icon_src  = g_root_url+'uploads/sections/'+section.icon;
        var image_src = g_root_url+'uploads/sections/'+section.image;

        if( ( form_div.find('input[name=icon]').length  > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }
        if( ( form_div.find('input[name=image]').length > 0 ) && RequestUtil.image_exists( image_src ) ){
            form_div.find('.image-upload[data-name=image]').find(".preview").html( '<img src="'+image_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageSection - post edit :['+err+']');
    }
};

/******************************************************************************/

ManageSection.validate       = function(form_div){
    
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
        console.log('Error in : ManageSection - validate :['+err+']');
    }

    return errors;
};

ManageSection.validate_notes = function(form_div){

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
        console.log('Error in : ManageSection - validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageSection.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>' +
                    //'<option value="2">Style 2</option>' +
                    '<option value="'+STYLE_MEDIA+'">Media</option>';

    return select_html;
};
