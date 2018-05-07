<?php

namespace Model\Game\Building ;

class Need extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->provided > $this->needed) {
            $this->provided = $this->needed ;
        }
        parent::update() ;
    }
    
    public function getRemain() {
        return ceil( 100 * ($this->needed - $this->provided)) / 100 ;
    }
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "site" :
                return Site::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "site" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromSite(Site $s) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `site` = :sid",
                array("sid" => $s->id)) ;
    }
}
