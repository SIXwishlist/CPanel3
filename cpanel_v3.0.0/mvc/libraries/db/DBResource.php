<?php
/*
 *
 */

/**
 * Description of DBResource
 *
 * @author Ahmad
 */

class DBResource extends CustomException {

    private static $database_config = null;
    private static $resources = null;
    
    private static $instance  = null;

    public static function get_instance(){

        if ( self::$instance == null) {

            self::$instance = new DBResource();

        }

        return self::$instance;
    }

    private function DBResource(){

        try {

            //$resources_array = parse_ini_file(BASE_DIR.'/config/ini/resources.ini');

            //self::$resources = $resources_array["resources"];

            self::$database_config = ResourceArray::get_properties_array(BASE_DIR.'/config/properties/database.properties');

            $resources_array = ResourceArray::get_properties_array(BASE_DIR.'/config/properties/database.sources.properties');

            $count = $resources_array["sources.count"];

            $resources = array();

            for ($i = 1; $i<=$count; $i++) {
                $resources[] = $resources_array["sources.src".$i];
            }

            self::$resources = $resources;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get db resource: \n' . $e->getMessage() . "\n");
        }
    }
    
    public function pick_resource(){

        $i = rand(0, count(self::$resources)-1 );

        $resource_string = self::$resources[$i];

        $resource = json_decode( $resource_string );

        return $resource;

    }
    
    public function get_db_config(){
        return self::$database_config;
    }

}

?>