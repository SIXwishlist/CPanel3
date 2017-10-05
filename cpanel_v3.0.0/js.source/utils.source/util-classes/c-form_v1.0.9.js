
/*! CForm */
/* Based on: [jQuery, CDictionary, RequestUtil] */
/* global CDictionary, Cover, CPopup, RequestUtil, CEditorPopup, Utils */

/******************************************************************************/

function CForm(options) {
    
    this.name   = options.name;
    this.params = options.params;
   
    this.action  = options.action;
    this.method  = options.method;
    this.enctype = options.enctype;

    this.style   = options.style;

    this.style   = ( this.style == null ) ? "" : this.style;
    
    this.heading_class = "label";
    this.data_class    = "value";
    

    //this.cprototype = cprototype;
        
    this.get_form_from_tpl = function (tpl_path) {

        var form_div = $( '<div></div>' );

        try{

            var __context = this;

            if( tpl_path != null ){

                $.ajax({
                    type:  'POST',
                    url:   tpl_path,
                    async: false,
                    success: function(data) {
                        form_div.append(data);
                        __context.custom_settings(form_div);
                    }
                });

            }

        }catch(err){
            throw 'CForm - Error in - get form div from tpl :['+err+']';
        }

        return form_div;

    };

    this.get_form_div  = function () {
        
        var form_div = null;

        try{

            var form_html = '';

            var hidden_element_arr  = [];
            var hidden_element_html = '';
           

            form_html += '<form id="'+this.name+'_form" class="'+this.style+'" action="'+this.action+'" method="'+this.method+'" enctype="'+this.enctype+'">';

            for(var i=0; i<this.params.length; i++){

                var obj  = this.params[i];

                if( obj.type != "hidden" ){
                    form_html += this.get_form_cell( obj );
                }else{
                    hidden_element_arr.push( obj );
                }

            }

            for(var i=0; i<hidden_element_arr.length; i++){

                hidden_element_html += this.get_form_element( hidden_element_arr[i] );

            }

            form_html +=    hidden_element_html + 

                            '<div class="clearfix"></div>' + 

                            '<input type="submit" name="form_submit" value="'+ CDictionary.get_text('Submit_lbl') + '" />' +
                            '<input type="reset"  name="form_reset"  value="'+ CDictionary.get_text('Reset_lbl') + '"  />' +

                            '<br /><br />' +

                         '</form>';



            form_div = $( form_html );
            
            this.custom_settings(form_div);
            
            this.hide_fields_for_form(form_div);

        }catch(err){
            throw 'CForm - Error in - get form div :['+err+']';
        }
        
        return form_div;

    };

    this.get_form_cell = function (object) {

        var div_html = '';

        try{
            //console.log( 'object.type : '+object.type );

            switch( object.type ){

                case "checkbox":
                case "radio":
                case "clear":
                case "custom":
                case "separator":
                case "hidden":
                    break;

                default:
                    div_html  = '<div class="'+this.heading_class+'">'+object.label+'</div>';
                    break;
            }
                    
            div_html  += this.get_form_element(object);

            if( object.grid != null ){
                div_html = '<div class="'+object.grid+'">' + div_html + '</div>';
            }

        }catch(err){
            throw 'CForm - Error in - get form cell :['+err+']';
        }
        
        return div_html;

    };

    this.get_form_element = function (object) {
        
        var div_html = '';
        
        try{

            switch( object.type ){

                case "custom":
                    div_html = object.value;
                    break;

                case "separator":
                    div_html = '<div class="separator"></div>';
                    break;

                case "clear":
                    div_html = '<div class="clearfix"></div>';
                    break;

                case "textarea":
                    div_html = '<textarea name="'+object.name+'" cols="45" rows="5"></textarea>';
                    break;

                case "editor":
                    div_html = '<a     class="open_editor" name="'+object.name+'" href="#open">Open Editor</a><br />' +
                               '<div   class="editor"      name="'+object.name+'"></div>'      +
                               '<input type="hidden"       name="'+object.name+'" value="" />';
                    break;

                case "color":
                    div_html = '<div   class="color_picker" name="'+object.name+'"></div>' +
                               '<input type="hidden"        name="'+object.name+'" value="" />';
                    break;

                case "select":
                    div_html = '<select name="'+object.name+'"></select>';
                    break;

                case "div":
                    div_html = '<div name="'+object.name+'"></div>';
                    break;

                case "image":                    
                    div_html = '<div class="image-upload" data-name="'+object.name+'">' + 
                                    '<div class="preview"></div>' + 
                                    '<input type="file"   name="'+object.name+'"  value="'+((object.value === undefined)?'':object.value)+'">' + 
                                    '<input type="button" value="Upload Image">' + 
                                '</div>';
                    break;

                case "file":
                case "upload":                    
                    div_html = '<div class="file-upload" data-name="'+object.name+'">' + 
                                    '<input type="text"   name="'+object.name+'_text" value="'+((object.value === undefined)?'':object.value)+'" />' +
                                    '<input type="button" value="Select File">' + 
                                    '<input type="file"   name="'+object.name+'"      value="'+((object.value === undefined)?'':object.value)+'">' + 
                                '</div>';
                    break;

                case "datetime" :
                case "date"     :
                case "time"     :
                    div_html = '<input type="text" data-type="'+object.type+'" data-format="'+object.format+'" name="'+object.name+'" value="'+((object.value === undefined)?'':object.value)+'" />';
                    break;


                case "checkbox" :
                    div_html = '<input id="'+object.name+'_checkbox_'+object.postfix+'" name="'+object.name+'" value="'+((object.value === undefined)?'':object.value)+'" type="checkbox" />' +
                               '<label for="'+object.name+'_checkbox_'+object.postfix+'">'+object.label+'</label>';
                    break;

                case "radio"    :
                    div_html = '<input id="'+object.name+'_radio_'+object.postfix+'" name="'+object.name+'" value="'+((object.value === undefined)?'':object.value)+'" type="radio" />' +
                               '<label for="'+object.name+'_radio_'+object.postfix+'">'+object.label+'</label>';
                    break;

                case "slider"    :
                    div_html = '<input type="text" data-type="slider" name="'+object.name+'" value="" />' +
                               '<div class="slider" data-name="'+object.name+'" name="'+object.name+'" data-step="'+object.step+'" data-min="'+object.min+'" data-max="'+object.max+'"></div>';
                    break;

                case "text"     :
                case "password" :
                case "hidden"   :
                    div_html = '<input type="'+object.type+'" name="'+object.name+'" value="'+((object.value === undefined)?'':object.value)+'" />';
                    break;

            }

        }catch(err){
            throw 'CForm - Error in - get form element :['+err+']';
        }
        
        return div_html;

    };
    
    this.custom_settings  = function(form){

        //alert('custom_settings!');
        
        try{

            __cms_form_apply_custom_settings__(form);

        }catch(err){
            throw 'CForm - Error in - other settings :['+err+']';
        }

    };


    this.get_preview_div  = function () {
        
        var preview_div = null;
        
        try{

            var preview_html = '';


            preview_html  = '<div class="preview">';

            for(var i=0; i<this.params.length; i++){

                preview_html += this.get_preview_cell( this.params[i] );

            }

            preview_html +=     '<div class="row"></div>' + 
                            '</div>';


            preview_div = $( preview_html );
            
            this.hide_fields_for_preview(preview_div);

        }catch(err){
            throw 'CForm - Error in - get preview div :['+err+']';
        }
        
        return preview_div;

    };

    this.get_preview_cell = function (object) {
        
        var div_html = '';
        
        try{

            if( object.ignore_preview ){
                return "";
            }

            div_html  = '<div class="row">' + 
                            '<div class="label">'       + object.label + '</div>' + 
                            '<div class="value" name="' + object.name  + '"></div>' + 
                        '</div>';

        }catch(err){
            throw 'CForm - Error in - get preview cell :['+err+']';
        }
        
        return div_html;

    };

    this.hide_fields_for_form = function(form_div){

        try{
            
            var langs = CForm.hidden_langs;

            if( langs.length == 0 ) return;
        
            for( var i=0; i<langs.length; i++ ){

                var lng = langs[i];

                form_div.find('input[name$="'+lng+'"]').parent().parent().hide();
                form_div.find('textarea[name$="'+lng+'"]').parent().parent().hide();
                form_div.find('div[name$="'+lng+'"]').parent().parent().hide();

            }

        }catch(err){
            throw 'CForm - Error in - hide fields for form :['+err+']';
        }

    };

    this.hide_fields_for_preview = function(preview_div){

        try{

            var langs = CForm.hidden_langs;

            if( langs.length == 0 ) return;
        
            for( var i=0; i<langs.length; i++ ){

                var lng = langs[i];

                //preview_div.find('input[name$="'+lng+'"]').parents('.row').hide();
                preview_div.find('input[name$="'+lng+'"]').parent().hide();
                preview_div.find('textarea[name$="'+lng+'"]').parent().hide();
                preview_div.find('div[name$="'+lng+'"]').parent().hide();

            }

        }catch(err){
            throw 'CForm - Error in - hide fields for preview :['+err+']';
        }
    };

}

