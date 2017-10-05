<?php

/**
 * Description of TextUtil
 * @author Ahmad
 */

class TextUtil {

    /**
     *
     */

    public static function trimURIRequest ($string) {

        if( $string != null ){

            $sPos = intval(  strrpos ( $string , "/" )    );
            $pPos = intval(  strrpos ( $string , ".php" ) );
            $qPos = intval(  strrpos ( $string , "?" )    );

            $startPos = 0;

            if( $sPos > $pPos ) {
                $startPos = $sPos + 1;
            }else{
                $startPos = $pPos + 4;
            }

            if( $qPos > 0 ) {
                $startPos = $qPos + 1;
            }




            $hPos = intval(  strrpos ( $string , "#" )    );
            $lPos = intval(  strrpos ( $string , "&lang" )    );

            if( $hPos > 0 ) {
                $endPos = $hPos;
            }
            if( $lPos > 0 ) {
                $endPos = $lPos;
            }

            if( $endPos > 0 ){
                $trimLength = $endPos - $startPos;
            }else{
                $trimLength = strlen($string) - $startPos;
            }

            $string = substr($string, $startPos, $trimLength);

            if( $string == null || $string == "" ){
                $string = "page=home";
            }
        }

        return $string;
    }

    public static function convertToWeb ($str) {

        if( $str != null ){
            $str = str_replace("\n", "<br />", $str);
        }

        return $str;
    }

    public static function briefText($content, $limit = 250) {

        $brief    = substr( $content, 0, $limit );

        $position = strrpos( $brief, " " );

        $brief    = substr( $brief, 0, $position );

        if ($brief != '') {
            $brief .= '...';
        }

        return $brief;
    }

    public static function fixText($str) {

        $str = str_replace('\r\n', '\n', $str);

        $str = stripslashes($str);

        return $str;
    }

    public static function br2nl($str) {
        return preg_replace( '!<br.*>!iU', "\n", $str );
    }

    public static function nl2br($str) {
        return nl2br( $str );
    }

    public static function convertArabicURL($str) {

        $newStr = str_replace(' / ', '_', $str);

	$newStr = rawurlencode( str_replace(' ', '_',$newStr) );

	return $newStr;
    }
    
    public static function is_arabic($string) {

        mb_regex_encoding('UTF-8');

        return mb_ereg('[\x{0600}-\x{06FF}]', $string);
    }

    public static function convert_array_to_object($array) {
        $object = json_decode(  json_encode( $array )  );
        return $object;
    }

    public static function convert_object_to_array($object) {
        $array = $object;
        return $array;
    }

}
    
?>