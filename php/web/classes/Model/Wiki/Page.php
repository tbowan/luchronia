<?php

namespace Model\Wiki ;

class Page extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public function create() {
        parent::create();
        $this->equiv = $this ;
        $this->update() ;
    }
    
    public function update() {
        $this->last_update = time() ;
        parent::update();
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "equiv" :
                return ($value == null ? null : Page::createFromId($value)) ;
            case "lang" :
                return \Model\I18n\Lang::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "equiv" :
                echo "equiv to : "  ; var_dump($value) ;
                return ($value == null ? null : $value->getId()) ;
            case "lang" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getNameField() {
        return "title" ;
    }

    public static function getAllSorted() {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " order by title" ;
        
        return static::getResult($query, array()) ;
    }
    
    public static function GetByLangAndName(\Model\I18n\Lang $lang, $name) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where `lang` = :lid and `title` = :title",
                array("lid" => $lang->id, "title" => $name)
                ) ;
    }
    
    public static function GetFromLang(\Model\I18n\Lang $lang) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `lang` = :lid",
                array("lid" => $lang->id)
                ) ;
    }
    
    public function getEquiv() {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `equiv` = :eid",
                array("eid" => $this->equiv->id)
                ) ;
    }
    
    public static function canMerce(Page $p1, Page $p2) {
        
        $row = static::getSingleRow(
                "   select"
                . "     true"
                . " from"
                . "     (select id, lang from wiki_page where equiv = :eq1) as p1,"
                . "     (select id, lang from wiki_page where equiv = :eq2) as p2"
                . " where"
                . "     p1.lang = p2.lang",
                array("eq1" => $p1->equiv->id, "eq2" => $p2->equiv->id)
                ) ;
        
        return $row === false ;
        
    }
    
    public static function Merge(Page $p1, Page $p2) {
        
        $min = min($p1->equiv->id, $p2->equiv->id) ;
        
        static::execRequest(
                "update wiki_page set equiv = :min where equiv = :i1 or equiv = :i2",
                array("min" => $min, "i1" => $p1->equiv->id, "i2" => $p2->equiv->id)
                ) ;
        
    }
}
