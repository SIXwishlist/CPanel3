
/*! embeds */

/* global DisplayUtil, Utils, CPopup */

$(document).ready(function() {
    
    EmbedsView.init();
    
    EmbedsView.check_embed_elem();

});

////////////////////////////////////////////////////////////////////////////////

function EmbedsView(){}

EmbedsView.init        = function(){
    
    try{

        var embeds_div = $("body").find(".embeds");

        if( embeds_div.length <= 0 ) { return; }

        embeds_div.find(".embed").each(function(i) {

            var embed_div = $(this);

            embed_div.css("cursor", "pointer");

            embed_div.click(function() {
                EmbedsView.open_embed( $(this) );
            });

        });
    
        //EmbedsView.open_embed( embeds_div.find(".embed:first-child") );

        EmbedsView.embeds_div = embeds_div;
        EmbedsView.max_index  = embeds_div.find('.embed').length - 1;
        
        EmbedsView.check_popup();

    } catch(err) {
        console.error('Error in : EmbedsView - init [' + err +']');
    }
};

EmbedsView.open_embed  = function(embed_div) {

    try{
        
        //var embeds_div = EmbedsView.embeds_div;

        var height = embed_div.data("height");
        var width  = embed_div.data("width");
        var folder = embed_div.data("folder");
        var file   = embed_div.data("file");
        var type   = embed_div.data("type");
        var index  = embed_div.data("index");

        var content = DisplayUtil.get_embed_output(file, type, width, height, folder, true);
        
        EmbedsView.index = index;
        
        var max       = EmbedsView.max_index;
        
        var next_func = ( index < max ) ? EmbedsView.next : null;
        var prev_func = ( index > 0   ) ? EmbedsView.prev : null;
        
        CPopup.display(content, '', 'flat', next_func, prev_func);
        //CPopup.display(content, '', 'flat', EmbedsView.next, EmbedsView.prev);

    } catch(err) {
        console.error('Error in : EmbedsView - open embed [' + err +']');
    }
};

EmbedsView.next        = function() {

    try{
        
        console.log('EmbedsView - next ');

        var embeds_div = EmbedsView.embeds_div;
        var index      = EmbedsView.index;
        var max_index  = EmbedsView.max_index;

        var new_index = index - 1;

        new_index = (new_index < 0) ? max_index : new_index;

        var embed_div = embeds_div.find(".embed[data-index=" + new_index + "]");

        EmbedsView.open_embed(embed_div);

    } catch(err) {
        console.error('Error in : EmbedsView - next [' + err +']');
    }
};

EmbedsView.prev        = function() {

    try{

        console.log('EmbedsView - prev ');
        
        var embeds_div = EmbedsView.embeds_div;
        var index      = EmbedsView.index;
        var max_index  = EmbedsView.max_index;

        var new_index = index + 1;

        new_index = (new_index > max_index) ? 0 : new_index;

        var embed_div = embeds_div.find(".embed[data-index=" + new_index + "]");

        EmbedsView.open_embed(embed_div);

    } catch(err) {
        console.error('Error in : EmbedsView - prev [' + err +']');
    }
};

EmbedsView.check_popup = function() {

    try{

        var embeds_div = EmbedsView.embeds_div;

        var url = window.location + "";
        var hash = window.location.hash + "";

        if (url.indexOf('#') > -1) {
            var hash = url.substring(url.indexOf('#') + 1);
            if (hash.indexOf(':') > -1) {
                var items = hash.split(':');
                var embed_id = items[0];
                var embed_div = embeds_div.find("#embed_" + embed_id);
                EmbedsView.open_embed(embed_div);
            }
        }

    } catch(err) {
        console.error('Error in : EmbedsView - check popup [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////

EmbedsView.check_embed_elem = function(){

    try{

        var embed_div = $('body').find(".embed_elem");

        if( embed_div.length > 0 ){
            EmbedsView.create_embed_div(embed_div);
        }

    } catch(err) {
        console.error('Error in : EmbedsView - check embed elem [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////

 EmbedsView.create_embed_div = function(embed_div){

    try{
        var type   = parseInt( embed_div.data("type") );
        var file   = embed_div.data("file");
        var folder = embed_div.data("folder");

        var width  = embed_div.data("width");
        var height = embed_div.data("height");

        var winWidth = $(window).width();

        if(winWidth>800){ width *= 2; height *= 1.5; }

        var content   = DisplayUtil.get_embed_output( file, type, width, height, folder, true );

        //alert(content);

        $(embed_div).replaceWith( content );

    } catch(err) {
        console.error('Error in : EmbedsView - create embed div [' + err +']');
    }
};
