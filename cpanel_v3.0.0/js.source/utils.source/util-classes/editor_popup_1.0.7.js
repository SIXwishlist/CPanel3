
/*! CEditorPopup */
/* Based on: [CEditorPopup, CMSUtil] */
/* global g_template_url, CEditorPopup, CMSUtil, RequestUtil, CPopup, CKEDITOR */

function CEditorPopup() {
}

CEditorPopup.EDITOR_POPUP_SHOWN = "editor_popup_shown";

CEditorPopup.editorName = '';

CEditorPopup.ROUTE_URL  = './template.php';
CEditorPopup.CLASS_NAME = 'editor_popup_tpl';

//CEditorPopup.POPUP_TPL_PATH = g_root_url+"mvc/views/tpl/js/popup/popup.tpl";
//CEditorPopup.POPUP_TPL_PATH = g_template_url + "?tpl=popup";

CEditorPopup.div_loaded = false;
CEditorPopup.div_html   = '';


CEditorPopup.init         = function () {

    try{

        this.div_html = this.get_div_tpl();
        
        if( this.div_html != null && this.div_html != undefined && this.div_html != "" ){
            this.div_loaded = true;
        }

    } catch(err) {
        throw 'Error in : CEditorPopup - init : [' + err +']';
    }
};

CEditorPopup.get_div_tpl  = function () {

    var div_html = '';

    try{

        var class_name = CEditorPopup.CLASS_NAME;

        div_html = $('body > #hidden').find('.'+class_name).html();

        //div_html = $('body > #hidden').find('.editor_popup_tpl').html();

    } catch(err) {
        throw 'Error in : CEditorPopup - ajax get sync request : [' + err +']';
    }

    return div_html;
};

CEditorPopup.load_div_tpl  = function () {

    var div_html = '';

    try{

        var tpl_path = CEditorPopup.ROUTE_URL + "?tpl=editor_popup";

        div_html = $.ajax({
              url:      tpl_path,
              global:   false,
              type:     "GET",
              data:     null,
              dataType: "html",
              async:    false,
              success: function(msg){
                //alert(msg);
              }
           }
        ).responseText;

    } catch(err) {
        throw 'Error in : CEditorPopup - ajax get sync request : [' + err +']';
    }
    
    return div_html;
};




CEditorPopup.open_editor         = function(parentForm, elementName) {
    
    try{

        this.create_editor_popup();

        this._setup_editor_(parentForm, elementName);

    } catch(err) {
        console.error('Error in : CEditorPopup - in open editor [' + err +']');
    }

};

CEditorPopup.create_editor_popup = function(){

    try{

        var editor_html = '';

        if( ! this.div_loaded ){
            
            this.init();
            
            editor_html = this.div_html;

        }else{

            editor_html = this.div_html;

        }

        var content = $('<div></div').append( editor_html ).html();//;$(this).html();

        CPopup.display(content, 'Editor Popup');

    } catch(err) {
        throw 'Error in : CEditorPopup - in create editor popup [' + err +']';
    }
};

CEditorPopup._setup_editor_      = function(parentForm, elementName){

    try{

        var editorData  = '';
        
        var element       = parentForm.find('div[name='+elementName+']');
        var hiddenElement = parentForm.find('input[name='+elementName+']');
        
        if( element != null ){
            editorData = element.html();
        }


        var popup = CPopup.get_object();

        var editor_popup = popup.find("#EditorPopup");

        editor_popup.attr("qid", "editor_popup" );


        var random  = Math.floor(Math.random()*1000);
        var newName = "editor-"+random;
        editor_popup.find("#editor").attr("id", "editor-"+random);


        var cEditorName = this.editorName;

        if ( CKEDITOR.instances[cEditorName] ) {
            CKEDITOR.remove( CKEDITOR.instances[cEditorName] );//Does the same as line below
            //delete CKEDITOR.instances[cEditorName];
            //alert('deleted')
        }

        editor_popup.find("#save").css("cursor","pointer");
        editor_popup.find("#save").click(function (){

            //var editorData = CKEDITOR.instances.editorName.getData();
            var editorData = editor.getData();

            if(element!=null){
                element.html(editorData);
                hiddenElement.val(editorData);
                //console.log( element.html() );
                //console.log( element.attr("class") );
                //console.log( element.attr("name") );
            }

            CPopup.close();
        });

        editor_popup.find("#cancel").css("cursor","pointer");
        editor_popup.find("#cancel").click(function (){
            CPopup.close();
        });

        //console.log('editor_popup.find(#'+newName+').length : '+editor_popup.find('#'+newName).length);
        //console.log('CKEDITOR : '+CKEDITOR);

        if( editor_popup.find('#'+newName).length > 0 && CKEDITOR != undefined ){

            var editor = CKEDITOR.appendTo( newName );//'editor' );
            this.editorName = editor.name;

            //console.log(this.editorName);

            editor.setData(editorData);
            //editor.insertHtml('</p>editor data</p>');
            //editor.insertHtml(editorData);
        }

    } catch(err) {
        throw 'Error in : CEditorPopup - in setup editor [' + err +']';
    } finally {
        //$('body').unbind(this.EDITOR_POPUP_SHOWN);
    }

};
