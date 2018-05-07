<?php

namespace Model\Game ;

class Citizenship extends \Quantyl\Dao\BddObject {
 
    public function accept() {
        $this->from = time() ;
        $this->update() ;
    }
    
    public function refuse() {
        $this->from = time() ;
        $this->to   = $this->from ;
        $this->update() ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "city" :
                return \Model\Game\City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "city" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function IsCitizen(Character $char, City $city) {
        $res = self::getSingleRow(""
                . " select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city`      = :city and"
                . "     `character` = :char and"
                . "     `from`      < :t    and"
                . "     ( isnull(`to`) or `to` > :t)",
                array (
                    "city" => $city->getId(),
                    "char" => $char->getId(),
                    "t"    => time()
                )) ;
        return $res !== false ;
    }
    
    public static function GetCitizenship(Character $char, City $city) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city`      = :city and"
                . "     `character` = :char and"
                . "     `from`      < :t    and"
                . "     ( isnull(`to`) or `to` > :t)",
                array (
                    "city" => $city->getId(),
                    "char" => $char->getId(),
                    "t"    => time()
                )) ;
    }
    
    public static function HasPending(Character $char, City $city) {
        return self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city`      = :city and"
                . "     `character` = :char and"
                . "     isnull(`to`)",
                array (
                    "city" => $city->getId(),
                    "char" => $char->getId()
                )) ;
        return $cnt > 0 ;
    }
    
    public static function GetPending(Character $char, City $city) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city`      = :city and"
                . "     `character` = :char and"
                . "     isnull(`to`)",
                array (
                    "city" => $city->getId(),
                    "char" => $char->getId()
                )) ;
    }
    
    public static function GetFromCitizen(Character $char) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :char and"
                . "     not isnull(`from`) and"
                . "         isnull(`to`)",
                array (
                    "char" => $char->getId()
                )) ;
    }
    
    public static function GetCitizen(City $c) {
        return Character::getResult(""
                . " select ch.*"
                . " from"
                . "     `" . self::getTableName() . "` as ci,"
                . "     `" . Character::getTableName() . "` as ch"
                . " where"
                . "     ci.city = :id           and"
                . "     ci.`character` = ch.id  and"
                . "     not isnull(ci.`from`)   and"
                . "         isnull(ci.`to`)",
                array (
                    "id" => $c->getId()
                )) ;
    }
    
    public static function CountCitizen(City $c) {
        return self::getCount(""
                . " select count(ch.id) as c"
                . " from"
                . "     `" . self::getTableName() . "` as ci,"
                . "     `" . Character::getTableName() . "` as ch"
                . " where"
                . "     ci.city = :id           and"
                . "     ci.`character` = ch.id  and"
                . "     not isnull(ci.`from`)   and"
                . "         isnull(ci.`to`)",
                array (
                    "id" => $c->getId()
                )) ;
    }
    
    public static function GetWaitingAnswer(City $c) {
        return self::getResult(""
                . " select *"
                . " from"
                . "     `" . self::getTableName() . "`"
                . " where"
                . "     city = :id          and"
                . "     isnull(`from`)",
                array (
                    "id" => $c->getId()
                )) ;
    }
    
    public static function GetInivtations(Character $c) {
        return self::getResult(""
                . " select *"
                . " from"
                . "     `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :id and"
                . "     isnull(`from`)    and"
                . "     isInvite",
                array (
                    "id" => $c->getId()
                )) ;
    }

    
}
