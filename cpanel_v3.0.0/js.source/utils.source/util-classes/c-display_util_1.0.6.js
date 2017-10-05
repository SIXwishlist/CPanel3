
/*! DisplayUtil */
/* Based on: [CDictionary] */
/* global CDictionary, g_root_url, Utils */

function DisplayUtil() {
}

var TYPE_UNKNOWN     = 0;
var TYPE_DOWNLOAD    = 1;
var TYPE_IMAGE       = 2;
var TYPE_FLASH       = 3;
var TYPE_SOUND       = 4;
var TYPE_VIDEO       = 5;
var TYPE_YOUTUBE     = 6;
var TYPE_VIMEO       = 7;
var TYPE_EMBED_CODE  = 8;
var TYPE_SOUND_CLOUD = 9;

$(document).ready(function(){
});

DisplayUtil.get_embed_output = function ( file, type, width, height, folder, autoplay, params ){

    var output = '';

    try{

        if( g_root_url == null || g_root_url == "" ){
            throw 'DisplayUtil - Error in - get_embed_output : [ Please init "g_root_url" constant in config file ]';
        }

        //alert(autoplay);

        switch( type ){

            case TYPE_DOWNLOAD :
                output = this.get_download_embed(folder+'/'+file);
                break;

            case TYPE_IMAGE    :
                output = this.get_image_embed(folder+'/'+file, width, height);
                break;

            case TYPE_FLASH    :
                output = this.get_flash_embed(folder+'/'+file, width, height, params);
                break;

            case TYPE_SOUND    :
                //output = get_sound_embed(folder+'/'+file, width, height, autoplay);
                output = this.get_jsplayer_sound_embed(file, width, height, folder, autoplay);
                break;

            case TYPE_VIDEO    :
                output = this.get_jsplayer_video_embed(file, width, height, folder, '', autoplay);
                break;

            case TYPE_YOUTUBE  :
                output = this.get_youtube_video_embed(file, width, height, autoplay);
                break;

            case TYPE_VIMEO  :
                output = this.get_vimeo_video_embed(file, width, height, autoplay);
                break;

            case TYPE_SOUND_CLOUD  :
                output = this.get_sound_cloud_embed(file, width, height, autoplay);
                break;

            //case TYPE_UNKNOWN  :
            //    break;

            default:
                output = this.get_image_embed(folder+'/'+file, width, height);
                break;
        }

    } catch(err) {
        throw 'DisplayUtil - Error in - get embed output : [' + err +']';
    }

    return output;
};


DisplayUtil.get_image_embed = function (filePath, width, height){

    var output = '';

    try{

        if( width > 10 && height > 10 ){
            output += '<img src="' + filePath + '" alt="" width="' + width + '" height="' + height + '"   />';
        }else{
            output += '<img src="' + filePath + '" alt="" />';
        }

    } catch(err) {
        throw 'DisplayUtil - Error in - get image embed : [' + err +']';
    }

    return output;
};

DisplayUtil.get_flash_embed = function (filePath, width, height, params){

    var output = '';

    try{

        //alert(wmode);

        output += '<embed src="' + filePath + '"'
                    +  'type="application/x-shockwave-flash" '
                    +  'allowfullscreen="true" '
                    +  'allowscriptaccess="always" ';

        if( params != null ){
            for(var i=0; i<params.length; i++){
                var param = params[i];
                output += param.name + '="' + param.value + '" ';
                //alert( param.name + '="' + param.value + '" ' );
            }
        }

        output += 'width="' + width + '" height="' + height + '">'
            + '</embed>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get flash embed : [' + err +']';
    }
    
    return output;
};

DisplayUtil.get_jsplayer_sound_embed = function (file, width, height, folder, autoplay){
    
    var output = '';

    try{

        width  = 437;
        height = 130;

        //alert(file);

        var id   = file.substring( file.lastIndexOf("_")+1, file.length );
        var name = file.substring( 0, file.lastIndexOf("_") );

        output = '<iframe width="'+width+'" height="'+height+'" scrolling="no" frameborder="0" src="'+g_root_url+'audio.php?id='+id+'&name='+name+'&folder='+folder+'&width='+width+'&height='+height+'"></iframe>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get jsplayer sound embed : [' + err +']';
    }
    
    return output;
};

