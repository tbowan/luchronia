<?php

namespace Model\Game\Post ;

class Inbox extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "mail" :
                return Mail::GetById($value) ;
            case "box" :
                return Mailbox::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "mail" :
            case "box" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromMailbox(Mailbox $mailbox) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `box` = :bid"
                . " order by id desc",
                array("bid" => $mailbox->id)) ;
    }
    
    public static function CountFromMailbox(Mailbox $mailbox) {
        $row = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where `box` = :bid",
                array("bid" => $mailbox->id)) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function CountUnreadFromMailbox(Mailbox $mailbox) {
        $row = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where `box` = :bid and not `read`",
                array("bid" => $mailbox->id)) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function CountFromMail(Mail $mail) {
        $row = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where `mail` = :mid",
                array("mid" => $mail->id)) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function GetUnreadFromCharacter(\Model\Game\Character $char) {
        return static::getResult(""
                . " select `inbox`.*"
                . " from"
                . "     `" . self::getTableName() . "` as `inbox`,"
                . "     `" . Mailbox::getTableName() . "` as `mailbox`"
                . " where"
                . "     `inbox`.box = `mailbox`.id and"
                . "     `mailbox`.character = :cid and"
                . "     not `inbox`.`read`",
                array("cid" => $char->getId())) ;
    }
    
    public static function CountUnreadFromCharacter(\Model\Game\Character $char) {
        $row = static::getSingleRow(""
                . " select count(*) as c"
                . " from"
                . "     `" . self::getTableName() . "` as `inbox`,"
                . "     `" . Mailbox::getTableName() . "` as `mailbox`"
                . " where"
                . "     `inbox`.box = `mailbox`.id and"
                . "     `mailbox`.character = :cid and"
                . "     not `inbox`.`read`",
                array("cid" => $char->getId())) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
}
