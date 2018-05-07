<?php

namespace Model\Game\Avatar ;

class Item extends \Quantyl\Dao\BddObject {
    
    use \Model\Illustrable ;
    
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "race" :
                return ($value == null ? null : \Model\Enums\Race::GetById($value)) ;
            case "sex" :
                return ($value == null ? null : \Model\Enums\Sex::GetById($value)) ;
            case "layer" :
                return Layer::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "race" :
            case "sex" :
                return  ($value == null ? null : $value->getId() ) ;
            case "layer" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFiltered(\Model\Enums\Race $r, \Model\Enums\Sex $s, Layer $l) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  (`race`  = :rid or isnull(`race`)) and"
                . "  (`sex`   = :sid or isnull(`sex`) ) and"
                . "  `layer` = :lid",
                array(
                    "rid" => $r->getId(),
                    "sid" => $s->getId(),
                    "lid" => $l->getId()
                )) ;
    }

    public function getImgPath() {
        return 
                "/Media/icones/Model/Avatar/"
                . $this->filename
                ;
    }

    public function getThumbImage($class = null) {
        
        $path = "/Media/icones/Model/Avatar/" . $this->filename ;
        $newpath = preg_replace('#(.*/)([^/]+)#', '\1radio-\2', $path) ;
        
                return new \Quantyl\XML\Html\Img(
                $newpath,
                $this->getName(),
                $class
                ) ;
    }
    
    public function getName() {
        return $this->filename ;
    }

}
