
/*! obj cms */

/* global g_request_url */

var g_context     = null;

var ACTION_SOURCE_LIST   = 1;
var ACTION_SOURCE_SEARCH = 2;

function CMS(options) {
    
    this.name             = options.name;
    this.main_div         = options.main_div;

    this.object_lbl       = get_dictionary_text( options.object_lbl ), 

    this.id_lbl           = options.id_lbl;
    this.name_lbl         = options.name_lbl;

    this.array_lbl        = options.array_lbl;
    this.result_count_lbl = options.result_count_lbl;
    
    
    this.add_url          = options.add_url;
    this.update_url       = options.update_url;
    this.remove_url       = options.remove_url;

    this.list_url         = options.list_url;
    
    this.search_url       = options.search_url;
    this.quick_search_url = options.quick_search_url;
    
    this.form_div         = options.form_div;
    this.preview_div      = options.preview_div;
    
    this.form_settings    = options.form_settings;
    this.post_edit        = options.post_edit;
    this.post_preview     = options.post_preview;
    
    this.child_func       = options.child_func;

    
    this.extra_add_buttons = options.extra_add_buttons;
    
    this.editable         = (options.editable  == null) ? true : options.editable;
    this.removable        = (options.removable == null) ? true : options.removable;
    
    this.active_id        = -1;

    //other attributes
    this.index            = 0;
    this.count            = (options.count == null) ? 30 : options.count;
    this.array            = [];
    this.result_count     = 0;
    
    
    this.action_source    = 0;
    

    g_context = this;

    //this.cprototype = cprototype;

    this.add     = function () {

        //console.log( "add funcation called" );
        try{

            var new_id = 0;
            var name   = this.name;

            var form_div = this.get_form_div(this.name, new_id);
            form_div.attr( "action", g_request_url+"?"+this.add_url );

            var parent_div = $( '#'+name+'_form_cell_'+new_id );
            parent_div.append(form_div);

            //$( "#"+name+"_form_"+new_id ).jqupload({"callback":"action_callback"});
            //$( "#"+name+"_form_"+new_id ).jqupload_form();

            $(function () {
                form_div.iframePostForm({
                    post : function () {
                        //alert("sending request");
                    },
                    complete : function (response) {
                        //alert("complete");
                        var json_output = $.evalJSON( response );
                        g_context.action_callback(json_output);
                    }
                });
            });

            var submit = parent_div.find( 'input[name=submit]' );
            submit.click( function() {
                var loading = $('<div id="loading_form" class="loading_mid_s2"></div>');
                parent_div.append( loading.show() );
            });
            var reset = parent_div.find( 'input[name=reset]' );
            reset.click( function() {
                g_context.cancel_form(new_id);
            });

            var open = parent_div.find( '.open_editor' );
            open.click( function() {
                var textElement = $(this).attr("name");
                open_editor(parent_div, textElement);
            });

            parent_div.show("slide", {direction: "up"}, 1000);

        }catch(err){
            console.log('error in cms add :['+err+']');
        }

        return false;
    };
    
    this.update  = function (id) {
        
        try{
            //console.log( "update funcation called for id:"+id );

            var name = this.name;

            var form_div = this.get_form_div(this.name, id);
            form_div.attr( "action", g_request_url+"?"+this.update_url );

            var parent_li = $( '#'+name+'_form_cell_'+id );
            parent_li.append(form_div);

            //$( "#"+name+"_form_"+id ).jqupload({"callback":"_"+name+"_action_callback"});
            //$( "#"+name+"_form_"+id ).jqupload_form();

            $(function () {
                form_div.iframePostForm({
                    post : function () {
                        //alert("sending request");
                    },
                    complete : function (response) {
                        //alert("complete");
                        var json_output = $.evalJSON( response );
                        g_context.action_callback(json_output);
                    }
                });
            });

            $( '#'+name+'_'+id+'_edit'   ).hide();
            $( '#'+name+'_'+id+'_remove' ).hide();

            var submit = parent_li.find( 'input[name=submit]' );
            submit.click( function() {
                var loading = $('<div id="loading_form" class="loading_mid_s2"></div>');
                parent_li.append( loading.show() );
            });

            var reset = parent_li.find( 'input[name=reset]' );
            reset.click( function() {
                g_context.cancel_form(id);
            });

            var open = parent_li.find( '.open_editor' );
            open.click( function() {
                var textElement = $(this).attr("name");
                open_editor(parent_li, textElement);
            });


            var object = this.get_object(id);

            if( this.post_edit != null ){
                this.post_edit(parent_li, object);
            }

            parent_li.show("slide", {direction: "up"}, 1000);

        }catch(err){
            console.log('error in cms update :['+err+']');
        }
        
        return false;
    };

    this.remove  = function (id) {
    
        try{

            var sure = confirm("Are you sure you want to delete?",{buttons: {Ok:true,Cancel:false}});

            if(sure){

                var seqId = Math.floor(Math.random()*1000);

                var serverUrl = g_request_url;

                var data      = this.remove_url +
                                "&"+this.id_lbl+"=" + id;
                                "&seqId=" + seqId;

                var xmlHttp = $.get(serverUrl, data, this.action_callback, "json");

                var loading = $('<div id="loading_form" class="loading_mid_s2"></div>');
                $('#'+this.id_lbl+'_'+id).append( loading.show() );
            }

        }catch(err){
            console.log('error in cms remove :['+err+']');
        }
        
        return false;
    };

    this.preview = function (id){

        try{

            //console.log( 'preview info (id) : '+id );

            //var parent_div = $('<div></div>').append( get_tpl_div('.'+this.name+'_preview_tpl').html() );
            var parent_div = $( this.preview_div ).clone();

            var object = this.get_object(id);

            if( this.post_preview != null ){
                this.post_preview(parent_div, object);
            }

            //var content = parent_div.wrap('<div></div>').html();
            
            var content = $('<div>').append( parent_div.clone() ).html(); 

            display_popup(content, this.object_lbl+' Info');

            var popup = get_popup_object();
        
        }catch(err){
            console.log('error in cms preview :['+err+']');
        }
        
        return false;
    };

    this.child   = function (id){

        try{
        
            //console.log( 'child info (id) : '+id );
            //var object = this.get_object(id);

            if( this.child_func != null ){
                this.child_func(id);
            }
            
        }catch(err){
            console.log('error in cms child :['+err+']');
        }
        
        return false;
    };


    this.get_form_div    = function (name, id){

        try{

            //var form_div = $('<div></div>').append( get_tpl_div('.'+name+'_form_tpl').html() );
            var form_div = $( this.form_div ).clone();

            form_div.attr( "id", name+"_form_"+id );

            if( this.form_settings != null ){
                this.form_settings(form_div);
            }

        }catch(err){
            console.log('error in cms get form div :['+err+']');
        }
        
        return form_div;
    };
    
    this.action_callback = function (output_array){

        try{

            //console.log('Al Hamdo le Allah');
            //console.log('action_callback');
            //console.log(output_array.status);

            var name = g_context.name;

            $('#loading_form').hide();

            $( 'div[id^='+name+'_form_cell]' ).hide("slide", {direction: "up"}, 1000, function() {
                $(this).children().remove();
            });

            $('iframe[id$=_iframe]').remove();

                        
            if( g_context.action_source == ACTION_SOURCE_LIST ){
                g_context.load_list_inner(g_context.index);
            }else{
                g_context.load_search_inner(g_context.index);
            }

            //alert( output.status );

            //output.status;
            //output.messages;
            //output.warnings;
            //output.errors

        }catch(err){
            console.log('error in cms action callback :['+err+']');
        }
        
    };
    
    this.cancel_form     = function (id){

        try{

            //alert( 'cancel_form' );

            $( '#'+this.name+'_form_cell_'+id+'' ).hide("slide", {direction: "up"}, 1000, function() {
                $(this).children().remove();
            });

            $('iframe[id='+this.name+'_form_'+id+'_iframe]').remove();

            $( '#'+this.name+'_'+id+'_edit'   ).show();
            $( '#'+this.name+'_'+id+'_remove' ).show();

        }catch(err){
            console.log('error in cms cancel form :['+err+']');
        }
        
    };
    

    this.get_object      = function (id) {

        try{

            var array    = this.array;
            var id_lbl   = this.id_lbl;

            for(var i=0; i<array.length; i++){
                
                var object = array[i];
                                
                if( object[id_lbl] == id ){
                    return array[i];
                }
            }
            
        }catch(err){
            console.log('error in cms get object :['+err+']');
        }
        
        return null;
    };

    this.get_name_label  = function (item) {

        var label = '';
        
        try{
            
            label = item[ this.name_lbl ];
            
        }catch(err){
            console.log('error in cms get name label :['+err+']');
        }
        
        return label;
    };
    

    this.load_search       = function (search_item) {

        try{
            //console.log( 'load_list' );

            g_context.search_item   = search_item;

            g_context.action_source = ACTION_SOURCE_SEARCH;
            //console.log( 'load_list('+index+','+count+')' );

            g_context.load_search_inner(0);

        }catch(err){
            console.log('error in cms load search :['+err+']');
        }
    };

    this.load_search_inner = function (index, count) {

        try{
            
            console.log( 'load_search_inner' );

            this.main_div.html('');

            var index = ( index == null ) ? this.index : index;
            var count = ( count == null ) ? this.count : count;

            this.index = index;
            this.count = count;

            var seqId = Math.floor(Math.random()*1000);

            var serverUrl = g_request_url;

            var data      = this.search_url
                          + "&search_item="+this.search_item
                          + "&index="+index
                          + "&count="+count
                          + "&seqId="+seqId;

            var xmlHttp = $.get(serverUrl, data, this.on_load, "json");

            var loading = $('<div id="loading_list" class="loading_mid"></div>');
            this.main_div.append( loading.show() );

        }catch(err){
            console.log('error in cms load list inner :['+err+']');
        }
        
    };
    
    
    this.load_list       = function (index, count) {

        try{
            //console.log( 'load_list' );

            this.index = index;
            this.count = count;

            this.action_source = ACTION_SOURCE_LIST;
            //alert( 'load_list('+index+','+count+')' );

            this.load_list_inner(index);

        }catch(err){
            console.log('error in cms load list :['+err+']');
        }
    };

    this.load_list_inner = function (index) {

        try{
            
            //console.log( 'load_list_inner' );

            this.main_div.html('');

            var index = ( index == null ) ? this.index : index;
            var count = ( count == null ) ? this.count : count;

            this.index = index;
            this.count = count;

            var seqId = Math.floor(Math.random()*1000);

            var serverUrl = g_request_url;

            var data      = this.list_url
                          + "&index="+index
                          + "&count="+count
                          + "&seqId="+seqId;

            var xmlHttp = $.get(serverUrl, data, this.on_load, "json");

            var loading = $('<div id="loading_list" class="loading_mid"></div>');
            this.main_div.append( loading.show() );

        }catch(err){
            console.log('error in cms load list inner :['+err+']');
        }
        
    };
    
    this.on_load         = function (output_array){
    
        try{
            //console.log( 'on_load' );
            //console.log( g_context.list_url );

            //this = g_context;

            var array        = output_array[g_context.array_lbl];
            var result_count = output_array[g_context.result_count_lbl];

            //console.log( array );

            g_context.array = array;

            var index       = g_context.index;
            var count       = g_context.count;
            var group_count = g_context.count * 100;

            this.array        = array;
            this.result_count = result_count;

            //alert( admins.length );

            $('#loading_list').hide();

            var output = '';

            output += g_context.get_list_html_output(array);

            //console.log(output);

            output += '<br />';
            
            var action_func = null;
            
            if( g_context.action_source == ACTION_SOURCE_LIST ){
                action_func = 'g_context.load_list_inner';
            }else{
                action_func = 'g_context.load_search_inner';
            }

            output += g_context.get_pagination_html_output(action_func, result_count, index, count, group_count);

            output += '<br />';

            g_context.main_div.html('');

            g_context.main_div.append( output );


 
            var input               = $('#'+g_context.name+'_search_input');
            var qsearch_server_side = g_request_url+'?'+g_context.quick_search_url;

            apply_auto_complete(input, qsearch_server_side, g_context.load_search);


            Utils.scroll_to_element(g_context.main_div, 300);
        
        }catch(err){
            console.log('error in cms on load :['+err+']');
        }
        
    };

    
    this.get_list_html_output       = function (items){
    
        try{
            //console.log( 'get_list_html_output' );

            var output = '';

            var name = this.name;

            if( this.search_url != null && this.quick_search_url != null ){
                
                output += '<div id="'+name+'_search" class="sub">' +
                             get_dictionary_text('Search_lbl') + ' ' +
                          '</div>'+
                          '<div id="'+name+'_search" class="sub">' +
                             '<input id="'+name+'_search_input" size="50" />'+
                          '</div>'+
                          '<div class="clearfix"><br /></div>';                
            }

            if( this.extra_add_buttons == null ){
                
                var new_id = 0;

                output += '<div id="'+name+'_'+new_id+'" class="sub add" onclick="g_context.add(); return false;"> '+get_dictionary_text('New_lbl')+' '+g_context.object_lbl+'</div>' +
                          '<br />';

                output += '<div id="'+name+'_form_cell_'+new_id+'" style="display:none;" class="single"></div>' + 
                          '<div class="clearfix"><br /></div>';

            }else{
                
                output += this.extra_add_buttons() + 
                          '<div class="clearfix"><br /></div>';

            }
            
            output += '<ul>';

            for(var i=0; i<items.length; i++){

                var item   = items[i];

                var id     = item[this.id_lbl];
                var label  = this.get_name_label(item);

                var iclass = this.get_class(id);

                //console.log(item[id_lbl]);

                output += '<li id="'+name+'_'+id+'" class="item">' +
                              '<div id="'+name+'_'+id+'_label"   class="sub child '+iclass+'" onclick="g_context.child('+id+');   return false;">' + label + '</div>' +
                              '<div id="'+name+'_'+id+'_preview" class="sub_icon view"        onclick="g_context.preview('+id+'); return false;"></div>' +
                              '<div id="'+name+'_'+id+'_edit"    class="sub_icon edit"        onclick="g_context.update('+id+');  return false;"></div>' +
                              '<div id="'+name+'_'+id+'_remove"  class="sub_icon remove"      onclick="g_context.remove('+id+');  return false;"></div>' +
                          '</li>';

                output += '<li id="'+name+'_form_cell_'+id+'" style="display:none;" type="none" class="single"></li>';

            }

            output += '</ul>';

            //console.log( output );
            
        }catch(err){
            console.log('error in cms get list html output :['+err+']');
        }

        return output;
        
    };

    this.get_pagination_html_output = function (link_function, result_count, index, count, group_count){

        try{

            //alert( link_function+", "+result_count+", "+index+", "+count+", "+group_count );

            var start = parseInt( index / group_count ) * group_count;

            var output = '';
            output += '<div id="Pagination" class="pagination">';

                output += '<div id="Links">';

                    if(start >= group_count){
                        output += '<a href="javascript:'+link_function+'('+ (start-group_count)+');"> &laquo; </a>';
                    }
        //            if(index > 0){
        //                output += '<a href="javascript:'+link_function+'('+ (index-count)+');">' + get_dictionary_text('Back_lbl'') + '</a>';
        //            }
                    for(var i=start; i<result_count && i<start+group_count; i+=count ){
                        output += '<a href="javascript:'+link_function+'('+ i +');"> '+(parseInt(i/count)+1)+' </a>';
                    }
        //            if( index+count < result_count){
        //                output += '<a href="javascript:'+link_function+'('+ (index+count)+');">' + get_dictionary_text('Next_lbl'') + '</a>';
        //            }
                    if( start+group_count <= result_count){
                        output += '<a href="javascript:'+link_function+'('+ (start+group_count)+');"> &raquo; </a>';
                    }

                output += '</div>';

                output += '<div id="Text"> &nbsp; ' + result_count + ' ' + get_dictionary_text('Results_lbl') + '</div>';

            output += '</div>';
            
        }catch(err){
            console.log('error in cms get pagination html output :['+err+']');
        }
        
        return output;

    };

    
    this.get_class = function (id){
        
        return "";

//        try{
//
//            
//
//        }catch(err){
//            console.log('error in get class :['+err+']');
//        }

    };


    this.check  = function (){

        try{
            alert('check');
        }catch(err){
            console.log('error in cms check :['+err+']');
        }

    };
    
    this.destroy = function (){
        
        try{

            this.name          = null;
            this.main_div      = null;

            this.list_url      = null;

            this.array_lbl        = null;
            this.result_count_lbl = null;

            this.name_lbl = null;
            this.id_lbl      = null;

            this.index         = null;
            this.count         = null;

            this.add_url       = null;
            this.update_url    = null;
            this.remove_url    = null;

            this.form_settings     = null;
            this.post_preview  = null;

            this.array         =  null;

            g_context = null;

        }catch(err){
            console.log('error in cms destroy :['+err+']');
        }

    };

}

