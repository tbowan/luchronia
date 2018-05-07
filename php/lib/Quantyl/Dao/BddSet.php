<?php

namespace Quantyl\Dao ;

class BddSet implements \Iterator {

    private $_table ;
    private $_statement ;
    private $_current ;
    private $_offset ;
    
    public function __construct(\PDOStatement $st, BddTable $table) {
        $this->_table     = $table ;
        $this->_statement = $st ;
        $this->_statement->setFetchMode(\PDO::FETCH_ASSOC) ;
    }

    public function current() {
        return $this->_table->createFromRow($this->_current) ;
    }

    public function key() {
        return $this->_offset ;
    }

    public function next() {
        $this->_current = $this->_statement->fetch() ;
        $this->_offset++ ;
    }

    public function rewind() {
        $this->_current = $this->_statement->fetch() ;
        $this->_offset  = 0 ;
    }

    public function valid() {
        return $this->_current !== false ;
    }

}
