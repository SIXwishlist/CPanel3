
/*! files */

var g_embeds_parent = null;
var g_shows_parent  = null;

$(document).ready(function() {

    g_embeds_parent = $("#MainSide");
    g_shows_parent  = $("#MainSide");


    check_embed_popup();
    
    check_file_elem();


    init_embeds();

    init_shows();


    create_pagination();

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

function get_popup_html(content) {
    var popupHtml = '<div id="overlay"><div id="popup"><div id="close"></div><div id="content">' + content + '</div><div id="back"></div><div id="next"></div></div></div>';
    return popupHtml;
};

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

function init_shows() {

    g_shows_parent.find(".shows").find(".show").each(function(i) {

        var show_div = $(this);

        show_div.css("cursor", "pointer");

        show_div.click(function() {
            open_show_popup($(this));
        });

    });
}

function open_show_popup(show_div) {

    $('body').find("#overlay").remove();

    var type   = parseInt(show_div.data("type"));
    var file   = show_div.data("file");
    var folder = show_div.data("folder");
    var width  = show_div.data("width");
    var height = show_div.data("height");
    var index  = show_div.data("index");

    var shows_count = g_shows_parent.find(".shows").find(".show").length;

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
            var show_div = g_shows_parent.find(".shows").find(".show[data-index=" + new_index + "]");
            open_show_popup(show_div);
        });
        $('body').find("#overlay").find("#next").click(function() {
            var new_index = index + 1;
            new_index = (new_index > max_index) ? 0 : new_index;
            var show_div = g_shows_parent.find(".shows").find(".show[data-index=" + new_index + "]");
            open_show_popup(show_div);
        });
    } else {
        $('body').find("#overlay").find("#back").hide();
        $('body').find("#overlay").find("#next").hide();
    }
}

////////////////////////////////////////////////////////////////////////////////

function create_pagination(){
    
    $('body').find(".pages").each( function(i){

        var parent_div = $(this);
        var parent_id  = $(parent_div).data("parent");
        var count      = $(parent_div).data("count");
        var index      = $(parent_div).data("index");
        var elem       = $(parent_div).data("elem");
        
        //parent_div.find("."+elem+":nth-child(2)").append( "<span> - 2nd!</span>" );

        var childs = parent_div.find("."+elem);
        //var childs = parent_div.find(".category");

        var page_index = 0;
        for(var i=0; i<childs.length; i+=count) {
            childs.slice(i, i+count).wrapAll('<div class="page" data-index="'+page_index+'" data-active="0">');
            page_index++;
        }

        $(parent_div).find(".page").hide();
        $(parent_div).find(".page[data-index=0]").data("active", "1").show();
        
        update_pagination_nav(parent_id);

    });

}

function open_page(index, parent_id){

    var parent_div = $('body').find(".pages[data-parent="+parent_id+"]");

    var pagination_index = ((index-index%10)/10);

    $(parent_div).data("index", pagination_index);
    
    //$(parent_div).find(".page").data("active", 0).hide();
    $(parent_div).find(".page").data("active", 0);

    $(parent_div).find(".page").hide();

    //$(parent_div).find(".page[data-index="+index+"]").data("active", 1)
    $(parent_div).find(".page[data-index="+index+"]").data("active", 1).fadeIn(300);//.show();

    update_pagination_nav(parent_id);

}

function update_pagination_nav(parent_id){

    var parent_div = $('body').find(".pages[data-parent="+parent_id+"]");

    var index      = $(parent_div).data("index");

    //alert( 'pagination index : ' + index );

    var page_count = $(parent_div).find(".page").length;

    var pagination_output = '';
    var new_index = 0;

    if(index>0){
        new_index = (index*10)-10;
        pagination_output += '<a href="javascript:open_page('+new_index+', '+parent_id+')"> << </a>';
    }

    for(var i=index*10; i<(page_count) && i<((index*10)+10); i++){
        pagination_output += '<a href="javascript:open_page('+i+', '+parent_id+')">'+(i+1)+'</a>';
    }

    if( (page_count/10) > (index+1) ){
        new_index = (index*10)+10;
        pagination_output += '<a href="javascript:open_page('+new_index+', '+parent_id+')"> >> </a>';
    }


    var pagination_div = $('body').find("#pagination[data-parent="+parent_id+"]");

    if( pagination_div.length == 0 ){
        $(parent_div).after( '<div id="pagination" data-parent="'+parent_id+'" class="clearfix">' + pagination_output + '</div>' );
    }else{
        $(pagination_div).html( pagination_output );
    }
}

////////////////////////////////////////////////////////////////////////////////