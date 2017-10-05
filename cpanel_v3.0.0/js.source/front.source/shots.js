
/*! shots */

/* global DisplayUtil, Utils, CPopup */

$(document).ready(function() {
    
    ShotsView.init();

});

////////////////////////////////////////////////////////////////////////////////

function ShotsView(){}

ShotsView.init      = function(){
    
    try{

        var shots_div = $("body").find(".shots");

        if( shots_div.length <= 0 ) { return; }

        shots_div.find(".shot").each(function(i) {

            var shot_div = $(this);

            shot_div.css("cursor", "pointer");

            shot_div.click(function() {
                
                shots_div.find('.shot').removeClass('active');

                shot_div.addClass('active');
                
                ShotsView.open_shot( $(this) );

            });

        });
    
        ShotsView.open_shot( shots_div.find(".shot:first-child") );

        ShotsView.shots_div = shots_div;
        ShotsView.max_index = shots_div.find('.shot').length - 1;

    } catch(err) {
        console.error('Error in : ShotsView - init [' + err +']');
    }
};

ShotsView.open_shot = function(shot_div) {

    try{
        
        //var shots_div = ShotsView.shots_div;

        var image = $('body').find("#product-info").find(".image");

        var height = shot_div.data("height");
        var width  = shot_div.data("width");
        var folder = shot_div.data("folder");
        var file   = shot_div.data("file");
        var type   = shot_div.data("type");
        var index  = shot_div.data("index");

        image.data( "height", height );
        image.data( "width",  width  );
        image.data( "file",   file   );
        image.data( "folder", folder );
        image.data( "type",   type   );
        image.data( "index",  index  );

        var content = DisplayUtil.get_embed_output(file, type, width, height, folder, true);

        ShotsView.index = index;

        image.fadeOut().html( content ).fadeIn(300);

        image.css("cursor", "pointer");

        image.click(function() {

            var index     = ShotsView.index;
            var max       = ShotsView.max_index;

            //var next_func = ( max > 1 ) ? ShotsView.next : null;
            //var prev_func = ( max > 1 ) ? ShotsView.prev : null;

            var next_func = ( index < max ) ? ShotsView.next : null;
            var prev_func = ( index > 0   ) ? ShotsView.prev : null;
            
            CPopup.display(content, '', 'flat', next_func, prev_func);
            //ShotsView.open_shot_popup(image);
        });

    } catch(err) {
        console.error('Error in : ShotsView - open shot [' + err +']');
    }
};

ShotsView.next      = function() {

    try{
        
        console.log('ShotsView - next ');

        var shots_div  = ShotsView.shots_div;
        var index      = ShotsView.index;
        var max_index  = ShotsView.max_index;

        var new_index = index - 1;

        new_index = (new_index < 0) ? max_index : new_index;

        var shot_div = shots_div.find(".shot[data-index=" + new_index + "]");

        ShotsView.open_shot(shot_div);

    } catch(err) {
        console.error('Error in : ShotsView - next [' + err +']');
    }
};

ShotsView.prev      = function() {

    try{

        console.log('ShotsView - prev ');
        
        var shots_div  = ShotsView.shots_div;
        var index      = ShotsView.index;
        var max_index  = ShotsView.max_index;

        var new_index = index + 1;

        new_index = (new_index > max_index) ? 0 : new_index;

        var shot_div = shots_div.find(".shot[data-index=" + new_index + "]");

        ShotsView.open_shot(shot_div);

    } catch(err) {
        console.error('Error in : ShotsView - prev [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////