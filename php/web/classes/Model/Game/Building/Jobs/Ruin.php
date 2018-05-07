<?php

namespace Model\Game\Building\Jobs ;

class Ruin extends Base {
    
    protected $_ruin ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        
        $this->_ruin = \Model\Game\Building\Ruin::GetFromInstance($this->_instance) ;
    }
    
    public function getMaxHealth() {
        return $this->_ruin->job->getMaxHealth($this->_instance->level) ;
    }
    
    public function LostHealth() {
        // Nothin special, ruins can have negative health
        $this->_instance->update() ;
    }
    
    public function onDestroy() {
        $origin = Factory::createFromJob($this->_instance, $this->_ruin->job) ;
        $origin->onDestroy() ;
    }
    
    public function getName() {
        $res = parent::getName() ;
        $res .= " (" . $this->_ruin->job->getName() . ")" ;
        return $res ;
    }
    
    public function getRessources() {
        return array() ;
    }
    
    public function getCostBase() {
        return \Model\Game\Building\Map::getCostBase(
                $this->_ruin->job,
                $this->_instance->type,
                $this->_instance->level
                ) ;
    }
    
}
