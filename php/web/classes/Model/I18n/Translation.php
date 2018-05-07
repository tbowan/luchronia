<?php

namespace Model\I18n ;

class Translation extends \Quantyl\Dao\BddObject {
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "lang" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "lang" :
                return Lang::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    
    // Get Some sets
    
    public static function GetFromLang(Lang $l) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `lang` = :lid"
                . " order by `key`" ;
        return static::getResult($query, array("lid" => $l->getId())) ;
    }
    
    public static function GetFromLangKey(Lang $l, $key) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " `lang` = :lid and"
                . " `key`  = :key"
                . " order by `key`" ;
        return static::getSingleResult($query, array(
            "lid" => $l->getId(),
            "key" => $key
                )) ;
        
    }
    
    public static function GetFromKey($key) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `key` = :key" ;
        return static::getResult($query, array("key" => $key)) ;
    }
    
    public static function GetFirstUntranslated(Lang $l) {
        $query = "  select"
                . "     i1.`key` as `key`"
                . " from"
                . "     (select `key` from i18n_translation group by `key`) as i1"
                . " left join"
                . "     (select `key` from i18n_translation where lang = :lid group by `key`) as i2"
                . " on i1.key = i2.key"
                . " where isnull(i2.`key`)"
                . " order by i1.`key`"
                . " limit 1" ;
        $row = static::getSingleRow($query, array(
            "lid" => $l->getId()
                )) ;
        return ($row === false ? "" : $row["key"] );
    }
    
    // Create translation
    
    public static function createTranslation($key, $translation, Lang $lang) {
        $old = static::GetFromLangKey($lang, $key) ;
        if ($old !== null) {
            $old->translation = $translation ;
            $old->update() ;
            return $old ;
        } else {
            return static::createFromValues(array(
                "lang"        => $lang,
                "key"         => $key,
                "translation" => $translation
            )) ;
        }
    }
    
    public static function GetBySearch(Lang $l, $key, $translation) {
                $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " `lang`          =    :lid and"
                . " `key`           like :key and"
                . " `translation`   like :translation"
                . " order by `key`" ;
        return static::getResult($query, array(
            "lid"           => $l->getId(),
            "key"           => "%$key%",
            "translation"   => "%$translation%"
                )) ;
    }
    
    
    
}
