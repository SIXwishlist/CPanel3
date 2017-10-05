<?php

/**
 * Description of DateUtil
 * @author Ahmad
 */

//$expireDateString = date( "Y-m-d H:i:s", mktime(20, 08, 00, 03, 17, 2020) );
//$currentDate      = date( "Y-m-d H:i:s" );
//
//$expireDateTime   = strtotime($expireDateString);
//$currentDateTime  = strtotime($currentDate);
//
//if( $currentDateTime >= $expireDateTime ){
//    die("web site has been automatically removed ...");
//}

class DateUtil {

    /**
     *
     */
    public static function getCurrentDateTimeFormated() {

        $cDate = date("Y-m-d H:i:s");

        return $cDate;
    }

    public static function getDateTimeFormated($hours, $minutes, $seconds, $month, $day, $year) {

        $cDate = date("Y-m-d H:i:s", mktime($hours, $minutes, $seconds, $month, $day, $year));

        return $cDate;
    }

    public static function getTime($dateString = null) {

        if ($dateString == null) {
            $dateString = self::getCurrentDateTimeFormated();
        }

        $time = strtotime($dateString);

        return $time;
    }

    public static function getTimeForDate($hours, $minutes, $seconds, $month, $day, $year) {

        $dateString = self::getDateTimeFormated($hours, $minutes, $seconds, $month, $day, $year);

        $time = strtotime($dateString);

        return $time;
    }

}

?>