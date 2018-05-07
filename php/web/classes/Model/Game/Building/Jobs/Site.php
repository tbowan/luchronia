<?php

namespace Model\Game\Building\Jobs ;

class Site extends Base {
    
    protected $_site ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        $this->_site = \Model\Game\Building\Site::GetFromInstance($this->_instance) ;
    }
    
    public function lostHealth() {
        $this->onDestroy() ;
        $this->destroy() ;
    }
    
    public function onDestroy() {
        $origin = Factory::createFromJob($this->_instance, $this->_site->job) ;
        $origin->onDestroy() ;
    }
    
    public function getName() {
        $res = parent::getName() ;
        $res .= " (" . $this->_site->job->getName() . ")" ;
        return $res ;
    }
    
    public function getMaxHealth() {
        return $this->_site->job->getMaxHealth($this->_instance->level) ;
    }
    
}
