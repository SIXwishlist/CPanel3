<?php
/*
 *
 */

/**
 * Description of Meta
 *
 * @author Ahmad
 */
class Meta {

    public $attributes;

    public function Meta($attributes=null) {

        if( $attributes != null ){
            $this->attributes = $attributes;
        }else{
            $this->attributes = array();
        }

    }

}
?>
