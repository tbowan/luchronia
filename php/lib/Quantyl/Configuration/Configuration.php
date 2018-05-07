<?php

namespace Quantyl\Configuration ;

class Configuration implements \ArrayAccess {
    
    private $_values ;
    
    public function __construct($values) {
        $this->_values = $values ;
    }
    
    public function get($path) {
        $components = explode(".", $path) ;
        $res = $this->_values ;
        foreach ($components as $c) {
            if ($c !== "") {
                if (isset($res[$c])) {
                    $res = $res[$c] ;
                } else {
                    // Throw some 
                    throw new \Exception() ;
                }
            } else {
                // nothig to do
            }
        }
        return $res ;
    }

    public function offsetExists($offset) {
        try {
            $res = $this->get($offset) ;
            return true ;
        } catch (\Exception $ex) {
            return false ;
        }
    }

    public function offsetGet($offset) {
        try {
            return $this->get($offset) ;
        } catch (\Exception $ex) {
            return null ;
        }
    }

    public function offsetSet($offset, $value) {
        ;
    }

    public function offsetUnset($offset) {
        ;
    }

    

}
