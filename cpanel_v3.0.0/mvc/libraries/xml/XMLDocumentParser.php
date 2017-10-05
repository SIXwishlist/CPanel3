<?php
/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/xml/XMLNode.php';

/**
 * Description of XMLParser
 *
 * @author Ahmad
 */

class XMLDocumentParser {

    private $data;
    private $arrayElements;
    private $tags;

    private $rootElement;
    private $parseIndex;
    private $parsed = false;

    public function XMLDocumentParser($filename=null){

        if($filename!=null){
            $this->loadFile($filename);
        }
    }

    public function loadFile($filename){

        //$this->data = implode("", file($filename));
        
        try {
            $handle   = fopen($filename, "r") or die("Can't open file: ".$filename);
            $contents = fread($handle, filesize($filename));
            fclose($handle);
        } catch (CustomException $exc) {
            echo $exc->getTraceAsString();
        }

        $this->data = $contents;

        $fromType = TextConverter::$TYPE_UTF8_URL_ENCODED_CHARACTERS;
        $toType   = TextConverter::$TYPE_LITERAL_CHARACTERS;

        $this->data = TextConverter::convertChacters($this->data, $fromType, $toType );

        $this->startParsing();

    }

    public function loadString(string $data){

        $this->data = $data;

        $this->startParsing();
    }

    private function startParsing(){

        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $this->data, $this->arrayElements, $this->tags);
        xml_parser_free($parser);

//        print_r( $this->data );
//        print_r( $this->arrayElements );
//        print_r( $this->tags );

        $this->parseXML();
    }

    private function parseXML(){

        $this->rootElement = $this->getXMLNodeObject( $this->arrayElements[0] );

        $this->rootElement->childs = $this->parseChilds(1);

        $this->parsed = true;

    }

    private function parseChilds($index){

        $elements = array();

        for( $this->parseIndex = $index; $this->parseIndex < count($this->arrayElements); $this->parseIndex++ ){

            $arrayElement = $this->arrayElements[$this->parseIndex];

            $element = $this->getXMLNodeObject( $arrayElement );

            if( $arrayElement["type"] == "open" ){
                $element->childs = $this->parseChilds($this->parseIndex+1);
            }else if( $arrayElement["type"] == "close" ){
                break;
            }else if( $arrayElement["type"] == "complete" ){
            }

            $elements[] = $element;
        }

        return $elements;
    }

    private function getXMLNodeObject(array $arrayElement){

        $newXMLNode = new XMLNode();

        $newXMLNode->name       = $arrayElement["tag"];
        $newXMLNode->attributes = $this->getAttributes( $arrayElement["attributes"] );
        $newXMLNode->value      = trim( $arrayElement["value"] );

        return $newXMLNode;
    }

    private function getAttributes(array $arrayElements = null){

        if( $arrayElements == null ){
            return null;
        }

        $attributes = array();

        foreach ( $arrayElements  as $key => $value) {

            $attribute = new Attribute();

            $attribute->name  = $key;
            $attribute->value = trim( $value );

            $attributes[] = $attribute;
        }

        return $attributes;
    }

    public function getXMLRoot(){
        if($this->parsed){
            return $this->rootElement;
        }

        return null;
    }

    public function isParsed(){
        return $this->parsed;
    }

}

?>
