<?php

namespace Model\Forum ;

use Model\I18n\Lang;
use Model\Identity\Group;
use Quantyl\Dao\AbstractOrderedHierarchy;

class Category extends AbstractOrderedHierarchy {
    
    use \Model\Name ;
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return $this->image ;
    }

    
    public function getThreads() {
        return Thread::GetFromCategory($this) ;
    }
    
    public function getThreadCount() {
        $sum = Thread::CountFromCategory($this) ;
        foreach ($this->getChildren() as $c) {
            $sum += $c->getThreadCount() ;
        }
        return $sum ;
    }
    
    public function countPost() {
        $sum = Post::CountFromCategory($this) ;
        foreach ($this->getChildren() as $c) {
            $sum += $c->countPost() ;
        }
        return $sum ;
    }
    
    public function getLastPost() {
        $last = Post::getLastFromCategory($this) ;
        foreach ($this->getChildren() as $c) {
            $otherlast = $c->getLastPost() ;
            if ($last == null ||
                    ($otherlast != null &&
                     $last->date < $otherlast->date)) {
                $last = $otherlast ;
            }
        }
        return $last ;
    }
    
    public function canView($u) {
        
        $local = \Model\Identity\Role::acl($u, $this->view_group) ;
        
        $parent = $this->getParent() ;
        if ($parent == null) {
            $distant = true ;
        } else {
            $distant = $parent->canView($u) ;
        }
        
        return $local && $distant ;
    }
    
    public function canModerate($u) {
        $local = \Model\Identity\Role::acl($u, $this->moderate_group) ;
        
        $parent = $this->getParent() ;
        if ($parent == null) {
            $distant = true ;
        } else {
            $distant = $parent->canModerate($u) ;
        }
        
        return $local && $distant ;
    }
    
    // Parser
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "lang" :
                return $value->getId() ;
            case "view_group" :
            case "moderate_group" :
            case "parent" :
                return $value !== null ? $value->getId() : null ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "parent" :
                return $value == null ? null : Category::createFromId($value) ;
            case "view_group" :
            case "moderate_group" :
                return $value == null ? null : Group::createFromId($value) ;
            case "lang" :
                return Lang::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    public static function getNameField() {
        return "title" ;
    }
    
    // Hierarchy fields
    
    public static function getHierarchyField() {
        return "parent" ;
    }

    public static function getOrderField() {
        return "order" ;
    }
    
    public function countSiblings() {
        if ($this->getParent() == null) {
            $family = static::GetRoots($this->lang) ;
        } else {
            $family = $this->getParent()->getChildren() ;
        }
        
        $total = 0 ;
        foreach ($family as $f) {
            if (! $f->equals($this)) {
                $total++ ;
            }
        }
        return $total ;
    }
    
    
    // Get Some sets
    
    public static function GetRoots(Lang $lang) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "    `lang` = :id and"
                . "    isnull(`parent`)"
                . " order by `" . static::getOrderField() . "`" ;
        
        return static::getResult($query, array("id" => $lang->getId())) ;
    }

}
