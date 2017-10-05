
/*! section childs */

/* global CDictionary, CForm, CMSUtil, SOURCE_SEARCH, SOURCE_LIST, g_request_url, g_root_url, CPopup, Utils, CMSExtraUtil, RequestUtil, ManageLink, ManageEmbed, ManageTarget, ManageSection */

var COLOR_PATTERN = /#[a-fA-F0-9]{6}/;

var CHILD_TYPE_SECTION = 1;
var CHILD_TYPE_TARGET  = 2;
var CHILD_TYPE_EMBED   = 3;
var CHILD_TYPE_LINK    = 4;


var STYLE_DEFAULT = 1;
var STYLE_MEDIA   = 2;




function ManageSectionChilds(){}

ManageSectionChilds.parent_id = -1;
ManageSectionChilds.array     = [];


ManageSectionChilds.init      = function(parent_id){

    try{

        ManageSectionChilds.parent_id = Utils.get_int( parent_id );

        ManageSectionChilds.parent_id = ( ManageSectionChilds.parent_id  > 0 ) ? ManageSectionChilds.parent_id  : 0;


        var content_div = $("#body").find("#content");

        content_div.html( '' );

//        content_div.append( 
//            '<div class="controls clearfix">' + 
//                '<div class="top_label_main" onclick="return false;">' + CDictionary.get_text('SectionChilds_lbl') + '</div>' + 
//                '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
//                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
//                    CDictionary.get_text('New_lbl') + 
//                '</div>' + 
//                '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
//                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
//                    CDictionary.get_text('New_lbl') + 
//                '</div>' + 
//                '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
//                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
//                    CDictionary.get_text('New_lbl') + 
//                '</div>' + 
//                '<div class="top_button" onclick="ManageAdminForm.add(); return false;">' +
//                    '<i class="fa fa-plus-square" aria-hidden="true"></i>' +
//                    CDictionary.get_text('New_lbl') + 
//                '</div>' + 
//                '<div class="top_button" onclick="ManageAdminForm.search(); return false;">' +
//                    '<i class="fa fa-search" aria-hidden="true"></i>' +
//                    CDictionary.get_text('Search_lbl') + 
//                '</div>' + 
//                //'<div class="top_button" onclick="ManageAdminForm.import_form(); return false;">' +
//                //    '<i class="fa fa-download" aria-hidden="true"></i>' +
//                //    CDictionary.get_text('Import_lbl') + 
//                //'</div>' +
//                //'<div class="top_button" onclick="ManageAdminForm.export_form(); return false;">' +
//                //    '<i class="fa fa-share-square-o" aria-hidden="true"></i>' +
//                //    CDictionary.get_text('Export_lbl') + 
//                //'</div>' + 
//            '</div>' 
//        );

        content_div.append( 
            '<div class="top_label_main"         onclick="ManageSectionChilds.init('+ManageSectionChilds.parent_id+'); return false;">' + CDictionary.get_text('Sections_lbl') + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageSection.add(); return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Section_lbl')  + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageTarget.add();  return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Target_lbl')   + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageEmbed.add();   return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Embed_lbl')    + '</div>' + 
            '<div class="top_label new_label"    onclick="ManageLink.add();    return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Link_lbl')     + '</div>'
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

        ManageSectionChilds.form_div = form_div;
        ManageSectionChilds.path_div = path_div;
        ManageSectionChilds.list_div = list_div;


//        //alert('manage sections loaded');
//
//        var content_div = $("#body").find("#content");
//
//        content_div.html( '' );
//
//        content_div.append( 
//            '<div class="top_label_main"         onclick="ManageSectionChilds.init('+ManageSectionChilds.parent_id+'); return false;">' + CDictionary.get_text('Sections_lbl') + '</div>' + 
//            '<div class="top_label new_label"    onclick="add_section_form();                     return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Section_lbl') + '</div>' + 
//            '<div class="top_label new_label"    onclick="add_target_form();                      return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Target_lbl')  + '</div>' + 
//            '<div class="top_label new_label"    onclick="add_embed_form();                       return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Embed_lbl')   + '</div>' + 
//            '<div class="top_label new_label"    onclick="add_link_form();                        return false;">' + CDictionary.get_text('New_lbl') + ' ' + CDictionary.get_text('Link_lbl')    + '</div>'
//        );
//
//        content_div.append( '<div class="clearfix"></div>' );
//
//        content_div.append( 
//            '<div id="form_cell_new"></div>'    +
//            '<div id="form_cell_search"></div>' +
//            '<div id="form_cell_import"></div>' +
//            '<div id="form_cell_export"></div>'
//        );
//
//        content_div.append(
//            '<div id="section_path" class="path"></div>' + 
//            '<div class="main_label">' + CDictionary.get_text('List_lbl') + '</div>' + 
//            '<div id="section_child_list"></div>'
//        );


        ManageSectionChilds.index = 0;
        ManageSectionChilds.count = 10;

        CMSExtraUtil.show_list( ManageSectionChilds );

        ManageSectionChilds.load();

        ManageSectionChilds.load_path();

        //BackgroundRequests.load_organizations();

    }catch(err){
        console.error('Error in - ManageSectionChilds.init :['+err+']');
    }
};

