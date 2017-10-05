
/*! Cover */

/*#############################################################################*/

function Cover() {
}

Cover.LOADING_STYLE1 = '<div id="loading-container"><i class="fa fa-spin fa-refresh     fa-3x fa-fw"></i></div>';

Cover.LOADING_STYLE2 = '<div id="loading_list" class="loading_mid"></div>';

Cover.add = function (block, content, style) {

    //alert( 'cover' );

    try {

        block.find("#cover").remove();

        style = (style == null) ? "" : style;

        block.append('<div id="cover" class="' + style + '"></div>');

        var cover = block.find("#cover");

        cover.height( block.outerHeight() );
        cover.width( block.outerWidth() );

        cover.css("text-aligh", "center");

        cover.html(content);

        //cover.css("width", block.css("width"));
        //cover.css("height", block.css("height"));

        $('html, body').animate({scrollTop: cover.css("top")}, 'slow');

    } catch(err) {
        throw 'Cover - Error in - add [' + err +']';
    }

};

Cover.remove = function (block) {

    //alert( 'cover' );

    try {

        block.find("#cover").hide().remove();

    } catch(err) {
        throw 'Cover - Error in - remove [' + err +']';
    }

};
