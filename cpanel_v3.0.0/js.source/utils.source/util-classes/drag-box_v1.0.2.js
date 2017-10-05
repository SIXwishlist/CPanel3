
/*! DragBox */
/* Based on: [CDictionary, Cover, CPopup] */
/* global CDictionary, Cover, CPopup, RequestUtil */

function DragBox() {
}

DragBox.options      = false;
DragBox.files        = false;
DragBox.drag_box_div = false;

DragBox.isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
};

//var isAdvancedUpload = function() {
//          var div = document.createElement('div');
//          return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
//        }();

DragBox.init_default_options = function(){
    
    var defaults  = {};

    try{

        defaults.label = CDictionary.get_text('AddProductForm_DragYourFilesHere_lbl');
        defaults.icon  = '<svg class="box_icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>';

    } catch(err) {
        throw 'DragBox - Error in - init defaults : [' + err +']';
    }
    
    return defaults;
};

DragBox.reset_drag_box = function(){
    
    try{

        var options = DragBox.options;
        
        if( ! options ){

            options = DragBox.init_default_options();
            
            DragBox.options = options;
        }

        var drag_box_html = options.label 
                          + '<br />'
                          + options.icon;

        DragBox.drag_box_div.html( drag_box_html );

        DragBox.files = false;

    } catch(err) {
        throw 'DragBox - Error in - reset drag box : [' + err +']';
    }
};

DragBox.init_drag_box  = function($form, $drag_box_div){
    
    try{

        var droppedFiles = false;
        
        var advancedUpload = DragBox.isAdvancedUpload();

        if (advancedUpload) {
            
            console.log( 'isAdvancedUpload : '+advancedUpload );

            $drag_box_div.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
            })
            .on('dragover dragenter', function() {
                console.log('dragover dragenter');
                $drag_box_div.addClass('is-dragover');
            })
            .on('dragleave dragend drop', function() {
                console.log('dragleave dragend drop');
                $drag_box_div.removeClass('is-dragover');
            })
            .on('drop', function(e) {

                console.log('droping...');

                droppedFiles = e.originalEvent.dataTransfer.files;

                for(var i=0;i<droppedFiles.length;i++){
                    droppedFiles[i].index = i;
                }

                DragBox.files = droppedFiles;

                $drag_box_div.html('');

                for(var i=0;i<droppedFiles.length;i++){

                    console.log('droppedFiles['+i+'] : '+droppedFiles[i]);

                    var $div = $('<div></div>');
                    var span = $('<span></span>');

                    RequestUtil.read_image( droppedFiles[i], span, "100px", "100px" );//send this not $(this)

                    $div.append(span);

                    $div.append('<i class="fa fa-times" aria-hidden="true"></i>');

                    $drag_box_div.append($div);
                }

                $drag_box_div.find('div').find('i').each( function (i, element){

                    //$(this).data("index", i);
                    $(this).attr("data-index", i);

                    console.log('index   : '+i);
                    console.log('element : '+element);
                });

                $drag_box_div.find('div').find('i').click( function(){

                    var index = $(this).data("index");

                    console.log('index : '+index);

                    //console.log('i       : '+i);
                    //console.log('element : '+$(this));

                    DragBox.remove_image(index);

                });

            });

        }


        $form.on( 'submit', function( e ) {

            var droppedFiles = DragBox.files;

            // preventing the duplicate submissions if the current one is in progress
            if ($form.hasClass('is-uploading'))
                return false;

            $form.addClass('is-uploading').removeClass('is-error');

            var advancedUpload = DragBox.isAdvancedUpload();

            // ajax file upload for modern browsers
            if (advancedUpload) {

                e.preventDefault();

                // gathering the form data
                var ajaxData = new FormData($form.get(0));

                if (droppedFiles) {
                    $.each(droppedFiles, function (i, file){
                        //$input = $form.find('input[type=file]');
                        $input = $form.find('.box_file');
                        ajaxData.append($input.attr('name'), file);
                    });
                }

                // ajax request
                $.ajax({
                    url:  $form.attr('action'),
                    type: $form.attr('method'),
                    data: ajaxData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (jsonOutput) {
                        //alert("complete request 22");

                        $form.find('.loading_big').hide().remove();
                        $form.removeClass('is-uploading');

                        //var jsonOutput = $.evalJSON( response );
                        add_product_form_callback(jsonOutput);
                    },
                    error: function () {
                        console.log('Error. Please, contact the webmaster!');
                    }
                });

            } else {

                // fallback Ajax solution upload for older browsers
                var iframeName = 'uploadiframe' + new Date().getTime();
                $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                $('body').append($iframe);

                $form.attr('target', iframeName);

                $iframe.one('load', function (){

                    var data = $.parseJSON($iframe.contents().find('body').text());
                    $form.removeClass('is-uploading').addClass(data.success == true ? 'is-success' : 'is-error').removeAttr('target');
                    if (!data.success)
                        console.log(data.error);
                        //$errorMsg.text(data.error);

                    $iframe.remove();
                });
            }
        });

        DragBox.form_div     = $form;
        DragBox.drag_box_div = $drag_box_div;

    } catch(err) {
        throw 'DragBox - Error in - init drag box : [' + err +']';
    }
};

DragBox.remove_image   = function (index){

    try{
        
        var newFiles     = [];
        var droppedFiles = DragBox.files;

        for( var i=0; i<droppedFiles.length; i++ ){

            if( index == droppedFiles[i].index ) continue;

            newFiles.push(droppedFiles[i]);

        }

        console.log('remove image at index ['+index+'] : '+droppedFiles[index]);

        DragBox.drag_box_div.find('div').find('i[data-index='+index+']').parent().hide().remove();

        if( newFiles.length == 0 ){
            DragBox.reset_drag_box();
        }
        
        DragBox.files = newFiles;

    } catch(err) {
        throw 'DragBox - Error in - read image dropped : [' + err +']';
    }
};