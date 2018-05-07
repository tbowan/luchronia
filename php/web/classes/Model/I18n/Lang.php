<?php

namespace Model\I18n ;

class Lang extends \Quantyl\Dao\BddObject {
    
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return "/Media/flags/{$this->code}.svg" ;
    }
    
    public function getName() {
        return \I18n::translate($this->code) ;
    }
    
    public function getTranslations() {
        return Translation::GetFromLang($this) ;
    }
    
    public function getTranslation($key) {
        return Translation::GetFromLangKey($this, $key) ;
    }
    
    // Static
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "wikipage" :
            case "mainpage" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "wikipage" :
            case "mainpage" :
                return ($value == null ? null : \Model\Wiki\Page::createFromId($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCode($code) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `code` = :code" ;
        
        return static::getSingleResult($query, array("code" => $code)) ;
    }
    
    public static function GetFromDNS($dns) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `dns` = :dns" ;
        
        return static::getSingleResult($query, array("dns" => $dns)) ;
    }
    
    private static $_current ;
    
    public static function GetCurrent($server = null) {
        
        if ($server == null && self::$_current != null) {
            return self::$_current ;
        }
        
        if ($server == null) {
            $server = \Quantyl\Server\Server::getInstance() ;
        }
        
        self::$_current  = self::GetFromDNS($server->getServerHostname()) ;
        
        if (self::$_current  === null) {
            self::$_current  = self::GetFromCode($server->getConfig()->get("I18n.lang")) ;
        }
        
        return self::$_current  ;
    }

}