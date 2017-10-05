<!DOCTYPE html>
<html>
    <head>
       
        <style type="text/css">
            html, body {
                width:  100%;
                height: 100%;
            }
            body {
                margin: 0px;
            }
        </style>
<?php
    error_reporting(E_ERROR);

    $ini_array = parse_ini_file("config/ini/config.ini", true);

    $base_dir = $ini_array["base_dir"];
    $base_url = $ini_array["base_url"];
    $root_url = $ini_array["root_url"];

    $id     = $_REQUEST['id'];
    $name   = $_REQUEST['name'];
    $folder = $_REQUEST['folder'];
    $width  = $_REQUEST['width'];
    $height = $_REQUEST['height'];
?>

        <script type="text/javascript" src="<?= $base_url ?>js/lib/modernizr.js"></script>

        <? /*

        <link href="<?= $base_url ?>css/video-js/video-js.css" rel="stylesheet">
        <script src="<?= $base_url ?>js/lib/video-js/video-js.js"></script>

        <script>
          videojs.options.flash.swf = "<?= $base_url ?>js/lib/video-js/video-js.swf"
        </script>

        <!--[if IE 9]>
        <script type="text/javascript"> jQuery(document).ready(function($) { _V_.options.techOrder = ["flash", "html5", "links"]; }); </script>
        <![endif]-->

         */ ?>

        <link href="//vjs.zencdn.net/4.3/video-js.css" rel="stylesheet">
        <script src="//vjs.zencdn.net/4.3/video.js"></script>

        <script>
          videojs.options.flash.swf = "<?= $base_url ?>js/lib/video-js/video-js.swf"
        </script>

        <script type="text/javascript">
          document.createElement('video');document.createElement('audio');
        </script>

        <!--[if IE 9]>
        <script type="text/javascript"> jQuery(document).ready(function($) { _V_.options.techOrder = ["flash", "html5", "links"]; }); </script>
        <![endif]-->

    </head>
    <body>

        <div class="aspect-wrapper">
            
          <video id="mc_video_<?= $id ?>" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="none" width="<?= $width ?>" height="<?= $height ?>"
              poster="<?= $root_url . $folder .'/'. $name .'_'. $id ?>.jpg"
              data-setup="{}">
            <source src="<?= $root_url . $folder .'/'. $name .'_'. $id ?>.mp4"  type='video/mp4'   />
            <source src="<?= $root_url . $folder .'/'. $name .'_'. $id ?>.webm" type='video/webm' />
            <source src="<?= $root_url . $folder .'/'. $name .'_'. $id ?>.ogv"  type='video/ogg'   />
          </video>

        </div>

    </body>
</html>

<?
/*

    if(type==TYPE_VIDEO){
        //var video_obj = $("#video_"+file);
        //$("#video_"+file).VideoJS().ready( function(){
        //  // Player (this) is initialized and ready.
        //    alert('done');
        //    //this.play();
        //});
        //_V_("video_"+file, {}, function(){
        //  // Player (this) is initialized and ready.
        //    alert('done');
        //});
        //videojs("video_"+file, {}, function(){
        //    // Player (this) is initialized and ready.
        //    alert('done');
        //    //this.play();
        //});
        //var video_obj = _V_("#video_"+file);
        //video_obj.ready(function() {
        //    video_obj.play();
        //});
    }
//            if(type==TYPE_VIDEO){
//
//                var src  = embed_div.attr("file");
//
//                var thumb = embed_div.find("img").attr("href");
//
//                jwplayer("jwPlayerVideo").setup({
//                    //sources: [{file: src+".mp4"},{file: src+".mp4"},{file: src+".mp4"}],
//                    file: src+".mp4",
//                    file: src+".flv",
//                    file: src+".mp4",
//                    image: thumb,
//                    width:  width,
//                    height: height
//                });
//            }

*/