<?php

namespace Quantyl\Dao ;

class BddTable {
    
    private $_tablename ;
    
    // TODO : remplacer par une reflexion Class ?
    private $_classname ;
    
    public function __construct($tablename, $classname) {
        $this->_tablename = $tablename ;
        $this->_classname = $classname ;
    }
    
    public function getTableName() {
        return $this->_tablename ;
    }
    
    public function getClassName() {
        return $this->_classname ;
    }
    
    public static function fromTable($tablename) {
        return new BddTable($tablename, Dal::tableToClass($tablename)) ;
    }
    
    public static function fromClass($classname) {
        return new BddTable(Dal::classToTable($classname), $classname) ;
    }
    
    public function __call($name, $params) {
        
        // Todo : check method exists and is static
        $rfClass = new \ReflectionClass($this->_classname) ;
        $rfMethod = $rfClass->getMethod($name) ;
        return $rfMethod->invokeArgs(null, $params) ;
        
    }
    
    public function equals(BddTable $t) {
        return $this->getTableName() == $t->getTableName() ;
    }
    
}