function __cms_form_apply_custom_settings__(form) {


    //alert('apply custom settings!');

    try{

        //date, time settings

        var elem   = null;

        elem   = form.find( 'input[data-type=date]' );

        if( elem.length > 0 ) {

            var format = elem.data("format");

            elem.datepicker({
                dateFormat: format
            });
        }


        elem   = form.find( 'input[data-type=time]' );

        if( elem.length > 0 ) {

            var format = elem.data("format");

            elem.timepicker({
                dateFormat: format
            });
        }


        elem    = form.find( 'input[data-type=datetime]' );

        if( elem.length > 0 ) {
            
            var format = elem.data("format");

            var arr = format.split(" ");

            var dFormat = arr[0];
            var tFormat = arr[1];

            //var dFormat = elem.data("date-format");
            //var tFormat = elem.data("time-format");

            elem.datetimepicker({
                dateFormat: dFormat, 
                timeFormat: tFormat
            });
        }


        //Image Upload Settings
        elem = form.find(".image-upload");

        if( elem.length > 0 ){
            
            elem.find("input[type=button]").click(function(){
                //alert('clicked!');
                $(this).parent().find("input[type=file]").click();
            });

            elem.find("input[type=file]").change(function (){
                //alert('changed!');
                var display_div = $(this).parent().find(".preview");

                RequestUtil.read_image_input( this, display_div );//send this not $(this)

            });

        }

        //File Upload Settings
        elem = form.find(".file-upload");

        if( elem.length > 0 ){

            elem.find("input[type=button]").click(function(){
                //alert('clicked!');
                //$(this).parents(".file-upload").find("input[type=file]").click();
                $(this).parent().find("input[type=file]").click();
                //form.find(".file-upload").find("input[type=file]").click();
            });

            elem.find("input[type=file]").change(function (e){
                //alert('changed!');
                //$(this).parents(".file-upload").find("input[type=text]").val( $(this).val() );
                $(this).parent().find("input[type=text]").val( $(this).val() );
                //form.find(".file-upload").find("input[type=text]").val( $(this).val() );
                //alert( $(this).val() );
            });
        }
        
        //Editor Settings
        elem = form.find( '.open_editor' );
        elem.click( function() {
            try{
                var textElement = $(this).attr("name");
                CEditorPopup.open_editor(form, textElement);
            }catch(err){
                throw 'CForm - Error in - open editor :['+err+']';
            }
        });
        
        //Color Input
        elem = form.find( '.color_picker' );

        elem.spectrum({
            color: "#efefef",
            preferredFormat: "hex",
            showInput: true,
            change: function (color) {
                //alert('changed');

                var hexColor = color.toHexString(); // #ff0000
                $(this).css("background", hexColor);

                var textElement = $(this).attr("name");
                form.find('input[name='+textElement+']').val( hexColor);
            }
        });
        
        //slider Input
        elem = form.find( '.slider' );
        if( elem.length > 0 ){

            var min  = Utils.get_float( elem.data("min") );
            var max  = Utils.get_float( elem.data("max") );
            var step = Utils.get_float( elem.data("step") );

            elem.slider({

                min:  min,
                max:  max,
                step: step,

                change: function( event, ui ) {

                    var name = $(ui.handle).parent().data("name");
                    
                    form.find('input[name='+name+']').val( ui.value );
                    
                    //ui.handle
                    //The jQuery object representing the handle that was changed.

                    //ui.handleIndex
                    //The numeric index of the handle that was moved.

                    //ui.value
                    //The current value of the slider.
                }
            });
            
            
        }

    }catch(err){
        throw 'CForm - Error in - apply custom settings :['+err+']';
    }

}

