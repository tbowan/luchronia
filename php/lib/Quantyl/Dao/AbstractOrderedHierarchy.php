<?php

namespace Quantyl\Dao ;

abstract class AbstractOrderedHierarchy extends AbstractHierarchy {
    
    public function getChildren() {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `$bf` = :id"
                . " order by `$of`";
        
        return static::getResult($query, array("id" => $this->getId())) ;
    }
    
    private function getNext($offset) {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        if ($this->$bf == null) {
            $where = "isnull(`$bf`)" ;
            $vars = array() ;
        } else {
            $where = "`$bf` = :b" ;
            $vars = array("b" => $this->$bf->getId()) ;
        }
        $vars["o"] = $this->$of + $offset ;
        
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where $where and `$of` = :o";
        
        return static::getSingleResult($query, $vars) ;
    }
    
    public function getBefore() {
        return $this->getNext(-1) ;
    }
    
    public function moveBefore() {
        $of = static::getOrderField() ;
        
        
        $before = $this->getBefore() ;
        if ($before == null) {
            throw new \Exception(\I18n::EXP_CANNOT_MOVEABEFORE()) ;
        }
        $temp = $before->$of ;
        $before->$of = $this->$of ;
        $this->$of = $temp ;
        
        $before->update() ;
        $this->update() ;
    }
    
    public function getAfter() {
        return $this->getNext(+1) ;
    }
    
    public function moveAfter() {
        $of = static::getOrderField() ;
        
        $after = $this->getAfter() ;
        if ($after == null) {
            throw new \Exception(\I18n::EXP_CANNOT_MOVEAFTER()) ;
        }
        $temp = $after->$of ;
        $after->$of = $this->$of ;
        $this->$of = $temp ;
        
        $after->update() ;
        $this->update() ;
    }
    
    public function moveUp() {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        if ($this->$bf == null) {
            throw new \Exception(\I18n::EXP_CANNOT_MOVEUP()) ;
        }
        
        $this->slideNexts(-1) ;
        $this->$bf->slideNexts(+1) ;
        $this->$of = $this->$bf->$of + 1 ;
        $this->$bf = $this->$bf->$bf ;
        $this->update() ;
    }
    
    public function moveDown() {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        
        $prev = $this->getBefore() ;
        if ($prev == null) {
            throw new \Exception(\I18n::EXP_CANNOT_MOVEDOWN()) ;
        }
        
        $this->slideNexts(-1) ;
        $this->$of = $prev->countChildren() + 1 ;
        $this->$bf = $prev ;
        $this->update() ;
        
    }
    
    private function slideNexts($step) {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        $step = intval($step) ;
        
        if ($this->$bf == null) {
            $where = "isnull(`$bf`)" ;
            $vars = array() ;
        } else {
            $where = "`$bf` = :b" ;
            $vars = array("b" => $this->$bf->id) ;
        }
        $vars["o"] = $this->$of ;
        
        $pdo = \Quantyl\Dao\Dal::getPDO() ;
        $st = $pdo->prepare(
                "update `" . static::getTableName() . "`"
                . " set `$of`= `$of` + $step"
                . " where $where and `$of` > :o") ;
        $st->execute($vars) ;
        
    }
    
    public function create() {
        
        $this->order = $this->countSiblings() + 1;
        parent::create();
        
    }
    
    public function onDelete() {
        $pf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        // Step 1 : make room for children
        $count_children = $this->countChildren() ;
        $this->slideNexts($count_children - 1) ;
        
        // Step 2 : move children
        $base_order = $this->$of ;
        $curent_order = $base_order ;
        $parent = $this->getParent() ;
        foreach ($this->getChildren() as $c) {
            $c->$pf = $parent ;
            $c->$of = $curent_order ;
            $c->update() ;
            $curent_order++ ;
        }
        
    }
    
    // Get Some Sets

    
    public static function GetRoots() {
        $bf = static::getHierarchyField() ;
        $of = static::getOrderField() ;
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where isnull(`$bf`)"
                . " order by `$of`";
        
        return static::getResult($query, array()) ;
    }
    
    public abstract static function getOrderField() ;
    
}
