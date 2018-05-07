<?php

namespace Scripts ;

abstract class Base extends \Quantyl\Service\EnhancedService {
    
    private $_stat ;
    
    public function setPercent($p) {
        $this->_stat->percent = $p ;
        $this->_stat->update() ;
    }
    
    public function getView() {
        
        $this->_stat            = new \Model\Stats\Script() ;
        $this->_stat->hostname  = $this->getRequest()->getServer()->getServerHostname() ;
        $this->_stat->script    = get_class($this) ;
        $this->_stat->start     = time() ;
        $this->_stat->pid       = posix_getpid() ; // getmypid() ;
        $this->_stat->percent   = 0 ;
        $this->_stat->create() ;
        
        $answer = $this->doStuff() ;
        
        $this->_stat->percent = 100 ;
        $this->_stat->end = time() ;
        $this->_stat->update() ;
        
        return $answer ;
        
    }
    
    public abstract function doStuff() ;
}
