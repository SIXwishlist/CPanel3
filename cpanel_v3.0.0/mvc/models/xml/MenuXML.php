<?php
/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/xml/XMLDocumentParser.php';
//include_once BASE_DIR.'/mvc/libraries/xml/XMLDocumentWriter.php';
//include_once BASE_DIR.'/mvc/libraries/xml/XMLNode.php';

/**
 * Description of MenuXML
 *
 * @author Ahmad
 */
class MenuXML {

    public static $dir = 'xml/';
    public static $filename = "menu.xml";

    public static $xmlRoot;

    public static function load(){

        $path = UPLOAD_DIR . self::$dir;

        $parser = new XMLDocumentParser( $path . self::$filename );

        self::$xmlRoot = $parser->getXMLRoot();

    }


    public static function getSectionPathArray($section_id){

        $linksArray  = array();

        $linksArray = self::getSectionPathArrayReversed( $linksArray, $section_id );

        $linksArray = array_reverse( $linksArray );

        return $linksArray;

    }

    private static function getSectionPathArrayReversed(array $array, $section_id){

        try {

            $section = self::getSectionById($section_id);

            if($section!=null){
                $href = '?page=sections&parent_id='.$section->getSectionId();
            }

            $name = ( Dictionary::$lang == "ar" ) ? $section->getNameAr() : $section->getNameEn();

            $link = new Link();
            $link->href  = $href;
            $link->label = $name;

            $array[] = $link;

            if( $section->getParentId() > 0 ){

                $array = self::getSectionPathArrayReversed( $array, $section->getParentId() );

            }else{

                $link = new Link();
                $link->href  = "?page=sections&section_id=-1";
                $link->label = Dictionary::get_text('Sections_lbl');

                $array[] = $link;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $array;
    }


    public static function getSectionById($section_id=0){

        $section = new Section;

        try {

            $element = self::searchChilds(self::$xmlRoot, "section", "id", $section_id);

            if( $element != null ){
                $section = self::getSectionFromXMLNode($element);
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $section;
    }


    private static function searchChilds(XMLNode $element, $tag, $name, $value){

        $matchElement = null;

        foreach ( $element->childs as $child ){

            if( $child->name == $tag ){
                if( self::searchAttributes( $child->attributes, $name, $value ) ){
                    $matchElement = &$child;
                }
            }

            if(  $matchElement != null ){
                break;
            }

            if(  $child->childs != null ){
                $matchElement = self::searchChilds($child, $tag, $name, $value );
            }

        }

        return $matchElement;
    }

    private static function searchAttributes($attributes, $name, $value){

        foreach ( $attributes as $attribute ){
            if( $attribute->name == $name && $attribute->value == $value){
                return true;
            }
        }

        return false;
    }

    private static function getSectionFromXMLNode(XMLNode $element){

        $section = new Section();

        try {

            $section->setSectionId( $element->attributes[0]->value );
            $nameAr = $element->attributes[1]->value;
            $nameEn = $element->attributes[2]->value;
            $section->setNameAr( $nameAr );
            $section->setNameEn( $nameEn );
            $section->setForwardTo( $element->attributes[3]->value );
            $section->setOrder( $element->attributes[4]->value );
            $section->setParentId( $element->attributes[5]->value );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill section variables: \n' . $e->getMessage() . "\n" );
        }

        return $section;
    }


    public static function convertXMLEncoding(){

        $childs = self::$xmlRoot->childs;

        self::convertElementsEncoding( $childs );

    }

    private static function convertElementsEncoding(array $elements){

        foreach ( $elements as $element ){

            $element->attributes[0]->value = $element->attributes[0]->value;
            $nameAr = $element->attributes[1]->value;
            $nameEn = $element->attributes[2]->value;
            $element->attributes[1]->value = $nameAr;
            $element->attributes[2]->value = $nameEn;
            $element->attributes[3]->value = $element->attributes[3]->value;
            $element->attributes[4]->value = $element->attributes[4]->value;
            $element->attributes[5]->value = $element->attributes[5]->value;

            $childs = $element->childs;

            self::convertElementsEncoding( $childs );

        }
    }

    public static function getXMLOutput(){

        $xmlString = '';

        $writer = new XMLDocumentWriter(self::$xmlRoot);

        $writer->setEncoding( DEFAULT_ENCODING );
        $writer->convertToString();

        $xmlString = $writer->getXMLString();

        return $xmlString;
    }


    public static function addSection(Section $section){

        $element  = self::searchChilds( self::$xmlRoot, "section", "id", $section->getParentId() );
        $newChild = self::getXMLNodeFromSection($section);

        $element->childs [] = $newChild;

    }

    public static function updateSection(Section $section){

        $element        = self::searchChilds( self::$xmlRoot, "section", "id", $section->getSectionId() );

        $updatedElement = self::getXMLNodeFromSection($section);

        $element->attributes  = $updatedElement->attributes;
        $element->name        = $updatedElement->name;
        $element->value       = $updatedElement->value;

    }

    public static function removeSection(Section $section){

        //$element = self::searchChilds( self::$xmlRoot, "section", "id", $section->getParentId() );
        //$elementIndex = self::searchChildsIndex( self::$xmlRoot, "section", "id", $section->getSectionId() );
        //if( $element == null ){
            //$element = self::$xmlRoot;
        //}
        //unset( $element->childs[$elementIndex] );

        $element = self::searchChilds( self::$xmlRoot, "section", "id", $section->getSectionId() );

        unset( $element );

    }


    public static function saveChanges(){

        $path = UPLOAD_DIR . self::$dir;

        $writer = new XMLDocumentWriter(self::$xmlRoot);

        $writer->setEncoding( DEFAULT_ENCODING );
        $writer->convertToString();

        $stat = $writer->saveXML($path, self::$filename);

        return $stat;
    }


    public static function build(){

        $sections = SectionDB::getSections(0);

        $root = new XMLNode();
        $root->name = "menu";

        self::addSectionsToElement( $root, $sections );

        self::$xmlRoot = &$root;
    }

    private static function addSectionsToElement(XMLNode &$element, array $sections ){

        $result = 0;

        try {

            foreach ($sections as $section) {

                if( $section->getActive() ){
                    $child = self::getXMLNodeFromSection($section);
                    $element->childs [] = $child;

                    $subSections = SectionDB::getSections($section->getSectionId());
                    if( count($subSections) > 0 ){
                        self::addSectionsToElement($child, $subSections );
                    }
                }
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }
        return $result;
    }

    private static function getXMLNodeFromSection(Section $section){

        $element = new XMLNode();

        try {

            $element->name = "section";

            $element->attributes[] = new Attribute( "id",        $section->getSectionId() );
            $nameAr = $section->getNameAr();
            $nameEn = $section->getNameEn();
            $element->attributes[] = new Attribute( "nameAr",    $nameAr );
            $element->attributes[] = new Attribute( "nameEn",    $nameEn );
            $element->attributes[] = new Attribute( "forwardTo", $section->getForwardTo() );
            $element->attributes[] = new Attribute( "order",     $section->getOrder() );
            $element->attributes[] = new Attribute( "parent_id",  $section->getParentId() );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill section variables: \n' .  $e->getMessage() . "\n" );
        }

        return $element;
    }

}
?>
