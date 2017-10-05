
/*! category childs */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, Utils, CMSExtraUtil, RequestUtil, ManageLink, ManageEmbed, ManageProduct, ManageCategory */

var COLOR_PATTERN = /#[a-fA-F0-9]{6}/;

var CHILD_TYPE_CATEGORY = 1;
var CHILD_TYPE_PRODUCT  = 2;
var CHILD_TYPE_EMBED   = 3;
var CHILD_TYPE_LINK    = 4;


var STYLE_DEFAULT = 1;
var STYLE_MEDIA   = 2;



function ManageCategoryChilds(){}

ManageCategoryChilds.parent_id = -1;
ManageCategoryChilds.array     = [];


ManageCategoryChilds.init      = function(parent_id){

    try{

        ManageCategoryChilds.parent_id = Utils.get_int( parent_id );

        ManageCategoryChilds.parent_id = ( ManageCategoryChilds.parent_id  > 0 ) ? ManageCategoryChilds.parent_id  : 0;


        var content_div = $("#body").find("#content");

        content_div.html( '' );

        //content_div.append( 
        //    '<div class="controls clearfix">' + 
        //        '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('CategoryChilds_lbl') + '</div>' + 
        //        '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
        //            '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
        //            CDictionary.get_text('New_lbl') + 
        //        '</div>' + 
        //        '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
        //            '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
        //            CDictionary.get_text('New_lbl') + 
        //        '</div>' + 
        //        '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
        //            '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
        //            CDictionary.get_text('New_lbl') + 
        //        '</div>' + 
        //        '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
        //            '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
        //            CDictionary.get_text('New_lbl') + 
        //        '</div>' + 
        //        '<div class="top_button" onclick="ManageAdminForm.search(); return false;">' +
        //            '<i class="fa fa-search" aria-hidden="true"></i>' +
        //            CDictionary.get_text('Search_lbl') + 
        //        '</div>' + 
        //        //'<div class="top_button" onclick="ManageAdminForm.import_form(); return false;">' +
        //        //    '<i class="fa fa-download" aria-hidden="true"></i>' +
        //        //    CDictionary.get_text('Import_lbl') + 
        //        //'</div>' +
        //        //'<div class="top_button" onclick="ManageAdminForm.export_form(); return false;">' +
        //        //    '<i class="fa fa-share-square-o" aria-hidden="true"></i>' +
        //        //    CDictionary.get_text('Export_lbl') + 
        //        //'</div>' + 
        //    '</div>' 
        //);

        content_div.append( 
            '<div class="top_label_main"         onclick="ManageCategoryChilds.init('+ManageCategoryChilds.parent_id+'); return false;">' + CDictionary.get_text('Categories_lbl') + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageCategory.add(); return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Category_lbl')  + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageProduct.add();  return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Product_lbl')   + '</div>'
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
                '<div id="path"></div>' +
                '<div id="list"></div>' +
            '</div>' 
        );
        
        var form_div = $("#body").find("#content").find("#form");
        var path_div = $("#body").find("#content").find("#path");
        var list_div = $("#body").find("#content").find("#list");

        ManageCategoryChilds.form_div = form_div;
        ManageCategoryChilds.path_div = path_div;
        ManageCategoryChilds.list_div = list_div;


        ////alert('manage categorys loaded');
        //
        //var content_div = $("#body").find("#content");
        //
        //content_div.html( '' );
        //
        //content_div.append( 
        //    '<div class="top_label_main"         onclick="ManageCategoryChilds.init('+ManageCategoryChilds.parent_id+'); return false;">' + CDictionary.get_text('Categories_lbl') + '</div>' + 
        //    '<div class="top_label new_label"    onclick="add_category_form();                     return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Category_lbl') + '</div>' + 
        //    '<div class="top_label new_label"    onclick="add_target_form();                      return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Product_lbl')  + '</div>' + 
        //    '<div class="top_label new_label"    onclick="add_embed_form();                       return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Embed_lbl')   + '</div>' + 
        //    '<div class="top_label new_label"    onclick="add_link_form();                        return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Link_lbl')    + '</div>'
        //);
        //
        //content_div.append( '<div class="clearfix"></div>' );
        //
        //content_div.append( 
        //    '<div id="form_cell_new"></div>'    +
        //    '<div id="form_cell_search"></div>' +
        //    '<div id="form_cell_import"></div>' +
        //    '<div id="form_cell_export"></div>'
        //);
        //
        //content_div.append(
        //    '<div id="category_path" class="path"></div>' + 
        //    '<div class="main_label">' + CDictionary.get_text('List_lbl') + '</div>' + 
        //    '<div id="category_child_list"></div>'
        //);


        ManageCategoryChilds.index = 0;
        ManageCategoryChilds.count = 10;

        CMSExtraUtil.show_list( ManageCategoryChilds );

        ManageCategoryChilds.load();

        ManageCategoryChilds.load_path();

        //BackgroundRequests.load_organizations();

    }catch(err){
        console.error('Error in : ManageCategoryChilds - ManageCategoryChilds.init :['+err+']');
    }
};

