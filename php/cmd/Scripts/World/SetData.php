<?php

namespace Scripts\World ;

class SetData extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world",    new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
        $form->addInput("albedo",   new \Quantyl\Form\Fields\Text()) ;
        $form->addInput("altitude", new \Quantyl\Form\Fields\Text()) ;
        $form->addInput("force",    new \Quantyl\Form\Fields\Boolean()) ;
        $form->addInput("d",        new \Quantyl\Form\Fields\integer()) ;
        return $form ;
    }
    
    public function getView() {
        
        echo "Setting Altitude to world {$this->world->name}\n" ;
        echo "There are " . $this->world->getCityCount() . " cities to update\n" ;
        echo "Creating noises :\n" ;
        echo " - Creating Noise (Albedo)\n" ;
        $albedo = new \Quantyl\Misc\ImageNoise($this->albedo, $this->d) ;
        echo " - Creating Noise (Altitude)\n" ;
        $altitude = new \Quantyl\Misc\ImageNoise($this->altitude, $this->d) ;
        
        echo "Updating Cities\n" ;
        
        $cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        $cities = \Model\Game\City::GetFromWorld($this->world) ;
        foreach ($cities as $c) {
            
            if ($this->_force || $c->altitude == 0) {
                // 0   => noir  => riche
                // 255 => blanc => pauvre
                $long = $c->longitude ;
                $latt = $c->latitude ;
                $c->albedo   = 256 - $albedo->noise($long, $latt) ;
                $c->altitude = $altitude->noise($long, $latt) ;
                $c->update() ;
            }
            $cnt->step() ;
        }
        $cnt->elapsed() ;
        
        echo " - done\n" ;
    }
    
}
