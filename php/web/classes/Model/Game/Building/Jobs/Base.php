<?php

namespace Model\Game\Building\Jobs ;

class Base {
    
    protected $_instance ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        $this->_instance = $i ;
    }
    
    public function getName() {
        return $this->_instance->job->getName() ;
    }
    
    public function iterate($dt) {
        $i = $this->_instance ;
        // 1. Attack
        $damage = $dt / (60*60*24) ; // 1 points each day
        $i->health -= $damage * $i->getWear() ;
        
        
        if ($i->health < 0) {
            $this->LostHealth() ;
        } else {
            $i->update() ;
        }
    }
    
    public function attack($damages) {
        
        $total_health = $this->_instance->health + $this->_instance->barricade ;
        
        if ($total_health < 0) {
            return $damages ;
        }
        
        $mydamages = min (
                floor(($total_health) / 10.0),
                $damages
                ) ;
        
        $total_health -= $mydamages * $this->_instance->getWear() ;
        
        if ($total_health > $this->_instance->health) {
            $this->_instance->barricade = $total_health - $this->_instance->health ;
        } else {
            $this->_instance->health = $total_health ;
            $this->_instance->barricade = 0 ;
        }
        
        $this->_instance->update() ;
        
        /*
        echo "Attacking ". $this->_instance->job->getName() . "\n" ;
        echo " - Total damages : $damages\n" ;
        echo " - Taken damages : $mydamages\n" ;
        echo " - Instance State : \n" ;
        echo "    - Health : " . $this->_instance->health . "\n" ;
        echo "    - Barricade : " . $this->_instance->barricade . "\n" ;
        */
        return $damages - $mydamages ;
    }
    
    public function lostHealth() {
        $this->onLostHealth() ;
        $this->becomeRuin() ;
    }
    
    public function becomeRuin() {
        $this->onBecomeRuin() ;
        
        $job = $this->_instance->job ;
        // Make ruin
        $r = \Model\Game\Building\Ruin::createFromValues(array(
            "instance" => $this->_instance,
            "job" => $job
        )) ;
        
        $health = $this->_instance->health ;
        $ruin = \Model\Game\Building\Job::GetByName("Ruin") ;
        
        $this->_instance->job = $ruin ;
        $this->_instance->health = $job->getMaxHealth($this->_instance->level) + $health ;
        
        $this->_instance->update() ;
    }
    
    public function destroy() {
        $this->onDestroy() ;
        $this->_instance->delete() ;
    }
    
    // Called when building is destroyed
    public function onDestroy() {
        ;
    }
    
    // Called when building lost health
    public function onLostHealth() {
        ;
    }
    
    // Called when building become ruin
    public function onBecomeRuin() {
        ;
    }
    
    // Called when created
    public function onCreate() {
        ;
    }
    
    
    public function acceptStock(\Model\Game\Ressource\Item $item) {
        $level = $this->_instance->level ;
        $available = $this->_instance->job->stock * $level * ($level + 1) / 2;
        
        if ($available == 0) {
            return false ;
        } else if ($available <= \Model\Game\Ressource\City::CountFromInstance ($this->_instance)) {
            return false ;
        } else {
            return true ;
        }
    }
    
    public function getRessources() {
        $ressources = $this->_instance->getCosts() ;
        $coef = $this->_instance->health /  $this->getMaxHealth() ;
        
        foreach ($ressources as $k => $v) {
            $ressources[$k] = round($v * $coef, 2) ;
        }
        return $ressources ;
    }
    
    public function getCostBase() {
        return \Model\Game\Building\Map::getCostBase(
                $this->_instance->job,
                $this->_instance->type,
                $this->_instance->level
                ) ;
    }
    
    public function getMaxHealth() {
        return $this->_instance->job->getMaxHealth($this->_instance->level) ;
    }

}