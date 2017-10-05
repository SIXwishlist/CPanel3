
/*! embeds */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageSectionChilds.callback, ManageSectionChilds, CMSExtraUtil, RequestUtil, ManageSectionChilds, ManageSectionChildsOutput */


function ManageEmbed(){}

ManageEmbed.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'embed';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "title_ar",      type : "text",       label : CDictionary.get_text('EmbedForm_TitleAr_lbl')+":"               },
                { name : "title_en",      type : "text",       label : CDictionary.get_text('EmbedForm_TitleEn_lbl')+":"               },
                
                { type : "separator",      label : "" },
                
                //{ name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('EmbedForm_DescAr_lbl')+":"                },
                //{ name : "desc_en",       type : "textarea",   label : CDictionary.get_text('EmbedForm_DescEn_lbl')+":"                },
                
                //{ type : "separator",      label : "" },
                
                { name : "icon",          type : "image",      label : CDictionary.get_text('EmbedForm_Icon_lbl')+":"                  },
                { name : "type",          type : "select",     label : CDictionary.get_text('EmbedForm_Type_lbl')+":"                  },
                { name : "file",          type : "div",        label : CDictionary.get_text('EmbedForm_File_lbl')+":"                  },
                
                { type : "separator",      label : "" },
                
                { name : "order",         type : "text",       label : CDictionary.get_text('EmbedForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('EmbedForm_Active_lbl')+":",     value:"1" },

                { type : "separator",      label : "" },
                
                { name : "parent_type",   type : "hidden",   label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",   label : "", ignore_preview : true },
                { name : "embed_id",      type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageEmbed -  get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageEmbed.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_div      = ManageEmbed.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_embed",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageEmbed.prepare,
            validate_func       : ManageEmbed.validate,
            validate_notes_func : ManageEmbed.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageEmbed -  add :['+err+']');
    }
};

ManageEmbed.edit   = function(child_index){

    try{

        //var embed    = ManageSectionChildsOutput.get_object(child_index);
        //var embed_id = embed.embed_id;

        CMSExtraUtil.show_form( ManageSectionChilds );

        var cont_div_cell = ManageSectionChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageEmbed.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/embed_form.tpl",
            form_action         : g_request_url+"?action=update_embed",
            complete_callback   : ManageSectionChilds.callback,
            cancel_callback     : ManageSectionChilds.cancel,
            prepare_func        : ManageEmbed.prepare,
            post_func           : ManageEmbed.post_edit, 
            post_args           : child_index,
            validate_func       : ManageEmbed.validate,
            validate_notes_func : ManageEmbed.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageEmbed -  edit :['+err+']');
    }
};

ManageEmbed.remove = function(child_index){

    try{

        var embed    = ManageSectionChildsOutput.get_object(child_index);
        var embed_id = embed.embed_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageSectionChilds.list_div;

            var data = "action=remove_embed"
                        + "&embed_id=" + embed_id;

            RequestUtil.quick_post_request(list_div, data, ManageSectionChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageEmbed -  delete :['+err+']');
    }
};

ManageEmbed.view   = function(child_index){

    try{

        var embed    = ManageSectionChildsOutput.get_object(child_index);
        //var embed_id = embed.embed_id;

        var preview_object = ManageEmbed.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();

        preview_div.find( 'div[name=embed_id]'   ).html( embed.embed_id   );
        preview_div.find( 'div[name=title_ar]'   ).html( embed.title_ar   );
        preview_div.find( 'div[name=title_en]'   ).html( embed.title_en   );
        preview_div.find( 'div[name=keys_ar]'    ).html( embed.keys_ar    );
        preview_div.find( 'div[name=keys_en]'    ).html( embed.keys_en    );
        preview_div.find( 'div[name=desc_ar]'    ).html( embed.desc_ar    );
        preview_div.find( 'div[name=desc_en]'    ).html( embed.desc_en    );
        preview_div.find( 'div[name=content_ar]' ).html( embed.content_ar );
        preview_div.find( 'div[name=content_en]' ).html( embed.content_en );

        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/embeds/'+embed.icon+'" />'  );

        preview_div.find( 'div[name=file]'       ).html( ManageEmbed.get_file( embed.file, embed.type ) );
        preview_div.find( 'div[name=type]'       ).html( ManageEmbed.get_type( embed.type ) );

        preview_div.find( 'div[name=style]'      ).html( embed.style      );
        preview_div.find( 'div[name=top_menu]'   ).html( (embed.top_menu>0  )?gYes:gNo );
        //preview_div.find( 'div[name=main_menu]'  ).html( (embed.main_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=side_menu]'  ).html( (embed.side_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=foot_menu]'  ).html( (embed.foot_menu>0 )?gYes:gNo );

        preview_div.find( 'div[name=show_menu]'  ).html( (embed.show_menu>0 )?gYes:gNo );
        preview_div.find( 'div[name=show_text]'  ).html( (embed.show_text>0 )?gYes:gNo );

        preview_div.find( 'div[name=order]'      ).html( embed.order     );
        preview_div.find( 'div[name=active]'     ).html( (embed.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'  ).html( embed.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Embed Info'  );

    }catch(err){
        console.log('Error in : ManageEmbed -  view :['+err+']');
    }

};

ManageEmbed.print  = function(embed_div){
    
    try{

        CMSExtraUtil.print_div_popup(embed_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageEmbed -  print :['+err+']');
    }
};

/******************************************************************************/

ManageEmbed.prepare   = function(form_div){

    try{
        
        var style_html = ManageEmbed.get_style_select();
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

        ManageEmbed.form_settings(form_div);

        //alert( (new Date().getFormatted()) );
        //form_div.find( 'input[name=date]' ).val( (new Date().getFormatted()) );

    }catch(err){
        console.log('Error in : ManageEmbed -  prepare :['+err+']');
    }
};

ManageEmbed.post_edit = function(child_index){

    try{

        var embed    = ManageSectionChildsOutput.get_object(child_index);

        //var form_div = $("#body").find("#content").find("#embed_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageSectionChilds.form_div;

        form_div.find( 'input[name=embed_id]'     ).val( embed.embed_id    );
        form_div.find( 'input[name=title_ar]'     ).val( embed.title_ar    );
        form_div.find( 'input[name=title_en]'     ).val( embed.title_en    );
        form_div.find( 'textarea[name=desc_ar]'   ).val( embed.desc_ar     );
        form_div.find( 'textarea[name=desc_en]'   ).val( embed.desc_en     );
        //form_div.find( 'input[name=icon]'       ).val( embed.icon        );
        //form_div.find( 'input[name=file]'       ).val( embed.image       );
        form_div.find( 'select[name=type]'        ).find( 'option[value='+embed.type+']' ).attr( "selected", true );
        form_div.find( 'input[name=order]'        ).val( embed.order       );
        form_div.find( 'input[name=active]'       ).attr( "checked", (embed.active>0)   ? "checked":"" );
        form_div.find( 'input[name=parent_id]'    ).val( embed.parent_id   );

        ManageEmbed.change_file_type( form_div, embed.type );

        ManageEmbed.set_file_value( form_div, embed.type, embed.file );


        var icon_src  = g_root_url+'uploads/embeds/'+embed.icon;

        if( ( form_div.find( 'input[name=icon]' ).length > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageEmbed -  post edit :['+err+']');
    }
};

/******************************************************************************/

ManageEmbed.validate       = function(form_div){
    
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
        console.log('Error in : ManageEmbed -  validate :['+err+']');
    }

    return errors;
};

ManageEmbed.validate_notes = function(form_div){

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
        console.log('Error in : ManageEmbed -  validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageEmbed.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>';

    return select_html;
};

/******************************************************************************/

ManageEmbed.form_settings    = function(form_div){
    
    try{
        
        form_div.find( 'select[name=type]' ).html( 
            '<option value="1">Download</option>'    +
            '<option value="2">Image</option>'       +
            '<option value="3">Flash</option>'       +
            '<option value="5">Video</option>'       +
            '<option value="6">Youtube</option>'     +
            '<option value="7">Vimeo</option>'       +
            '<option value="8">Embed Code</option>'  +
            '<option value="9">Sound Cloud</option>' 
        );

        form_div.find( 'select[name=type]' ).change(function () {

            ManageEmbed.change_file_type( form_div, $(this).val() );

        });

        form_div.find( 'select[name=type]' ).find( 'option[value='+TYPE_IMAGE+']' ).attr( "selected", true );

        ManageEmbed.change_file_type( form_div, TYPE_IMAGE );

        form_div.find( 'input[name=parent_id]' ).val( g_parent_id );

    }catch(err){
        console.log('Error in : ManageEmbed - form setting:['+err+']');
    }
};

/******************************************************************************/
/* extra functions ************************************************************/
/******************************************************************************/

ManageEmbed.change_file_type = function(form_div, typeval){

    try{

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

            case TYPE_EMBED_CODE:
                form_div.find("div[name=file]").html(
                    '<textarea name="file" cols="45" rows="5"></textarea>'
                );
                break;

            case TYPE_SOUND_CLOUD:
                form_div.find("div[name=file]").html(
                    '<input type="text" name="file" value="" size="40" />'
                );
                break;

            default:
                break;

        }
    
    }catch(err){
        console.log('Error in : ManageEmbed - change file type :['+err+']');
    }

};
ManageEmbed.set_file_value   = function(form_div, typeval, fileval){

    try{

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

            case TYPE_EMBED_CODE:
                form_div.find("textarea[name=file]").html(fileval);
                break;

            case TYPE_SOUND_CLOUD:
                form_div.find("input[name=file]").val(fileval);
                break;

            default:
                break;

        }
    
    }catch(err){
        console.log('Error in : ManageEmbed - set file value :['+err+']');
    }
};

ManageEmbed.get_file         = function(file, type){
    
    var embed_file = '';
    
    try{

        type = parseInt(type);

        var folder = ''+g_root_url+'uploads/embeds';
        var width  = 350;
        var height = 300;

        switch( type ){

            case TYPE_DOWNLOAD:
                embed_file = DisplayUtil.get_download_embed(folder+'/'+file);
                break;

            case TYPE_IMAGE:
                embed_file = DisplayUtil.get_image_embed(folder+'/'+file, width, height);
                break;

            case TYPE_FLASH:
                embed_file = DisplayUtil.get_flash_embed(folder+'/'+file, width, height);
                break;

            case TYPE_SOUND:
                embed_file = DisplayUtil.get_jsplayer_sound_embed(file, width, height, folder);
                break;

            case TYPE_VIDEO:
                embed_file = DisplayUtil.get_jsplayer_video_embed(file, width, height, folder);
                break;

            case TYPE_YOUTUBE:
                embed_file = DisplayUtil.get_youtube_video_embed(file, width, height, false);
                break;

            case TYPE_VIMEO:
                embed_file = DisplayUtil.get_vimeo_video_embed(file, width, height, false);
                break;

            case TYPE_EMBED_CODE:
                embed_file = file;//Utils.escapeHTML((file);
                break;

            case TYPE_SOUND_CLOUD:
                embed_file = DisplayUtil.get_sound_cloud_embed(file, width, height, false);
                break;

            default:
                break;

        }

    }catch(err){
        console.log('Error in : ManageEmbed - get file :['+err+']');
    }

    return embed_file;
};
ManageEmbed.get_type         = function(type){
    
    var embed_type = '';

    try{

        type = parseInt(type);

        switch(type){

            case TYPE_DOWNLOAD:
                embed_type = CDictionary.get_text('File_Type_Download_lbl');
                break;

            case TYPE_IMAGE:
                embed_type = CDictionary.get_text('File_Type_Image_lbl');
                break;

            case TYPE_FLASH:
                embed_type = CDictionary.get_text('File_Type_Flash_lbl');
                break;

            case TYPE_SOUND:
                embed_type = CDictionary.get_text('File_Type_Sound_lbl');
                break;

            case TYPE_VIDEO:
                embed_type = CDictionary.get_text('File_Type_Video_lbl');
                break;

            case TYPE_YOUTUBE:
                embed_type = CDictionary.get_text('File_Type_Youtube_lbl');
                break;

            case TYPE_VIMEO:
                embed_type = CDictionary.get_text('File_Type_Vimeo_lbl');
                break;

            case TYPE_EMBED_CODE:
                embed_type = CDictionary.get_text('File_Type_Embed_Code_lbl');
                break;

            case TYPE_SOUND_CLOUD:
                embed_type = CDictionary.get_text('File_Type_SoundCloud_lbl');
                break;

        }

    }catch(err){
        console.log('Error in : ManageEmbed - get type :['+err+']');
    }

    return embed_type;
};

/******************************************************************************/
