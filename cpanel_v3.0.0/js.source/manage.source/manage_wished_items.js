
/*! wished items */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, WishedItemAuth, USER_TYPE_MASTER, CMSExtraUtil, DisplayUtil */

function ManageWishedItem() {}

ManageWishedItem.STATUS_NO_ACTION     = 0;
ManageWishedItem.STATUS_NOT_COMPLETED = 1;
ManageWishedItem.STATUS_COMPLETED     = 2;

//item_id 	status 	date 	user_id 	user_id 

////////////////////////////////////////////////////////////////////////////////

ManageWishedItem.init = function (user_id) {
    
    try{

        ManageWishedItem.user_id = Utils.get_int( user_id );
        ManageWishedItem.user_id = ( ManageWishedItem.user_id  > 0 ) ? ManageWishedItem.user_id : 0;

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=items]").addClass('active');


        content_div.html( '' );


        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('WishedItems_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManageWishedItemForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManageWishedItemForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManageWishedItemForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManageWishedItemForm.export_form(); return false;">' +
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
        
        var form_div = content_div.find("#form");
        var list_div = content_div.find("#list");
        
        ManageWishedItem.form_div = form_div;
        ManageWishedItem.list_div = list_div;


        ManageWishedItemList.load();

        //if( WishedItemAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManageWishedItem - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageWishedItemList() {}

ManageWishedItemList.index = 0;
ManageWishedItemList.count = 10;

ManageWishedItemList.load         = function (index, count) {
    
    try{

        CMSExtraUtil.show_list( ManageWishedItem );
        
        var list_div  = ManageWishedItem.list_div;

        index = ( index === undefined ) ? ManageWishedItemList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManageWishedItemList.count : Utils.get_int(count);

        ManageWishedItemList.index = index;
        ManageWishedItemList.count = count;

        var data = "action=wished_items"
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var wished_items       = outputArray["wished_items"];
                    var wished_items_count = outputArray["wished_items_count"];

                    ManageWishedItemList.display_list(wished_items, wished_items_count, CMSUtil.PAGINATION_LIST);

                    //ManageWishedItemList.display_chart(items);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageWishedItemList - load :['+err+']');
    }

};

ManageWishedItemList.search       = function (index, count) {
    
    try{

        CMSExtraUtil.show_list( ManageWishedItem );

        var list_div = ManageWishedItem.list_div;

        ManageWishedItemList.index = Utils.get_int( index );
        ManageWishedItemList.count = Utils.get_int( count );
        ManageWishedItemList.count = ( ManageWishedItemList.count > 0 ) ? ManageWishedItemList.count : 10;

        var data = ManageWishedItemList.search_object;

        data["index"] = ManageWishedItemList.index;
        data["count"] = ManageWishedItemList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var items       = outputArray["items"];
                    var items_count = outputArray["items_count"];

                    ManageWishedItemList.display_list(items, items_count, CMSUtil.PAGINATION_SEARCH);

                    //ManageWishedItemList.display_chart(items);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManageWishedItemList - search :['+err+']');
    }

};

ManageWishedItemList.display_list = function (array, result_count, source){

    try{

        ManageWishedItemList.array = array;

        var list_div = ManageWishedItem.list_div;

        var labels   = CDictionary.get_labels([
                        'WishItemList_ItemId_lbl', 
                        'WishItemList_Count_lbl', 
                        'WishItemList_Date_lbl', 
                        'WishItemList_Product_lbl', 
                        'WishItemList_User_lbl' ]);

        var fields   = [ "ManageWishedItemOutput.get_id(item_id)", 
                         "count", 
                         "date", 
                         "ManageWishedItemOutput.get_product(product_ar,product_en)", 
                         "user" ];

        var id_label = "item_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageWishedItemForm.edit', 'ManageWishedItemForm.remove', 'ManageWishedItemForm.view');//'ManageWishedItemForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageWishedItemList.search':'ManageWishedItemList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageWishedItemList.index, ManageWishedItemList.count, (ManageWishedItemList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageWishedItemList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManageWishedItemForm() {}

////////////////////////////////////////////////////////////////////////////////

ManageWishedItemForm.import_form = function(){

    try{

        CMSExtraUtil.show_form( ManageWishedItem );

        var cont_div_cell = ManageWishedItem.form_div;

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_items",
            complete_callback : ManageWishedItemForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_items_sample";
            //window.location = g_root_url+'uploads/samples/items.xls';
        });

    }catch(err){
        console.error('Error in : ManageWishedItemForm - import_form :['+err+']');
    }
};

ManageWishedItemForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManageWishedItemList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_items"
                  + "&item_id="   + Utils.get_int( data.item_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManageWishedItemForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageWishedItemForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'wished_item';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "date",        type : "date", format : "yy-mm-dd", label : CDictionary.get_text('WishedItemForm_Date_lbl')+":" },
                { name : "count",       type : "text",       label : CDictionary.get_text('WishedItemForm_Count_lbl')+":"              },

                { type : "seprator",     label : "" },
                
                { name : "product_id",   type : "div",        label : CDictionary.get_text('WishedItemForm_ProductId_lbl')+":"           },
                { name : "user_id",      type : "div",        label : CDictionary.get_text('WishedItemForm_UserId_lbl')+":"              },

                { type : "seprator",     label : "" },

                { name : "product_id", type : "hidden",   label : "", ignore_preview : true },
                { name : "user_id",    type : "hidden",   label : "", ignore_preview : true },
                { name : "item_id",    type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManageWishedItemForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManageWishedItemForm.search = function(){

    try{

        var list_div = ManageWishedItem.list_div;
        var form_div = ManageWishedItem.form_div;
        
        $(list_div).fadeIn(1000);
        $(form_div).fadeIn(1000);
        
        form_div.html('');

        var cont_form_div = TplUtil.get_hidden_div('item_search_tpl', true);
        
        form_div.append( cont_form_div );
        
        form_div.find( 'select[name=country]' ).html( ManageWishedItemForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManageWishedItemForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_items",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManageWishedItemList.search_object = data;

                ManageWishedItemList.search(0, ManageWishedItemList.count);

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

ManageWishedItemForm.add    = function(){
    
    try{

        CMSExtraUtil.show_form( ManageWishedItem );

        var cont_div_cell = ManageWishedItem.form_div;

        //var cont_form_div = TplUtil.get_hidden_div('item_form_tpl', true);
        var form_div = ManageWishedItemForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_item.tpl",
            form_action         : g_request_url+"?action=add_wished_item",
            complete_callback   : ManageWishedItemForm.callback,
            cancel_callback     : ManageWishedItemForm.cancel,
            prepare_func        : ManageWishedItemForm.prepare,
            validate_func       : ManageWishedItemForm.validate,
            validate_notes_func : ManageWishedItemForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageWishedItemForm - add :['+err+']');
    }
};

ManageWishedItemForm.edit   = function(item_id) {

    try{
        
        item_id = Utils.get_int(item_id);

        CMSExtraUtil.show_form( ManageWishedItem );

        var cont_div_cell = ManageWishedItem.form_div;

        //var cont_form_div = TplUtil.get_hidden_div('item_form_tpl', true);
        var form_div = ManageWishedItemForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_item.tpl",
            form_action         : g_request_url+"?action=update_wished_item",
            complete_callback   : ManageWishedItemForm.callback,
            cancel_callback     : ManageWishedItemForm.cancel,
            prepare_func        : ManageWishedItemForm.prepare,
            post_func           : ManageWishedItemForm.post_edit, 
            post_args           : item_id,
            validate_func       : ManageWishedItemForm.validate,
            validate_notes_func : ManageWishedItemForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManageWishedItemForm - edit :['+err+']');
    }
};

ManageWishedItemForm.view   = function(item_id) {

    try{

        item_id = Utils.get_int(item_id);

        var item = ManageWishedItemForm.get_object(item_id);
        
        //var preview_div = TplUtil.get_hidden_div('item_preview_tpl');
        var preview_div = ManageWishedItemForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=item_id]'    ).html( item.item_id    );
        preview_div.find( 'div[name=date]'       ).html( item.date       );
        preview_div.find( 'div[name=count]'      ).html( item.count      );

        preview_div.find( 'div[name=user_id]'    ).val( item.user        );
        preview_div.find( 'div[name=product_id]' ).val( ManageWishedItemOutput.get_product(item.product_ar, item.product_en) );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Wished Item Info'  );

    }catch(err){
        console.error('Error in : ManageWishedItemForm - view :['+err+']');
    }
};

ManageWishedItemForm.remove = function(item_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManageWishedItem.list_div;

            var data = "action=remove_wished_item"
                        + "&item_id=" + item_id;

            RequestUtil.quick_post_request(list_div, data, ManageWishedItemForm.callback);

        });

    }catch(err){
        console.error('Error in : ManageWishedItemForm - delete :['+err+']');
    }
    
};

ManageWishedItemForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManageWishedItemForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManageWishedItemForm.prepare        = function(form_div){

    try{

        form_div.find( 'select[name=status]' ).html( ManageWishedItemForm.get_status_select() );

        form_div.find( 'input[name=user_id]'    ).val( ManageWishedItem.user_id    );
        form_div.find( 'input[name=user_id]' ).val( ManageWishedItem.user_id );
    
    }catch(err){
        console.error('Error in : ManageWishedItemForm - prepare form :['+err+']');
    }
};

ManageWishedItemForm.post_edit      = function(item_id){

    try{

        var item = ManageWishedItemForm.get_object(item_id);

        var form_cont_div = ManageWishedItem.form_div;
 
 
        var form_div = form_cont_div.find('form');

        form_div.find( 'input[name=item_id]'      ).val( item.item_id    );
        form_div.find( 'input[name=date]'         ).val( item.date       );
        form_div.find( 'input[name=count]'        ).val( item.count      );

        form_div.find( 'input[name=user_id]'    ).val( item.user        );
        form_div.find( 'input[name=product_id]' ).val( ManageWishedItemOutput.get_product(item.product_ar, item.product_en) );

        form_div.find( 'div[name=user_id]'      ).html( item.user        );
        form_div.find( 'div[name=product_id]'   ).html( ManageWishedItemOutput.get_product(item.product_ar, item.product_en) );

    }catch(err){
        console.error('Error in : ManageWishedItemForm - post edit :['+err+']');
    }
};

ManageWishedItemForm.validate       = function(form_div){
    
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
        console.error('Error in : ManageWishedItemForm - validate form :['+err+']');
    }

    return errors;
};

ManageWishedItemForm.validate_notes = function(form_div){

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
        console.error('Error in : ManageWishedItemForm - show validate notes :['+err+']');
    }

};

ManageWishedItemForm.callback       = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }


        ManageWishedItemList.load();

        $('body').trigger( "items_updated" );

    }catch(err){
        console.error('Error in : ManageWishedItemForm - callback :['+err+']');
    }

};

ManageWishedItemForm.cancel         = function(){

    try{

        ManageWishedItemList.load();

    }catch(err){
        console.error('Error in : ManageWishedItemForm - cancel :['+err+']');
    }
};

ManageWishedItemForm.get_object     = function(item_id) {

    var object = null;

    try{
        
        var object_array = ManageWishedItemList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].item_id == item_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManageWishedItemForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////

function ManageWishedItemOutput() {}

ManageWishedItemOutput.get_id      = function(item_id){
    return number_pad(item_id, 6);
};

ManageWishedItemOutput.get_product = function(product_ar, product_en){
    
    var product = '';

    product = ( CDictionary.lang == "ar" ) ? product_ar : product_en;
    
    return product;
};
