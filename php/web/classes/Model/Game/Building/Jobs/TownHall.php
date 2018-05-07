<?php

namespace Model\Game\Building\Jobs ;

class TownHall extends Base {
    
    protected $_townhall ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        $this->_townhall = \Model\Game\Building\TownHall::GetFromInstance($this->_instance) ;
    }
    
    public function onDestroy() {
        $this->onDisapear() ;
    }
    
    public function onBecomeRuin() {
        $this->onDisapear() ;
    }
    
    public function getName() {
        $res = parent::getName() ;
        $res .= " : " . $this->_townhall->name ;
        return $res ;
    }
    
    public function hasOtherTownHall() {
        $city = $this->_instance->city ;
        
        foreach (\Model\Game\Building\Instance::GetFromCityAndJob($city, \Model\Game\Building\Job::GetByName("TownHall")) as $inst) {
            if (! $inst->equals($this->_instance)) {
                return true ;
            }
        }
        return false ;
    }
    
    public function onDisapear() {
        if (! $this->hasOtherTownHall()) {
            \Model\Game\Politic\System::LostCity($this->_instance->city) ;
        }
    }
}