ManageCategoryChilds.load_path = function(){

    try{

        var category_id = ManageCategoryChilds.parent_id;

        var data = {
            action      : "category_path",
            index       : -1,
            count       : -1,

            category_id  : category_id
        };

        var path_div = ManageCategoryChilds.path_div;

        path_div.html( '' );

        RequestUtil.quick_post_request(path_div, data, function (outputArray){

            //console.log("outputArray : "+outputArray);

            var status = outputArray["status"];

            if( status > 0 ){

                var categories = outputArray["categories"];

                var path_html = '';

                path_html += '<a href="javascript:ManageCategoryChilds.init(0);" class="cell">'+ CDictionary.get_text('Categories_lbl') +'</a>';

                for (var i=0; i<categories.length; i++){

                    var category = categories[i];

                    var category_name = CDictionary.get_text_by_lang(category, "title");

                    path_html += '<a href="javascript:ManageCategoryChilds.init('+ category.category_id +');" class="cell">'+ category_name +'</a>';

                }

                path_div.html( path_html );

            }
        });

    }catch(err){
        console.error('Error in : ManageCategoryChilds - load_path :['+err+']');
    }

};


ManageCategoryChilds.cancel    = function(){

    try{
        
        CMSExtraUtil.show_list( ManageCategoryChilds );

        ManageCategoryChilds.load();

    }catch(err){
        console.error('Error in : ManageCategoryChilds - cancel :['+err+']');
    }
};


ManageCategoryChilds.load         = function(index, count){
    
    try{

        ManageCategoryChilds.index = ( index == null ) ? ManageCategoryChilds.index : index;
        ManageCategoryChilds.count = ( count == null ) ? ManageCategoryChilds.count : count;

        var list_div = ManageCategoryChilds.list_div;

        var data    = "action=category_childs"
                    + "&parent_id="+ManageCategoryChilds.parent_id
                    + "&index="+ManageCategoryChilds.index+"&count="+ManageCategoryChilds.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var category_childs = outputArray["category_childs"];
                    var result_count   = outputArray["result_count"];

                    ManageCategoryChilds.display_list(category_childs, result_count, SOURCE_LIST);

                }

            } catch (err) {
                console.log('error in load category childs :[' + err + ']');
            }

        });
    
    }catch(err){
        console.error('Error in : ManageCategoryChilds - load :['+err+']');
    }

};

