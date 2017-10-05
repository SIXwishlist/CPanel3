<?php
/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/xml/XMLNode.php';

/**
 * Description of XMLWriter
 *
 * @author Ahmad
 */

class XMLDocumentWriter {

    private $stringBuffer;

    private $encoding = "UTF-8";

    private $rootElement;
    private $converted = false;

    public function XMLDocumentWriter($rootElement=null){
        if( $rootElement != null ){
            $this->setRootElement($rootElement);
        }
    }

    public function setRootElement($rootElement){

        $this->rootElement = $rootElement;
    }

    public function getEncoding(){
        return $this->encoding;
    }

    public function setEncoding($encoding){
        $this->encoding = $encoding;
    }

    public function convertToString(){

        $this->stringBuffer = '<?xml version="1.0" encoding="'.$this->encoding.'"?>';

        $this->stringBuffer .= $this->getStringFromObject($this->rootElement);

        $this->converted = true;
    }

    private function getStringFromObject($element){

        $buffer = '';

        $buffer .= $this->getStartTag( $element );
        $buffer .= $this->getChildsOrValue( $element );
        $buffer .= $this->getEndTag( $element );

        return $buffer;
    }

    private function getStartTag($element){

        $buffer = '';

        $buffer = '<' . $element->name;
        if( $element->attributes != null ){
            $buffer .= $this->getAttributes( $element->attributes );
        }
        $buffer .= '>';

        return $buffer;
    }

    private function getEndTag($element){

        $buffer = '';
        $buffer = '</' . $element->name . '>';
        return $buffer;
    }

    private function getChildsOrValue($element){

        $buffer = '';

        if( $element->childs == null ){

            $buffer .= $element->value;

        }else{

            foreach ( $element->childs as $child ){

                $buffer .= $this->getStringFromObject($child);

            }
        }

        return $buffer;
    }

    private function getAttributes($attributes){

        $buffer = '';

        foreach ( $attributes as $attribute ){
            $buffer .= ' ' . $attribute->name . '="' . $attribute->value . '"';
        }

        return $buffer;
    }

    public function saveXML($path, $filename){

        if($this->converted){

            if( !is_dir($path) ){
                mkdir($path);
            }

            $fp      = fopen($path.$filename, "w+");
            $success = fwrite( $fp, $this->stringBuffer );
            $success = fclose($fp);
            
        }

        $state = (  $actual == count($this->stringBuffer)  );

        return $state;
    }

    public function getXMLString(){
        if($this->converted){
            return $this->stringBuffer;
        }

        return null;
    }

    public function isConverted(){
        return $this->converted;
    }

}

?>
