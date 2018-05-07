<?php

namespace Model\Identity ;

class User extends \Quantyl\Dao\BddObject {
    
    public function getName() {
        
        $username = $this->first_name . " " . $this->last_name ;
        
        $charnames = array() ;
        foreach (\Model\Game\Character::GetFromUser($this) as $c) {
        $charnames[] = $c->getName() ;
        }
        $charname = implode(", ", $charnames);
        
        return "$username ($charname)" ;
    }
    
    public function changeMail($email) {
        $this->email = $email ;
        $this->email_valid = false ;
        $this->generateToken() ;
        $this->update() ;
    }
    
    public function generateToken() {
        $this->email_token = bin2hex(openssl_random_pseudo_bytes(16)) ;
    }
    
    public function sendMailCheck() {
        $url = "http://luchronia.com/User/Mail/Validate?token=". $this->email_token ;
        
        \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $this->email,
                \I18n::MAIL_EMAIL_CHECK_SUBJECT(),
                \I18n::MAIL_EMAIL_CHECK_MESSAGE(
                        $this->getName(),
                        new \Quantyl\XML\Html\A($url, $url),
                        $this->email_token)) ;
    }
    
    public function getFirstCharacter() {
        foreach (\Model\Game\Character::GetFromUser($this) as $c) {
            return $c ;
        }
        return null ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "sex" :
                return ($value == "" ? null : \Model\Enums\Sex::GetById($value)) ;
            case "character" :
                return ($value == null ? null : \Model\Game\Character::GetById($value)) ;
            case "cgvu" :
                return ($value == null ? null : Cgvu::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "sex" :
            case "character" :
            case "cgvu" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public static function GetByName($name) {
        return static::getSingleResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "  concat(first_name, ' ', last_name) = :name",
                array("name" => $name)
                ) ;
    }
    
    public static function GetByEmail($email) {
        return static::getResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "  email = :name",
                array("name" => $email)
                ) ;
    }
    
    public static function GetNotifiable($field) {
        return self::getResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where email_valid and $field",
                array()) ;
    }
    
    public function hasBlogCategoryAccess() {
        $query = "select true"
                . " from"
                . "     `" . Role::getTableName() . "` as role,"
                . "     `" . \Model\Blog\Category::getTableName() . "` as category"
                . " where"
                . "     category.group = role.group and"
                . "     role.user = :uid" ;
        $res = self::getSingleRow($query, array("uid" => $this->getId())) ;
        return ($res !== false) ;
    }
}
