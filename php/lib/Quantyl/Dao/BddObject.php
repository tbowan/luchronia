<?php

namespace Quantyl\Dao ;

use \PDO ;

abstract class BddObject implements \Serializable {

    private $_attrs ;
    
    public function __construct() {
        $this->_attrs = array() ;
    }
    
    public function getId() {
        if (isset($this->_attrs["id"])) {
            return $this->id ;
        } else {
            return null ;
        }
    }
    
    public function __get($name) {
        if (! array_key_exists($name, $this->_attrs)) {
            $this->_attrs[$name] = null ;
            if (isset($this->_attrs["id"])) {
                $this->read() ;
            }
        }
        return $this->_attrs[$name] ;
    }
    
    public function __set($name, $value) {
        $this->_attrs[$name] = $value ;
    }
    
    public function equals($o) {
        if ($o instanceof BddObject) {
            $t1 = $this->getTable() ;
            $t2 = $o->getTable() ;
            
            return $t1->equals($t2) && $this->getId() == $o->getId() ;
        } else {
            return false ;
        }
    }
    
    public function getTable() {
        return static::getBddTable() ;
    }
    
    // To be overriden bu subclasses
    public static function FromBddValue($name, $value) {
        return $value ;
    }
    
    public static function ToBddValue($name, $value) {
        return $value ;
    }
    
    // CRUD methods
    
    public function create() {
        
        if ($this->exists()) {
            // TODO better exception
            throw new \Exception() ;
        }
        
        $query = "insert into `"
                . static::getTableName()
                . "` " ;
        
        $keys = array() ;
        $params = array() ;
        $values = array() ;
        foreach ($this->_attrs as $key => $value) {
            $keys[]       = "`$key`" ;
            $params[]     = ":$key" ;
            $values[$key] = static::ToBddValue($key, $value) ;
        }
        
        $query .= "(" . implode(", ", $keys) . ") " ;
        $query .= " values " ;
        $query .= "(" . implode(", ", $params) . ") " ;
        

        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;

        $st->execute($values) ;
        
        $this->id = $pdo->lastInsertId() ;
        
    }
    
    public function read($force = false) {
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where id = :id" ;
        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->execute(array("id" => $this->getId())) ;
        
        $res = $st->fetch(PDO::FETCH_ASSOC) ;
        
        if ($res !== false) {
            foreach ($res as $key => $value) {
                if ($force || ! isset($this->_attrs[$key])) {
                    $this->$key = static::FromBddValue($key, $value) ;
                }
            }
        } else {
            throw new \Exception("Does Not Exists (" . get_called_class() . ")") ;
        }
        
    }
    
    public function update() {
        $query = "update `"
                . static::getTableName()
                . "`" ;
        
        $set    = array() ;
        $values = array("id" => $this->getId()) ;
        foreach ($this->_attrs as $key => $value) {
            if ($key != "id") {
                $set[]        = "`$key` = :$key" ;
                $values[$key] = static::ToBddValue($key, $value) ;
            }
        }
        
        $query .= " set " . implode(", ", $set) ;
        $query .= " where id = :id" ;
        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->execute($values) ;

    }
    
    public function delete() {
        $query = "delete"
                . " from `" . static::getTableName() . "`"
                . " where id = :id" ;
        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->execute(array("id" => $this->getId())) ;
    }
    
    // CRUD shortcuts
    
    public function exists() {
        $query = "select 1"
                . " from `" . static::getTableName() . "`"
                . " where id = :id" ;
        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->execute(array("id" => $this->getId())) ;
        
        return $st->fetch() !== false ;
    }
    
    public function synch() {
        if ($this->exists()) {
            $this->update() ;
        } else {
            $this->create() ;
        }
    }
    
    // Get Some Element
    
    public static function GetById($id, $read = false) {
        if ($id == 0) {
            throw new \Exception() ;
        }
        $res = static::createFromId($id) ;
        if ($read) {
            $res->read(true) ;
        }
        return $res ;
    }

    public static function GetAll() {
        $query = "select *"
                . " from `" . static::getTableName() . "`" ;
        
        return static::getResult($query, array()) ;
    }
    
    public static function CountAll() {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`" ;
        
        return static::getCount($query, array()) ;
    }
    
    public static function getResult($query, $params) {
        
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->setFetchMode(PDO::FETCH_ASSOC) ;
        $st->execute($params) ;
        return new BddSet($st, static::getBddTable()) ;
        
    }
    
    public static function getSingleResult($query, $params) {
        $set = static::getResult($query, $params) ;
        foreach ($set as $res) {
            return $res ;
        }
        return null ;
    }
    
    public static function getCount($query, $params, $key = "c") {
        $row = self::getSingleRow($query, $params) ;
        return ($row === false ? 0 : floatval($row[$key])) ;
    }
    
    public static function getTrue($query, $params) {
        $row = self::getSingleRow($query, $params) ;
        return ($row !== false) ;
    }
    
    public static function getStatement($query, $params) {
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->setFetchMode(PDO::FETCH_ASSOC) ;
        $st->execute($params) ;
        
        return $st ;
    }
    
    public static function getSingleRow($query, $params) {
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->setFetchMode(PDO::FETCH_ASSOC) ;
        $st->execute($params) ;
        
        return $st->fetch() ;
    }
    
    public static function execRequest($query, $params) {
        $pdo = Dal::getPdo() ;
        $st = $pdo->prepare($query) ;
        $st->execute($params) ;
    }
    
    // Factory
    
    public static function createFromId($id) {
        return static::createFromValues(array("id" => $id), false) ;
    }
    
    public static function createFromRow($row) {
        $classname = get_called_class() ;
        $res = new $classname() ;
        foreach ($row as $key => $value) {
            $res->$key = static::FromBddValue($key, $value) ;
        }
        return $res ;
    }
    
    public static function createFromValues($values, $insert = true) {
        $classname = get_called_class() ;
        $res = new $classname() ;
        foreach ($values as $key => $value) {
            $res->$key = $value ;
        }
        if ($insert) {
            $res->create() ;
        }
        return $res ;
    }
    
    // Methods to get BddTable
    
    public static function getBddTable() {
        return BddTable::fromClass(get_called_class()) ;
    }
    
    public static function getTableName() {
        return Dal::classToTable(get_called_class()) ;
    }
    
    /*
     *  Serialisation
     */
    
    
    public function serialize() {
        return "" . $this->getId() ;
    }
    
    public function unserialize($serialized) {
        $this->id = intval($serialized) ;
        $this->read() ;
    }
    
}
