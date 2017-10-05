<?

class ResourceArray {

    public static function get_properties_array($filepath) {

        $variables = array();

        try {

            $properties = new Properties();

            $properties->load( file_get_contents($filepath) );

            $variables = $properties->toArray();

        } catch (Exception $e) {
            throw new CustomException('Error : cannont load properties file, ' . $e->getMessage() . "\n");
        }
        
        return $variables;

    }
    
}

?>