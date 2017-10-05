<?php
/* 
 * 
 */

/**
 * Description of XMLNode
 *
 * @author Ahmad
 */
class XMLNode {
    
    public $name;
    public $attributes;
    public $parent;
    public $childs;
    public $value;

}

class Attribute {

    public $name;
    public $value;

    public function Attribute($name=null, $value=null){
        $this->name  = $name;
        $this->value = $value;
    }

}
?>
