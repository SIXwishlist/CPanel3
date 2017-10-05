
////////////////////////////////////////////////////////////////////////////////
/// responsive-table ///
////////////////////////////////////////////////////////////////////////////////


$(document).ready(function () {
    on_resize();
});

$(window).resize(function() {

    on_resize();

}).trigger("resize");
        
function on_resize(){
    
    var w  = $(window).width();
    //var dw = $(document).width();

    //console.log('window   width = '+w);
    //console.log('document width = '+dw);
    
    if ( w <= 480 ) {

        //console.log('handheld style ');
        
        //td:nth-of-type(1):before { content: "First Name"; }
        //td:nth-of-type(2):before { content: "Last Name"; }
        //td:nth-of-type(3):before { content: "Job Title"; }
        
        var style_html  = '';
        
        $('#MainSide').find('table').each( function(i){

            var table_id = "reponsive_table_"+(i+1);

            $(this).attr("id", table_id);
            
            if( $(this).find('thead').length > 0 ){
                $(this).removeClass().addClass('thd');
            }
            
            //$(this).find('thead > th').each( function(i, l){
            $(this).find('thead').find('th').each( function(i, l){

                var th_val = $.trim( $(this).text() );

                //var psedo = (lang == "ar") ?  "after" : "before";
                style_html += '#'+table_id+'.thd td:nth-of-type('+(i+1)+'):before { content: "'+th_val+':"; }\n';

                //alert( "Index #" + i + ": " + l );
            });
            
        });

        
        $('html > head').find('style.responsive').remove();
        
        var style = $('<style class="responsive">'+style_html+'</style>');
        $('html > head').append(style);

    }else{
        $('html > head').find('style.responsive').remove();
    }

}

/*
$('#thetable tr').find('td:nth-child(1),th:nth-child(1)').toggle();

td {
    padding-left: 50%;
}

/*
td:before {
    /* Now like a table header * /
    position: absolute;
    /* Top/left values mimic padding * /
    top: 6px;
    left: 6px;
    width: 45% !important;
    height: auto !important;
    padding-right: 10px;
    white-space: nowrap;
}

Label the data
td:nth-of-type(1):before { content: "First Name"; }
td:nth-of-type(2):before { content: "Last Name"; }
td:nth-of-type(3):before { content: "Job Title"; }

*/