<?php

namespace Scripts ;

class Test extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
    }
    
    public function getView() {
        
        echo $this->world->getCityCount() . "\n" ;
        echo $this->world->size . "\n" ;
        
        $city = \Model\Game\City::GetFirst($this->world) ;
        while ($city != null) {
            $v = $city->getCoordinate() ;
            $lat = $v->lattitude(true) ;
            $long = $v->longitude(true) ;
            
            echo "{$city->id}\t$lat\t$long\t{$city->albedo}\n" ;
            $city = $city->getNext() ;
        }
        
        return new \Quantyl\Answer\NullAnswer() ;
        
    }
    
}
