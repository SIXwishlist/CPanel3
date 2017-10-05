<?php
/*
 *
 */

/**
 * Description of AbuseManage
 *
 * @author Ahmad
 */

class CountryManage extends ManageController {


    public static function get_countries(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                $section_id = ( $section_id <= 0 ) ? -1 : $section_id;
                $user_id    = ( $user_id    <= 0 ) ? -1 : $user_id;

                $status = -1;
                
                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $countries       = CountryDB::get_countries($index, $count);
                $countries_count = CountryDB::get_countries_count();

                $countries_array_list = self::get_formated_array($countries);

                $output_array["countries"]       = $countries_array_list;
                $output_array["countries_count"] = $countries_count;

                $status = SUCCESS;

                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    public static function get_formated_array(array $countries){

        $countries_array_list = array();

        for ( $i=0; $i<count($countries); $i++ ){

            $country = $countries[$i];

            $countryObject = self::get_formated_object($country);

            $countries_array_list[] = $countryObject;
        }

        return $countries_array_list;
    }

    private static function get_formated_object($country){

        $country_object = array();

        $country_object["country_id"] = $country->country_id;
        $country_object["name_ar"]    = $country->name_ar;
        $country_object["name_en"]    = $country->name_en;
        $country_object["code"]       = $country->code;
        $country_object["dial"]       = $country->dial;
        $country_object["tld"]        = $country->tld;

        return $country_object;
    }

}

?>