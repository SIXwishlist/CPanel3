
/*! CMSUtil */
/* Based on: [ CDictionary, Cover, CPopup, RequestUtil ] */
/* global CDictionary, Cover, CPopup, RequestUtil */

function CMSUtil() {
}


CMSUtil.PAGINATION_LIST   = 1;
CMSUtil.PAGINATION_SEARCH = 2;

CMSUtil.ANIMATION_TYPE_SLIDE = 1;
CMSUtil.ANIMATION_TYPE_FADE  = 2;

CMSUtil.create_form        = function (options) {

    var cont_div = options.cont_div;
    var form_div = options.form_div;
    var tpl_path = options.tpl_path;

    try{

        //alert('_inner_create_form_func');

        var __context = this;

        if( tpl_path != null ){

            cont_div.load(tpl_path, null, function(){

                options.cont_div = cont_div;

                __context._inner_create_form(options);

            });

        }else{

            cont_div.append(form_div);

            options.cont_div = cont_div;

            __context._inner_create_form(options);

        }

    } catch(err) {
        throw 'Error in : CMSUtil - create form [' + err +']';
    }
};

CMSUtil._inner_create_form = function (options) {

    var cont_div            = options.cont_div;
    var form_id             = options.form_id;
    //var tpl_path            = options.tpl_path;
    var form_action         = options.form_action;
    var complete_callback   = options.complete_callback;
    var cancel_callback     = options.cancel_callback;
    var prepare_func        = options.prepare_func;
    var post_func           = options.post_func;
    var post_args           = options.post_args;
    var validate_func       = options.validate_func;
    var validate_notes_func = options.validate_notes_func;
    var animated            = options.animated;
    var animation_type      = options.animation_type;


    try{

        //alert('_inner_create_form_func');

        var __context = this;

        //var form = cont_div.find("#"+form_id);
        var form = cont_div.find("form");

        animated = (animated == null) ? true : false;

        if(animated){
            switch( animation_type ){

                case CMSUtil.ANIMATION_TYPE_SLIDE:
                    form.hide().show("slide", {direction: "up"}, 1000);
                    break;

                case CMSUtil.ANIMATION_TYPE_FADE:
                    form.hide().fadeIn( 1000 );
                    break;

                default:
                    form.hide().fadeIn( 1000 );
                    break;
            }
        }

        cont_div.parent().addClass('open');

        form.attr("action", form_action);

        //form.submit(function( event ) {
        //    alert('done :)');
        //    return true;//continue event
        //    //return false;//break event
        //});

        //form.submit(function( event ) {
        //    alert('done :)');
        //    event.preventDefault();
        //    form.submit();                
        //    form.get(0).reset();
        //});

        //Apply post from
        $(function () {
            form.iframePostForm({
                post: function () {

                    try{

                        //alert("sending request");
                        //var loading = $('<div id="loading_list" class="loading_big"></div>');
                        //cont_div.append(loading.show());

                    } catch(err) {
                        throw 'Error in : CMSUtil - create form - iframePostForm - post : [' + err +']';
                    }
                },
                complete: function (response) {

                    try{
                        //alert("complete request");
                        cont_div.find("#loading_list").remove();

                        var jsonOutput = $.evalJSON(response);

                        form.get(0).reset();

                        cont_div.parent().removeClass('open');

                        if(animated){

                            switch( animation_type ){

                                case CMSUtil.ANIMATION_TYPE_SLIDE:
                                    form.hide("slide", {direction: "up"}, 1000, function(){
                                        cont_div.html('');
                                    });
                                    break;

                                case CMSUtil.ANIMATION_TYPE_FADE:
                                    form.fadeOut( 1000, function(){
                                        cont_div.html('');
                                    });
                                    break;

                                default:
                                    form.fadeOut( 1000, function(){
                                        cont_div.html('');
                                    });
                                    break;
                            }
                        }

                        complete_callback(jsonOutput);

                    } catch(err) {
                        throw 'Error in : CMSUtil - create form - iframePostForm - complete : [' + err +']';
                    }
                }
            });
        });

        //form.find('input[type=submit]').click(function(event){
        form.submit(function( event ) {

            try{

                //alert("submit");

                //event.preventDefault();

                if( validate_func != null ){

                    var errors = validate_func( cont_div );

                    if( errors <= 0 ){

                        var loading = $('<div id="loading_list" class="loading_big"></div>');
                        cont_div.append(loading.show());

                        return true;//continue submit

                        //form.submit();                

                        //form.get(0).reset();

                    } else {

                        validate_notes_func(form);

                        return false;//break submit

                    }

                }else{

                    var loading = $('<div id="loading_list" class="loading_big"></div>');
                    cont_div.append(loading.show());

                    return true;//continue submit
                    //form.submit();
                }


            } catch(err) {
                throw 'Error in : CMSUtil - create form - iframePostForm - submit : [' + err +']';
            }
        });

        form.find('input[type=reset]').click(function(event){

            try{
                //alert("reset");

                event.preventDefault();

                form.find('.errors').html('').hide();

                form.find('.error').html('').remove();

                form.get(0).reset();

                cont_div.parent().removeClass('open');

                if(animated){
                    
                    switch( animation_type ){

                        case CMSUtil.ANIMATION_TYPE_SLIDE:
                            form.hide("slide", {direction: "up"}, 1000, function(){
                                cont_div.html('');
                            });
                            break;

                        case CMSUtil.ANIMATION_TYPE_FADE:
                            form.fadeOut( 1000, function(){
                                cont_div.html('');
                            });
                            break;

                        default:
                            form.fadeOut( 1000, function(){
                                cont_div.html('');
                            });
                            break;
                    }
                }
                
                cancel_callback();

            } catch(err) {
                throw 'Error in : CMSUtil - create form - iframePostForm - reset : [' + err +']';
            }
        });



        if( prepare_func != null ){
            prepare_func( cont_div );
        }

        if( post_func != null ){
            post_func( post_args );
        }

        //console.log('After: create form inner');

    } catch(err) {
        throw 'Error in : CMSUtil - create form inner : [' + err +']';
    }

};

