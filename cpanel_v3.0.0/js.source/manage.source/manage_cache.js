
/*! cache */

/* global CMSUtil, RequestUtil */

function ManageCache(){}

ManageCache.rebuild_category_tree = function(){
    
    var data = "action=rebuild_category_tree";

    RequestUtil.quick_post_request(null, data, function (outputArray){

        try{

            var status = outputArray["status"];

            if( status > 0 ){
                CMSExtraUtil.show_success("Rebuild category tree [Success]", "");
            }else{
                CMSExtraUtil.show_error("Rebuild category tree [Failed]", "");
            }

        } catch (err) {
            console.log('error in loading products :[' + err + ']');
        }

    });

};

ManageCache.rebuild_section_tree  = function(){
    
    var data = "action=rebuild_section_tree";

    RequestUtil.quick_post_request(null, data, function (outputArray){

        try{

            var status = outputArray["status"];

            if( status > 0 ){
                CMSExtraUtil.show_success("Rebuild section tree [Success]", "");
            }else{
                CMSExtraUtil.show_error("Rebuild section tree  [Failed]", "");
            }

        } catch (err) {
            console.log('error in loading products :[' + err + ']');
        }

    });

};

ManageCache.clear_cache           = function(){
    
    var data = "action=clear_cache";

    RequestUtil.quick_post_request(null, data, function (outputArray){

        try{

            var status = outputArray["status"];

            if( status > 0 ){
                CMSExtraUtil.show_success("Clear Cache [Success]", "");
            }else{
                CMSExtraUtil.show_error("Clear Cache [Failed]", "");
            }

        } catch (err) {
            console.log('error in clear cache :[' + err + ']');
        }

    });

};
