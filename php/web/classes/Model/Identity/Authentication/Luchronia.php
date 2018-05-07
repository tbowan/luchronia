<?php

namespace Model\Identity\Authentication ;

class Luchronia extends \Quantyl\Dao\BddObject {

    use \Model\Name ;
    
    public static function getNameField() {
        return "nickname" ;
    }
    
    public function hash($password)
    {
        return hash("sha512", $this->salt . $password);
    }

    public function checkPassword($password)
    {
        return $this->hash($password) == $this->password;
    }

    // parse
    
    public static function FromBddValue($name, $value) {
        switch ($name) {
            case "user" :
                return \Model\Identity\User::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch ($name) {
            case "user" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }

    // Get some sets
    
    public static function GetFromUser(\Model\Identity\User $u) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `user` = :user",
                array(
                    "user" => $u->getId()
                )
                ) ;
    }
    
    // create a new authentication for a given user
    
    public static function Register($user, $nickname, $password)
    {
        $res = new Luchronia() ;
        $res->user     = $user ;
        $res->nickname = $nickname;
        $res->salt     = bin2hex(openssl_random_pseudo_bytes(8));
        $res->password = $res->hash($password);
        
        $res->create();

        return $res ;
    }
    
    public function changeAuth($nickname, $passwd) {
        $this->nickname = $nickname ;
        $this->salt     = bin2hex(openssl_random_pseudo_bytes(8));
        $this->password = $this->hash($passwd) ;
        $this->update() ;
        
    }
    
    
}
