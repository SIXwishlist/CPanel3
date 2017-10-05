 

var is_locked = false;

$(document).ready(function(){
    
    if( $("#gallery").length > 0 ){
        init_gallery();
    }

});

function init_gallery(){

    var gallery = $("#gallery");
    
    var median = parseInt( gallery.find(".image").length / 2 );

    gallery.find(".image").each(function (index) {
        //console.log( $(this) );
        //$(this).data("index", index);
        $(this).attr("data-index", index);
        //console.log( $(this).data("index") );

        if( index == median ){
            $(this).addClass("active");
        }
    });

    gallery.find(".image").click(function() {

        gallery.find(".image").removeClass("active");

        $(this).addClass("active");

        reorder_divs();

    });

    gallery.find("#prev").click(function() {
        prev_div();
    });

    gallery.find("#next").click(function() {
        next_div();
    });

    reorder_divs();

}

function prev_div(){

    var gallery   = $("#gallery");

    var index     = gallery.find(".active").data("index");
    
    var max_index = gallery.find(".image").length - 1;

    index--;
    
    index = ( index >= 0 ) ? index : max_index;

    gallery.find(".image").removeClass("active");

    var image = gallery.find(".image[data-index="+index+"]");
    image.addClass("active");

    reorder_divs();
}

function next_div(){
    
    var gallery   = $("#gallery");

    var index     = gallery.find(".active").data("index");
    
    var max_index = gallery.find(".image").length - 1;

    index++;
    
    index = ( index <= max_index ) ? index : 0;

    gallery.find(".image").removeClass("active");

    var image = gallery.find(".image[data-index="+index+"]");
    image.addClass("active");

    reorder_divs();
}

function reorder_divs(){

    if( is_locked ){ return false; }

    is_locked = true; 

    var gallery = $("#gallery");

    var w   = gallery.width();
    var lng = gallery.find(".image").length;

    var d_w = 240;
    var d_h = 180;

    var top = 30;
    var left = (w / 2 - d_w / 2);

    var p_shift = 30;

    var s_fct = 0.9;
    var p_fct = p_shift;

    var z_index = lng;

    var count = 0;

    var c_div = null;

    //console.log("active div");

    c_div = $(".active");

    var a_index = c_div.data("index");

    c_div.css("z-index", z_index);

    c_div.animate({
        top: top,
        left: left,
        height: d_h,
        width: d_w
    }, 1000);
    
    c_div.css("right", "auto");

    c_div.css("z-index", z_index);

    z_index -= 1;

    //console.log( a_index );

    //console.log( top );
    //console.log( left );

    //console.log("left divs");

    for (var i = a_index - 1; i >= 0; i--) {

        c_div = gallery.find('.image:eq(' + i + ')');

        //console.log( c_div.data("index") );

        c_div.animate({
            top: (top + p_fct * 0.35),
            left: (left - p_fct),
            height: (d_h * s_fct),
            width: (d_w * s_fct)
        }, 1000);

        c_div.css("right", "auto");

        s_fct -= 0.1;
        p_fct += p_shift;

        c_div.css("z-index", z_index);
        c_div.show();

        z_index -= 1;

        if( count > 3 ){
            c_div.hide();
        }

        count++;

    }

    s_fct = 0.9;
    p_fct = p_shift;

    count = 0;

    //console.log("right divs");

    for (var j = a_index + 1; j < lng; j++) {

        c_div = gallery.find('.image:eq(' + j + ')');
        
        //console.log( c_div.data("index") );

        c_div.animate({
            top: (top + p_fct * 0.35),
            right: (left - p_fct),
            height: (d_h * s_fct),
            width: (d_w * s_fct)
        }, 1000);
        
        c_div.css("left", "auto");

        s_fct -= 0.1;
        p_fct += p_shift;

        c_div.css("z-index", z_index);
        c_div.show();

        z_index -= 1;
        
        if( count > 3 ){
            c_div.hide();
        }
        
        count++;
    }

    $(".image").unbind( "click", div_action );
    
    $(".active").click(div_action);

    is_locked = false; 
}

function div_action(event){
    
    //alert( 'done' );
    
    var div = $( event.target ).parent();

    var href = div.data("href");

    navigate_to_url(href);

    //console.log( div           );
    //console.log( div.html()    );
    //console.log( event.target  );
    //console.log( $(event.target).html() );
    //console.log( event.data  );
    //console.log( $(event.data).html()   );

    //alert( href );
}