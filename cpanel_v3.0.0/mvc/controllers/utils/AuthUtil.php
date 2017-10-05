<?php

/*
 *
 */

/**
 * Description of AuthUtil
 *
 * @author Ahmad
 */

class AuthUtil {

    public static function authenticate_parent(){
        
        $status = SERVER_ERROR;

        try {

            $request = HttpRequest::get_instance();

            $parent_id  = $request->get_int_parameter("parent_id");
            $key        = $request->get_parameter("key");
            
            $parent_id  = ( $parent_id  > 0 ) ? $parent_id  : -1;
            $key        = ( $key    != null ) ? $key    : "no_key";
                        
            $key_exist  = ParentDB::check_key($parent_id, $key);
            
            if( $key_exist ){
                
                $status = SUCCESS;
            
            }else{
                
                $status = NOT_EXIST;
            }
            
        } catch (Exception $e) {
            $status = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
    }

    public static function authenticate_driver(){

        $status = SERVER_ERROR;

        try {

            $request = HttpRequest::get_instance();

            $driver_id  = $request->get_int_parameter("driver_id");
            $key        = $request->get_parameter("key");
            
            $driver_id  = ( $driver_id  > 0 ) ? $driver_id  : -1;
            $key        = ( $key    != null ) ? $key    : "no_key";
                        
            $key_exist  = DriverDB::check_key($driver_id, $key);
            
            if( $key_exist ){
                
                $status = SUCCESS;
            
            }else{
                
                $status = NOT_EXIST;
            }
            
        } catch (Exception $e) {
            $status = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
    }

    public static function authenticate_student(){
        
        $status = SERVER_ERROR;

        try {

            $request = HttpRequest::get_instance();

            $student_id = $request->get_int_parameter("student_id");
            $key        = $request->get_parameter("key");
            
            $student_id = ( $student_id  > 0 ) ? $student_id  : -1;
            $key        = ( $key     != null ) ? $key    : "no_key";

            
            $options = array( "student_id" => $student_id,
                              "key"        => $key         );
            
            $student = StudentDB::get_student_with($options);
            
            if(  $student != null  &&  $student->student_id > 0  ){
                
                $status = SUCCESS;
            
            }else{
                
                $status = NOT_EXIST;
            }
            
        } catch (Exception $e) {
            $status = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
    }
    
    public static function authenticate_teacher(){
        return SUCCESS;
    }
    
    public static function authenticate_absence_supervisor(){

        $status = SERVER_ERROR;

        try {

            $request = HttpRequest::get_instance();

            $supervisor_id = $request->get_int_parameter("supervisor_id");
            $key           = $request->get_parameter("key");
            
            $supervisor_id = ( $supervisor_id  > 0 ) ? $supervisor_id  : -1;
            $key           = ( $key        != null ) ? $key            : "no_key";

            
            $options = array(
                "supervisor_id" => $supervisor_id,
                "key"           => $key
            );

            $supervisor  = AbsenceSupervisorDB::get_supervisor_with($options);
            
            if( !empty($supervisor) ){
                
                $status = SUCCESS;
            
            }else{
                
                $status = NOT_EXIST;
            }
            
        } catch (Exception $e) {
            $status = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
    }
    
    public static function authenticate_bus_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_attendance_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_admission_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_behavior_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_health_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_event_supervisor(){
        return SUCCESS;
    }
    
    public static function authenticate_financial_supervisor(){
        return SUCCESS;
    }

}
