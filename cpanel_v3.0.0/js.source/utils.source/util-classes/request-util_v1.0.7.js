
/*! RequestUtil */
/* Based on: [CDictionary, Cover, CPopup] */
/* global CDictionary, Cover, CPopup */

function RequestUtil() {
}

//RequestUtil.ROUTE_URL = g_root_url+"ajax.php";
RequestUtil.ROUTE_URL = "./ajax.php";

RequestUtil.LOADING_FORM_DIV = '<div class="loading_form"></div>';//<i class="fa fa-spin fa-refresh fa-3x fa-fw"></i>
RequestUtil.LOADING_LIST_DIV = '<div class="loading_list"><i class="fa fa-spin fa-refresh fa-3x fa-fw"></i></div>';

RequestUtil.quick_sync_request = function(data, dataType, method){

    var output = '';

    try{

        var seqId = Math.floor(Math.random()*1000);

        if( typeof(data) == "object" ){
            data.seqId = seqId;
        }else{
            data += "&seqId="+seqId;
        }

        method   = ( method   == null ) ? "GET"  : method;
        dataType = ( dataType == null ) ? "json" : dataType;

        var html_output = $.ajax({
              url:      RequestUtil.ROUTE_URL,
              global:   false,
              type:     method,
              data:     data,
              dataType: dataType,
              async:    false
           }
        ).responseText;

        if( dataType == "json" ){
            output = $.parseJSON( html_output );
        }
    
    } catch(err) {
        throw 'RequestUtil - Error in : quick sync request : [' + err +']';
    } finally {
    }

    return output;
};

RequestUtil.quick_ajax_request = function(main_div, data, complete_func){

    var html_output = '';

    try{

        var seqId = Math.floor(Math.random()*1000);

        if( typeof(data) == "object" ){
            data.seqId = seqId;
        }else{
            data += "&seqId="+seqId;
        }

        RequestUtil.ajax_request(main_div, RequestUtil.ROUTE_URL, data, complete_func, "GET", "json", true);
    
    } catch(err) {
        throw 'RequestUtil - Error in : quick ajax request : [' + err +']';
    } finally {
    }

    return html_output;
};

RequestUtil.quick_post_request = function(main_div, data, complete_func){

    var html_output = '';

    try{

        var seqId = Math.floor(Math.random()*1000);

        if( typeof(data) == "object" ){
            data.seqId = seqId;
        }else{
            data += "&seqId="+seqId;
        }

        RequestUtil.ajax_request(main_div, RequestUtil.ROUTE_URL, data, complete_func, "POST", "json", true);
    
    } catch(err) {
        throw 'RequestUtil - Error in : quick post request : [' + err +']';
    } finally {
    }

    return html_output;
};


RequestUtil.ajax_sync_request  = function(server_url, data, dataType, method){

    var output = '';

    try{

        var seqId = Math.floor(Math.random()*1000);

        if( typeof(data) == "object" ){
            data.seqId = seqId;
        }else{
            data += "&seqId="+seqId;
        }

        method   = ( method   == null ) ? "GET"  : method;
        dataType = ( dataType == null ) ? "json" : dataType;

        var html_output = $.ajax({
              url:      server_url,
              global:   false,
              type:     method,
              data:     data,
              dataType: dataType,
              async:    false
           }
        ).responseText;

        if( dataType == "json" ){
            output = $.parseJSON( html_output );
        }
    
    } catch(err) {
        throw 'RequestUtil - Error in : quick sync request : [' + err +']';
    } finally {
    }

    return output;
};