CForm.hidden_langs   = [];
CForm.hide_languages = function(langs){

    try{

        CForm.hidden_langs = langs;

    }catch(err){
        throw 'CForm - Error in - hide languages :['+err+']';
    }

};

CForm.apply_custom_settings = function (form) {

    try{

        __cms_form_apply_custom_settings__(form);

    }catch(err){
        throw 'CForm - Error in - apply custom settings :['+err+']';
    }

};

CForm.get_form_div_from_tpl = function (tpl_path) {

    var form_div = $( '<div></div>' );

    try{

        var __context = CForm;

        if( tpl_path != null ){

            $.ajax({
                type:  'POST',
                url:   tpl_path,
                async: false,
                success: function(data) {
                    form_div.append(data);
                    __context.apply_custom_settings(form_div);
                }
            });

        }

    }catch(err){
        throw 'CForm - Error in - get form div from tpl :['+err+']';
    }

    return form_div;

};

//var form_div = new CForm({}).get_form_from_tpl(  g_root_url+"mvc/views/tpl/js/forms/import_form.tpl" );
//var form_div = CForm.get_form_div_from_tpl(  g_root_url+"mvc/views/tpl/js/forms/import_form.tpl" );

CForm.use_upload_button = function (form_div, types, multiple){

    try {
    
        //form_div.find('input[type=file]').each(function( index, element ) {
        form_div.find('input[data-file=upload-button]').each(function( index, element ) {

            //console.log( index + ": " + $( this ).text() );
            var name = $(this).attr("name");
            
            var extra_code = '';

            //$(element).each(function () {
            //    $.each(this.attributes, function () {
            //        // this.attributes is not a plain object, but an array
            //        // of attribute nodes, which contain both the name and value
            //        if (this.specified) {
            //            console.log(this.name, this.value);
            //            extra_code += this.name+'="'+this.value+'"';
            //        }
            //    });
            //});
            
            var multi_upload = '';
            var file_types   = '';
            
            if( multiple != null && multiple != false ){
                //multi_upload = multiple;
                extra_code += 'multiple="true"';
            }
            
            if( types != null && types.length > 0 ){
                file_types = types.join(',');
                extra_code += 'accept="'   +file_types    +'"';
            }


            //var multiple = $(this).attr("multiple");
            //var accept   = $(this).attr("accept");

            //extra_code += ( multiple ) ? 'multiple="' +multiple  +'"' : '';
            //extra_code += ( accept   ) ? 'accept="'   +accept    +'"' : '';

            //var name = element.attr("name");

            $(this).replaceWith(
              '<div class="file-upload" data-name="'+name+'">' + 
                  '<input name="'+name+'" value="" type="file" '+extra_code+' />' + 
                  '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>' + 
                  '<span>Choose a file…</span>' + 
              '</div>'
            );

        });

        var file_types = '';
        
        if( types != null && types.length > 0 ){
            file_types = types.join(',');
        }

        form_div.find('input[data-file=upload]').attr("accept", file_types);

        var elem = form_div.find(".file-upload");

        elem.find('span,svg').click(function (e){
          //alert('clicked!');
          $(this).parent().find("input[type=file]").click();
        });

        elem.find("input[type=file]").change(function (e){
            
            var label = '';
            //var uploadedFiles = e.files;
            var uploadedFiles = e.target.files;
            
            if( uploadedFiles.length > 1 ){
                label = uploadedFiles.length + ' files selected';
            }else{
                //label = $(this).val();
                label = uploadedFiles[0].name;
            }

            //alert('changed!');//alert( $(this).val() );
            $(this).parent().find("span").html( label );
        });

    }catch(err){
        throw 'CForm - Error in - use upload button :['+err+']';
    }

};

/******************************************************************************/

function FileType(){}
    
FileType.image_types      = ['image/png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/bmp'];

FileType.sound_types      = ['audio/mp3', 'audio/ogg'];//mp3,oga
FileType.video_types      = ['video/mp4', 'video/webm', 'video/ogg'];

FileType.compressed_types = [ 'application/zip', 'application/x-compressed-zip', 'application/x-rar', 'application/x-rar-compressed', 'application/octet-stream' ];

FileType.document_types   = [ 'application/pdf', 'application/msword', 'application/vnd.ms-excel',
                         'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

FileType.get_all_media_types = function () {

    var types = [];

    try{

        types = $.merge( [], this.image_types, this.sound_types, this.video_types, this.compressed_types, this.document_types );

    }catch(err){
        throw 'FileType - Error in - get all media types :['+err+']';
    }

    return types;

};

/******************************************************************************/

function add_print_button(preview_div, id, label){
    
    $(preview_div).append( '<a href="#print" onclick="print_school('+id+')"> < '+CDictionary.get_text('Print_lbl')+' > </a>' );

    $(preview_div).wrap('<div class="#view_'+label+'_'+id+'" />');

}


/******************************************************************************/