DisplayUtil.get_jsplayer_video_embed = function (file, width, height, folder, thumb, autoplay){

    var output = '';

    try{

        //var autoplay_val = (autoplay==true) ? 'autoplay' : ''; 
        var autoplay_val = ''; 

        //alert( autoplay );//alert( autoplay_val );

        var baseUrl = Utils.get_base_url();

        var thumbUrl = folder+'/'+thumb;
        var videoUrl = folder+'/'+file;

        var id   = file.substring( file.lastIndexOf("_")+1, file.length );
        var name = file.substring( 0, file.lastIndexOf("_") );

        output = '<iframe width="'+width+'" height="'+height+'" scrolling="no" frameborder="0" allowfullscreen="true" src="'+g_root_url+'video.php?id='+id+'&name='+name+'&folder='+folder+'&width='+width+'&height='+height+'"></iframe>';

    //    output = '<video id="video_'+file+'" class="video-js vjs-default-skin" '+autoplay_val+' controls preload="none" ' + 
    //                    ' width="'+width+'" height="'+height+'" ' + 
    //                    ' poster="'+thumbUrl+'" ' + 
    //                    ' data-setup="{}">' + 
    //                '<source src="'+videoUrl+'.mp4"  type="video/mp4"  />' + 
    //                '<source src="'+videoUrl+'.webm" type="video/webm" />' + 
    //                '<source src="'+videoUrl+'.ogv"  type="video/ogg"  />' +
    //                //'<track kind="captions" src="captions.vtt" srclang="en" label="English" />' + 
    //             '</video>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get jsplayer video embed : [' + err +']';
    }
    
    return output;

};

DisplayUtil.get_jwplayer_video_embed = function (){

    var output = '';

    try{

        output += '<div id="jwPlayerVideo">Loading the player...</div>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get jwplayer video embed : [' + err +']';
    }

    return output;

};

DisplayUtil.get_youtube_video_embed = function (youtubeID, width, height, autoplay){

    var output = '';

    try{

        var autoplay_val = (autoplay==true) ? 1 : 0; 

        //var youtubeID = Utils.get_youtube_id( ytUrl );
        output += '<iframe width="'+width+'" height="'+height+'" src="http://www.youtube.com/embed/'+youtubeID+'?rel=0&autoplay='+autoplay_val+'"  allowfullscreen></iframe>';
        //http://www.youtube.com/v/+youtubeID

    } catch(err) {
        throw 'DisplayUtil - Error in - get youtube video embed : [' + err +']';
    }
    
    return output;
};

DisplayUtil.get_vimeo_video_embed = function (vimeoID, width, height, autoplay){

    var output = '';

    try{

        var autoplay_val = (autoplay==true) ? 1 : 0; 

        output += '<iframe src="http://player.vimeo.com/video/'+vimeoID+'?title=0&amp;byline=0&amp;portrait=0&amp;autoplay='+autoplay_val+'" width="'+width+'" height="'+height+'"  webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get vimeo video embed : [' + err +']';
    }

    return output;
};

DisplayUtil.get_sound_cloud_embed = function (soundCloudID, width, height, autoplay){

    var output = '';

    try{
        
        //<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/278564760&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
        output += '<iframe width="'+width+'" height="'+height+'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'+soundCloudID+'&amp;auto_play='+autoplay+'&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;visual=true"></iframe>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get sound cloud embed : [' + err +']';
    }

    return output;
};

DisplayUtil.get_download_embed = function (filePath){

    var output = '';

    try{

        output += '<div><br /><a href="' + filePath + '">' + CDictionary.get_text("Download_lbl") + '</a><br /></div>';

    } catch(err) {
        throw 'DisplayUtil - Error in - get download embed : [' + err +']';
    }

    return output;
};
