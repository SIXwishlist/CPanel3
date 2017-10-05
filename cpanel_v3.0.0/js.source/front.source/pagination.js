
/*! pagination #new */

/* global Utils */

$(document).ready(function() {

    try{

        Pagination.init();

    } catch(err) {
        console.error('Error in : Pagination - document - ready : [' + err +']');
    }
});

////////////////////////////////////////////////////////////////////////////////

function Pagination(){}

Pagination.init       = function(){
    
    try{

        $('body').find(".pagination").each( function(e_index, element){

            var parent_div = $(this);
            
            var done = Utils.get_int( parent_div.data("done") );
            //var done = $(parent_div).data("done");

            if( done <= 0 ){
                
                var parent_id  = $(parent_div).data("parent");
                var count      = $(parent_div).data("count");
                var index      = $(parent_div).data("index");
                var elem       = $(parent_div).data("elem");

                //parent_div.find("."+elem+":nth-child(2)").append( "<span> - 2nd!</span>" );

                var childs = parent_div.find('[data-elem='+elem+']');
                //var childs = parent_div.find(".category");

                var page_index = 0;
                for(var i=0; i<childs.length; i+=count) {
                    childs.slice(i, i+count).wrapAll('<div class="page" data-index="'+page_index+'" data-active="0">');
                    page_index++;
                }

                $(parent_div).find(".page").hide();
                $(parent_div).find(".page[data-index=0]").data("active", "1").show();

                Pagination.update_nav(parent_id);

                //parent_div.attr("data-done", "1");
                parent_div.data("done", "1");
            }

        });

    } catch(err) {
        console.error('Error in : Pagination - init : [' + err +']');
    }
};

Pagination.open_page  = function(index, parent_id){

    try{

        var parent_div = $('body').find(".pagination[data-parent="+parent_id+"]");

        var pagination_index = ((index-index%10)/10);

        $(parent_div).data("index", pagination_index);

        //$(parent_div).find(".page").data("active", 0).hide();
        $(parent_div).find(".page").data("active", 0);

        $(parent_div).find(".page").hide();

        //$(parent_div).find(".page[data-index="+index+"]").data("active", 1)
        $(parent_div).find(".page[data-index="+index+"]").data("active", 1).fadeIn(300);//.show();

        Pagination.update_nav(parent_id);

    } catch(err) {
        console.error('Error in : Pagination - open page : [' + err +']');
    }

};

Pagination.update_nav = function(parent_id){

    try{

        var parent_div = $('body').find(".pagination[data-parent="+parent_id+"]");

        var index      = $(parent_div).data("index");

        //alert( 'pagination index : ' + index );

        var page_count = $(parent_div).find(".page").length;

        var pagination_output = '';
        var new_index = 0;

        if(index>0){
            new_index = (index*10)-10;
            pagination_output += '<a href="javascript:Pagination.open_page('+new_index+', '+parent_id+')"> << </a>';
        }

        for(var i=index*10; i<(page_count) && i<((index*10)+10); i++){
            pagination_output += '<a href="javascript:Pagination.open_page('+i+', '+parent_id+')">'+(i+1)+'</a>';
        }

        if( (page_count/10) > (index+1) ){
            new_index = (index*10)+10;
            pagination_output += '<a href="javascript:Pagination.open_page('+new_index+', '+parent_id+')"> >> </a>';
        }


        var pagination_div = $('body').find("#pagination[data-parent="+parent_id+"]");

        if( pagination_div.length == 0 ){
            $(parent_div).after( '<div id="pagination" data-parent="'+parent_id+'" class="clearfix">' + pagination_output + '</div>' );
        }else{
            $(pagination_div).html( pagination_output );
        }

    } catch(err) {
        console.error('Error in : Pagination - open page : [' + err +']');
    }

};

////////////////////////////////////////////////////////////////////////////////