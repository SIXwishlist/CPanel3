<?php

/*
 * 
 */

/**
 * Description of TplLoader
 *
 * @author Arak
 */
class TplLoader {

    public static function get_tpl_data($file='', $folder=null, $data=null){
     
        $tpl_data = '';

        if( $folder == null || $folder == '' ){
            $tpl_path =  BASE_DIR.'/'.$file;
        }else{
            $tpl_path =  BASE_DIR.'/'.$folder.'/'.$file;
        }

        if( file_exists($tpl_path) ){

            try{

                if ( !is_null($data) && is_array($data) ){
                    extract($data);
                    
                    $data = (object) $data;
                }
                
                ob_start();

                include_once $tpl_path;

                $tpl_data = ob_get_contents();

                ob_end_clean();

            } catch (Exception $e) {
                throw new CustomException( 'Error in : loading tpl file: '.$e->getMessage().'');
            }

        }else{
            throw new CustomException('Error : tpl file: '.$tpl_path.', was not found');
        }

        unset($data, $tpl_path, $folder);
        
        return $tpl_data;
        
    }
    
}

?>