RequestUtil.ajax_request       = function(main_div, server_url, data, complete_func, method, dataType, async, error_func){

    var html_output = '';

    try{

        if( main_div != null && main_div.length > 0 ){
            main_div.append('<div class="loading_list"><i class="fa fa-spin fa-refresh fa-3x fa-fw"></i></div>');
        }

        method   = ( method   == null ) ? "GET"  : method;
        dataType = ( dataType == null ) ? "json" : dataType;
        async    = ( async    == null ) ? true   : async;

        html_output = $.ajax({
              url:      server_url,
              global:   false,
              type:     method,
              data:     data,
              dataType: dataType,
              async:    async,
              success:  function (output) {
                  
                  //console.log(output);
                  
                  if( main_div != null && main_div.length > 0 ){
                      main_div.find(".loading_list").hide().remove();
                  }

                  if( complete_func != null ){
                      complete_func(output);
                  }
              },
              error:    function (request, status, error) {

                  //console.error(request.status);
                  //console.error(status);
                  //console.error(error);

                  console.error('RequestUtil - Error in : load ajax request for ['+server_url+'] with data ['+data+'] : ['+request.status+'] [' + error +']');
                  
                  if( main_div != null && main_div.length > 0 ){
                      main_div.find(".loading_list").hide().remove();
                  }
                  
                  
                  if( error_func != null ){
                      error_func(request, status, error);
                  }

                  //throw 'RequestUtil - Error in : ajax request : [' + error +']';
              }
           }
        ).responseText;
    
    } catch(err) {
        throw 'RequestUtil - Error in : ajax request : [' + err +']';
    } finally {
    }

    return html_output;
};


RequestUtil.read_image         = function(file, display_div, width, height) {
    
    try{
        
        if (file) {

            var reader = new FileReader();

            reader.onload = function (e) {

                //alert(e.target.result);
                
                width  = ( width  == null ) ? 'auto' : width;
                height = ( height == null ) ? 'auto' : height;

                display_div.html('<img src="' + e.target.result + '" width="'+width+'" height="'+height+'" />');

            };

            reader.readAsDataURL(file);

        }

    } catch(err) {
        throw 'RequestUtil - Error in - read image dropped : [' + err +']';
    }
};

RequestUtil.read_image_input   = function(input, display_div) {
    
    try{
        
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {

                //alert(e.target.result);

                display_div.html('<img src="' + e.target.result + '" />');

            };

            reader.readAsDataURL(input.files[0]);

        }

    } catch(err) {
        throw 'RequestUtil - Error in - read image : [' + err +']';
    }
};


RequestUtil.init_post_form     = function (form_div, callback, jsonOutputFormat){

    try{

        jsonOutputFormat = (jsonOutputFormat == null) ? true : jsonOutputFormat;

        //$(function () {
            form_div.iframePostForm({
                json : jsonOutputFormat,
                post : function () {

                    try{
                        //alert("sending request");
                        form_div.addClass('is-posting');
                        form_div.append('<div class="loading_form"></div>');
                    
                    } catch(err) {
                        console.error('RequestUtil - Error in - init post form - post : [' + err +']');
                        throw 'RequestUtil - Error in - init post form - post : [' + err +']';
                    }
                },
                complete : function (jsonOutput) {
                    
                    try{
                        //alert("complete request");
                        form_div.find('.loading_form').hide().remove();
                        form_div.removeClass('is-posting');

                        if( !jsonOutputFormat ){
                            jsonOutput = $.evalJSON( jsonOutput );
                        }

                        callback(jsonOutput);

                    } catch(err) {
                        console.error('RequestUtil - Error in - init post form - complete : [' + err +']');
                        throw 'RequestUtil - Error in - init post form - complete : [' + err +']';
                    }
                    
                }
            });
        //});

    } catch(err) {
        throw 'RequestUtil - Error in - init post form : [' + err +']';
    }
};


RequestUtil.image_exists       = function ( image_url ){

    try{
        var http = new XMLHttpRequest();

        http.open('HEAD', image_url, false);
        http.send();

        return ( http.status != 404 );

    } catch(err) {
        throw 'RequestUtil - Error in - image exists : [' + err +']';
    }

    return -1;
};

RequestUtil.get_parameter      = function (name) {
    
    try{

        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);

        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));

    } catch(err) {
        throw 'RequestUtil - Error in - get parameter : [' + err +']';
    }

    return null;
};
