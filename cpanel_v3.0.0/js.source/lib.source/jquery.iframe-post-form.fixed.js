
/*! iframePostForm - fixed */
/**
 * jQuery plugin for posting form including file inputs.
 * 
 * Copyright (c) 2010 - 2011 Ewen Elder
 *
 * Licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * @author: Ewen Elder <ewen at jainaewen dot com> <glomainn at yahoo dot co dot uk>
 * @version: 1.1.1 (2011-07-29)
**/
(function ($){

    try{

        $.fn.iframePostForm = function (options){

            try{

                var response,
                    returnReponse,
                    element,
                    status = true,
                    iframe;

                options = $.extend({}, $.fn.iframePostForm.defaults, options);

                // Add the iframe.
                if (!$('#' + options.iframeID).length){
                    $('body').append('<iframe id="' + options.iframeID + '" name="' + options.iframeID + '" style="display:none" />');
                }

                return $(this).each(function () {

                    try{

                        element = $(this);

                        // Target the iframe.
                        element.attr('target', options.iframeID);

                        // Submit listener.
                        element.submit(function (){

                            try{

                                // If status is false then abort.
                                status = options.post.apply(this);

                                if (status === false){
                                    return status;
                                }

                                iframe = $('#' + options.iframeID).load(function (){

                                    try{

                                        response = iframe.contents().find('body');

                                        if( response.find('pre').length > 0 ){
                                            response = response.find('pre');
                                        }

                                        if (options.json){
                                            returnReponse = $.parseJSON(response.html());
                                        } else {
                                            returnReponse = response.html();
                                        }

                                        options.complete.apply(this, [returnReponse]);

                                        iframe.unbind('load');

                                        setTimeout(function (){
                                            response.html('');
                                        }, 1);

                                    }catch(err){
                                        console.error('iframePostForm - Error in - iframe load :['+err+']');
                                        throw 'iframePostForm - Error in - iframe load :['+err+']';
                                    }

                                });

                            }catch(err){
                                console.error('iframePostForm - Error in - submit :['+err+']');
                                throw 'iframePostForm - Error in - submit :['+err+']';
                            }
                        });

                    }catch(err){
                        console.error('iframePostForm - Error in - each :['+err+']');
                        throw 'iframePostForm - Error in - each :['+err+']';
                    }
                });

            }catch(err){
                console.error('iframePostForm - Error in - constructor :['+err+']');
                throw 'iframePostForm - Error in - constructor :['+err+']';
            }
        };

        $.fn.iframePostForm.defaults = {
            iframeID : 'iframe-post-form',       // Iframe ID.
            json : false,                        // Parse server response as a json object.
            post : function () {},               // Form onsubmit.
            complete : function (response) {}    // After response from the server has been received.
        };    

    }catch(err){
        console.error('iframePostForm - Error in - main :['+err+']');
        throw 'iframePostForm - Error in - main :['+err+']';
    }

})(jQuery);