ManageSectionChilds.load_path = function(){

    try{

        var section_id = ManageSectionChilds.parent_id;

        var data = {
            action      : "section_path",
            index       : -1,
            count       : -1,

            section_id  : section_id
        };

        var path_div = ManageSectionChilds.path_div;

        path_div.html( '' );

        RequestUtil.quick_post_request(path_div, data, function (outputArray){

            //console.log("outputArray : "+outputArray);

            var status = outputArray["status"];

            if( status > 0 ){

                var sections = outputArray["sections"];

                var path_html = '';

                path_html += '<a href="javascript:ManageSectionChilds.init(0);" class="cell">'+ CDictionary.get_text('Sections_lbl') +'</a>';

                for (var i=0; i<sections.length; i++){

                    var section = sections[i];

                    var section_name = CDictionary.get_text_by_lang(section, "title");

                    path_html += '<a href="javascript:ManageSectionChilds.init('+ section.section_id +');" class="cell">'+ section_name +'</a>';

                }

                path_div.html( path_html );

            }
        });

    }catch(err){
        console.error('Error in - load section path :['+err+']');
    }

};


ManageSectionChilds.cancel    = function(){

    try{
        
        CMSExtraUtil.show_list( ManageSectionChilds );

        ManageSectionChilds.load();

    }catch(err){
        console.error('Error in : ManageSectionChilds - cancel :['+err+']');
    }
};


ManageSectionChilds.load         = function(index, count){
    
    try{

        ManageSectionChilds.index = ( index == null ) ? ManageSectionChilds.index : index;
        ManageSectionChilds.count = ( count == null ) ? ManageSectionChilds.count : count;

        var list_div = ManageSectionChilds.list_div;

        var data    = "action=section_childs"
                    + "&parent_id="+ManageSectionChilds.parent_id
                    + "&index="+ManageSectionChilds.index+"&count="+ManageSectionChilds.count;

        RequestUtil.quick_post_request(list_div, data, function (outputArray){

            try{

                var status = outputArray["status"];

                if( status > 0 ){

                    var section_childs = outputArray["section_childs"];
                    var result_count   = outputArray["result_count"];

                    ManageSectionChilds.display_list(section_childs, result_count, SOURCE_LIST);

                }

            } catch (err) {
                console.log('error in load section childs :[' + err + ']');
            }

        });
    
    }catch(err){
        console.error('Error in - load section childs :['+err+']');
    }

};

ManageSectionChilds.display_list = function (array, result_count, source){

    try{

        ManageSectionChilds.array = array;

        var list_div = ManageSectionChilds.list_div;


        var labels   = CDictionary.get_labels([
                        'SectionChildsList_ChildId_lbl',
                        'SectionChildsList_Title_lbl',
                        'SectionChildsList_Order_lbl',
                        'SectionChildsList_Active_lbl' ]);//,
                        //'SectionChildsList_ParentId_lbl' ]);

        var fields   = [ "ManageSectionChildsOutput.get_id(child_index)", 
                         "ManageSectionChildsOutput.get_link(title_ar,title_en,child_type,child_id)",
                         "order", 
                         "active" ];//,
                         //"parent_id" ];

        var id_label = "child_index";

        CMSUtil.show_list(list_div, labels, fields, array, id_label, 'ManageSectionChilds.edit', 'ManageSectionChilds.remove', 'ManageSectionChilds.view');//'ManageAdminForm.view'

        var func = ( source === CMSUtil.PAGINATION_SEARCH ) ?'ManageSectionChilds.search':'ManageSectionChilds.load';
        CMSUtil.show_pagination(list_div, func, [], result_count, ManageSectionChilds.index, ManageSectionChilds.count, (ManageSectionChilds.count*10));

        Utils.scroll_to_element(list_div, 300);
    
    }catch(err){
        console.error('Error in : ManageAdminList - display list :['+err+']');
    }

};


