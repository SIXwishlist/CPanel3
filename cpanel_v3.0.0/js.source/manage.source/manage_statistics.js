
/*! statistics */

/* global CDictionary, g_root_url, CPopup, AdminAuth, BackgroundRequests, MainGlobals, ADMIN_RULE_MASTER, ADMIN_RULE_CHECKER, ADMIN_RULE_ENTRY, CMSUtil, USER_TYPE_MASTER, USER_TYPE_MANAGER, AuthUtil, ManageOrganization, ManageOrganizationProfile, RequestUtil, Utils, CanvasJS */

function ManageStatistics() {}

////////////////////////////////////////////////////////////////////////////////

ManageStatistics.init = function () {

    try{

        var menu_div    = $("#body").find("#menu");
        var content_div = $("#body").find("#content");


        menu_div.find('a').removeClass();
        menu_div.find("a[data-module=statistics]").addClass('active');

        content_div.html( '' );

        var content_html = '<br /><br /><br />' +
                    '<div id="main-links">' +  
                        '<div data-group="admin"><a href="javascript:ManageStatistics.init_accidents_statistics();"    ><img src="'+g_root_url+'images/manage-icons/accidents.png"        > ' + CDictionary.get_text('Accidents_lbl')          + '</a></div>' + 
                        '<div data-group="admin"><a href="javascript:ManageStatistics.init_fuels_statistics();"        ><img src="'+g_root_url+'images/manage-icons/fuels.png"            > ' + CDictionary.get_text('Fuels_lbl')              + '</a></div>' + 
                        '<div data-group="admin"><a href="javascript:ManageStatistics.init_maintenances_statistics();" ><img src="'+g_root_url+'images/manage-icons/maintenances.png"     > ' + CDictionary.get_text('Maintenance_lbl')        + '</a></div>' + 
                        //'<div data-group="admin"><a href="javascript:ManageStatistics.init_store();"       ><img src="'+g_root_url+'images/manage-icons/parts.png"            > ' + CDictionary.get_text('Parts_lbl')              + '</a></div>' + 
                    '</div>';

        content_div.html( content_html );

    } catch(err) {
        console.error('Error in : ManageMaintenance - init : [' + err +']');
    }
};

////////////////////////////////////////////////////////////////////////////////

ManageStatistics.init_accidents_statistics = function(){
        
    var content_div = $("#body").find("#content");

    content_div.html('<div id="chart_area"></div>');
    
    var form_data = {
        action : "accidents_statistics"
    };


    var main_div = content_div.find("#chart_area");

    RequestUtil.quick_post_request(main_div, form_data, function(outputArray){

        //console.log("outputArray : "+outputArray);

        var status = outputArray["status"];

        if( status > 0 ){

            var accidents_statistics = outputArray["accidents_statistics"];

            ManageStatistics.show_accidents_chart(accidents_statistics);

        }
    });
    
};

ManageStatistics.show_accidents_chart      = function(statistics){

    var dataPoints = [];
                    
    for(var i=0; i<statistics.length; i++){
        
        var statistic = statistics[i];
        
        var car_plate = statistic.plate;
        var count     = Utils.get_int(statistic.count);
        
        dataPoints.push( { label: car_plate, x: (i+1), y: count } );

    }
    
    var chart_div =  $("#body").find("#content").find("#chart_area");
    
    chart_div.html('');

    chart_div.html('<div id="ChartContainer" style="height: 400px; width: 800px;"></div>');

    var chart = new CanvasJS.Chart("ChartContainer", {

            theme: "theme3",
            animationEnabled: true,

            title:{
                text: CDictionary.get_text('AccidentsStatistics_lbl')
            },

            data: [ 
                    {
                        // Change type "column" to "doughnut", "line", "splineArea", etc.
                        type: "doughnut",
                        dataPoints: dataPoints
                    }
            ]
    });

    chart.render();
};

//{
//    // Change type "column" to "doughnut", "line", "splineArea", etc.
//    type: "pie",
//    showInLegend: true,
//    dataPoints: dataPoints
//}


////////////////////////////////////////////////////////////////////////////////

ManageStatistics.init_maintenances_statistics = function(){
        
    var content_div = $("#body").find("#content");

    content_div.html('<div id="chart_area"></div>');
    
    var form_data = {
        action : "maintenances_statistics"
    };


    var main_div = content_div.find("#chart_area");

    RequestUtil.quick_post_request(main_div, form_data, function(outputArray){

        //console.log("outputArray : "+outputArray);

        var status = outputArray["status"];

        if( status > 0 ){

            var maintenances_statistics = outputArray["maintenances_statistics"];

            ManageStatistics.show_maintenances_chart(maintenances_statistics);

        }
    });
    
};

ManageStatistics.show_maintenances_chart      = function(statistics){

    var dataPoints = [];
                    
    for(var i=0; i<statistics.length; i++){
        
        var statistic = statistics[i];
        
        var car_plate  = statistic.plate;
        var count      = Utils.get_int(statistic.count);
        var total_cost = Utils.get_float(statistic.total_cost);
        
        dataPoints.push( { label: car_plate, x: (i+1), y: total_cost } );

    }
    
    var chart_div =  $("#body").find("#content").find("#chart_area");
    
    chart_div.html('');

    chart_div.html('<div id="ChartContainer" style="height: 400px; width: 800px;"></div>');

    var chart = new CanvasJS.Chart("ChartContainer", {

            theme: "theme3",
            animationEnabled: true,

            title:{
                text: CDictionary.get_text('AccidentsStatistics_lbl')
            },

            data: [ 
                    {
                        // Change type "column" to "doughnut", "line", "splineArea", etc.
                        type: "column",
                        dataPoints: dataPoints
                    }
            ]
    });

    chart.render();
};

////////////////////////////////////////////////////////////////////////////////

ManageStatistics.init_fuels_statistics = function(){
        
    var content_div = $("#body").find("#content");

    content_div.html('<div id="chart_area"></div>');
    
    var form_data = {
        action : "fuels_statistics"
    };


    var main_div = content_div.find("#chart_area");

    RequestUtil.quick_post_request(main_div, form_data, function(outputArray){

        //console.log("outputArray : "+outputArray);

        var status = outputArray["status"];

        if( status > 0 ){

            var fuels_statistics = outputArray["fuels_statistics"];

            ManageStatistics.show_fuels_chart(fuels_statistics);

        }
    });
    
};

ManageStatistics.show_fuels_chart      = function(statistics){

    var dataPoints = [];
                    
    for(var i=0; i<statistics.length; i++){
        
        var statistic = statistics[i];
        
        var car_plate      = statistic.plate;
        var count          = Utils.get_int(statistic.count);
        var total_quantity = Utils.get_float(statistic.total_quantity);
        
        dataPoints.push( { label: car_plate, x: (i+1), y: total_quantity } );

    }
    
    var chart_div =  $("#body").find("#content").find("#chart_area");
    
    chart_div.html('');

    chart_div.html('<div id="ChartContainer" style="height: 400px; width: 800px;"></div>');

    var chart = new CanvasJS.Chart("ChartContainer", {

            theme: "theme3",
            animationEnabled: true,

            title:{
                text: CDictionary.get_text('AccidentsStatistics_lbl')
            },

            data: [ 
                    {
                        // Change type "column" to "doughnut", "line", "splineArea", etc.
                        type: "pie",
                        dataPoints: dataPoints
                    }
            ]
    });

    chart.render();
};

////////////////////////////////////////////////////////////////////////////////