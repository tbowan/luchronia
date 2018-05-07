<?php

namespace Model\Game\Post ;

class Mailbox extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "type" :
                return Type::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "type" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid",
                array("cid" => $c->id)) ;
    }

    public static function GetInboxFromCharacter(\Model\Game\Character $c) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid and `type` = :tid",
                array("cid" => $c->id, "tid" => Type::INBOX()->getId())) ;
    }
    
    public static function GetOutboxFromCharacter(\Model\Game\Character $c) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid and `type` = :tid",
                array("cid" => $c->id, "tid" => Type::OUTBOX()->getId())) ;
    }
    
    public function getName() {
        $inbox = Type::INBOX() ;
        $outbox = Type::OUTBOX() ;
        
        if ($this->name == "" && $this->type->equals($inbox)) {
            return $inbox->getName() ;
        } else if ($this->name == "" && $this->type->equals($outbox)) {
            return $outbox->getName() ;
        } else {
            return $this->name ;
        }
    }

}
