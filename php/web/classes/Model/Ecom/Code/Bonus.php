<?php

namespace Model\Ecom\Code;

class Bonus extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public function isUsable(\Model\Identity\User $me) {
        $tot_u = Apply::CountUserUses($me, $this) ;
        $tot_t = Apply::CountTotalUses($this) ;
        return ! $this->active &&
                $this->max_u > $tot_u &&
                $this->max_t > $tot_t
                ; 
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "active" :
                return boolval($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "bonus" :
            case "quanta" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    // Get som sets

    public static function GetFromCode($code) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `code` > :code and"
                . "     `from` < :t and"
                . "     `to`   > :t and"
                . "     `active`",
                array("code" => $code, "t" => time())) ;
    }

    public static function getNameField() {
        return "Code" ;
    }

}
