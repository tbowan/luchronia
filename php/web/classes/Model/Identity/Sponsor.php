<?php

namespace Model\Identity ;

class Sponsor extends \Quantyl\Dao\BddObject {
    
    public function create() {
        
        if ($this->code == null) {
            $this->code = self::getUniqueCode() ;
        }
        
        parent::create();
    }
    
    public function getName() {
        return $this->mail == "" ? $this->code : $this->mail ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "sponsor" :
            case "protege" :
                return ($value == null ? null : User::GetById($value) );
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "sponsor" :
            case "protege" :
                return ($value == null ? null : $value->getId() );
            default:
                return $value ;
        }
    }
    
    public static function GetFromSponsor(User $u) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `sponsor` = :u ",
                array("u" => $u->getId())
                ) ;
    }
    
    public static function GetFromProtege(User $u) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `protege` = :u ",
                array("u" => $u->getId())
                ) ;
    }
    
    public static function isInvited($mail) {
        $res = static::getSingleRow(
                "select true from `" . self::getTableName() . "` where `mail` = :u ",
                array("u" => $mail)
                ) ;
        return $res !== false ;
    }
    
    public static function hasAlreadyInvidedd(User $me, $mail) {
        $res = static::getSingleRow(""
                . " select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `mail` = :mail and"
                . "     `sponsor` = :user",
                array(
                    "mail" => $mail,
                    "user" => $me->getId()
                        ) ) ;
        return $res !== false ;
    }
    
    public static function CountFromUserSince(User $me, $since) {
        $res = static::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `date` > :since and"
                . "     `sponsor` = :user",
                array(
                    "since" => $since,
                    "user" => $me->getId()
                        ) ) ;
        return ($res === false ? 0 : intval($res["c"]) ) ;
    }
    
    public static function GetFromMail($mail) {
        if ($mail == "") {
            return null ;
        } else {
            return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where `mail` = :u ",
                array("u" => $mail)
                ) ;
        }
    }
    
    public static function GetFromCode($code) {
        if ($code == "") {
            return null ;
        } else {
            return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where `code` = :c ",
                array("c" => $code)
                ) ;
        }
    }
    
    public static function getByUserCode($codeormail) {
        if ($codeormail == "") {
            return array() ;
        }
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `mail` = :c or"
                . "     `code` = :c ",
                array("c" => $codeormail)
                ) ;
    }
    
    public static function canUseInvitation($codeormail) {
        foreach (self::getByUserCode($codeormail) as $temp) {
            if ($temp->sponsor != null && $temp->protege == null) {
                return true ;
            }
        }
        return false ;
    }
    
    public static function getUniqueCode() {
        $c = bin2hex(openssl_random_pseudo_bytes(8));
        $t = self::GetFromCode($c) ;
        while ($t != null) {
            $c = bin2hex(openssl_random_pseudo_bytes(8));
            $t = self::GetFromCode($c) ;
        }
        return $c ;
    }
    
    public static function getRequest() {
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     isnull(`sponsor`) and"
                . "     isnull(`protege`)"
                . " order by"
                . "     `date` desc",
                array()
                ) ;
    }
}
