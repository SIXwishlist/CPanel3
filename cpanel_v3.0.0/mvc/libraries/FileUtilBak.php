<?php
/*
 *
 */

/**
 * Description of FileUtil
 *
 * @author Ahmad
 */
class FileUtilBak {

    public static $UNKNOWN  = 0;
    public static $DOWNLOAD = 1;
    public static $IMAGE    = 2;
    public static $SWF      = 3;
    public static $SOUND    = 4;
    public static $VIDEO    = 5;
    public static $YOUTUBE_VIDEO = 6;

    public static $image_types = array('image/png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/bmp');

    public static $sound_types = array( 'audio/mp3', 'audio/ogg' );//mp3,oga
    public static $video_types = array( 'video/mp4', 'video/webm', 'video/ogg' );

    public static $compressed_types = array( 'application/zip', 'application/x-compressed-zip', 'application/x-rar', 'application/x-rar-compressed', 'application/octet-stream' );
    public static $document_types   = array(
        'application/pdf', 'application/msword', 'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    private function FileUtil(){
    }

    public static function get_all_media_types(){

        $types = array();

        try {

            $types = array_merge(
                self::$image_types,
                self::$sound_types,
                self::$video_types,
                self::$compressed_types,
                self::$document_types
            );


        } catch (Exception $e) {
            throw new CustomException( 'Error in : get all media types', $e );//from php 5.3 no need to custum
        }

        return $types;
    }


    public static function get_random_name($prefix, $orgfile){

        try {

            //$ext = substr($orgfile, strpos($orgfile, "."), strlen($orgfile) );
            $ext = substr($orgfile, strripos($orgfile, "."), strlen($orgfile) );

            $postfix = date("U") . "_" . mt_rand(0, 1000);

            $filename = $prefix . "_" . $postfix . $ext;

            return $filename;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get random name', $e );//from php 5.3 no need to custum
        }
    }

    public static function get_file_ext($orgfile){

        $ext = '';

        try {

            //$ext = substr($orgfile, strpos($orgfile, "."), strlen($orgfile) );
            $ext = substr($orgfile, strripos($orgfile, "."), strlen($orgfile) );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get random name', $e );//from php 5.3 no need to custum
        }

        return $ext;
    }

    public static function get_file_type($type){

        $typeNo = self::$UNKNOWN;

        try {

            if ( strstr($type, "image") ){

                $typeNo = self::$IMAGE;

            } else if ( strstr($type, "swf" ) ){

                $typeNo = self::$SWF;

            } else if ( strstr($type, "video") ){

                $typeNo = self::$VIDEO;

            } else if ( strstr($type, "sound") ){

                $typeNo = self::$SOUND;

            } else if ( strstr($type, "audio") ){

                $typeNo = self::$SOUND;

            } else {

                $typeNo = self::$DOWNLOAD;

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get file type', $e );//from php 5.3 no need to custum
        }

        return $typeNo;
    }


    public static function get_vimeo_id($url){

        $vimeo_id = '';

        try {
            //http://vimeo.com/34127945

            sscanf(parse_url($url, PHP_URL_PATH), '/%d', $vimeo_id);

            $vimeo_id = intval( $vimeo_id );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get vimeo id, url my be incorrect', $e );//from php 5.3 no need to custum
        }

        return $vimeo_id;
    }

    public static function get_youtube_id($url){

        $youtube_id = '';

        try {
            //http://www.youtube.com/watch?v=C4kxS1ksqtw&feature=relate

            parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );

            $youtube_id = $vars['v'];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get youtube id, url my be incorrect', $e );//from php 5.3 no need to custum
        }

        return $youtube_id;
    }


    public static function save_file($name, $prefix, $path, $random_name=true, $types=null){

        $filename = '';

        try{

            $request = HttpRequest::get_instance();

            if( empty($types) ){
                $types = self::get_all_media_types();
            }

            $uploaded_file = $request->get_file($name);

            if( $uploaded_file != null ){

                if(  ($uploaded_file->error == 0)  &&  in_array($uploaded_file->type, $types)  ){

                    if( $random_name ){
                        $filename  = self::get_random_name( $prefix, $uploaded_file->name );
                    }else{
                        $filename  = $prefix;
                    }

                    $status = $request->save_uploaded_file($uploaded_file->tmp_name, $path, $filename);

                    $filename = ( $status > 0 ) ? $filename : '';

                }

            }//else{
            //    throw new CustomException( $request->file_upload_error_message( $uploaded_file->error ) );
            //}

        } catch (Exception $e) {
            throw new CustomException( 'file save error ', $e );//from php 5.3 no need to custum
        }

        return $filename;
    }

