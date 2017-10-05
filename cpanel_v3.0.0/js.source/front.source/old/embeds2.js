
/*! embeds */

var g_embeds_parent = null;

$(document).ready(function() {

    g_embeds_parent = $("#MainSide");


    check_embed_popup();
    
    check_file_elem();


    init_embeds();

});

////////////////////////////////////////////////////////////////////////////////

function check_embed_popup() {
    var url = window.location + "";
    var hash = window.location.hash + "";
    if (url.indexOf('#') > -1) {
        var hash = url.substring(url.indexOf('#') + 1);
        if (hash.indexOf(':') > -1) {
            var items = hash.split(':');
            var embed_id = items[0];
            var embed_div = g_embeds_parent.find(".embeds").find("#embed_" + embed_id);
            open_embed_popup(embed_div);
        }
    }
}

function check_file_elem(){

    var embed_div = $("#MainSide").find(".embed_elem");

    if( embed_div.length > 0 ){
        create_embed_div(embed_div);
    }

}

////////////////////////////////////////////////////////////////////////////////

function create_embed_div(embed_div){

    var type   = parseInt( embed_div.data("type") );
    var file   = embed_div.data("file");
    var folder = embed_div.data("folder");

    var width  = embed_div.data("width");
    var height = embed_div.data("height");

    var winWidth = $(window).width();

    if(winWidth>800){ width *= 2; height *= 1.5; }

    var content   = get_embed_output( file, type, width, height, folder, true );

    //alert(content);

    $(embed_div).replaceWith( content );

}

////////////////////////////////////////////////////////////////////////////////

function init_embeds() {

    var winWidth = $(window).width();

    g_embeds_parent.find(".embeds").find(".embed").find(".title").hide();
    g_embeds_parent.find(".embeds").find(".embed").each(function(i) {

        var embed_div = $(this);

        embed_div.css("cursor", "pointer");

        embed_div.click(function() {
            open_embed_popup($(this));
            
            return false;
        });

    });
}

function open_embed_popup(embed_div) {

    $('body').find("#overlay").remove();

    var type   = parseInt(embed_div.data("type"));
    var file   = embed_div.data("file");
    var folder = embed_div.data("folder");
    var width  = embed_div.data("width");
    var height = embed_div.data("height");
    var index  = embed_div.data("index");

    var embeds_count = g_embeds_parent.find(".embeds").find(".embed").length;

    var winWidth = $(window).width();

    if (winWidth > 800) {
        width *= 2;
        height *= 1.5;
    }

    var content = get_embed_output(file, type, width, height, folder, true);

    var popupHtml = get_popup_html(content);
    $('body').append(popupHtml);

    $('body').find("#overlay").height($(document).height());
    //scroll_to_element()
    window.scrollTo(0, 25);

    $('body').find("#overlay").find("#close").click(function() {
        $('body').find("#overlay").remove();
    });

    if (embeds_count > 1) {
        var max_index = embeds_count - 1;
        $('body').find("#overlay").find("#back").click(function() {
            var new_index = index - 1;
            new_index = (new_index < 0) ? max_index : new_index;
            var embed_div = g_embeds_parent.find(".embeds").find(".embed[data-index=" + new_index + "]");
            open_embed_popup(embed_div);
        });
        $('body').find("#overlay").find("#next").click(function() {
            var new_index = index + 1;
            new_index = (new_index > max_index) ? 0 : new_index;
            var embed_div = g_embeds_parent.find(".embeds").find(".embed[data-index=" + new_index + "]");
            open_embed_popup(embed_div);
        });
    } else {
        $('body').find("#overlay").find("#back").hide();
        $('body').find("#overlay").find("#next").hide();
    }
};

////////////////////////////////////////////////////////////////////////////////