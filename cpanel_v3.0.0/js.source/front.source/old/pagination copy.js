
/*! pagination */

$(document).ready(function() {

    create_pagination();

});

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

function get_popup_html(content) {
    var popupHtml = '<div id="overlay"><div id="popup"><div id="close"></div><div id="content">' + content + '</div><div id="back"></div><div id="next"></div></div></div>';
    return popupHtml;
};

////////////////////////////////////////////////////////////////////////////////