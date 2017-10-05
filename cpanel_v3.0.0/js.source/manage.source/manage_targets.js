
/*! targets */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageSectionChilds.callback, ManageSectionChilds, CMSExtraUtil, RequestUtil, ManageSectionChilds, ManageSectionChildsOutput */


function ManageTarget(){}

ManageTarget.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'target';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",      type : "text",       label : CDictionary.get_text('TargetForm_TitleAr_lbl')+":"               },
                { name : "title_en",      type : "text",       label : CDictionary.get_text('TargetForm_TitleEn_lbl')+":"               },
                
                { type : "separator",      label : "" },
                
                { name : "keys_ar",       type : "textarea",   label : CDictionary.get_text('TargetForm_KeysAr_lbl')+":"                },
                { name : "keys_en",       type : "textarea",   label : CDictionary.get_text('TargetForm_KeysEn_lbl')+":"                },
                { name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('TargetForm_DescAr_lbl')+":"                },
                { name : "desc_en",       type : "textarea",   label : CDictionary.get_text('TargetForm_DescEn_lbl')+":"                },
                
                { type : "separator",      label : "" },
                
                { name : "content_ar",    type : "editor",     label : CDictionary.get_text('TargetForm_ContentAr_lbl')+":"             },
                { name : "content_en",    type : "editor",     label : CDictionary.get_text('TargetForm_ContentEn_lbl')+":"             },
                
                { type : "separator",      label : "" },
                
                { name : "icon",          type : "image",      label : CDictionary.get_text('TargetForm_Icon_lbl')+":"                  },
                { name : "image",         type : "image",      label : CDictionary.get_text('TargetForm_Image_lbl')+":"                 },
                
                { type : "separator",      label : "" },
                
                { name : "format",        type : "select",     label : CDictionary.get_text('TargetForm_Format_lbl')+":"                 },
                
                { type : "separator",      label : "" },
                
                { name : "top_menu",      type : "checkbox",   label : CDictionary.get_text('TargetForm_TopMenu_lbl'),    value:"1" },
                { name : "main_menu",     type : "checkbox",   label : CDictionary.get_text('TargetForm_MainMenu_lbl'),   value:"1" },
                //{ name : "side_menu",     type : "checkbox",   label : CDictionary.get_text('TargetForm_SideMenu_lbl'),   value:"1" },
                { name : "foot_menu",     type : "checkbox",   label : CDictionary.get_text('TargetForm_FootMenu_lbl'),   value:"1" },
                
                { type : "separator",      label : "" },
                
                { name : "order",         type : "text",       label : CDictionary.get_text('TargetForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('TargetForm_Active_lbl'),     value:"1" },

                { type : "separator",      label : "" },

                { name : "editable",      type : "hidden",   label : "", ignore_preview : true },
                { name : "removable",     type : "hidden",   label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",   label : "", ignore_preview : true },
                { name : "target_id",     type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageTarget - get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageTarget.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_div      = ManageTarget.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_target",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageTarget.prepare,
            validate_func       : ManageTarget.validate,
            validate_notes_func : ManageTarget.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageTarget - add :['+err+']');
    }
};

ManageTarget.edit   = function(child_index){

    try{

        //var target    = ManageSectionChildsOutput.get_object(child_index);
        //var target_id = target.target_id;

        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageTarget.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/target_form.tpl",
            form_action         : g_request_url+"?action=update_target",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageTarget.prepare,
            post_func           : ManageTarget.post_edit, 
            post_args           : child_index,
            validate_func       : ManageTarget.validate,
            validate_notes_func : ManageTarget.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageTarget - edit :['+err+']');
    }
};

ManageTarget.remove = function(child_index){

    try{

        var target    = ManageSectionChildsOutput.get_object(child_index);
        var target_id = target.target_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageSectionChilds.list_div;

            var data = "action=remove_target"
                        + "&target_id=" + target_id;

            RequestUtil.quick_post_request(list_div, data, ManageSectionChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageTarget - delete :['+err+']');
    }
};

ManageTarget.view   = function(child_index){

    try{

        var target    = ManageSectionChildsOutput.get_object(child_index);
        //var target_id = target.target_id;

        var preview_object = ManageTarget.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();


        preview_div.find( 'div[name=target_id]'  ).html( target.target_id );
        //preview_div.find( 'div[name=target_id]'  ).html( target.target_id  );
        preview_div.find( 'div[name=title_ar]'   ).html( target.title_ar   );
        preview_div.find( 'div[name=title_en]'   ).html( target.title_en   );
        preview_div.find( 'div[name=keys_ar]'    ).html( target.keys_ar    );
        preview_div.find( 'div[name=keys_en]'    ).html( target.keys_en    );
        preview_div.find( 'div[name=desc_ar]'    ).html( target.desc_ar    );
        preview_div.find( 'div[name=desc_en]'    ).html( target.desc_en    );
        preview_div.find( 'div[name=content_ar]' ).html( target.content_ar );
        preview_div.find( 'div[name=content_en]' ).html( target.content_en );

        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/targets/'+target.icon+'" />'  );
        preview_div.find( 'div[name=image]'      ).html( '<img src="'+g_root_url+'uploads/targets/'+target.image+'" />' );

        preview_div.find( 'div[name=format]'     ).html( target.format      );
        preview_div.find( 'div[name=top_menu]'   ).html( (target.top_menu>0  )?gYes:gNo );
        preview_div.find( 'div[name=main_menu]'  ).html( (target.main_menu>0 )?gYes:gNo );
        //preview_div.find( 'div[name=side_menu]'  ).html( (target.side_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=foot_menu]'  ).html( (target.foot_menu>0 )?gYes:gNo );

        preview_div.find( 'div[name=show_menu]'  ).html( (target.show_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_text]'  ).html( (target.show_text>0 )?gYes:gNo );

        preview_div.find( 'div[name=order]'      ).html( target.order     );
        preview_div.find( 'div[name=active]'     ).html( (target.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'  ).html( target.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Target Info'  );

    }catch(err){
        console.log('Error in : ManageTarget - view :['+err+']');
    }

};

ManageTarget.print  = function(target_div){
    
    try{

        CMSExtraUtil.print_div_popup(target_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageTarget - print :['+err+']');
    }
};

/******************************************************************************/

ManageTarget.prepare   = function(form_div){

    try{

        
        var format_html = ManageTarget.get_format_select();

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
        console.log('Error in : ManageTarget - prepare :['+err+']');
    }
};

ManageTarget.post_edit = function(child_index){

    try{

        var target    = ManageSectionChildsOutput.get_object(child_index);
        //var target       = get_target_child(target_id);

        //var form_div = $("#body").find("#content").find("#target_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageSectionChilds.form_div;

        form_div.find( 'input[name=target_id]'    ).val( target.target_id   );
        form_div.find( 'input[name=title_ar]'     ).val( target.title_ar    );
        form_div.find( 'input[name=title_en]'     ).val( target.title_en    );
        form_div.find( 'textarea[name=keys_ar]'   ).val( target.keys_ar     );
        form_div.find( 'textarea[name=keys_en]'   ).val( target.keys_en     );
        form_div.find( 'textarea[name=desc_ar]'   ).val( target.desc_ar     );
        form_div.find( 'textarea[name=desc_en]'   ).val( target.desc_en     );
        form_div.find( 'input[name=content_ar]'   ).val( target.content_ar  );
        form_div.find( 'input[name=content_en]'   ).val( target.content_en  );
        form_div.find( 'div[name=content_ar]'     ).html( target.content_ar );
        form_div.find( 'div[name=content_en]'     ).html( target.content_en );
        //form_div.find( 'input[name=icon]'       ).val( target.icon        );
        //form_div.find( 'input[name=image]'      ).val( target.image       );
        form_div.find( 'select[name=style]'       ).find('option[value='+target.style+']').attr('selected', true);
        form_div.find( 'input[name=top_menu]'     ).attr( "checked", (target.top_menu>0)  ? true:false );
        form_div.find( 'input[name=main_menu]'    ).attr( "checked", (target.main_menu>0) ? true:false );
        //form_div.find( 'input[name=side_menu]'    ).attr( "checked", (target.side_menu>0) ? true:false );
        form_div.find( 'input[name=foot_menu]'    ).attr( "checked", (target.foot_menu>0) ? true:false );
        form_div.find( 'input[name=editable]'     ).val( target.editable    );
        form_div.find( 'input[name=removable]'    ).val( target.removable   );
        form_div.find( 'input[name=order]'        ).val( target.order       );
        form_div.find( 'input[name=active]'       ).attr( "checked", (target.active>0)   ? true:false );
        form_div.find( 'input[name=parent_id]'    ).val( target.parent_id  );

        form_div.find( 'input[name=parent_id]'    ).val( target.parent_id  );


        var icon_src  = g_root_url+'uploads/targets/'+target.icon;
        var image_src = g_root_url+'uploads/targets/'+target.image;

        if( (form_div.find('input[name=icon]').length  > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }
        if( (form_div.find('input[name=image]').length > 0 ) && RequestUtil.image_exists( image_src ) ){
            form_div.find('.image-upload[data-name=image]').find(".preview").html( '<img src="'+image_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageTarget - post edit :['+err+']');
    }
};

/******************************************************************************/

ManageTarget.validate       = function(form_div){
    
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
        console.log('Error in : ManageTarget - validate :['+err+']');
    }

    return errors;
};

ManageTarget.validate_notes = function(form_div){

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
        console.log('Error in : ManageTarget - validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageTarget.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>';

    return select_html;
};
