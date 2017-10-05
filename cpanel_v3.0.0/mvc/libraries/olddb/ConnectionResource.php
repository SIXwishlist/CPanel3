<?php
/*
 *
 */

/**
 * Description of ConnectionResource
 *
 * @author Ahmad
 */

class ConnectionResource extends CustomException {

    private static $resources = null;
    private static $instance  = null;

    public static function get_instance(){

        if ( self::$instance == null) {

            self::$instance = new ConnectionResource();

        }

        return self::$instance;
    }

    private function ConnectionResource(){

        //$resources_array = parse_ini_file(BASE_DIR.'/config/ini/resources.ini');

        //self::$resources = $resources_array["resources"];

        $resources_array = ResourceArray::get_properties_array(BASE_DIR.'/config/properties/database.sources.properties');
		
        $count = $resources_array["sources.count"];

        $resources = array();

        for ($i = 1; $i<=$count; $i++) {
            $resources[] = $resources_array["sources.src".$i];
        }

        self::$resources = $resources;

    }
    
    public function pick_resource(){

        $i = rand(0, count(self::$resources)-1 );

        $resource_string = self::$resources[$i];

        $resource = json_decode( $resource_string );

        return $resource;

    }

}

?>