CMSUtil.execute_function_by_name = function(function_name, context, args) {

    try{

        //var args = [].slice.call(arguments).splice(2);

        var namespaces = function_name.split(".");

        var func = namespaces.pop();

        for(var i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }

        return context[func].apply(context, args);

    } catch(err) {
        throw 'Error in : CMSUtil - execute function by name : [' + err +']';
    }
};

CMSUtil.show_list                = function (main_div, labels, fields, array, id_label, edit_func, delete_func, view_func){

    try{

        var editLabel   = CDictionary.get_text('Edit_lbl');
        var deleteLabel = CDictionary.get_text('Delete_lbl');
        var viewLabel   = CDictionary.get_text('View_lbl');

        var editFuncLabel   = CDictionary.get_text('EditFunc_lbl');
        var deleteFuncLabel = CDictionary.get_text('DeleteFunc_lbl');
        var viewFuncLabel   = CDictionary.get_text('ViewFunc_lbl');

        var span_col = 0;

        var list_html = '<table class="list-table" cellspacing="0" cellpadding="0" border="1">'
                        + '<tr class="head">';

            for(var i=0; i<labels.length; i++){
                 list_html += '<td>'+labels[i]+'</td>';
            }

            if( edit_func != null ){
                span_col += 1;
                list_html += '<td class="ctrl">'+editLabel+'</td>';
            }

            if( delete_func != null ){
                span_col += 1;
                list_html += '<td class="ctrl">'+deleteLabel+'</td>';
            }

            if( view_func != null ){
                span_col += 1;
                list_html += '<td class="ctrl">'+viewLabel+'</td>';
            }

             list_html += '</tr>';

        for(var i=0; i<array.length; i++){

            list_html += '<tr>';

            var item = array[i];

            for(var j=0; j<fields.length; j++){

                var item_label = fields[j];

                if( item_label.indexOf("(") >= 0 ){

                    var func = item_label.substring(0, item_label.indexOf("(")); 
                    var lbl  = item_label.substring(item_label.indexOf("(")+1, item_label.indexOf(")")); 

                    var vars = lbl.split(',');

                    for (var a=0; a<vars.length; a++){

                        vars[a] = item[ vars[a].trim() ];

                    }

                    //var label = (window[func]).apply(null, vars);
                    //var label = (window.func).apply(null, vars);

                    var label = CMSUtil.execute_function_by_name(func, window, vars);

                    //var label = window[func]( item[lbl] );

                    list_html += '<td>'+label+'</td>';

                }else{

                    list_html += '<td>'+item[item_label]+'</td>';

                }

            }

            if( edit_func != null ){
                list_html += '<td class="ctrl"><a href="#" onclick="'+ edit_func   +'('+item[id_label]+'); return false;">'+editFuncLabel+'</a></td>';
            }
            if( delete_func != null ){
                list_html += '<td class="ctrl"><a href="#" onclick="'+ delete_func +'('+item[id_label]+'); return false;">'+deleteFuncLabel+'</a></td>';
            }
            if( view_func != null ){
                list_html += '<td class="ctrl"><a href="#" onclick="'+ view_func   +'('+item[id_label]+'); return false;">'+viewFuncLabel+'</a></td>';
            }

            list_html += '</tr>'
                    + '<tr>'
                    +    '<td colspan="'+(fields.length+span_col)+'" class="form_cell">'
                    +       '<div id="form_cell_'+item[id_label]+'"></div>'
                    +    '</td>'
                    + '</tr>';

        }

        list_html += '</table>';

        //alert(labels.length);

        main_div.html(list_html);

    } catch(err) {
        throw 'Error in : CMSUtil - show list : [' + err +']';
    }
};

