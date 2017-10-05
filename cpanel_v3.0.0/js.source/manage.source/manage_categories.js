
/*! categories */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageCategoryChilds.callback, ManageCategoryChilds, CMSExtraUtil, RequestUtil, ManageCategoryChilds, ManageCategoryChildsOutput */

function ManageCategory(){}

ManageCategory.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'category';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",      type : "text",       label : CDictionary.get_text('CategoryForm_TitleAr_lbl')+":"               },
                { name : "title_en",      type : "text",       label : CDictionary.get_text('CategoryForm_TitleEn_lbl')+":"               },
                
                { type : "separator",      label : "" },
                
                { name : "keys_ar",       type : "textarea",   label : CDictionary.get_text('CategoryForm_KeysAr_lbl')+":"                },
                { name : "keys_en",       type : "textarea",   label : CDictionary.get_text('CategoryForm_KeysEn_lbl')+":"                },
                { name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('CategoryForm_DescAr_lbl')+":"                },
                { name : "desc_en",       type : "textarea",   label : CDictionary.get_text('CategoryForm_DescEn_lbl')+":"                },
                
                { type : "separator",      label : "" },
                
                //{ name : "content_ar",    type : "editor",     label : CDictionary.get_text('CategoryForm_ContentAr_lbl')+":"             },
                //{ name : "content_en",    type : "editor",     label : CDictionary.get_text('CategoryForm_ContentEn_lbl')+":"             },
                
                { type : "separator",      label : "" },
                
                { name : "icon",          type : "image",       label : CDictionary.get_text('CategoryForm_Icon_lbl')+":"                  },
                //{ name : "image",         type : "file",       label : CDictionary.get_text('CategoryForm_Image_lbl')+":"                 },
                
                { type : "separator",      label : "" },
                
                { name : "format",      type : "select",    label : CDictionary.get_text('CategoryForm_Format_lbl')+":"       },
                //{ name : "style",         type : "select",     label : CDictionary.get_text('CategoryForm_Style_lbl')+":"                 },
                
                { type : "separator",      label : "" },
                
                { name : "top_menu",      type : "checkbox",   label : CDictionary.get_text('CategoryForm_TopMenu_lbl')+":",    value:"1" },
                //{ name : "main_menu",     type : "checkbox",   label : CDictionary.get_text('CategoryForm_MainMenu_lbl')+":", value:"1" },
                //{ name : "side_menu",     type : "checkbox",   label : CDictionary.get_text('CategoryForm_SideMenu_lbl')+":",   value:"1" },
                { name : "foot_menu",     type : "checkbox",   label : CDictionary.get_text('CategoryForm_FootMenu_lbl')+":",   value:"1" },
                
                { type : "separator",      label : "" },
                
                { name : "show_menu",     type : "checkbox",   label : CDictionary.get_text('CategoryForm_ShowMenu_lbl')+":",   value:"1" },
                { name : "show_text",     type : "checkbox",   label : CDictionary.get_text('CategoryForm_ShowText_lbl')+":",   value:"1" },
                
                { type : "separator",      label : "" },
                
                { name : "order",         type : "text",       label : CDictionary.get_text('CategoryForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('CategoryForm_Active_lbl')+":",     value:"1" },

                { type : "separator",      label : "" },

                { name : "editable",      type : "hidden",    label : "", ignore_preview : true },
                { name : "removable",     type : "hidden",    label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",    label : "", ignore_preview : true },
                { name : "category_id",   type : "hidden",    label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageCategory - get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageCategory.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageCategoryChilds );

        var cont_div_cell = ManageCategoryChilds.form_div;

        var form_div      = ManageCategory.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_category",
            complete_callback   : ManageCategoryChilds.callback,
            cancel_callback     : ManageCategoryChilds.cancel,
            prepare_func        : ManageCategory.prepare,
            validate_func       : ManageCategory.validate,
            validate_notes_func : ManageCategory.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageCategory - add :['+err+']');
    }
};

ManageCategory.edit   = function(child_index){

    try{

        //var category    = ManageCategoryChildsOutput.get_object(child_index);
        //var category_id = category.category_id;

        CMSExtraUtil.show_form( ManageCategoryChilds );

        var cont_div_cell = ManageCategoryChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageCategory.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/category_form.tpl",
            form_action         : g_request_url+"?action=update_category",
            complete_callback   : ManageCategoryChilds.callback,
            cancel_callback     : ManageCategoryChilds.cancel,
            prepare_func        : ManageCategory.prepare,
            post_func           : ManageCategory.post_edit, 
            post_args           : child_index,
            validate_func       : ManageCategory.validate,
            validate_notes_func : ManageCategory.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageCategory - edit :['+err+']');
    }
};

ManageCategory.remove = function(child_index){

    try{

        var category    = ManageCategoryChildsOutput.get_object(child_index);
        var category_id = category.category_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageCategoryChilds.list_div;

            var data = "action=remove_category"
                        + "&category_id=" + category_id;

            RequestUtil.quick_post_request(list_div, data, ManageCategoryChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageCategory - delete :['+err+']');
    }
};

ManageCategory.view   = function(child_index){

    try{

        var category    = ManageCategoryChildsOutput.get_object(child_index);
        //var category_id = category.category_id;

        var preview_object = ManageCategory.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();


        preview_div.find( 'div[name=category_id]' ).html( category.category_id );
        preview_div.find( 'div[name=title_ar]'    ).html( category.title_ar    );
        preview_div.find( 'div[name=title_en]'    ).html( category.title_en    );
        preview_div.find( 'div[name=keys_ar]'     ).html( category.keys_ar     );
        preview_div.find( 'div[name=keys_en]'     ).html( category.keys_en     );
        preview_div.find( 'div[name=desc_ar]'     ).html( category.desc_ar     );
        preview_div.find( 'div[name=desc_en]'     ).html( category.desc_en     );
        preview_div.find( 'div[name=content_ar]'  ).html( category.content_ar  );
        preview_div.find( 'div[name=content_en]'  ).html( category.content_en  );
        preview_div.find( 'div[name=icon]'        ).html( '<img src="'+g_root_url+'uploads/categories/'+category.icon+'" />'  );
        preview_div.find( 'div[name=image]'       ).html( '<img src="'+g_root_url+'uploads/categories/'+category.image+'" />' );
        preview_div.find( 'div[name=format]'      ).html( category.format      );
        preview_div.find( 'div[name=top_menu]'    ).html( (category.top_menu>0  )?gYes:gNo );
        //preview_div.find( 'div[name=main_menu]'  ).html( (category.main_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=side_menu]'   ).html( (category.side_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=foot_menu]'   ).html( (category.foot_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_menu]'   ).html( (category.show_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_text]'   ).html( (category.show_text>0 )?gYes:gNo );
        preview_div.find( 'div[name=order]'       ).html( category.order        );
        preview_div.find( 'div[name=active]'      ).html( (category.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'   ).html( category.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Category Info'  );

    }catch(err){
        console.log('Error in : ManageCategory - view :['+err+']');
    }

};

ManageCategory.print  = function(category_div){
    
    try{

        CMSExtraUtil.print_div_popup(category_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageCategory - print :['+err+']');
    }
};

/******************************************************************************/

ManageCategory.prepare   = function(form_div){

    try{

        var format_html = ManageCategory.get_format_select();

        form_div.find( 'select[name=format]' ).html( format_html );


        if( ManageCategoryChilds.parent_id > 0 ){

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

            //form_div.find( 'input[name=top_menu]'  ).parent().parent().hide();
            //form_div.find( 'input[name=main_menu]' ).parent().parent().hide();
            //form_div.find( 'input[name=side_menu]' ).parent().parent().hide();
            //form_div.find( 'input[name=foot_menu]' ).parent().parent().hide();

        }else{
            //form_div.find( 'input[name=icon]' ).parent().parent().parent().hide();
        }

        form_div.find( 'input[name=parent_id]' ).val( ManageCategoryChilds.parent_id );

        //alert( (new Date().getFormatted()) );
        //form_div.find( 'input[name=date]' ).val( (new Date().getFormatted()) );

    }catch(err){
        console.log('Error in : ManageCategory - prepare :['+err+']');
    }
};

ManageCategory.post_edit = function(child_index){

    try{

        var category    = ManageCategoryChildsOutput.get_object(child_index);
        //var category       = get_category_child(category_id);

        //var form_div = $("#body").find("#content").find("#category_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageCategoryChilds.form_div;

        form_div.find( 'input[name=category_id]'  ).val( category.category_id  );

        form_div.find( 'input[name=title_ar]'     ).val( category.title_ar    );
        form_div.find( 'input[name=title_en]'     ).val( category.title_en    );
        form_div.find( 'textarea[name=keys_ar]'   ).val( category.keys_ar     );
        form_div.find( 'textarea[name=keys_en]'   ).val( category.keys_en     );
        form_div.find( 'textarea[name=desc_ar]'   ).val( category.desc_ar     );
        form_div.find( 'textarea[name=desc_en]'   ).val( category.desc_en     );
        
        form_div.find( 'input[name=content_ar]'   ).val( category.content_ar  );
        form_div.find( 'input[name=content_en]'   ).val( category.content_en  );
        form_div.find( 'div[name=content_ar]'     ).html( category.content_ar );
        form_div.find( 'div[name=content_en]'     ).html( category.content_en );

        //form_div.find( 'input[name=icon]'       ).val( category.icon        );
        //form_div.find( 'input[name=image]'      ).val( category.image       );
        
        form_div.find( 'select[name=format]'       ).find('option[value='+category.format+']').attr('selected', true);

        form_div.find( 'input[name=top_menu]'     ).attr( "checked", (category.top_menu>0 ) ? true:false );
        //form_div.find( 'input[name=side_menu]'    ).attr( "checked", (category.side_menu>0) ? true:false );
        form_div.find( 'input[name=main_menu]'    ).attr( "checked", (category.main_menu>0) ? true:false );
        form_div.find( 'input[name=foot_menu]'    ).attr( "checked", (category.foot_menu>0) ? true:false );

        form_div.find( 'input[name=editable]'     ).val( category.editable   );
        form_div.find( 'input[name=removable]'    ).val( category.removable  );

        form_div.find( 'input[name=show_menu]'    ).attr( "checked", (category.show_menu>0) ? true:false );
        form_div.find( 'input[name=show_text]'    ).attr( "checked", (category.show_text>0) ? true:false );

        form_div.find( 'input[name=order]'        ).val( category.order      );

        form_div.find( 'input[name=active]'       ).attr( "checked", (category.active>0)   ? true:false );

        form_div.find( 'input[name=parent_id]'    ).val( category.parent_id  );

        var icon_src  = g_root_url+'uploads/categories/'+category.icon;
        var image_src = g_root_url+'uploads/categories/'+category.image;

        if( ( form_div.find('input[name=icon]').length  > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }
        if( ( form_div.find('input[name=image]').length > 0 ) && RequestUtil.image_exists( image_src ) ){
            form_div.find('.image-upload[data-name=image]').find(".preview").html( '<img src="'+image_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageCategory - post edit :['+err+']');
    }
};

/******************************************************************************/

ManageCategory.validate       = function(form_div){
    
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
        console.log('Error in : ManageCategory - validate :['+err+']');
    }

    return errors;
};

ManageCategory.validate_notes = function(form_div){

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
        console.log('Error in : ManageCategory - validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageCategory.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>';

    return select_html;
};
