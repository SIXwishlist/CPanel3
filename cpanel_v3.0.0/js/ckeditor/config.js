/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

//CKEDITOR.editorConfig = function( config ) {
//	// Define changes to default configuration here. For example:
//	// config.language = 'fr';
//	// config.uiColor = '#AADC6E';
//};

// ckeditor/config.js
CKEDITOR.editorConfig = function(config) {
   config.extraPlugins='video';
   config.extraAllowedContent = 'span video source[*]{*}(*);img[alt,border,width,height,align,vspace,hspace,!src];';
   //config.extraPlugins='MediaEmbed';
   CKEDITOR.config.allowedContent = true;

   config.filebrowserBrowseUrl      = g_root_url+'js/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = g_root_url+'js/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = g_root_url+'js/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl      = g_root_url+'js/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = g_root_url+'js/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = g_root_url+'js/kcfinder/upload.php?type=flash';
};

CKEDITOR.on('instanceReady', function(ev) {

    // Ends self closing tags the HTML4 way, like <br>.
    ev.editor.dataProcessor.htmlFilter.addRules({
        elements: {
            $: function(element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        // Get the float from the style.
                        match = /(?:^|\s)float\s*:\s*(\w+)/i.exec(style);
                        var float = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                        if (float) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)float\s*:\s*(\w+)/i, '');
                            element.attributes.align = float;
                        }

                    }
                }

                if (!element.attributes.style) delete element.attributes.style;

                return element;
            }
        }
    });
});

/*
CKEDITOR.editorConfig = function(config) {
   config.filebrowserBrowseUrl      = '../js/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '../js/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '../js/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl      = '../js/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '../js/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '../js/kcfinder/upload.php?type=flash';
};

CKEDITOR.editorConfig = function( config ) {
   config.filebrowserBrowseUrl      = '../js/ckfinder/ckfinder.html';
   config.filebrowserImageBrowseUrl = '../js/ckfinder/ckfinder.html?type=Images';
   config.filebrowserFlashBrowseUrl = '../js/ckfinder/ckfinder.html?type=Flash';
   config.filebrowserUploadUrl      = '../js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
   config.filebrowserImageUploadUrl = '../js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
   config.filebrowserFlashUploadUrl = '../js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};

CKEDITOR.editorConfig = function( config ) {
   config.filebrowserBrowseUrl      = '../ckfinder/ckfinder.html';
   config.filebrowserImageBrowseUrl = '../ckfinder/ckfinder.html?type=Images';
   config.filebrowserFlashBrowseUrl = '../ckfinder/ckfinder.html?type=Flash';
   config.filebrowserUploadUrl      = '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
   config.filebrowserImageUploadUrl = '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
   config.filebrowserFlashUploadUrl = '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
*/