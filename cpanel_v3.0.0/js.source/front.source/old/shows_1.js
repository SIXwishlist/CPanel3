
/*! shows */

var g_shows_parent  = null;

$(document).ready(function() {

    g_shows_parent  = $("#MainSide");

    init_product_shows();

});

////////////////////////////////////////////////////////////////////////////////

function init_product_shows() {

    g_shows_parent.find("#shows").find(".show").each(function(i) {

        var show_div = $(this);

        show_div.css("cursor", "pointer");

        show_div.click(function() {
            open_show( $(this) );
        });

    });
    
    open_show( g_shows_parent.find("#shows").find(".show:first-child") );
    
}

function open_show(show_div) {

    var image = g_shows_parent.find("#product-info").find(".image");
    
    var height = show_div.data("height");
    var width  = show_div.data("width");
    var folder = show_div.data("folder");
    var file   = show_div.data("file");
    var type   = show_div.data("type");
    var index  = show_div.data("index");

    image.data( "height", height );
    image.data( "width",  width  );
    image.data( "file",   file   );
    image.data( "folder", folder );
    image.data( "type",   type   );
    image.data( "index",  index  );
    
    var content = get_embed_output(file, type, width, height, folder, true);
    
    image.fadeOut().html( content ).fadeIn(300);
    
    image.css("cursor", "pointer");
    
    image.click(function() {
        open_show_popup(image);
    });
}

function open_show_popup(image_div) {

    $('body').find("#overlay").remove();

    var type   = parseInt(image_div.data("type"));
    var file   = image_div.data("file");
    var folder = image_div.data("folder");
    var width  = image_div.data("width");
    var height = image_div.data("height");
    var index  = image_div.data("index");

    var shows_count = g_shows_parent.find("#shows").find(".show").length;

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

    if (shows_count > 1) {
        var max_index = shows_count - 1;
        $('body').find("#overlay").find("#back").click(function() {
            var new_index = index - 1;
            new_index = (new_index < 0) ? max_index : new_index;
            var show_div = g_shows_parent.find("#shows").find(".show[data-index=" + new_index + "]");
            open_show_popup(show_div);
        });
        $('body').find("#overlay").find("#next").click(function() {
            var new_index = index + 1;
            new_index = (new_index > max_index) ? 0 : new_index;
            var show_div = g_shows_parent.find("#shows").find(".show[data-index=" + new_index + "]");
            open_show_popup(show_div);
        });
    } else {
        $('body').find("#overlay").find("#back").hide();
        $('body').find("#overlay").find("#next").hide();
    }
}

////////////////////////////////////////////////////////////////////////////////
