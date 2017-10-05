
/*! background requests */

/* global USER_TYPE_MASTER, USER_TYPE_MANAGER, USER_TYPE_TEACHER, USER_TYPE_HEALTH, USER_TYPE_BEHAVIOR, USER_TYPE_MOVEMENT, USER_TYPE_RELATION, USER_TYPE_FINANCE, Cover, CMSUtil, Utils, RequestUtil */

$(document).ready(function(){

    try{

        $('body').bind( "in_background",     BackgroundRequests.load_main );

        //$('body').bind( "countries_updated", BackgroundRequests.load_countries );

    } catch (err) {
        console.error('error in background requests document ready :[' + err + ']');
    }
});

////////////////////////////////////////////////////////////////////////////////
// Main Globals ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function MainGlobals(){}

////////////////////////////////////////////////////////////////////////////////
// In Backgrounds //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function BackgroundRequests(){}

BackgroundRequests.load_main      = function (){
    
    try{

        var main_div = $('body');

        Cover.add(main_div, Cover.LOADING_STYLE1, 'light');

        var data = "action=main_background_requests";

        RequestUtil.quick_ajax_request(null, data, function (outputArray){

            try{

                Cover.remove(main_div);

                var status = outputArray["status"];

                if( status > 0 ){

                    MainGlobals.countries     = outputArray["countries"];
                    //alert("countries length : "+countries.length);
                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch (err) {
        console.error('Error in : BackgroundRequests - load_main :[' + err + ']');
    }

};

BackgroundRequests.load_countries = function (){
    
    try{

        var main_div = $('body');

        Cover.add(main_div, Cover.LOADING_STYLE1, 'light');

        var data = "action=countries";

        RequestUtil.quick_ajax_request(null, data, function (outputArray){

            try{

                Cover.remove(main_div);

                var status = outputArray["status"];

                if( status > 0 ){
                    
                    MainGlobals.countries = outputArray["countries"];

                }

            } catch (err) {
                console.error('error in request :[' + err + ']');
            }

        });

    } catch (err) {
        console.error('Error in : BackgroundRequests - load countries :[' + err + ']');
    }

};

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////