<?php

namespace Quantyl\Misc ;

class Counter {
    
    private $_current ;
    private $_max ;
    
    private $_t0 ;
    private $_t1 ;
    private $_dt ;
    
    private $_obj ;
    private $_methodname ;
    
    public function __construct($max, $dt = 1, $obj = null, $methodname = null) {
        $this->_current = 0 ;
        $this->reset($max) ;
        $this->_dt     = $dt ;
        $this->_obj    = $obj ;
        $this->_methodname = $methodname ;
    }
    
    public function getPercent() {
        return 100 * $this->_current / $this->_max ;
    }
    
    public function tick($t) {
        $progress  = intval(100 * $this->_current / $this->_max) ;
        $speed     = $this->_current / ($t - $this->_t0) ;
        $speedstr  = number_format($speed, 2) ;
        $remain    = intval(($this->_max - $this->_current) / $speed) ;
        // TODO : corriger l'affichage de 1 heure par défaut
        $remainstr = gmdate("z H:i:s", $remain) ;

        echo " - $progress %\t{$this->_current} / {$this->_max}\t- $remainstr ($remain)\t$speedstr /s\n" ;
        
        if ($this->_obj != null && $this->_methodname != null) {
            $mn = $this->_methodname ;
            $this->_obj->$mn($this) ;
        }
    }
    
    public function reset($max) {
        $this->_max    = $max ;
        $this->_t0     = microtime(true) ;
        $this->_t1     = $this->_t0 ;
    }
    
    public function step() {
        
        $this->_current++ ;
        
        $t = microtime(true) ;
        
        if ($t-$this->_t1 > $this->_dt) {
            $this->_t1 = $t ;
            $this->tick($t) ;
        }
    }
    
    public function elapsed() {
        $t = microtime(true) ;
        // TODO : corriger l'affichage de 1 heure par défaut
        $this->_current = $this->_max ;
        $this->tick($t) ;
        $dt = $t - $this->_t0 ;
        echo " - Time : $dt sec\n" ;
    }
    

}