    public static function save_multi_file($name, $prefix, $path, $random_name=true, $types=null){

        $filenames = array();

        try{

            $request = HttpRequest::get_instance();

            if( empty($types) ){
                $types = self::get_all_media_types();
            }

            $uploaded_files = $request->get_file($name);

            if( !is_array($uploaded_files) ){
                $uploaded_files = array( $uploaded_files );
            }

            foreach ($uploaded_files as $index => $uploaded_file) {

                //Name:           '. $myFile["name"][$i]     . '<br />'
                //Temporary file: '. $myFile["tmp_name"][$i] . '<br />'
                //Type:           '. $myFile["type"][$i]     . '<br />'
                //Size:           '. $myFile["size"][$i]     . '<br />'
                //Error:          '. $myFile["error"][$i]    . '<br />'

                if(  ($uploaded_file->error == 0)  &&  in_array($uploaded_file->type, $types)  ){

                    if( $random_name ){
                        $filename  = self::get_random_name( $prefix,  $uploaded_file->name );
                    }else{
                        $filename  = $prefix;
                    }

                    $status = $request->save_uploaded_file($uploaded_file->tmp_name, $path, $filename);

                    $filename = ( $status > 0 ) ? $filename : '';

                    $filenames[] = $filename;

                }//else{
                //    throw new CustomException( $request->file_upload_error_message( $uploaded_file->error ) );
                //}

            }

        } catch (Exception $e) {
            throw new CustomException( 'file save error ', $e );//from php 5.3 no need to custum
        }

        return $filenames;
    }


