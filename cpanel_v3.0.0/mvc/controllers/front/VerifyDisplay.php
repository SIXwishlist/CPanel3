<?php
/*
 *
 */

/**
 * Description of VerifyDisplay
 *
 * @author Ahmad
 */

class VerifyDisplay {
    
    public static $date_format = 'Y-m-d';

    public static function get_page(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $lang   = Dictionary::get_language();

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $target     = TargetDB::get_target(HOME_PAGE);

            if( $target != null ){
                $layout->title  = Dictionary::get_text_by_lang($target, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($target, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($target, "desc") );
            }

            $status = -1;
            
            $user_id = $request->get_int_parameter("user_id");
            $ukey    = $request->get_parameter("ukey");
            
            $user = UserDB::get_user($user_id);

            
            if( !empty($user) ){

                if( $user->key == $ukey ){
                    
                    $create_date = date(self::$date_format);
                    
                    $user->status  = USER_STATUS_EMAIL_VERIFIED;
                    $user->created = $create_date;
                    $user->updated = $create_date;

                    $status = UserDB::update_user($user);
                    
                    $session->set_attribute( "user_id",   $user->user_id   );
                    $session->set_attribute( "rule_id",   $user->rule_id   );
                    $session->set_attribute( "status",    $user->status    );
                    $session->set_attribute( "name",      $user->name      );

                }
            }

            $page_data = array(
                "status" => $status
            );

            
            $frame_data["lang_ar"] = BASE_URL."ar";
            $frame_data["lang_en"] = BASE_URL."en";

            $frame_data["slide_mode"] = false;
            $frame_data["wide_main"]  = 'wide_main';
            
            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('verify.tpl.php', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $front_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);         
            
            QueryUtil::close();
            
            if( CACHING_ENABLED ){
                $cache->set_data($lang, $output_string);
            }
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }

}

?>