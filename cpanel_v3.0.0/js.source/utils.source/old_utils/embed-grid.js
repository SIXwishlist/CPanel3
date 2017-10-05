
/*
     Arak for Information Technology
 */

var gGridWidth = 0;
var gGridContainerWidth = 0;

var gClickLocked = false;
var gGridInitiated = false;

$(document).ready(function (){

/*******************************************************************************/

    init_grid();

/*******************************************************************************/

    //$("#Grid").swipeleft(function() {
    //    next_screen();
    //});
    //
    //$("#Grid").swiperight(function() {
    //    prev_screen();
    //});
    
    $("#Grid").swipe( {
        //Generic swipe handler for all directions
        swipe:function(event, direction, distance, duration, fingerCount) {
          //$(this).text("You swiped " + direction );  
            if( direction == "left" ) {
                next_screen();
            }else{
                prev_screen();
            }
        },
        //Default is 75px, set to 0 for demo so any distance triggers swipe
         threshold:0
      });
    
    $("#LSide").find("#Arrow").click(function(){
        prev_screen();
        return false;
    });

    $("#RSide").find("#Arrow").click(function(){
        next_screen();
        return false;
    });

/*******************************************************************************/

    $("#Grid").find(".video").find(".title").hide();

    $("#Grid").find(".video").each( function(i){

        $(this).mouseenter(function(){
            //console.log('mouseover action');
            $(this).find(".title").slideToggle(700);
        });

        $(this).mouseleave(function(){
            //console.log('mouseout action');
            $(this).find(".title").slideToggle(700);
        });

        $(this).click(function(){

            open_embed_popup( $(this) );

        });
    });

/*******************************************************************************/

    check_file_popup();

});

$(window).resize(function() {
    //console.log( $(window).width() );
    
    if( gGridInitiated ){
        update_grid();
    }

});

function check_file_popup(){
    
    var url  = window.location+"";  
    var hash = window.location.hash+"";
    
    if( url.indexOf('#') > -1 ){       
        var hash = url.substring( url.indexOf('#')+1 ); // '#foo'
        //alert(hash);

        if( hash.indexOf(':') > -1 ){

            var items = hash.split(':');
            var embed_id = items[0];

            //alert(embed_id);
            
            var embed_div = $("#Grid").find("#embed_"+embed_id);
            //embed_5054
            open_embed_popup( embed_div );

        }
    }

    
}
function init_grid(){

    var grid = $('body').find("#Grid").clone()

    $('body').find("#Grid").replaceWith('<div id="VideoGrid"></div>');

    var container = '<div id="LSide"><div id="Arrow"/></div><div id="Center"><div id="GridContainer"/></div><div id="RSide"><div id="Arrow"/></div>'

    $('body').find("#VideoGrid").append( container );

    $('body').find("#VideoGrid").find("#GridContainer").append( grid );

    gGridInitiated = true;

    update_grid();
}

function open_embed_popup(embed_div){

    var type   = parseInt( embed_div.data("type") );
    var file   = embed_div.data("file");
    var folder = embed_div.data("folder");

    var width  = embed_div.data("width");
    var height = embed_div.data("height");

    var winWidth = $(window).width();

    if(winWidth>800){ width *= 2; height *= 1.5; }

    var content   = get_embed_output( file, type, width, height, folder, true );
    var popupHtml = get_popup_html(content);

    $('body').append( popupHtml );

    $('body').find("#overlay").height( $(document).height() );
    window.scrollTo(0, 25);

    $('body').find("#overlay").find("#close").click( function () {
        $('body').find("#overlay").remove();
    });

}

function next_screen(){

    var gridWidth          = $("#Grid").width();
    var gridContainerWidth = $("#GridContainer").width();

    var videoWidth = $("#Grid").find(".video").width();
    
    var shift     = gridContainerWidth - ( gridContainerWidth % videoWidth );

    var oldLeft   = $("#Grid").position().left;
    var newLeft   = oldLeft - shift;

    var borderPos = gridContainerWidth - gridWidth;

    if( newLeft <= borderPos ){
        newLeft = borderPos;
    }

    $("#Grid").animate( {left: newLeft}, 700, "easeOutQuart" );

}

function prev_screen(){

    var gridWidth          = $("#Grid").width();
    var gridContainerWidth = $("#GridContainer").width();

    var videoWidth = $("#Grid").find(".video").width();
    
    var shift     = gridContainerWidth - ( gridContainerWidth % videoWidth );

    var oldLeft   = $("#Grid").position().left;
    var newLeft   = oldLeft + shift;

    var borderPos = gridContainerWidth - gridWidth;

    if( newLeft > 0 ){
        newLeft = 0;
    }

    $("#Grid").animate( {left: newLeft}, 700, "easeOutQuart" );

}


function update_grid(){

    $("#Grid").animate( {left: 0}, 700, "easeOutQuart" );
    
    var videoWidth  = 210; //.video width  + left margin
    var videoHeight = 216; //.video height + top margin
    
    var video = $("#Grid").find(".video");

    if( video.length > 0 ){

        //console.log( video.width());
        //console.log( parseInt( video.css('margin-left') ) );
        //console.log( video.height());
        //console.log( parseInt( video.css('margin-top') ) );
        
        videoHeight  = parseInt( video.height() );
        videoHeight += parseInt( video.css('margin-top') );
        
        videoWidth  = parseInt( video.width() );
        videoWidth += parseInt( video.css('margin-left') );
    }
    

    var videosLength = video.length;
    var winHeight    = $(window).height();
    
    var gridHeight   = winHeight - winHeight%videoHeight;
    
    $("#VideoGrid").height(gridHeight);
    //$("#Grid").height(gridHeight);
    
    //console.log('winHeight  : '+ winHeight);
    //console.log('gridHeight : '+ gridHeight);
    
    var videoPerCol = parseInt( gridHeight / videoHeight );

    var numCols   = parseInt( videosLength / videoPerCol );
    
    var gridContainerWidth = $("#GridContainer").width();

    //gridContainerWidth = gridContainerWidth - ( gridContainerWidth % videoWidth );
    //$("#GridContainer").width( gridContainerWidth );
    //console.log('gridContainerWidth : '+gridContainerWidth);
    //console.log('$("#GridContainer").width() = ' + $("#GridContainer").width() );

    var gridWidth = numCols * videoWidth;
    
    if( gridWidth < gridContainerWidth ){
        gridWidth = gridContainerWidth;
    }

    $("#Grid").width( gridWidth );

    gGridContainerWidth = gridContainerWidth;
    gGridWidth          = gridWidth;

    //console.log( 'Videos Length : '+videosLength );
    //console.log( 'Grid Height   : '+gridHeight   );
    //console.log( 'Video Height  : '+videoHeight  );
    //console.log( 'Video Per Col : '+videoPerCol  );
    //console.log( 'Num of Cols   : '+numCols      );
    //console.log( 'Grid Width    : '+gridWidth    );
    //console.log( 'Grid Container Width : '+gridContainerWidth );

}

function get_popup_html(content){

    var popupHtml = '<div id="overlay"><div id="popup"><div id="close"></div><div id="content">'+content+'</div></div></div>';

    return popupHtml;
}

function lock_click(e){
    gClickLocked = true;
}

function unlock_click(e){
    gClickLocked = false;
}

function locked(){
    return gClickLocked;
}
