<?php

namespace Model\Game\Forum ;

class Category extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public function create() {
        $this->order = self::CountFromInstance($this->instance) ;
        parent::create() ;
    }
    
    public function goNext() {
        $next = $this->getNext() ;
        if ($next != null) {
            $this->order += 1 ;
            $this->update() ;
            $next->order -= 1 ;
            $next->update() ;
        }
    }
    
    public function goPrev() {
        $prev = $this->getPrev() ;
        if ($prev != null) {
            $this->order -= 1 ;
            $this->update() ;
            $prev->order += 1 ;
            $prev->update() ;
        }
    }
    
    public function delete() {
        
        self::execRequest(""
                . " update `" . self::getTableName() . "`"
                . " set `order` = `order` - 1"
                . " where"
                . "     `instance` = :iid and"
                . "     `order` > :order",
                array(
                    "iid" => $this->instance->id,
                    "order" => $this->order
                )) ;
        
        parent::delete();
    }
    
    public function canRW($char) {
        return $this->rw->canRW($char, $this) ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return \Model\Game\Building\Instance::GetById($value) ;
            case "rw" :
                return Access::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
            case "rw" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(\Model\Game\Building\Instance $i) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where `instance` = :iid"
                . " order by `order`",
                array("iid" => $i->getId())) ;
    }
    
    public function getNext() {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid and"
                . "     `order`    = :order",
                array(
                    "iid" => $this->instance->getId(),
                    "order" => $this->order + 1
                        )) ;
    }
    
    public function getPrev() {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid and"
                . "     `order`    = :order",
                array(
                    "iid" => $this->instance->getId(),
                    "order" => $this->order - 1
                        )) ;
    }
    
    public static function CountFromInstance(\Model\Game\Building\Instance $i) {
        $res = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where `instance` = :iid"
                . " order by `order`",
                array("iid" => $i->getId())) ;
        
        return ($res === false ? 0 : intval($res["c"])) ;
    }

    public static function getNameField() {
        return "title" ;
    }

}
