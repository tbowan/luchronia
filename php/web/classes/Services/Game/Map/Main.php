<?php

namespace Services\Game\Map ;

class Main extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city",      new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
        $form->addInput("lattitude", new \Quantyl\Form\Fields\Float("", false)) ;
        $form->addInput("longitude", new \Quantyl\Form\Fields\Float("", false)) ;
    }
    
    
    public function getView() {
        
        $char = $this->getCharacter() ;
        $city = $char->position ;
        if ($this->lattitude !== null && $this->longitude !== null) {
            $center = \Quantyl\Misc\Vertex3D::FromSpheric($this->longitude, $this->lattitude, 1.0, true) ;
            $city = \Model\Game\City::GetClosest($this->world, $center) ;
        }
        
        return new \Answer\View\Game\Map($this, $city, $this->getCharacter()) ;
        
    }

}
