<?php

namespace Model\Identity\Authentication ;

class Reinit extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public function create() {
        
        if ($this->token == null) {
            $this->token = self::getUniqueToken() ;
        }
        
        parent::create();
    }
    
    public function canUse() {
        return $this->until > time() ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "user" :
                return \Model\Identity\User::GetById($value);
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "user" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromUser(User $u) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `user` = :u ",
                array("u" => $u->getId())
                ) ;
    }
        
    public static function GetFromToken($token) {
        if ($token == "") {
            return null ;
        } else {
            return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where `token` = :t ",
                array("t" => $token)
                ) ;
        }
    }
    
    public static function getUniqueToken() {
        $c = bin2hex(openssl_random_pseudo_bytes(8));
        $t = self::GetFromToken($c) ;
        while ($t != null) {
            $c = bin2hex(openssl_random_pseudo_bytes(8));
            $t = self::GetFromToken($c) ;
        }
        return $c ;
    }

    public static function getNameField() {
        return "token" ;
    }

}
