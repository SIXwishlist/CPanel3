
var TYPE_CALC_1 = 1;
var TYPE_CALC_2 = 2;
var TYPE_CALC_3 = 3;
var TYPE_CALC_4 = 4;


$(document).ready(function(){

    //alert('front js loaded');

    init_calcs();

});

function open_calc(type){

    var content = '';

    var width  = '';
    var height = '';


    var content   = get_calc_content(type);
    var popupHtml = get_popup_html(content);

    $('body').append( popupHtml );

    $('body').find("#overlay").height( $(document).height() );
    window.scrollTo(0, 25);

    $('body').find("#overlay").find("#close").click( function () {
        $('body').find("#overlay").remove();
    });

}

function get_calc_content(type){

    var output = '';
    
    switch( type ){

        case TYPE_CALC_1 :
            output = get_download_embed(folder+'/'+file);
            break;

        case TYPE_CALC_2    :
            output = get_image_embed(folder+'/'+file, width, height);
            break;

        case TYPE_CALC_3    :
            output = get_flash_embed(folder+'/'+file, width, height, params);
            break;

        case TYPE_CALC_4    :
            //output = get_sound_embed(folder+'/'+file, width, height, autoplay);
            output = get_jsplayer_sound_embed(file, width, height, folder, autoplay);
            break;

        default:
            break;
    }

    return output;

}

function get_popup_html(content){

    var popupHtml = '<div id="overlay"><div id="popup"><div id="close"></div><div id="content">'+content+'</div></div></div>';

    return popupHtml;
}
