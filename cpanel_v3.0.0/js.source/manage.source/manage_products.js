
/*! products */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, ManageCategoryChilds.callback, ManageCategoryChilds, CMSExtraUtil, RequestUtil, ManageCategoryChilds, ManageCategoryChildsOutput */


function ManageProduct(){}

ManageProduct.get_form_properties = function(){

    var form_object = null;

    try{

        var name = 'product';

        var form_properties = { 

            name    : name,

            params  : [                
                
                { name : "title_ar",      type : "text",       label : CDictionary.get_text('ProductForm_TitleAr_lbl')+":"               },
                { name : "title_en",      type : "text",       label : CDictionary.get_text('ProductForm_TitleEn_lbl')+":"               },
                
                { type : "seprator",      label : "" },
                
                { name : "keys_ar",       type : "textarea",   label : CDictionary.get_text('ProductForm_KeysAr_lbl')+":"                },
                { name : "keys_en",       type : "textarea",   label : CDictionary.get_text('ProductForm_KeysEn_lbl')+":"                },
                { name : "desc_ar",       type : "textarea",   label : CDictionary.get_text('ProductForm_DescAr_lbl')+":"                },
                { name : "desc_en",       type : "textarea",   label : CDictionary.get_text('ProductForm_DescEn_lbl')+":"                },
                
                { type : "seprator",      label : "" },
                
                { name : "content_ar",    type : "editor",     label : CDictionary.get_text('ProductForm_ContentAr_lbl')+":"             },
                { name : "content_en",    type : "editor",     label : CDictionary.get_text('ProductForm_ContentEn_lbl')+":"             },
                
                { type : "seprator",      label : "" },
                
                { name : "icon",          type : "image",       label : CDictionary.get_text('ProductForm_Icon_lbl')+":"                  },
                //{ name : "image",         type : "image",       label : CDictionary.get_text('ProductForm_Image_lbl')+":"                 },
                
                { type : "seprator",      label : "" },
                
                { name : "style",         type : "select",     label : CDictionary.get_text('ProductForm_Style_lbl')+":"                 },
                
                { type : "seprator",      label : "" },
                
                { name : "featured",      type : "checkbox",   label : CDictionary.get_text('ProductForm_Featured_lbl')+":", value:"1" },
                { name : "offer",         type : "checkbox",   label : CDictionary.get_text('ProductForm_Offer_lbl')+":",    value:"1" },
                { name : "recent",        type : "checkbox",   label : CDictionary.get_text('ProductForm_Recent_lbl')+":",   value:"1" },
                { name : "sale",          type : "checkbox",   label : CDictionary.get_text('ProductForm_Sale_lbl')+":",     value:"1" },
                
                { type : "seprator",      label : "" },
                
                { name : "price",         type : "text",       label : CDictionary.get_text('ProductForm_Price_lbl')+":"      },
                { name : "discount",      type : "slider",     label : CDictionary.get_text('ProductForm_Discount_lbl')+":", min:"0", max:"1", step:"0.1"   },
                { name : "available",     type : "text",       label : CDictionary.get_text('ProductForm_Available_lbl')+":"  },
                
                { type : "seprator",      label : "" },
                
                { name : "order",         type : "text",       label : CDictionary.get_text('ProductForm_Order_lbl')+":"                 },
                { name : "active",        type : "checkbox",   label : CDictionary.get_text('ProductForm_Active_lbl')+":",     value:"1" },

                { type : "seprator",      label : "" },

                { name : "editable",      type : "hidden",   label : "", ignore_preview : true },
                { name : "removable",     type : "hidden",   label : "", ignore_preview : true },
                { name : "parent_id",     type : "hidden",   label : "", ignore_preview : true },
                { name : "product_id",    type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//enctype : 'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);    
    
    }catch(err){
        console.log('Error in : ManageProduct - get form properties :['+err+']');
    }

    return form_object;
    
};

/******************************************************************************/

ManageProduct.add    = function(){
    
    try{
        
        CMSExtraUtil.show_form( ManageCategoryChilds );

        var cont_div_cell = ManageCategoryChilds.form_div;

        var form_div      = ManageProduct.get_form_properties().get_form_div();

        var form_options  = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            form_action         : g_request_url+"?action=add_product",
            complete_callback   : ManageCategoryChilds.callback,
            cancel_callback     : ManageCategoryChilds.cancel,
            prepare_func        : ManageProduct.prepare,
            validate_func       : ManageProduct.validate,
            validate_notes_func : ManageProduct.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageProduct - add :['+err+']');
    }
};

ManageProduct.edit   = function(child_index){

    try{

        //var product    = ManageCategoryChildsOutput.get_object(child_index);
        //var product_id = product.product_id;

        CMSExtraUtil.show_form( ManageCategoryChilds );

        var cont_div_cell = ManageCategoryChilds.form_div;

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : ManageProduct.get_form_properties().get_form_div(),
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/product_form.tpl",
            form_action         : g_request_url+"?action=update_product",
            complete_callback   : ManageCategoryChilds.callback,
            cancel_callback     : ManageCategoryChilds.cancel,
            prepare_func        : ManageProduct.prepare,
            post_func           : ManageProduct.post_edit, 
            post_args           : child_index,
            validate_func       : ManageProduct.validate,
            validate_notes_func : ManageProduct.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.log('Error in : ManageProduct - edit :['+err+']');
    }
};

ManageProduct.remove = function(child_index){

    try{

        var product    = ManageCategoryChildsOutput.get_object(child_index);
        var product_id = product.product_id;

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageCategoryChilds.list_div;

            var data = "action=remove_product"
                        + "&product_id=" + product_id;

            RequestUtil.quick_post_request(list_div, data, ManageCategoryChilds.callback);

        });

    }catch(err){
        console.log('Error in : ManageProduct - delete :['+err+']');
    }
};

ManageProduct.view   = function(child_index){

    try{

        var product    = ManageCategoryChildsOutput.get_object(child_index);
        //var product_id = product.product_id;

        var preview_object = ManageProduct.get_form_properties(); 

        var preview_div    = preview_object.get_preview_div();


        preview_div.find( 'div[name=product_id]' ).html( product.product_id );
        preview_div.find( 'div[name=title_ar]'   ).html( product.title_ar   );
        preview_div.find( 'div[name=title_en]'   ).html( product.title_en   );
        preview_div.find( 'div[name=keys_ar]'    ).html( product.keys_ar    );
        preview_div.find( 'div[name=keys_en]'    ).html( product.keys_en    );
        preview_div.find( 'div[name=desc_ar]'    ).html( product.desc_ar    );
        preview_div.find( 'div[name=desc_en]'    ).html( product.desc_en    );
        preview_div.find( 'div[name=content_ar]' ).html( product.content_ar );
        preview_div.find( 'div[name=content_en]' ).html( product.content_en );
        preview_div.find( 'div[name=icon]'       ).html( '<img src="'+g_root_url+'uploads/products/'+product.icon+'" />'  );
        preview_div.find( 'div[name=image]'      ).html( '<img src="'+g_root_url+'uploads/products/'+product.image+'" />' );
        //preview_div.find( 'div[name=image]'    ).html( '<a href="'+product.image+'" product="_blank">'+get_dictionary_text('View_lbl')+'</a>' );
        preview_div.find( 'div[name=style]'      ).html( product.style      );

        preview_div.find( 'div[name=price]'      ).html( product.price      );
        preview_div.find( 'div[name=discount]'   ).html( product.discount   );
        preview_div.find( 'div[name=available]'  ).html( product.available   );

        preview_div.find( 'div[name=featured]'   ).html( (product.featured>0 )?gYes:gNo );
        preview_div.find( 'div[name=offer]'      ).html( (product.offer>0    )?gYes:gNo );
        preview_div.find( 'div[name=recent]'     ).html( (product.recent>0   )?gYes:gNo );
        preview_div.find( 'div[name=sale]'       ).html( (product.sale>0     )?gYes:gNo );

        preview_div.find( 'div[name=order]'      ).html( product.order       );
        preview_div.find( 'div[name=active]'     ).html( (product.active>0   )?gYes:gNo );
        //preview_div.find( 'div[name=parent_id]'  ).html( product.parent_id  );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Product Info'  );

    }catch(err){
        console.log('Error in : ManageProduct - view :['+err+']');
    }

};

ManageProduct.print  = function(product_div){
    
    try{

        CMSExtraUtil.print_div_popup(product_div.html(), '', 950, 700);

    }catch(err){
        console.log('Error in : ManageProduct - print :['+err+']');
    }
};

/******************************************************************************/

ManageProduct.prepare   = function(form_div){

    try{

        
        var format_html = ManageProduct.get_format_select();

        form_div.find( 'select[name=format]' ).html( format_html );


        if( ManageCategoryChilds.parent_id > 0 ){

            form_div.find( 'input[name=top_menu]'  ).attr( "disabled", true );
            form_div.find( 'input[name=main_menu]' ).attr( "disabled", true );
            form_div.find( 'input[name=side_menu]' ).attr( "disabled", true );
            form_div.find( 'input[name=foot_menu]' ).attr( "disabled", true );

            form_div.find( 'input[name=top_menu]'  ).parent().parent().hide();
            form_div.find( 'input[name=main_menu]' ).parent().parent().hide();
            form_div.find( 'input[name=side_menu]' ).parent().parent().hide();
            form_div.find( 'input[name=foot_menu]' ).parent().parent().hide();

        }else{
            //form_div.find( 'input[name=icon]' ).parent().parent().parent().hide();
        }

        form_div.find( 'input[name=parent_id]' ).val( ManageCategoryChilds.parent_id );

        //alert( (new Date().getFormatted()) );
        //form_div.find( 'input[name=date]' ).val( (new Date().getFormatted()) );

    }catch(err){
        console.log('Error in : ManageProduct - prepare :['+err+']');
    }
};

ManageProduct.post_edit = function(child_index){

    try{

        var product    = ManageCategoryChildsOutput.get_object(child_index);
        //var product       = get_product_child(product_id);

        //var form_div = $("#body").find("#content").find("#product_child_list").find(".form_cell").find("#form_cell_"+child_index);
        var form_div = ManageCategoryChilds.form_div;

        form_div.find( 'input[name=product_id]'   ).val( product.product_id   );

        form_div.find( 'input[name=title_ar]'     ).val( product.title_ar     );
        form_div.find( 'input[name=title_en]'     ).val( product.title_en     );
        form_div.find( 'textarea[name=keys_ar]'   ).val( product.keys_ar      );
        form_div.find( 'textarea[name=keys_en]'   ).val( product.keys_en      );
        form_div.find( 'textarea[name=desc_ar]'   ).val( product.desc_ar      );
        form_div.find( 'textarea[name=desc_en]'   ).val( product.desc_en      );
        form_div.find( 'input[name=content_ar]'   ).val( product.content_ar   );
        form_div.find( 'input[name=content_en]'   ).val( product.content_en   );
        form_div.find( 'div[name=content_ar]'     ).html( product.content_ar  );
        form_div.find( 'div[name=content_en]'     ).html( product.content_en  );
        //form_div.find( 'input[name=icon]'       ).val( product.icon         );
        //form_div.find( 'input[name=image]'      ).val( product.image        );

        form_div.find( 'select[name=format]'      ).find('option[value='+product.format+']').attr('selected', true);

        form_div.find( 'input[name=featured]'     ).attr( "checked", (product.featured>0)  ? true:false );
        form_div.find( 'input[name=offer]'        ).attr( "checked", (product.offer>0)     ? true:false );
        form_div.find( 'input[name=recent]'       ).attr( "checked", (product.recent>0)    ? true:false );
        form_div.find( 'input[name=sale]'         ).attr( "checked", (product.sale>0)      ? true:false );

        form_div.find( 'input[name=price]'        ).val( product.price       );
        form_div.find( 'input[name=discount]'     ).val( product.discount    );
        form_div.find( 'input[name=available]'    ).val( product.available   );

        form_div.find( 'input[name=editable]'     ).val( product.editable    );
        form_div.find( 'input[name=removable]'    ).val( product.removable   );
        form_div.find( 'input[name=order]'        ).val( product.order       );
        form_div.find( 'input[name=active]'       ).attr( "checked", (product.active>0)   ? true:false );
        form_div.find( 'input[name=parent_id]'    ).val( product.parent_id  );


        var icon_src  = g_root_url+'uploads/products/'+product.icon;
        var image_src = g_root_url+'uploads/products/'+product.image;

        if( (form_div.find('input[name=icon]').length  > 0 ) && RequestUtil.image_exists( icon_src ) ){
            form_div.find('.image-upload[data-name=icon]').find(".preview").html( '<img src="'+icon_src+'" />' );
        }
        if( (form_div.find('input[name=image]').length > 0 ) && RequestUtil.image_exists( image_src ) ){
            form_div.find('.image-upload[data-name=image]').find(".preview").html( '<img src="'+image_src+'" />' );
        }

    }catch(err){
        console.log('Error in : ManageProduct - post edit :['+err+']');
    }
};

/******************************************************************************/

ManageProduct.validate       = function(form_div){
    
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
        console.log('Error in : ManageProduct - validate :['+err+']');
    }

    return errors;
};

ManageProduct.validate_notes = function(form_div){

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
        console.log('Error in : ManageProduct - validate notes :['+err+']');
    }
    
};

/******************************************************************************/

ManageProduct.get_format_select = function(){

    var select_html = '';

    select_html +=  '<option value="">Please select style</option>' +
                    '<option value="'+STYLE_DEFAULT+'" selected>Default</option>';

    return select_html;
};