ManageCategoryChilds.display_list = function (array, result_count, source){

    try{

        ManageCategoryChilds.array = array;

        var list_div = ManageCategoryChilds.list_div;


        var labels   = CDictionary.get_labels([
                        'CategoryChildsList_ChildId_lbl',
                        'CategoryChildsList_Title_lbl',
                        'CategoryChildsList_Order_lbl',
                        'CategoryChildsList_Active_lbl' ]);//,
                        //'CategoryChildsList_ParentId_lbl' ]);

        var fields   = [ "ManageCategoryChildsOutput.get_id(child_index)", 
                         "ManageCategoryChildsOutput.get_link(title_ar,title_en,child_type,child_id)",
                         "order", 
                         "active" ];//,
                         //"parent_id" ];

        var id_label = "child_index";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageCategoryChilds.edit', 'ManageCategoryChilds.remove', 'ManageCategoryChilds.view');//'ManageAdminForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageCategoryChilds.search':'ManageCategoryChilds.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageCategoryChilds.index, ManageCategoryChilds.count, (ManageCategoryChilds.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageCategoryChilds - display list :['+err+']');
    }

};


ManageCategoryChilds.edit      = function(child_index){

    try{

        var child = ManageCategoryChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_CATEGORY:
                ManageCategory.edit( child.child_index );
                break;

            case CHILD_TYPE_PRODUCT:
                ManageProduct.edit( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in : ManageCategoryChilds - edit category child :['+err+']');
    }
};

ManageCategoryChilds.remove    = function(child_index){

    try{

        var child = ManageCategoryChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_CATEGORY:
                ManageCategory.remove( child.child_index );
                break;

            case CHILD_TYPE_PRODUCT:
                ManageProduct.remove( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in : ManageCategoryChilds - remove :['+err+']');
    }

};

ManageCategoryChilds.view      = function(child_index){

    try{

        var child = ManageCategoryChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_CATEGORY:
                ManageCategory.view( child.child_index );
                break;

            case CHILD_TYPE_PRODUCT:
                ManageProduct.view( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in : ManageCategoryChilds - view :['+err+']');
    }

};

ManageCategoryChilds.print     = function(child_index){

    try{

        var child = ManageCategoryChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_CATEGORY:
                ManageCategory.print( child.child_index );
                break;

            case CHILD_TYPE_PRODUCT:
                ManageProduct.print( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in : ManageCategoryChilds - print :['+err+']');
    }
};

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/

ManageCategoryChilds.callback  = function(outputArray){
        
    try{
        
        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }
        
        CMSExtraUtil.show_list(ManageCategoryChilds);

        ManageCategoryChilds.load();

        $('body').trigger( "categorys_updated" );

        //if( g_rule_id == USER_TYPE_MASTER ){
        //}
    
    }catch(err){
        console.error('Error in : ManageCategoryChilds - callback :['+err+']');
    }

};

/******************************************************************************/

ManageCategoryChilds.get_search_form_properties = function(){
    
    var name = 'category';

    var form_properties = { 

        name    : name,

        params  : [

            { name : "category_id", type : "text",    label : CDictionary.get_text('CategoryForm_CategoryId_lbl')+":" },

            { name : "title",       type : "text",    label : CDictionary.get_text('CategoryForm_Title_lbl')+":"      },
            
            { name : "child_type",  type : "select",  label : CDictionary.get_text('CategoryForm_ChildType_lbl')+":"  },

            { type : "separator",    label : "" }

        ],

        action  : '',
        method  : 'post',
        enctype : 'application/x-www-form-urlencoded',

        style   : 'style2'
        
    };
    
    var form_object = new CForm(form_properties);
    
    return form_object;
};

ManageCategoryChilds.search_form                = function(){

    //p_search_array
    
    $("#body").find("#content").find("#form_cell_new").html('');
    $("#body").find("#content").find("#form_cell_import").html('');
    
    var cont_div_cell = $("#body").find("#content").find("#form_cell_search");

    cont_div_cell.html( '' );
        
    //var tpl_path = g_root_url+"mvc/views/tpl/js/forms/category_search_form.tpl";
    
    var form_div    = ManageCategoryChilds.get_search_form_properties().get_form_div(); 

    var form_options = { 
        cont_div          : cont_div_cell,
        //tpl_path          : tpl_path,
        form_div          : form_div,
        form_action       : g_request_url+"?action=search_category_childs&index="+ManageCategoryChilds.index+"&count="+ManageCategoryChilds.count,
        complete_callback : null,
        prepare_func      : ManageCategoryChilds.prepare_search_form,
        animated          : false
    };

    CMSUtil.create_form( form_options );
    
};

ManageCategoryChilds.prepare_search_form        = function(form_div){

    //alert('ManageCategoryChilds.prepare_search_form');


    var child_type_html = ManageCategoryChildsOutput.get_object_type_select_options();
    form_div.find( 'select[name=child_type]' ).html( child_type_html );

    //var student_html = get_students_select_options();
    //form_div.find( 'select[name=student_id]' ).html( student_html );

    form_div.find( 'input[type=submit]' ).click( function(event) {

        event.preventDefault();

        //if(event.preventDefault){
        //    event.preventDefault();
        //}

        //if (event.stopPropagation) {
        //    event.stopPropagation();
        //}

        //alert('preventDefault');
        
        var data = {
            action     : "ManageCategoryChilds.search",
            index      : ManageCategoryChilds.index,
            count      : ManageCategoryChilds.count,

            category_id : form_div.find( 'input[name=category_id]' ).val() ,
            title       : form_div.find( 'input[name=title]'       ).val() ,
            child_type  : form_div.find( 'select[name=child_type]' ).val()

        };
                
        g_search_object = data;
        
        ManageCategoryChilds.search(ManageCategoryChilds.index, ManageCategoryChilds.count);
        
        return false;
        
    });
};

ManageCategoryChilds.search                     = function(index, count){
        
    ManageCategoryChilds.index = ( index == null ) ? ManageCategoryChilds.index : index;
    ManageCategoryChilds.count = ( count == null ) ? ManageCategoryChilds.count : count;


    var list_div = ManageCategoryChilds.list_div;

    var data = ManageCategoryChilds.search_object;

    data["index"] = ManageCategoryChilds.index;
    data["count"] = ManageCategoryChilds.count;

    RequestUtil.quick_post_request(list_div, data, function (outputArray){

        //console.log("outputArray : "+outputArray);

        var status = outputArray["status"];

        if( status > 0 ){

            var categorys   = outputArray["categorys"];
            var result_count = outputArray["result_count"];

            ManageCategoryChilds.display_list(categorys, result_count, SOURCE_SEARCH);

        }
    });  
    
};

/******************************************************************************/

function ManageCategoryChildsOutput(){}

ManageCategoryChildsOutput.get_id              = function(child_id){

    return number_pad(child_id, 6);

};

ManageCategoryChildsOutput.get_object          = function(child_index){

    var child = null;

    try{

        for(var i=0; i<ManageCategoryChilds.array.length; i++){

            if( ManageCategoryChilds.array[i].child_index == child_index ){
                
                child = ManageCategoryChilds.array[i];
                
                break;
            }
        }

    }catch(err){
        console.log('Error in : ManageCategoryChildsOutput - get object :['+err+']');
    }

    return child;

};

ManageCategoryChildsOutput.get_link            = function(title_ar,title_en,child_type,child_id){

    var child_link = '';

    var child_title = (CDictionary.lang == "ar") ? title_ar : title_en;

    child_type = Utils.get_int(child_type);
    
    switch ( child_type ){

        case CHILD_TYPE_CATEGORY:
            var category_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/category.png"> ' +
                         '<a href="#" onclick="ManageCategoryChilds.init('+category_id+')">'+ child_title +'</a>';
            break;

        case CHILD_TYPE_PRODUCT:
            var product_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/product.png"> ' +
                         '<a href="#" onclick="ManageProduct.view('+product_id+')">'  + child_title +'</a> [ ' +
                         '<a href="#" onclick="ManageShot.init('+product_id+')">'     + CDictionary.get_text('Shots_lbl')    +'</a> ] ';
            break;

    }

    return child_link;

};

ManageCategoryChildsOutput.get_icon            = function(child_type){
    
    var child_type_string = '';

    child_type = Utils.get_int(child_type);
    
    switch ( child_type ){

        case CHILD_TYPE_CATEGORY:
            //child_type_string = CDictionary.get_text('Category_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/category.png">';
            break;

        case CHILD_TYPE_PRODUCT:
            //child_type_string = CDictionary.get_text('Product_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/product.png">';
            break;

    }
    
    return child_type_string;
};

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
