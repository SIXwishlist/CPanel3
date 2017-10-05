<?php
/*
 *
 */

/**
 * Description of DeviceIOSManage
 *
 * @author Ahmad
 */

class DeviceIOSManage {

    public static $dir = "devices";
    public static $date_format = 'Y-m-d H:i:s';

    public static function add_device(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_ADD);

            if( $permitted ){

                $device  = self::read_device_form();
                
                $status = DeviceIOSDB::add_device($device);
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function update_device(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = AdminManage::check_admin_permission(ACTION_EDIT);

            if( $permitted ){

                $device = self::read_device_form();

                $device_id  = $request->get_int_parameter("device_id");

                //$old_device = DeviceIOSDB::get_device($device_id);
                
                $status = DeviceIOSDB::update_device($device);

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function remove_device(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_EDIT);

            if( $permitted ){

                $device_id = $request->get_int_parameter("device_id");

                $device    = DeviceIOSDB::get_device($device_id);

                $status    = DeviceIOSDB::remove_device($device);

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    
    public static function get_devices(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_VIEW_ALL);
            
            if( $permitted ){

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $devices      = DeviceIOSDB::get_devices($index, $count);
                $result_count = DeviceIOSDB::get_devices_count();

                $devices_array_list = self::get_formated_array($devices);

                $output_array["devices"]      = $devices_array_list;
                $output_array["result_count"] = $result_count;
                
                $status = intval($permitted);

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

    public static function search_devices(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $devices_array_list = array();
            $result_count    = 0;

            $permitted = AdminManage::check_admin_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                $device_params = array();

                $device_params['dev_id']  = $request->get_int_parameter("dev_id");
                $device_params['reg_id']  = $request->get_parameter("reg_id");
                $device_params['phone']   = $request->get_parameter("phone");
                $device_params['created'] = $request->get_parameter("created");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $devices      = DeviceIOSDB::search_devices($device_params, $index, $count, '`device_id`', 'ASC');
                $result_count = DeviceIOSDB::search_devices_count($device_params);

                $devices_array_list = self::get_formated_array($devices);
            
                $output_array["devices"]      = $devices_array_list;
                $output_array["result_count"] = $result_count;
                
                $status = intval($permitted);

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


    public static function import_devices(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_ADD);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){

                $file = FileUtil::save_file("file", "file", $path);

                $devices_array_list = ExcelUtil::import_from_excel( $path . '/' . $file );

                unset( $devices_array_list[0] );

                FileUtil::remove_file($path, $file);

                $device_records = array();

                foreach ($devices_array_list as $row) {

                    $device = new stdClass();

                    $device->name = $row[0];

                    $device_records[] = $device;

                }

                $status = DeviceIOSDB::add_devices_list($device_records);

                $affected_rows = QueryUtil::get_affected_rows();

                if( $affected_rows == count($device_records) ){
                    $status = SUCCESS;
                }

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

    public static function export_devices(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                $device_params = array();

                $device_params["device_id"]   = $request->get_int_parameter("device_id");
                $device_params["name"]        = $request->get_parameter("name");
                $device_params["all_results"] = true;

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $devices            = DeviceIOSDB::search_devices($device_params, $index, $count, '`device_id`', 'ASC');

                $devices_array_list = self::get_formated_array($devices);

                $devices_headers = array();
                $devices_data    = array();

                if( count($devices_array_list) > 0 ){

                    $devices_headers = array_keys($devices_array_list[0]);

                    foreach ($devices_array_list as $row) {
                        $devices_data[] = array_values($row);
                    }
                }

                $status = ExcelUtil::export_to_excel( $devices_data, $devices_headers, "devices.xls" );


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

    public static function export_devices_sample(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){


                $devices_headers = array( "name" );
                $devices_data    = array();

                $status = ExcelUtil::export_to_excel( $devices_data, $devices_headers, "devices.xls" );

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


    private static function get_formated_array(array $devices){

        $devices_array_list = array();

        for ( $i=0; $i<count($devices); $i++ ){

            $device = $devices[$i];

            $device_object = self::get_formated_object($device);

            $devices_array_list[] = $device_object;
        }

        return $devices_array_list;
    }

    private static function get_formated_object($device){

        $device_object = array();
                
        $device_object["dev_id"]       = $device->dev_id;
        
        $device_object['device_token'] = $device->device_token;
        $device_object['phone']        = $device->phone;
        $device_object['created']      = $device->created;

        return $device_object;
    }


    private static function read_device_form(){

        $device = new stdClass();

        try {

            $device_arr = array();
            
            $request = HttpRequest::get_instance();
            
            $device_arr['dev_id']       = $request->get_int_parameter("dev_id");
            
            $device_arr['device_token'] = $request->get_parameter("device_token");
            $device_arr['phone']        = $request->get_parameter("phone");
            $device_arr['created']      = $request->get_parameter("created");

            $device = (object) $device_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read device form', $e );//from php 5.3 no need to custum
        }

        return $device;
    }

}

?>