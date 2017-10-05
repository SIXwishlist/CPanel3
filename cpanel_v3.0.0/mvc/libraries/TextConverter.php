<?php
/*
 *
 */

/**
 * Description of TextConverter
 * This class for converting character encoding according to case
 * @author Ahmad
 */
class TextConverter {

    public static $TYPE_LITERAL_CHARACTERS  = 1;
    public static $TYPE_UTF8_CHARACTERS     = 2;
    public static $TYPE_CP1252_CHARACTERS   = 3;
    public static $TYPE_CP1256_CHARACTERS   = 4;
    public static $TYPE_WEB_CODE_CHARACTERS = 5;
    public static $TYPE_WINDOWS_1252_URL_ENCODED_CHARACTERS             = 6;
    public static $TYPE_UTF8_URL_ENCODED_CHARACTERS                     = 7;
    public static $TYPE_WESTERN_EUROPEAN_WINDOWS_URL_ENCODED_CHARACTERS = 8;

    public static $TYPE_SPECIAL_CHARACTERS             = 9;
    public static $TYPE_SPECIAL_URL_ENCODED_CHARACTERS = 10;

    /**
     * Letter Characters
     */
    public static $LITERAL_CHARACTERS = array (
        '�', '�', '�', '�', '�', '�', '�', '�',
        '�', '�', '�', '�', '�', '�', '�', '�',
        '�', '�', '�', '�', '�', '�', '�', '�',
        '�', '�', '�', '�', '�', '�', '�', '�',
        '�', '�', '�', '�'
    );

    /**
     * UTF-8 encoding
     */
    public static $UTF8_CHARACTERS = array(
        'أ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د',
        'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط',
        'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل',  'م',
        'ن', 'ه', 'و', 'ي', 'ا', 'إ', 'آ', 'ى',
        'ء', 'ؤ', 'ئ', 'ـ'
    );

    /**
     * CP1252 Characters code
     */
    public static $CP1252_CHARACTERS = array (
        '\u0623', '\u0628', '\u062a', '\u062b', '\u062c', '\u062d', '\u062e', '\u062f',
        '\u0630', '\u0631', '\u0632', '\u0633', '\u0634', '\u0635', '\u0636', '\u0637',
        '\u0638', '\u0639', '\u063a', '\u0641', '\u0642', '\u0643', '\u0644', '\u0645',
        '\u0646', '\u0647', '\u0648', '\u064a', '\u0627', '\u0629', '\u0649', '\u0622',
        '\u0624', '\u0621', '\u0626', '\u0625'
    );

    /**
     *  CP1256 Characters code
     */
    public static $CP1256_CHARACTERS = array (
        '\u00c3', '\u00c8', '\u00ca', '\u00cb', '\u00cc', '\u00cd', '\u00ce', '\u00cf',
        '\u00d0', '\u00d1', '\u00d2', '\u00d3', '\u00d4', '\u00d5', '\u00d6', '\u00d8',
        '\u00d9', '\u00da', '\u00db', '\u00dd', '\u00de', '\u00df', '\u00e1', '\u00e3',
        '\u00e4', '\u00e5', '\u00e6', '\u00ed', '\u00c7', '\u00c9', '\u00ec', '\u00c2',
        '\u00c4', '\u00c1', '\u00c6', '\u00c5'
    );

    /**
     * WEB CODE
     */
    public static $WEB_CODE_CHARACTERS = array (
        "&#1571;", "&#1576;", "&#1578;", "&#1579;", "&#1580;", "&#1581;", "&#1582;", "&#1583;",
        "&#1584;", "&#1585;", "&#1586;", "&#1587;", "&#1588;", "&#1589;", "&#1590;", "&#1591;",
        "&#1592;", "&#1593;", "&#1594;", "&#1601;", "&#1602;", "&#1603;", "&#1604;", "&#1605;",
        "&#1606;", "&#1607;", "&#1608;", "&#1610;", "&#1575;", "&#1577;", "&#1609;", "&#1570;",
        "&#1572;", "&#1569;", "&#1574;", "&#1573;"
    );

    /**
     * URLEncoded Windows-1252
     */
    public static $WINDOWS_1252_URL_ENCODED_CHARACTERS = array (
        "%C3", "%C8", "%CA", "%CB", "%CC", "%CD", "%CE", "%CF",
        "%D0", "%D1", "%D2", "%D3", "%D4", "%D5", "%D6", "%D8",
        "%D9", "%DA", "%DB", "%DD", "%DE", "%DF", "%E1", "%E3",
        "%E4", "%E5", "%E6", "%ED", "%C7", "%C9", "%EC", "%C2",
        "%C4", "%C1", "%C6", "%C5"
    );