    public static function replace_file($name, $prefix, $path, $old_filename){

        try{

            $filename = self::save_file($name, $prefix, $path);

            if($filename != ''){
                self::remove_file($path, $old_filename);
            }else{
                $filename = $old_filename;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : replacing file', $e );//from php 5.3 no need to custum
        }

        return $filename;
    }

    public static function remove_file($path, $filename){

        $result = 0;

        try{

            $deleteFile = $path."/".$filename;

            if( file_exists($deleteFile) ){
                $result = unlink( $deleteFile );
            }

        } catch (Exception $e) {
            throw new CustomException( 'file remove error ', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function rename_file($path, $filename, $new_name){

        $result = 0;

        try{

            $file_path     = $path."/".$filename;
            $new_file_path = $path."/".$new_name;

            if( file_exists($file_path) ){
                $result = rename( $file_path, $new_file_path );
            }

        } catch (Exception $e) {
            throw new CustomException( 'file rename error ', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function get_directory_list($path){

        $dirArray = array();

        try{

            if (  ( $handle = opendir($path) ) !== false  ) {

                while ( ($entry = readdir($handle)) !== false ) {

                    if ( $entry != null && $entry != "." && $entry != "..") {
                        $dirArray[] = $entry;
                    }
                }
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get directory list', $e );//from php 5.3 no need to custum
        }

        return $dirArray;
    }

    public static function get_directory_tree($path){

        $dirArray = array();

        try{
            // open this directory
            $myDirectory = opendir($path);

            // get each entry
            while (false !== ($entryName = readdir($myDirectory))) {

                if (substr("$entryName", 0, 1) != ".") {

                    $type = filetype($path.'/'.$entryName);

                    if( $type == "dir" ){
                        $dirArray[] = self::get_directory_tree($path.'/'.$entryName);
                    }else{
                        $dirArray[] = $entryName;
                    }
                }
            }

            // close directory
            closedir($myDirectory);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get directory tree', $e );//from php 5.3 no need to custum
        }
        return $dirArray;
    }

    public static function delete_tree($path){

        $status = 0;

        try{

            if ( $handle = opendir($path) ) {

                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        if(is_dir($file)) {
                            deleteAll($path.'/'.$file);
                        } else {
                            unlink($path.'/'.$file);
                        }
                    }

                }

                closedir($handle);
            }

            rmdir($path);

            $status = 1;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : delete tree', $e );//from php 5.3 no need to custum
        }

        return $status;
    }


    public static function add_watermark($imageName, $stampName, $imageDir, $stampDir=null) {

        try{

            if ( $stampDir == null || $stampDir == ""){
                $stampDir = $imageDir;
            }

            $imageFile = $imageDir.'/'.$imageName;
            $stampFile = $stampDir.'/'.$stampName;


            $ext = substr($imageFile, strripos($imageFile, "."), strlen($imageFile) );


            if (preg_match("/jpg|jpeg/",$ext)){
                $image = imagecreatefromjpeg($imageFile);
            }else if (preg_match("/png/",$ext)){
                $image = imagecreatefrompng($imageFile);
            }else if (preg_match("/gif/",$ext)){
                $image = imagecreatefromgif($imageFile);
            }else{
                $image = imagecreatefrompng($imageFile);
            }



            // Load the stamp and the photo to apply the watermark to
            $stamp = imagecreatefrompng($stampFile);

            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_right  = 10;
            $marge_bottom = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            //imagecopy($image, $stamp, $marge_right, imagesy($image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
            imagecopy($image, $stamp, imagesx($image) - $sx - $marge_right, imagesy($image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));


            if (  preg_match("/png/",$ext)  ) {
                imagepng($image, $imageFile);
            } else if (  preg_match("/jpg|jpeg/",$ext)  ) {
                imagejpeg($image, $imageFile);
            } else if (  preg_match("/gif/",$ext)  ) {
                imagegif($image, $imageFile);
            } else {
                imagepng($image, $imageFile);
            }

            imagedestroy($image);
            imagedestroy($stamp);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : createing watermark', $e );//from php 5.3 no need to custum
        }
    }

    public static function resize_image($imageName, $imageDir, $newW, $newH) {

        try{

            $imageFile = $imageDir.'/'.$imageName;

            //$ext = substr($imageFile, strpos($imageFile, "."), strlen($imageFile) );
            $ext = substr($imageFile, strripos($imageFile, "."), strlen($imageFile) );

            //$mime = getimagesize($imageFile);
            //
            //switch ( $mime['mime'] ) {
            //
            //    case 'image/png':
            //        $srcImg = imagecreatefrompng($imageFile);
            //        break;
            //
            //    case 'image/jpg':
            //    case 'image/jpeg':
            //    case 'image/pjpeg':
            //        $srcImg = imagecreatefromjpeg($imageFile);
            //        break;
            //
            //    case 'image/gif':
            //        $srcImg = imagecreatefromgif($imageFile);
            //        break;
            //
            //    default:
            //        break;
            //}

            if (preg_match("/jpg|jpeg/",$ext)){
                $srcImg = imagecreatefromjpeg($imageFile);
            }else if (preg_match("/png/",$ext)){
                $srcImg = imagecreatefrompng($imageFile);
            }else if (preg_match("/gif/",$ext)){
                $srcImg = imagecreatefromgif($imageFile);
            }

            $orgW = imageSX($srcImg);
            $orgH = imageSY($srcImg);

            if ($orgW > $orgH) {
                $thumbW = $newW;
                $thumbH = $orgH * ($newW / $orgW);
            }
            if ($orgW < $orgH) {
                $thumbW = $orgW * ($newH / $orgH);
                $thumbH = $newH;
            }
            if ($orgW == $orgH) {
                $thumbW = $newW;
                $thumbH = $newH;
            }


            $dstX = ( $newW - $thumbW ) / 2;
            $dstY = ( $newH - $thumbH ) / 2;


            $dstImg = ImageCreateTrueColor( $thumbW, $thumbH );//$dstImg = imagecreatetruecolor( $thumbW, $thumbH );

            if (  preg_match("/png/",$ext)  ) {
                imagealphablending($dstImg, false);
                imagesavealpha($dstImg,true);

                $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
                imagefilledrectangle($dstImg, 0, 0, $thumbW, $thumbH, $transparent);
            }

            imagecopyresampled( $dstImg, $srcImg, 0, 0, 0, 0, $thumbW, $thumbH, $orgW, $orgH );

            //Create Tranparent BG
            $bgImg = imagecreatetruecolor($newW, $newH);
            imagesavealpha($bgImg, true);
            $color = imagecolorallocatealpha($bgImg, 0, 0, 0, 127);
            imagefill($bgImg, 0, 0, $color);

            imagecopy($bgImg, $dstImg, $dstX, $dstY, 0, 0, imagesx($dstImg), imagesy($dstImg));


            imagepng($bgImg, $imageFile);

            //if (  preg_match("/png/",$ext)  ) {
            //    imagepng($bgImg, $thumbFile);
            //} else if (  preg_match("/jpg|jpeg/",$ext)  ) {
            //    imagejpeg($bgImg, $thumbFile);
            //} else if (  preg_match("/gif/",$ext)  ) {
            //    imagegif($bgImg, $thumbFile);
            //}

            imagedestroy($dstImg);
            imagedestroy($srcImg);
            imagedestroy($bgImg);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : resize image', $e );//from php 5.3 no need to custum
        }
    }


    public static function create_thumbnail($imageName, $thumbName, $newW, $newH, $imageDir, $thumbDir=null) {

        try{

            if ( $thumbDir == null || $thumbDir == ""){
                $thumbDir = $imageDir;
            }

            $imageFile = $imageDir.'/'.$imageName;
            $thumbFile = $thumbDir.'/'.$thumbName;

            //$system = explode(".",$imageFile);
            //$ext = substr($imageFile, strpos($imageFile, "."), strlen($imageFile) );
            $ext = substr($imageFile, strripos($imageFile, "."), strlen($imageFile) );

            //$mime = getimagesize($imageFile);
            //
            //switch ( $mime['mime'] ) {
            //
            //    case 'image/png':
            //        $srcImg = imagecreatefrompng($imageFile);
            //        break;
            //
            //    case 'image/jpg':
            //    case 'image/jpeg':
            //    case 'image/pjpeg':
            //        $srcImg = imagecreatefromjpeg($imageFile);
            //        break;
            //
            //    case 'image/gif':
            //        $srcImg = imagecreatefromgif($imageFile);
            //        break;
            //
            //    default:
            //        break;
            //}

            if (preg_match("/jpg|jpeg/",$ext)){
                $srcImg = imagecreatefromjpeg($imageFile);
            }else if (preg_match("/png/",$ext)){
                $srcImg = imagecreatefrompng($imageFile);
            }else if (preg_match("/gif/",$ext)){
                $srcImg = imagecreatefromgif($imageFile);
            }

            $oldW = imageSX($srcImg);
            $oldH = imageSY($srcImg);

            if ($oldW > $oldH) {
                $thumbW = $newW;
                $thumbH = $oldH*($newH/$oldW);
            }
            if ($oldW < $oldH) {
                $thumbW = $oldW*($newW/$oldH);
                $thumbH = $newH;
            }
            if ($oldW == $oldH) {
                $thumbW = $newW;
                $thumbH = $newH;
            }

            $ratio_orig = $oldW/$oldH;
            $ratio_new  = $thumbW/$thumbH;

            if ( $ratio_new > $ratio_orig) {
                $thumbW = $thumbH*$ratio_orig;
            } else {
                $thumbH = $thumbW/$ratio_orig;
            }

//            if ( $thumbW < $newW && $thumbH < $newH ) {
//
//                if ( $oldW > $oldH ) {
//                    $thumbW = $newW;
//                    $thumbH = $newH*$ratio_orig;
//                } else {
//                    $thumbW = $newW/$ratio_orig;
//                    $thumbH = $newH;
//                }
//            }

            $dstImg = ImageCreateTrueColor( $thumbW, $thumbH );//$dstImg = imagecreatetruecolor( $thumbW, $thumbH );

            if (  preg_match("/png/",$ext)  ) {
                imagealphablending($dstImg, false);
                imagesavealpha($dstImg,true);

                $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
                imagefilledrectangle($dstImg, 0, 0, $thumbW, $thumbH, $transparent);
            }

            imagecopyresampled( $dstImg, $srcImg, 0, 0, 0, 0, $thumbW, $thumbH, $oldW, $oldH );

            if (  preg_match("/png/",$ext)  ) {
                imagepng($dstImg, $thumbFile);
            } else if (  preg_match("/jpg|jpeg/",$ext)  ) {
                imagejpeg($dstImg, $thumbFile);
            } else if (  preg_match("/gif/",$ext)  ) {
                imagegif($dstImg, $thumbFile);
            }

            imagedestroy($dstImg);
            imagedestroy($srcImg);


        } catch (Exception $e) {
            throw new CustomException( 'Error in : createing thumb', $e );//from php 5.3 no need to custum
        }
    }

    public static function create_thumb_old_way($imageName, $thumbName, $newW, $newH, $imageDir, $thumbDir=null) {

        try{

            if ( $thumbDir == null || $thumbDir == ""){
                $thumbDir = $imageDir;
            }

            $imageFile = $imageDir.'/'.$imageName;
            $thumbFile = $thumbDir.'/'.$thumbName;

            //$system = explode(".",$imageFile);
            //$ext = substr($imageFile, strpos($imageFile, "."), strlen($imageFile) );
            $ext = substr($imageFile, strripos($imageFile, "."), strlen($imageFile) );

            if (preg_match("/jpg|jpeg/",$ext)){
                $srcImg = imagecreatefromjpeg($imageFile);
            }else if (preg_match("/png/",$ext)){
                $srcImg = imagecreatefrompng($imageFile);
            }else if (preg_match("/gif/",$ext)){
                $srcImg = imagecreatefromgif($imageFile);
            }

            $oldW = imageSX($srcImg);
            $oldH = imageSY($srcImg);

            if ($oldW > $oldH) {
                $thumbW = $newW;
                $thumbH = $oldH*($newH/$oldW);
            }
            if ($oldW < $oldH) {
                $thumbW = $oldW*($newW/$oldH);
                $thumbH = $newH;
            }
            if ($oldW == $oldH) {
                $thumbW = $newW;
                $thumbH = $newH;
            }

            $dstImg = ImageCreateTrueColor( $thumbW, $thumbH );//$dstImg = imagecreatetruecolor( $thumbW, $thumbH );

            imagecopyresampled( $dstImg, $srcImg, 0, 0, 0, 0, $thumbW, $thumbH, $oldW, $oldH );

            if (  preg_match("/png/",$ext)  ) {
                imagepng($dstImg, $thumbFile);
            } else if (  preg_match("/jpg|jpeg/",$ext)  ) {
                imagejpeg($dstImg, $thumbFile);
            } else if (  preg_match("/gif/",$ext)  ) {
                imagegif($dstImg, $thumbFile);
            }

            imagedestroy($dstImg);
            imagedestroy($srcImg);


        } catch (Exception $e) {
            throw new CustomException( 'Error in : createing thumb', $e );//from php 5.3 no need to custum
        }
    }


    public static function save_thumb($imageName, $thumbPrefix, $newW, $newH, $imageDir, $thumbDir=null) {

        $thumb = '';

        try{

            $tempImage = self::save_file($imageName, $thumbPrefix, $imageDir, true);

            $thumb     = self::get_random_name($thumbPrefix, $tempImage);

            if( !empty($tempImage) ){
                self::create_thumbnail($tempImage, $thumb, $newW, $newH, $imageDir, $thumbDir);
            }

            self::remove_file($imageDir, $tempImage);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : saveing thumb', $e );//from php 5.3 no need to custum
        }

        return $thumb;
    }

    public static function replace_thumb($imageName, $thumbPrefix, $newW, $newH, $oldThumb, $imageDir, $thumbDir=null){

        $thumb = '';

        try{

            if ( $thumbDir == null || $thumbDir == ""){
                $thumbDir = $imageDir;
            }

            $request = HttpRequest::get_instance();

            $uploadedFile = $request->get_file($imageName);

            $noChange = ($uploadedFile == null) || ( $uploadedFile->size <= 0 );

            if( $noChange ){
                $thumb = $oldThumb;
            }else{

                $tempImage = self::save_file($imageName, $thumbPrefix, $imageDir, true);

                $thumb     = self::get_random_name($thumbPrefix, $tempImage);

                if( !empty($tempImage) ){
                    self::create_thumbnail($tempImage, $thumb, $newW, $newH, $imageDir, $thumbDir);
                }

                self::remove_file($imageDir, $tempImage);

                self::remove_file($thumbDir, $oldThumb);

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : replacing thumb', $e );//from php 5.3 no need to custum
        }

        return $thumb;

    }

}
?>
