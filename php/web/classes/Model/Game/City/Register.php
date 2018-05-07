<?php

namespace Model\Game\City ;

class Register extends \Quantyl\Dao\BddObject {
    
    public function create() {
        if ($this->date == null) {
            $this->date = time() ;
        }
        return parent::create();
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "from" :
            case "to" :
                return ($value == null ? null : \Model\Game\Building\Instance::GetById($value)) ;
            case "ressource" :
                return ($value == null ? null : \Model\Game\Ressource\Item::GetById($value)) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "city" :
                return $value->getId() ;
            case "from" :
            case "to" :
            case "ressource" :
                return ($value == null ? null : $value->getId()) ;
            default :
                return $value ;
        }
    }
    
    public static function GetFromCity(\Model\Game\City $city) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     city = :cid"
                . " order by `date` desc",
                array("cid" => $city->id)) ;
    }
    
    public static function GetIOCity(\Model\Game\City $city) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     city = :cid and"
                . "     ("
                . "         (isnull(`from`) and isnull(`to`)) OR"
                . "         `amount` < 0"
                . "     )"
                . " order by `date` desc",
                array("cid" => $city->id)) ;
    }
    
}
