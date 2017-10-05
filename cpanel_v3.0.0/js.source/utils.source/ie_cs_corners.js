
/**
 * IE curved shadowed corners workaround
*/

//var xmlHttp = $.get(serverUrl, data, callback, type);
//type: "xml", "html", "script", "json", "jsonp", or "text".

$(document).ready(function(){

    //alert('curved corners js loaded');

    $('body').bind("convert_corners", convert_corners );

    //$('body').trigger("convert_corners", []);
    convert_corners(null);
});

function convert_corners(e){

     //alert( 'convert corners called' );

    if (  ! $.browser.msie || $.browser.version.substr(0,1) > 8  ) {
        //alert('NOT IE 6, 7, 8');
        return;
    }

    //alert('IE');

     var csList = $('body').find('div[cs_use=yes]');
     //alert( csList.length );

    $.each(csList, function(index, value) {

        //alert( index + ': ' + value );

        var cDiv = $( value );

        applyContainer( cDiv );

    });

}

function applyContainer(cDiv){

    if (  ! $.browser.msie || $.browser.version.substr(0,1) > 8  ) {
        return;
    }

    if( cDiv.find( ".container" ).length > 0 ){
        cDiv.find( ".container" ).remove();
    }

    //var csDone = cDiv.attr("cs_done");

    //if( csDone=="no" ){
        drawContainer( cDiv );
    //}
}

function drawContainer(cDiv){

    var bg = cDiv.attr("cs_bg");

    var dimension = get_intVal( cDiv.attr("cs_dimention") );

    var topShift    = get_intVal( cDiv.attr("cs_top_shift")    );
    var rightShift  = get_intVal( cDiv.attr("cs_right_shift")  );
    var bottomShift = get_intVal( cDiv.attr("cs_bottom_shift") );
    var leftShift   = get_intVal( cDiv.attr("cs_left_shift")   );

    var csTLDim = get_intVal( cDiv.attr("cs_tl_dim"), dimension );
    var csTCDim = get_intVal( cDiv.attr("cs_tc_dim"), dimension );
    var csTRDim = get_intVal( cDiv.attr("cs_tr_dim"), dimension );
    var csMLDim = get_intVal( cDiv.attr("cs_ml_dim"), dimension );
    var csMRDim = get_intVal( cDiv.attr("cs_mr_dim"), dimension );
    var csBLDim = get_intVal( cDiv.attr("cs_bl_dim"), dimension );
    var csBCDim = get_intVal( cDiv.attr("cs_bc_dim"), dimension );
    var csBRDim = get_intVal( cDiv.attr("cs_br_dim"), dimension );

    var dPadTop    = get_intVal( cDiv.css("padding-top")    );
    var dPadRight  = get_intVal( cDiv.css("padding-right")  );
    var dPadBottom = get_intVal( cDiv.css("padding-bottom") );
    var dPadLeft   = get_intVal( cDiv.css("padding-left")   );

    var divWidth  = get_intVal( cDiv.width()  );
    var divHeight = get_intVal( cDiv.height() );

    divWidth  = divWidth  + dPadLeft + dPadRight;
    divHeight = divHeight + dPadTop  + dPadBottom;

     var cornerDivs = '<div id="tl" class="container"></div>' +
                      '<div id="tc" class="container"></div>' +
                      '<div id="tr" class="container"></div>' +

                      '<div id="ml" class="container"></div>' +
                      '<div id="mr" class="container"></div>' +

                      '<div id="bl" class="container"></div>' +
                      '<div id="bc" class="container"></div>' +
                      '<div id="br" class="container"></div>';

    cDiv.css("position", "relative");
    cDiv.css("overflow", "visible");

    cDiv.prepend( cornerDivs );//cDiv.append( cornerDivs );

    var zIndex = get_intVal( cDiv.css("z-index"), 0 );

    var containerSel = cDiv.find( ".container" );

    containerSel.css( "display",  "block"    );
    containerSel.css( "position", "absolute" );
    containerSel.css( "z-index",  zIndex     );

    var tl = cDiv.find( "#tl" );
    tl.css("top",        (-topShift)+"px");
    tl.css("left",       (-leftShift)+"px");
    tl.css("width",      csTLDim+"px");
    tl.css("height",     csTLDim+"px");
    tl.css("background", "url(./images/containers/"+bg+"_tl.png) no-repeat");

    var tc = cDiv.find( "#tc" );
    tc.css("top",        (-topShift)+"px");
    tc.css("left",       (csTLDim-leftShift)+"px");
    tc.css("width",      (divWidth-(csTLDim-leftShift)-(csTRDim-rightShift))+"px");
    tc.css("height",     csTCDim+"px");
    tc.css("background", "url(./images/containers/"+bg+"_tc.png) repeat-x");

    var tr = cDiv.find( "#tr" );
    tr.css("top",        (-topShift)+"px");
    tr.css("left",       (divWidth-(csTRDim-rightShift))+"px");//
    tr.css("width",      csTRDim+"px");
    tr.css("height",     csTRDim+"px");
    tr.css("background", "url(./images/containers/"+bg+"_tr.png) no-repeat");


    var ml = cDiv.find( "#ml" );
    ml.css("top",        (csTLDim-topShift)+"px");
    ml.css("left",       (-leftShift)+"px");
    ml.css("width",      csMLDim+"px");
    ml.css("height",     (divHeight-(csTLDim-topShift)-(csBLDim-bottomShift))+"px");
    ml.css("background", "url(./images/containers/"+bg+"_ml.png) repeat-y");

    var mr = cDiv.find( "#mr" );
    mr.css("top",        (csTRDim-topShift)+"px");
    mr.css("left",       (divWidth-(csMRDim-rightShift))+"px");//
    mr.css("width",      csMRDim+"px");
    mr.css("height",     (divHeight-(csTRDim-topShift)-(csBRDim-bottomShift))+"px");
    mr.css("background", "url(./images/containers/"+bg+"_mr.png) repeat-y");


    var bl = cDiv.find( "#bl" );
    bl.css("top",        (divHeight-(csBLDim-topShift))+"px");
    bl.css("left",       (-leftShift)+"px");
    bl.css("width",      csBLDim+"px");
    bl.css("height",     csBLDim+"px");
    bl.css("background", "url(./images/containers/"+bg+"_bl.png) no-repeat");

    var bc = cDiv.find( "#bc" );
    bc.css("top",        (divHeight-(csBCDim-topShift))+"px");
    bc.css("left",       (csBLDim-leftShift)+"px");
    bc.css("width",      (divWidth-(csBLDim-leftShift)-(csBRDim-rightShift))+"px");
    bc.css("height",     csBCDim+"px");
    bc.css("background", "url(./images/containers/"+bg+"_bc.png) repeat-x");

    var br = cDiv.find( "#br" );
    br.css("top",        (divHeight-(csBRDim-topShift))+"px");
    br.css("left",       (divWidth-(csBRDim-rightShift))+"px");
    br.css("width",      csBRDim+"px");
    br.css("height",     csBRDim+"px");
    br.css("background", "url(./images/containers/"+bg+"_br.png) no-repeat");

    cDiv.attr("cs_done", "yes");
}


function get_intVal(stringVal, defaultVal){
    defaultVal = (defaultVal==null) ? 0 : defaultVal;
    var val = parseInt( stringVal );
    val = isNaN( val ) ? defaultVal : val;
    return val;
}