    /**
     * URLEncoded UTF-8
     */
    public static $UTF8_URL_ENCODED_CHARACTERS = array (
        "%D8%A3", "%D8%A8", "%D8%AA", "%D8%AB", "%D8%AC", "%D8%AD", "%D8%AE", "%D8%AF",
        "%D8%B0", "%D8%B1", "%D8%B2", "%D8%B3", "%D8%B4", "%D8%B5", "%D8%B6", "%D8%B7",
        "%D8%B8", "%D8%B9", "%D8%BA", "%D9%81", "%D9%82", "%D9%83", "%D9%84", "%D9%85",
        "%D9%86", "%D9%87", "%D9%88", "%D9%8A", "%D8%A7", "%D8%A9", "%D9%89", "%D8%A2",
        "%D8%A4", "%D8%A1", "%D8%A6", "%D8%A5"
    );

    /**
     * URLEncoded WESTERN EUROPEAN WINDOWS
     */
    public static $WESTERN_EUROPEAN_WINDOWS_URL_ENCODED_CHARACTERS = array (
        "%26%231571%3B", "%26%231576%3B", "%26%231578%3B", "%26%231579%3B", "%26%231580%3B", "%26%231581%3B", "%26%231582%3B", "%26%231583%3B",
        "%26%231584%3B", "%26%231585%3B", "%26%231586%3B", "%26%231587%3B", "%26%231588%3B", "%26%231589%3B", "%26%231590%3B", "%26%231591%3B",
        "%26%231592%3B", "%26%231593%3B", "%26%231594%3B", "%26%231601%3B", "%26%231602%3B", "%26%231603%3B", "%26%231604%3B", "%26%231605%3B",
        "%26%231606%3B", "%26%231607%3B", "%26%231608%3B", "%26%231610%3B", "%26%231575%3B", "%26%231577%3B", "%26%231609%3B", "%26%231570%3B",
        "%26%231572%3B", "%26%231569%3B", "%26%231574%3B", "%26%231573%3B"
    );


    /**
     * Special character - Normal
     */
    public static $SPECIAL_CHARACTERS = array (
        '\'', '\"', '`', '~', '!', '@', '#', '$',
        '%',  '^',  '&', '(', ')', '+', '=', '\\',
        '|',  '/',  '?', '<', '>', '{', '}', '[',
        ']',  ' ',  ' '
    );

    /**
     * Special character - URLEncoded
     */
    public static $SPECIAL_URL_ENCODED_CHARACTERS = array (
        "%27", "%22", "%60", "%7E", "%21", "%40", "%23", "%24",
        "%25", "%5E", "%26", "%28", "%29", "%2B", "%3D", "%5C",
        "%7C", "%2F", "%3F", "%3C", "%3E", "%7B", "%7D", "%5B",
        "%5D", "%20", "+"
    );


    public static function convertChacters($string, $fromType, $toType){

        $fromArray = self::getCharactersArray( $fromType );
        $toArray   = self::getCharactersArray( $toType );

        for($i=0; $i<count($fromArray); $i++){
            $string = str_replace($fromArray[$i], $toArray[$i], $string);
        }

        return $string;

    }

    public static function getCharactersArray($type){

        $charArray = array();

        switch ($type){

            case self::$TYPE_LITERAL_CHARACTERS:
                $charArray = self::$LITERAL_CHARACTERS;
                break;

            case self::$TYPE_UTF8_CHARACTERS:
                $charArray = self::$UTF8_CHARACTERS;
                break;

            case self::$TYPE_CP1252_CHARACTERS:
                $charArray = self::$CP1252_CHARACTERS;
                break;

            case self::$TYPE_CP1256_CHARACTERS:
                $charArray = self::$CP1256_CHARACTERS;
                break;

            case self::$TYPE_WEB_CODE_CHARACTERS:
                $charArray = self::$WEB_CODE_CHARACTERS;
                break;

            case self::$TYPE_WINDOWS_1252_URL_ENCODED_CHARACTERS:
                $charArray = self::$WINDOWS_1252_URL_ENCODED_CHARACTERS;
                break;

            case self::$TYPE_UTF8_URL_ENCODED_CHARACTERS:
                $charArray = self::$UTF8_URL_ENCODED_CHARACTERS;
                break;

            case self::$TYPE_WESTERN_EUROPEAN_WINDOWS_URL_ENCODED_CHARACTERS:
                $charArray = self::$WESTERN_EUROPEAN_WINDOWS_URL_ENCODED_CHARACTERS;
                break;

            case self::$TYPE_SPECIAL_CHARACTERS:
                $charArray = self::$SPECIAL_CHARACTERS;
                break;

            case self::$TYPE_SPECIAL_URL_ENCODED_CHARACTERS:
                $charArray = self::$SPECIAL_URL_ENCODED_CHARACTERS;
                break;

            default:
                break;
        }

        return $charArray;
    }
}

?>