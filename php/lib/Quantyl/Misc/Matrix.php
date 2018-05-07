<?php

namespace Quantyl\Misc ;

class Matrix {

    private $_lines ;
    private $_columns ;
    
    private $_values ;
    
    protected function __construct($values, $lines, $columns) {
        
        $this->_lines   = $lines ;
        $this->_columns = $columns ;
        $this->_values  = $values ;
        
    }
    
    public function getValue($line, $column) {
        if ($line < 0 ||$line >= $this->_lines) {
            throw new \Exception() ;
        } else if ($column < 0 || $column >= $this->_columns) {
            throw new \Exception() ;
        } else if (isset($this->_values[$line][$column])) {
            return $this->_values[$line][$column] ;
        } else {
            return 0 ;
        }
    }
    
    public function getValues() {
        return $this->_values ;
    }
    
    public function setValue($line, $column, $value) {
        if ($line < 0 || $line >= $this->_lines) {
            throw new \Exception() ;
        } else if ($column < 0 || $column >= $this->_columns) {
            throw new \Exception() ;
        } else {
            if (! isset($this->_values[$line])) {
                $this->_values[$line] = array() ;
            }
            $this->_values[$line][$column] = $value ;
        }
    }
    
    public function getLines() {
        return $this->_lines ;
    }
    
    public function getColumns() {
        return $this->_columns ;
    }
    
    public function getDimension() {
        return array ($this->_lines, $this->_columns) ;
    }
    
    public function add(Matrix $m) {
        for ($i=0; $i< $m->getLines(); $i++) {
            for ($j=0; $j<$m->getColumns(); $j++) {
                if (! isset($this->_values[$i][$j])) {
                    $this->_values[$i][$j] = $m->getValue($i, $j) ;
                } else {
                    $this->_values[$i][$j] += $m->getValue($i, $j) ;
                }
            }
        }
    }
    
    public function substract(Matrix $m) {
        for ($i=0; $i < $m->getLines(); $i++) {
            for ($j=0; $j<$m->getColumns(); $j++) {
                if (! isset($this->_values[$i][$j])) {
                    $this->_values[$i][$j] = - $m->getValue($i, $j) ;
                } else {
                    $this->_values[$i][$j] -= $m->getValue($i, $j) ;
                }
            }
        }
    }
    
    public static function sum(Matrix $m1, Matrix $m2) {
        if ($m1->getLines() != $m2->getLines()) {
            throw new \Exception() ;
        } else if ($m1->getColumns() != $m2->getColumns()) {
            throw new \Exception() ;
        } else {
            $res = self::Zero($m1->getLines(), $m1->getColumns()) ;
            $res->add($m1) ;
            $res->add($m2) ;
            return $res ;
        }
    }
    
    public function multiply($lambda) {
        if ($lambda == 1.0) {
            return ;
        }
        
        foreach ($this->_values as $i => $line) {
            foreach ($line as $j => $v) {
                $line[$j] = $lambda * $v ;
            }
            $this->_values[$i] = $line ;
        }
    }
    
    public static function multiplyStatic($m, $lambda) {
        $res = clone $m ;
        $res->multiply($lambda) ;
        return $res ;
    }
    
    public static function product($m1, $m2) {
        if ($m1->getColumns() != $m2->getLines()) {
            throw new \Exception() ;
        } else {
            $values = array() ;
            for ($i=0; $i< $m1->getLines(); $i++) {
                $values[$i] = array() ;
                for ($j=0; $j<$m2->getColumns(); $j++) {
                    $sum = 0 ;
                    for ($k = 0; $k< $m1->getColumns(); $k++) {
                        $sum += $m1->getValue($i, $k) * $m2->getValue($k, $j) ;
                    }
                    if ($sum != 0) {
                        $values[$i][$j] = $sum ;
                    }
                }
            }
            return self::create($values, $m1->getLines(), $m2->getColumns()) ;
        }
    }
    
    public static function Zero($lines, $columns) {
        $values = array() ;
        for ($l = 0; $l < $lines; $l++) {
            $values[$l] = array() ;
            for ($c = 0; $c < $columns; $c++) {
                $values[$l][$c] = 0 ;
            }
        }
        return self::create($values, $lines, $columns) ;
    }
    
    public static function I($lines) {
        $values = array() ;
        for ($l = 0; $l < $lines; $l++) {
            $values[$l] = array() ;
            for ($c = 0; $c < $lines; $c++) {
                if ($c == $l) {
                    $values[$l][$c] = 1 ;
                } else {
                    $values[$l][$c] = 0 ;
                }
            }
        }
        return self::create($values, $lines, $lines) ;
    }
    
    public function __clone() {
        $values = $this->_values ;
        return static::create($values, $this->_lines, $this->_columns) ;
    }
    
    /**
     * Test si la matrice en paramètre est "équivalente" :
     *  - mêmes dimentions
     *  - mêmes valeurs (à epsilon près)
     * @param \Misc\Matrix $m la matrice à comparer
     * @param type $epsilon l'intervale de confiance
     * @return boolean si les deux matrices sont équivalentes
     */
    public function equals(Matrix $m, $epsilon = 0) {
        list($l, $c) = $m->getDimension() ;
        if ($this->_columns != $c ||$this->_lines != $l) {
            return false ;
        }
        
        foreach ($this->_values as $i => $line) {
            foreach ($line as $j => $v) {
                if (abs($v - $m->getValue($i, $j)) > $epsilon) {
                    return false ;
                }
            }
        }
        return true ;
    }
    
    /*
     * FACTORY
     */
    
    public static function transpose($m) {
        $values = array() ;
        for ($i = 0; $i< $m->getColumns(); $i++) {
            $values[$i] = array() ;
            for ($j=0; $j<$m->getLines(); $j++) {
                $values[$i][$j] = $m->getValue($j, $i) ;
            }
        }
        return self::create($values) ;
    }
            
    public static function findDimentions($values) {
        $lines   = 0 ;
        $columns = 0 ;
        foreach ($values as $i => $line) {
            $lines = max($lines, $i) ;
            foreach ($line as $j => $v) {
                $columns = max($columns, $j) ;
            }
        }
        return array($lines + 1, $columns + 1) ;
    }
    
    public static function create($values, $lines = null, $columns = null) {
        
        if ($lines === null || $columns === null) {
            list ($lines, $columns) = self::findDimentions($values) ;
        }
        
        if ($columns == 1) {
            return Vertex::create($values, $lines, $columns) ;
        } else if ($lines == 4 && $columns == 4) {
            return Matrix4D::create($values, $lines, $columns) ;
        } else {
            return new Matrix($values, $lines, $columns) ;
        }
    }
}
