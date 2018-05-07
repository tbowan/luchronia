<?php

namespace Quantyl\Misc\Thread ;

abstract class AbstractThread {

    private $_pid ;
    private $_mutex ;
    
    private $_isStarted ;
    private $_isFinished ;
    private $_result ;
    
    public function __construct() {
        
        $this->_id = null ;
        $this->_mutex = new Mutex() ;
        $this->_isStarted = false ;
        $this->_isFinished = false ;
        $this->_result = false ;
        
    }
    
    public function isStarted() {
        $locker = new Locker($this->_mutex) ;
        return $this->_isStarted ; 
    }
    
    public function isFinished() {
        $locker = new Locker($this->_mutex) ;
        return $this->_isFinished ; 
    }
    
    public function getPid() {
        $locker = new Locker($this->_mutex) ;
        return $this->_pid ; 
    }
    
    private function setStarted($bool) {
        $locker = new Locker($this->_mutex) ;
        $this->_isStarted = $bool ;
    }
    
    private function setFinished($bool) {
        $locker = new Locker($this->_mutex) ;
        $this->_isFinished = $bool ;
    }
    
    private function setPid($bool) {
        $locker = new Locker($this->_mutex) ;
        $this->_pid = $bool ;
    }
    
    private function getResult() {
        $locker = new Locker($this->_mutex) ;
        return $this->_result ; 
    }
    
    private function setResult($result) {
        $locker = new Locker($this->_mutex) ;
        $this->_result = $result ;
    }
    
    public function start() {
        if ($this->isStarted()) {
            throw new \Exception("Already Running (as PID : " . $this->getPid() . ")") ;
        }
        
        $pid = pcntl_fork() ;
        if ($pid === -1) {
            throw new \Exception("Fork Failed") ;
        } else if ($pid === 0) {
            // Child
            $this->setStarted(true) ;
            $result = $this->run() ;
            $this->setResult($result) ;
            $this->setFinished(true) ;
            exi() ;
        } else {
            // Father
            $this->setPid($pid) ;
            return ;
        }
    }
    
    public abstract function run() ;
    
    public function wait() {
        if ( ! $this->_isStarted()) {
            throw new \Exception("Not yet started") ;
        } else if ($this->isFinished()) {
            return $this->getResult() ;
        } else {
            pcntl_waitpid($this->getPid(), $status) ;
            return $this->getResult() ;
        }
    }
    
}