ManageSectionChilds.edit      = function(child_index){

    try{

        var child = ManageSectionChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_SECTION:
                ManageSection.edit( child.child_index );
                break;

            case CHILD_TYPE_TARGET:
                ManageTarget.edit( child.child_index );
                break;

            case CHILD_TYPE_EMBED:
                ManageEmbed.edit( child.child_index );
                break;

            case CHILD_TYPE_LINK:
                ManageLink.edit( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in - edit section child :['+err+']');
    }
};

ManageSectionChilds.remove    = function(child_index){

    try{

        var child = ManageSectionChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_SECTION:
                ManageSection.remove( child.child_index );
                break;

            case CHILD_TYPE_TARGET:
                ManageTarget.remove( child.child_index );
                break;

            case CHILD_TYPE_EMBED:
                ManageEmbed.remove( child.child_index );
                break;

            case CHILD_TYPE_LINK:
                ManageLink.remove( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in - delete section child :['+err+']');
    }

};

ManageSectionChilds.view      = function(child_index){

    try{

        var child = ManageSectionChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_SECTION:
                ManageSection.view( child.child_index );
                break;

            case CHILD_TYPE_TARGET:
                ManageTarget.view( child.child_index );
                break;

            case CHILD_TYPE_EMBED:
                ManageEmbed.view( child.child_index );
                break;

            case CHILD_TYPE_LINK:
                ManageLink.view( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in - view section child :['+err+']');
    }

};

ManageSectionChilds.print     = function(child_index){

    try{

        var child = ManageSectionChildsOutput.get_object(child_index);

        var child_type = Utils.get_int(child.child_type);

        switch ( child_type ){

            case CHILD_TYPE_SECTION:
                ManageSection.print( child.child_index );
                break;

            case CHILD_TYPE_TARGET:
                ManageTarget.print( child.child_index );
                break;

            case CHILD_TYPE_EMBED:
                ManageEmbed.print( child.child_index );
                break;

            case CHILD_TYPE_LINK:
                ManageLink.print( child.child_index );
                break;

        }

    }catch(err){
        console.error('Error in - print section child :['+err+']');
    }
};

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/

ManageSectionChilds.callback  = function(outputArray){
        
    try{
        
        var status = outputArray["status"];

        if( status > 0 ){
            CMSExtraUtil.show_success( CDictionary.get_text('CommonCallback_Success_Title_lbl') );
        }else{
            CMSExtraUtil.show_error( CDictionary.get_text('CommonCallback_Failed_Title_lbl'), CDictionary.get_text('CommonCallback_Failed_Title_lbl') );
        }
        
        CMSExtraUtil.show_list(ManageSectionChilds);

        ManageSectionChilds.load();

        $('body').trigger( "sections_updated" );

        //if( g_rule_id == USER_TYPE_MASTER ){
        //}
    
    }catch(err){
        console.error('Error in : ManageSectionChilds - callback :['+err+']');
    }

};

/******************************************************************************/

ManageSectionChilds.get_search_form_properties = function(){
    
    var name = 'section';

    var form_properties = { 

        name    : name,

        params  : [

            { name : "section_id", type : "text",    label : CDictionary.get_text('SectionForm_SectionId_lbl')+":" },

            { name : "title",       type : "text",    label : CDictionary.get_text('SectionForm_Title_lbl')+":"      },
            
            { name : "child_type",  type : "select",  label : CDictionary.get_text('SectionForm_ChildType_lbl')+":"  },

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

ManageSectionChilds.search_form                = function(){

    //p_search_array
    
    $("#body").find("#content").find("#form_cell_new").html('');
    $("#body").find("#content").find("#form_cell_import").html('');
    
    var cont_div_cell = $("#body").find("#content").find("#form_cell_search");

    cont_div_cell.html( '' );
        
    //var tpl_path = g_root_url+"mvc/views/tpl/js/forms/section_search_form.tpl";
    
    var form_div    = ManageSectionChilds.get_search_form_properties().get_form_div(); 

    var form_options = { 
        cont_div          : cont_div_cell,
        //tpl_path          : tpl_path,
        form_div          : form_div,
        form_action       : g_request_url+"?action=ManageSectionChilds.search&index="+ManageSectionChilds.index+"&count="+ManageSectionChilds.count,
        complete_callback : null,
        prepare_func      : ManageSectionChilds.prepare_search_form,
        animated          : false
    };

    CMSUtil.create_form( form_options );
    
};

ManageSectionChilds.prepare_search_form        = function(form_div){

    //alert('ManageSectionChilds.prepare_search_form');


    var child_type_html = ManageSectionChildsOutput.get_object_type_select_options();
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
            action     : "ManageSectionChilds.search",
            index      : ManageSectionChilds.index,
            count      : ManageSectionChilds.count,

            section_id : form_div.find( 'input[name=section_id]' ).val() ,
            title       : form_div.find( 'input[name=title]'       ).val() ,
            child_type  : form_div.find( 'select[name=child_type]' ).val()

        };
                
        g_search_object = data;
        
        ManageSectionChilds.search(ManageSectionChilds.index, ManageSectionChilds.count);
        
        return false;
        
    });
};

ManageSectionChilds.search                     = function(index, count){
        
    ManageSectionChilds.index = ( index == null ) ? ManageSectionChilds.index : index;
    ManageSectionChilds.count = ( count == null ) ? ManageSectionChilds.count : count;


    var list_div = ManageSectionChilds.list_div;

    var data = ManageSectionChilds.search_object;

    data["index"] = ManageSectionChilds.index;
    data["count"] = ManageSectionChilds.count;

    RequestUtil.quick_post_request(list_div, data, function (outputArray){

        //console.log("outputArray : "+outputArray);

        var status = outputArray["status"];

        if( status > 0 ){

            var sections   = outputArray["sections"];
            var result_count = outputArray["result_count"];

            ManageSectionChilds.display_list(sections, result_count, SOURCE_SEARCH);

        }
    });  
    
};

/******************************************************************************/

function ManageSectionChildsOutput(){}

ManageSectionChildsOutput.get_id              = function(child_id){

    return number_pad(child_id, 6);

};

ManageSectionChildsOutput.get_object          = function(child_index){

    var child = null;

    try{

        for(var i=0; i<ManageSectionChilds.array.length; i++){

            if( ManageSectionChilds.array[i].child_index == child_index ){
                
                child = ManageSectionChilds.array[i];
                
                break;
            }
        }

    }catch(err){
        console.log('error in cms get section child object :['+err+']');
    }

    return child;

};

ManageSectionChildsOutput.get_link            = function(title_ar,title_en,child_type,child_id){

    var child_link = '';

    var child_title = (CDictionary.lang == "ar") ? title_ar : title_en;

    child_type = Utils.get_int(child_type);
    
    switch ( child_type ){

        case CHILD_TYPE_SECTION:
            var section_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/section.png"> ' +
                         '<a href="#" onclick="ManageSectionChilds.init('+section_id+')">'+ child_title +'</a>';
            break;

        case CHILD_TYPE_TARGET:
            var target_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/page.png"> ' +
                         '<a href="#" onclick="view_target('+target_id+')">'+ child_title +'</a>';
            break;

        case CHILD_TYPE_EMBED:
            var embed_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/file.png"> ' +
                         '<a href="#" onclick="view_embed('+embed_id+')">'+ child_title +'</a>';
            break;

        case CHILD_TYPE_LINK:
            var link_id = child_id;
            child_link = '<img src="'+g_root_url+'images/manage/icons/link.png"> ' +
                         '<a href="#" onclick="view_link('+link_id+')">'+ child_title +'</a>';
            break;

    }

    return child_link;

};

ManageSectionChildsOutput.get_icon            = function(child_type){
    
    var child_type_string = '';

    child_type = Utils.get_int(child_type);
    
    switch ( child_type ){

        case CHILD_TYPE_SECTION:
            //child_type_string = CDictionary.get_text('Section_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/section.png">';
            break;

        case CHILD_TYPE_TARGET:
            //child_type_string = CDictionary.get_text('Target_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/page.png">';
            break;

        case CHILD_TYPE_EMBED:
            //child_type_string = CDictionary.get_text('Embed_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/file.png">';
            break;

        case CHILD_TYPE_LINK:
            //child_type_string = CDictionary.get_text('Link_lbl');
            child_type_string = '<img src="'+g_root_url+'images/cms/link.png">';
            break;

    }
    
    return child_type_string;
};

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/
