<?php

namespace Services\Game\Cheat ;

class Main extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city",      new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
        $form->addInput("world",     new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable(), "", false)) ;
        $form->addInput("lattitude", new \Quantyl\Form\Fields\Float("", false)) ;
        $form->addInput("longitude", new \Quantyl\Form\Fields\Float("", false)) ;
        $form->addInput("width",     new \Quantyl\Form\Fields\Integer("", false)) ;
        
    }
    
    public function getView() {
        
        if ($this->width == null) {
            $w = 5 ;
        } else {
            $w = $this->width ;
        }
        
        if ($this->city != null) {
            $center = $this->city->getCoordinate() ;
            $world = $this->city->world ;
        } else if ($this->lattitude !== null && $this->longitude !== null) {
            $center = \Quantyl\Misc\Vertex3D::FromSpheric($this->longitude, $this->lattitude, 1.0, true) ;
            $world = $this->world ;
        }
        
        return new \Widget\Game\Map\Map($center, $world, new \Model\Game\Character(), $w) ;
    }

}