CMSUtil.show_list_with_checkbox  = function (main_div, labels, fields, array, id_label){

    try{

        var list_html = '<table class="list-table" cellspacing="0" cellpadding="0" border="1">'
                        + '<tr class="head">'
                            + '<td>Select</td>';

            for(var i=0; i<labels.length; i++){
                 list_html += '<td>'+labels[i]+'</td>';
            }

             list_html += '</tr>';

        for(var i=0; i<array.length; i++){

            var item = array[i];

            list_html += '<tr>'
                            + '<td><input type="checkbox" name="list_check_'+item[id_label]+'" value="1" /></td>';

            for(var j=0; j<fields.length; j++){

                var item_label = fields[j];

                if( item_label.indexOf("(") >= 0 ){

                    var func = item_label.substring(0, item_label.indexOf("(")); 
                    var lbl  = item_label.substring(item_label.indexOf("(")+1, item_label.indexOf(")")); 

                    //var label = window[func]( item[lbl] );
                    var label = window.func( item[lbl] );

                    list_html += '<td>'+label+'</td>';

                }else{

                    list_html += '<td>'+item[item_label]+'</td>';

                }

            }

            list_html += '</tr>';

        }

        list_html += '</table>';


        main_div.html(list_html);

    } catch(err) {
        throw 'Error in : CMSUtil - show list check box : [' + err +']';
    }
};

CMSUtil.show_pagination          = function (main_div, link_function, params, result_count, index, count, group_count){

    try{

        //alert( link_function+", "+result_count+", "+index+", "+count+", "+group_count );

        var start = parseInt( index / group_count ) * group_count;

        var output = '';
        output += '<div id="pagination" class="pagination">';

            output += '<div id="links" class="links">';

                if(start >= group_count){
                    //output += '<a data-start="'+(start-group_count)+'" href="javascript:'+link_function+'('+ (start-group_count)+', '+count+');"> &laquo; </a>';
                    output += '<a data-start="'+(start-group_count)+'" href="#"> &laquo; </a>';
                }

                //if(index > 0){
                //    output += '<a href="javascript:'+link_function+'('+ (index-count)+');">' + get_dictionary_text('Back_lbl'') + '</a>';
                //}

                for(var i=start; i<result_count && i<start+group_count; i+=count ){
                    output += (index==i)?'<strong>':'';
                    output += '<a data-start="'+ i +'" href="#"> '+(parseInt(i/count)+1)+' </a>';
                    //output += '<a data-start="'+ i +'" href="javascript:'+link_function+'('+ i +', '+count+');"> '+(parseInt(i/count)+1)+' </a>';
                    output += (index==i)?'</strong>':'';
                }

                //if( index+count < result_count){
                //    output += '<a href="javascript:'+link_function+'('+ (index+count)+', '+count+');">' + get_dictionary_text('Next_lbl'') + '</a>';
                //}

                if( start+group_count <= result_count){
                    output += '<a data-start="'+(start+group_count)+'" href="#"> &raquo; </a>';
                    //output += '<a data-start="'+(start+group_count)+'" href="javascript:'+link_function+'('+ (start+group_count)+', '+count+');"> &raquo; </a>';
                }

            output += '</div>';

            var results_label = CDictionary.get_text('Results_lbl');// 'Results';

            output += '<div class="text"> &nbsp; ' + result_count + ' ' + results_label + '</div>';

        output += '</div>';

        main_div.append(output);
        
        main_div.find(".links").find("a").click(function(){

            try{

                var start = $(this).data("start");
                
                var new_params = params;

                new_params = ( new_params == null ) ? [] : new_params;

                new_params.push(start);
                new_params.push(count);

                CMSUtil.execute_function_by_name(link_function, window, new_params);
                
            } catch(err) {
                throw 'Error in : CMSUtil - show pagination - a click : [' + err +']';
            }
        });

    } catch(err) {
        throw 'Error in : CMSUtil - show pagination : [' + err +']';
    }

};
