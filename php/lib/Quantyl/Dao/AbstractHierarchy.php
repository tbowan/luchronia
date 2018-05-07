<?php

namespace Quantyl\Dao ;

abstract class AbstractHierarchy extends \Quantyl\Dao\BddObject {
    
    
    public function getChildren() {
        $bf = static::getHierarchyField() ;
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `$bf` = :id" ;
        return static::getResult($query, array("id" => $this->getId())) ;
    }
    
    public function countChildren() {
        $bf = static::getHierarchyField() ;
        
        $query = "select count(*) as o"
                . " from `" . static::getTableName() . "`"
                . " where `$bf` = :b" ;
        
        $row = static::getSingleRow($query, array("b" => $this->id)) ;
        return intval($row["o"]) ;
    }
    
    
    public function getParent() {
        $bf = static::getHierarchyField() ;
        return $this->$bf ;
    }
    
    public function countSiblings() {
        if ($this->parent === null) {
            $family = static::GetRoots() ;
        } else {
            $family = $this->parent->getChildren() ;
        }
        
        $total = 0 ;
        foreach ($family as $f) {
            if (! $f->equals($this)) {
                $total++ ;
            }
        }
        return $total ;
    }
    
    public function isParentOf($descend) {
        if ($descend === null) {
            return false ;
        } else {
            if ($this->equals($descend->getParent())) {
                return true ;
            } else {
                return $this->isParentOf($descend->getParent()) ;
            }
        }
    }
    
    public function isDescendOf($parent) {
        if ($parent === null) {
            return true ;
        } else {
            return $parent->isParentOf($this) ;
        }
    }
    
    public function moveUp() {
        $bf = static::getHierarchyField() ;
        
        if ($this->$bf == null) {
            throw new \Exception(\I18n::EXP_CANNOT_MOVEUP()) ;
        }

        $this->$bf = $this->$bf->$bf ;
        $this->update() ;
    }
    
    public function delete() {
        $this->onDelete() ;
        parent::delete() ;
    }
    
    public function onDelete() {
        $pf = static::getHierarchyField() ;
        
        // what to do with childrens ?
        foreach ($this->getChildren() as $c) {
            $c->$pf = $this->getParent() ;
            $c->update() ;
        }
    }
    
    // Get Some Sets
    
    public static function GetRoots() {
        $bf = static::getHierarchyField() ;
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where isnull(`$bf`)" ;
        
        return static::getResult($query, array()) ;
        
    }
    
    public abstract static function getHierarchyField() ;
    
}
