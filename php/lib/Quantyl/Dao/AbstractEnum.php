<?php

namespace Quantyl\Dao ;

abstract class AbstractEnum {
    
    private $_id ;
    private $_value ;
    
    private function __construct($id, $value) {
        $this->_id    = $id ;
        $this->_value = $value ;
    }
    
    public function getId() {
        return $this->_id ;
    }
    
    public function getValue() {
        return $this->_value ;
    }
    
    public function getPrefix() {
        return "" ;
    }
    
    public function getName() {
        return \I18n::translate($this->getPrefix() . $this->getValue(), array()) ;
    }
    
    public function getDescription() {
        return \I18n::translate($this->getPrefix() . $this->getValue() . "_DESCRIPTION", array()) ;
    }
    
    public function __toString() {
        return $this->getName() ;
    }
    
    public function equals($o) {
        if (is_a($o, get_class($this))) {
            return $this->getId() == $o->getId() ;
        } else {
            return false ;
        }
    }
    
    // Get Some Sets
    
    public static function GetById($id) {
        $values = static::$_enumeration ;
        if (isset($values[$id])) {
            $classname = get_called_class() ;
            return new $classname($id, $values[$id]) ;
        } else {
            return null ;
        }
    }
    
    public static function GetByName($name) {
        $classname = get_called_class() ;
        foreach (static::$_enumeration as $id => $value) {
            if ($value == $name) {
                return new $classname($id, $value) ;
            }
        }
        return null ;
    }
    
    public static function GetAll() {
        $values = static::$_enumeration ;
        $classname = get_called_class() ;
        
        $res = array() ;
        foreach ($values as $id => $value) {
            $res[$id] = new $classname($id, $value) ;
        }
        return $res ;
    }
    
    public static function getBddTable() {
        return BddTable::fromClass(get_called_class()) ;
    }
    
    public static function __callStatic($name, $arguments) {
        return static::GetByName($name);
    }
    
}
