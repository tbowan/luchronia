<?php

namespace Scripts\World ;

class SetSun extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
        $form->addInput("time", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function getView() {
        
        echo "Setting Sun value to world \"{$this->world->name}\"\n" ;
        $cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        $pos = \Model\Game\Ephemeris\Sun::GetPosByTime(strtotime($this->time)) ;
        
        echo " - foreach cities\n" ;
        $cities = \Model\Game\City::GetFromWorld($this->world) ;
        foreach ($cities as $c) {
            
            $c->sun = $pos->ScalarProduct($c->getCoordinate()) ;
            $c->update() ;
            
            $cnt->step() ;
        }
        $cnt->elapsed() ;
        echo " - done\n" ;
    }
    
}
