/*
 *
 */

var gClip = null;

$(document).ready(function(){

    init_clip_board();

});



function init_clip_board() {

    gClip = new ZeroClipboard.Client();
    gClip.setHandCursor( true );

    gClip.addEventListener('load', function (client) {
        //debugstr("Flash movie loaded and ready.");
    });

    gClip.addEventListener('complete', function (client, text) {
        //debugstr("Copied text to clipboard: " + text );
    });
}

function apply_copy_clipborad_on( textId, buttonId, buttonContainerId ) {

    $('#'+textId ).change(function (){
        gClip.setText( $(this).val() );
    });

    gClip.addEventListener('mouseOver', function (client) {
        // update the text on mouse over
        gClip.setText(  $('#'+textId ).val()  );
    });

    gClip.glue( buttonId, buttonContainerId );

}
		