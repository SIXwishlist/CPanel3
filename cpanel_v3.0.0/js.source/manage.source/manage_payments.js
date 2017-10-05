
/*! payments */

/* global CDictionary, RequestUtil, Utils, CMSUtil, CForm, g_root_url, CPopup, Validate, g_template_url, g_request_url, MainGlobals, ADVERT_TYPE_ALL, ADVERT_TYPE_PARENTS, ADVERT_TYPE_STUDENTS, ADVERT_TYPE_TEACHERS, ADVERT_TYPE_DRIVERS, USER_TYPE_TEACHER, BackgroundRequests, TplUtil, PaymentAuth, USER_TYPE_MASTER, CMSExtraUtil, DisplayUtil */

function ManagePayment() {}

ManagePayment.STATUS_NO_ACTION     = 0;
ManagePayment.STATUS_NOT_COMPLETED = 1;
ManagePayment.STATUS_COMPLETED     = 2;

//payment_id 	status 	date 	tnx_id 	user_id 	user_id 

////////////////////////////////////////////////////////////////////////////////

ManagePayment.init = function (user_id) {
    
    try{

        ManagePayment.user_id = Utils.get_int( user_id );
        ManagePayment.user_id = ( ManagePayment.user_id  > 0 ) ? ManagePayment.user_id : 0;

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=payments]").addClass('active');


        content_div.html( '' );


        content_div.append( 
            '<div class="controls clearfix">' + 
                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('Payments_lbl') + '</div>' + 
                '<div class="top_button" onclick="ManagePaymentForm.add(); return false;">' +
                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
                    CDictionary.get_text('New_lbl') + 
                '</div>' + 
                '<div class="top_button" onclick="ManagePaymentForm.search(); return false;">' +
                    '<i class="fa fa-search" aria-hidden="true"></i>' +
                    CDictionary.get_text('Search_lbl') + 
                '</div>' + 
                //'<div class="top_button" onclick="ManagePaymentForm.import_form(); return false;">' +
                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
                //    CDictionary.get_text('Import_lbl') + 
                //'</div>' +
                //'<div class="top_button" onclick="ManagePaymentForm.export_form(); return false;">' +
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
        
        ManagePayment.form_div = form_div;
        ManagePayment.list_div = list_div;


        ManagePaymentList.load();

        //if( PaymentAuth.rule_id == USER_TYPE_MASTER ){
        //BackgroundRequests.load_organizations();
        //}

    } catch(err) {
        console.error('Error in : ManagePayment - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManagePaymentList() {}

ManagePaymentList.index = 0;
ManagePaymentList.count = 10;

ManagePaymentList.load         = function (index, count) {
    
    try{

        CMSExtraUtil.show_list( ManagePayment );
        
        var list_div  = ManagePayment.list_div;


        var user_id   = ManagePayment.user_id;

        index = ( index === undefined ) ? ManagePaymentList.index : Utils.get_int(index);
        count = ( count === undefined ) ? ManagePaymentList.count : Utils.get_int(count);

        ManagePaymentList.index = index;
        ManagePaymentList.count = count;

        var data = "action=payments"
                + "&user_id="+user_id
                + "&index="+index+"&count="+count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var payments       = outputArray["payments"];
                    var payments_count = outputArray["payments_count"];

                    ManagePaymentList.display_list(payments, payments_count, CMSUtil.PAGINATION_LIST);

                    //ManagePaymentList.display_chart(payments);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManagePaymentList - load :['+err+']');
    }

};

ManagePaymentList.search       = function (index, count) {
    
    try{

        CMSExtraUtil.show_list( ManagePayment );

        var list_div = ManagePayment.list_div;

        ManagePaymentList.index = Utils.get_int( index );
        ManagePaymentList.count = Utils.get_int( count );
        ManagePaymentList.count = ( ManagePaymentList.count > 0 ) ? ManagePaymentList.count : 10;

        var data = ManagePaymentList.search_object;

        data["index"] = ManagePaymentList.index;
        data["count"] = ManagePaymentList.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var payments       = outputArray["payments"];
                    var payments_count = outputArray["payments_count"];

                    ManagePaymentList.display_list(payments, payments_count, CMSUtil.PAGINATION_SEARCH);

                    //ManagePaymentList.display_chart(payments);

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch(err) {
        console.error('Error in : ManagePaymentList - search :['+err+']');
    }

};

ManagePaymentList.display_list = function (array, result_count, source){

    try{

        ManagePaymentList.array = array;

        var list_div = ManagePayment.list_div;

        var labels   = CDictionary.get_labels([
                        'PaymentList_PaymentId_lbl', 
                        'PaymentList_Amount_lbl', 
                        'PaymentList_Status_lbl', 
                        'PaymentList_Date_lbl', 
                        'PaymentList_TnxId_lbl', 
                        'PaymentList_Product_lbl', 
                        'PaymentList_User_lbl' ]);

        var fields   = [ "ManagePaymentOutput.get_id(payment_id)", 
                         "amount", 
                         "ManagePaymentOutput.get_status(status)", 
                         "date", 
                         "tnx_id", 
                         "ManagePaymentOutput.get_product(product_ar,product_en)", 
                         "user" ];

        var id_label = "payment_id";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManagePaymentForm.edit', 'ManagePaymentForm.remove', 'ManagePaymentForm.view');//'ManagePaymentForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManagePaymentList.search':'ManagePaymentList.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManagePaymentList.index, ManagePaymentList.count, (ManagePaymentList.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManagePaymentList - display list :['+err+']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function ManagePaymentForm() {}

////////////////////////////////////////////////////////////////////////////////

ManagePaymentForm.import_form = function(){

    try{

        CMSExtraUtil.show_form( ManagePayment );

        var cont_div_cell = ManagePayment.form_div;

        var form_div = CForm.get_form_div_from_tpl( g_template_url+"?tpl=import_form" );


        var form_options = { 
            cont_div          : cont_div_cell,
    
            form_div          : form_div,
            //tpl_path          : tpl_path,
            form_action       : g_request_url+"?action=import_form_payments",
            complete_callback : ManagePaymentForm.callback,
            prepare_func      : null,
            animated          : true
        };

        CMSUtil.create_form( form_options );


        form_div.find("input[name=download]").click( function() {
            window.location = g_request_url+"?action=export_payments_sample";
            //window.location = g_root_url+'uploads/samples/payments.xls';
        });

    }catch(err){
        console.error('Error in : ManagePaymentForm - import_form :['+err+']');
    }
};

ManagePaymentForm.export_form = function(){

    try{
        //alert( JSON.stringify(g_search_object) );

        var data = ManagePaymentList.search_object;
        
        if( data === null ){
            data = {};
        }

        var seqId = Math.floor(Math.random() * 1000);

        var data  = "action=export_payments"
                  + "&payment_id="   + Utils.get_int( data.payment_id )
                  + "&title="       + get_string( data.title )
                  + "&date="        + get_string( data.date )
                  + "&status="      + Utils.get_int( data.status )
                  + "&seqId="       + seqId;

        //window.open( g_request_url + '?' + data );

        window.location = g_request_url+"?"+data;

    }catch(err){
        console.error('Error in : ManagePaymentForm - export :['+err+']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManagePaymentForm.get_form_properties = function() {
   
    var form_object = {};

    try{

        var name = 'payment';

        var form_properties = { 

            name    : name,

            params  : [

                { name : "amount",       type : "text",       label : CDictionary.get_text('PaymentForm_Amount_lbl')+":"              },

                { type : "seprator",     label : "" },

                { name : "tnx_id",       type : "text",       label : CDictionary.get_text('PaymentForm_TnxId_lbl')+":"               },
                { name : "date",         type : "date", format : "yy-mm-dd", label : CDictionary.get_text('PaymentForm_Date_lbl')+":" },

                { type : "seprator",     label : "" },
                { name : "status",       type : "select",     label : CDictionary.get_text('PaymentForm_Status_lbl')+":"              },
                
                { type : "seprator",     label : "" },

                { name : "product_id",   type : "div",        label : CDictionary.get_text('PaymentForm_ProductId_lbl')+":"           },
                { name : "user_id",      type : "div",        label : CDictionary.get_text('PaymentForm_UserId_lbl')+":"              },

                { type : "seprator",     label : "" },

                { name : "product_id",   type : "hidden",   label : "", ignore_preview : true },
                { name : "user_id",      type : "hidden",   label : "", ignore_preview : true },
                { name : "payment_id",   type : "hidden",   label : "", ignore_preview : true }

            ],

            action  : '',
            method  : 'post',
            enctype : 'multipart/form-data',//'application/x-www-form-urlencoded',

            style   : 'style1'

        };

        form_object = new CForm(form_properties);

    }catch(err){
        console.error('Error in : ManagePaymentForm - get form properties :['+err+']');
    }
    
    return form_object;
};

ManagePaymentForm.search = function(){

    try{

        CMSExtraUtil.show_form( ManagePayment );

        var cont_div_cell = ManagePayment.form_div;

        var form_div = TplUtil.get_hidden_div('payment_search_tpl', true);
        
        cont_div_cell.append( form_div );


        form_div = cont_div_cell.find('form');
        
        form_div.find( 'select[name=country]' ).html( ManagePaymentForm.get_country_select() );

        form_div.find( 'select[name=status]' ).html( ManagePaymentForm.get_rule_select() );

        form_div.find( 'input[type=submit]' ).click( function(event) {

            try{

                event.preventDefault();

                var data = {
                    action          : "search_payments",

                    cvc             : form_div.find( 'input[name=cvc]'             ).val() ,
                    name            : form_div.find( 'input[name=name]'            ).val() ,
                    number          : form_div.find( 'input[name=number]'          ).val() ,
                    type            : form_div.find( 'select[name=type]'           ).val() ,
                    graduation_date : form_div.find( 'input[name=graduation_date]' ).val() ,
                    qr_key          : form_div.find( 'input[name=qr_key]'         ).val() ,
                    country         : form_div.find( 'select[name=country]'        ).val()
                };

                ManagePaymentList.search_object = data;

                ManagePaymentList.search(0, ManagePaymentList.count);

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

ManagePaymentForm.add    = function(){
    
    try{

        CMSExtraUtil.show_form( ManagePayment );

        var cont_div_cell = ManagePayment.form_div;

        //var cont_form_div = TplUtil.get_hidden_div('payment_form_tpl', true);
        var form_div = ManagePaymentForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_payment.tpl",
            form_action         : g_request_url+"?action=add_payment",
            complete_callback   : ManagePaymentForm.callback,
            cancel_callback     : ManagePaymentForm.cancel,
            prepare_func        : ManagePaymentForm.prepare,
            validate_func       : ManagePaymentForm.validate,
            validate_notes_func : ManagePaymentForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManagePaymentForm - add :['+err+']');
    }
};

ManagePaymentForm.edit   = function(payment_id) {

    try{
        
        payment_id = Utils.get_int(payment_id);

        CMSExtraUtil.show_form( ManagePayment );

        var cont_div_cell = ManagePayment.form_div;

        //var cont_form_div = TplUtil.get_hidden_div('payment_form_tpl', true);
        var form_div = ManagePaymentForm.get_form_properties().get_form_div();

        var form_options = { 
            cont_div            : cont_div_cell,
            form_div            : form_div,
            //tpl_path            : g_root_url+"mvc/views/tpl/js/forms/update_payment.tpl",
            form_action         : g_request_url+"?action=update_payment",
            complete_callback   : ManagePaymentForm.callback,
            cancel_callback     : ManagePaymentForm.cancel,
            prepare_func        : ManagePaymentForm.prepare,
            post_func           : ManagePaymentForm.post_edit, 
            post_args           : payment_id,
            validate_func       : ManagePaymentForm.validate,
            validate_notes_func : ManagePaymentForm.validate_notes
        };

        CMSUtil.create_form( form_options );

    }catch(err){
        console.error('Error in : ManagePaymentForm - edit :['+err+']');
    }
};

ManagePaymentForm.view   = function(payment_id) {

    try{

        payment_id = Utils.get_int(payment_id);

        var payment = ManagePaymentForm.get_object(payment_id);
        
        //var preview_div = TplUtil.get_hidden_div('payment_preview_tpl');
        var preview_div = ManagePaymentForm.get_form_properties().get_preview_div();

        preview_div.find( 'div[name=payment_id]' ).html( payment.payment_id );
        preview_div.find( 'div[name=amount]'     ).html( payment.amount     );
        preview_div.find( 'div[name=status]'     ).html( ManagePaymentOutput.get_status(payment.status) );
        preview_div.find( 'div[name=date]'       ).html( payment.date       );
        preview_div.find( 'div[name=tnx_id]'     ).html( payment.tnx_id     );

        preview_div.find( 'div[name=user_id]'    ).val( payment.user        );
        preview_div.find( 'div[name=product_id]' ).val( ManagePaymentOutput.get_product(payment.product_ar, payment.product_en) );

        CPopup.display( $('<div></div>').append( preview_div ).html(), 'Payment Info'  );

    }catch(err){
        console.error('Error in : ManagePaymentForm - view :['+err+']');
    }
};

ManagePaymentForm.remove = function(payment_id){

    try{

        CMSExtraUtil.delete_popup( function(){

            var list_div = ManagePayment.list_div;

            var data = "action=remove_payment"
                        + "&payment_id=" + payment_id;

            RequestUtil.quick_post_request(list_div, data, ManagePaymentForm.callback);

        });

    }catch(err){
        console.error('Error in : ManagePaymentForm - delete :['+err+']');
    }
    
};

ManagePaymentForm.print  = function(form_div){
  
    try{

        CMSExtraUtil.print_div_popup(form_div.html(), '', 950, 700);
  
    }catch(err){
        console.error('Error in : ManagePaymentForm - print :['+err+']');
    }  
};

////////////////////////////////////////////////////////////////////////////////

ManagePaymentForm.prepare        = function(form_div){

    try{

        form_div.find( 'select[name=status]'    ).html( ManagePaymentForm.get_status_select() );

        form_div.find( 'input[name=user_id]'    ).val( ManagePayment.user_id    );
        form_div.find( 'input[name=product_id]' ).val( ManagePayment.product_id );
    
    }catch(err){
        console.error('Error in : ManagePaymentForm - prepare form :['+err+']');
    }
};

ManagePaymentForm.post_edit      = function(payment_id){

    try{

        var payment = ManagePaymentForm.get_object(payment_id);

        var form_cont_div = ManagePayment.form_div;
 
 
        var form_div = form_cont_div.find('form');

        form_div.find( 'input[name=payment_id]' ).val( payment.payment_id );
        form_div.find( 'input[name=amount]'     ).val( payment.amount     );
        form_div.find( 'select[name=status]'    ).find( 'option[value='+payment.status+']' ).attr( "selected", true );
        form_div.find( 'input[name=date]'       ).val( payment.date       );
        form_div.find( 'input[name=tnx_id]'     ).val( payment.tnx_id     );

        form_div.find( 'input[name=user_id]'    ).val( payment.user_id    );
        form_div.find( 'input[name=product_id]' ).val( payment.product_id );

    }catch(err){
        console.error('Error in : ManagePaymentForm - post edit :['+err+']');
    }
};

ManagePaymentForm.validate       = function(form_div){
    
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
        console.error('Error in : ManagePaymentForm - validate form :['+err+']');
    }

    return errors;
};

ManagePaymentForm.validate_notes = function(form_div){

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
        console.error('Error in : ManagePaymentForm - show validate notes :['+err+']');
    }

};

ManagePaymentForm.callback       = function(outputArray){

    try{

        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }


        ManagePaymentList.load();

        $('body').trigger( "payments_updated" );

    }catch(err){
        console.error('Error in : ManagePaymentForm - callback :['+err+']');
    }

};

ManagePaymentForm.cancel         = function(){

    try{

        ManagePaymentList.load();

    }catch(err){
        console.error('Error in : ManagePaymentForm - cancel :['+err+']');
    }
};

ManagePaymentForm.get_object     = function(payment_id) {

    var object = null;

    try{
        
        var object_array = ManagePaymentList.array;

        for(var i=0; i<object_array.length; i++){

            if( object_array[i].payment_id == payment_id ){
                
                object = object_array[i];
                
                break;
            }
        }

    }catch(err){
        console.error('Error in : ManagePaymentForm - get object :['+err+']');
    }

    return object;
};

////////////////////////////////////////////////////////////////////////////////

ManagePaymentForm.get_status_select  = function(){

    var select_html = '';

    select_html += '<option value="-1">Please select status</option>'+
                   '<option value="'+ManagePayment.STATUS_NO_ACTION     +'">'+ CDictionary.get_text('PaymentForm_Status_NoAction_lbl')     +'</option>' +
                   '<option value="'+ManagePayment.STATUS_NOT_COMPLETED +'">'+ CDictionary.get_text('PaymentForm_Status_NotCompleted_lbl') +'</option>' +
                   '<option value="'+ManagePayment.STATUS_COMPLETED     +'">'+ CDictionary.get_text('PaymentForm_Status_Completed_lbl')    +'</option>';

    return select_html;
};

////////////////////////////////////////////////////////////////////////////////

function ManagePaymentOutput() {}

ManagePaymentOutput.get_id      = function(payment_id){
    return number_pad(payment_id, 6);
};

ManagePaymentOutput.get_status  = function(status){
    
    var show_status = '';

    status = Utils.get_int(status);

    switch(status){
        case ManagePayment.STATUS_NO_ACTION:
            show_status = CDictionary.get_text('PaymentForm_Status_NoAction_lbl');
            break;

        case ManagePayment.STATUS_NOT_COMPLETED:
            show_status = CDictionary.get_text('PaymentForm_Status_NotCompleted_lbl');
            break;

        case ManagePayment.STATUS_COMPLETED:
            show_status = CDictionary.get_text('PaymentForm_Status_Completed_lbl');
            break;
            
    }

    return show_status;
};

ManagePaymentOutput.get_product = function(product_ar, product_en){
    
    var product = '';

    product = ( CDictionary.lang == "ar" ) ? product_ar : product_en;
    
    return product;
};
