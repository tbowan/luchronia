<?php

namespace Scripts\World ;

class SetSelenites extends \Quantyl\Service\EnhancedService {
    
    private $_noise ;
        
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
    }
    
    private function initNoises() {
        $this->_noise = new \Quantyl\Misc\Noise\MultiScale(0.25, 8, "\\Quantyl\\Misc\\Noise\\Perlin") ;
    }
    
    private function noiseValue($city) {
        return $this->_noise->noise_3d($city->x, $city->y, $city->z) ;
    }
    
    public function getView() {
        
        $this->initNoises() ;

        echo "Setting Biomes to world \"{$this->world->name}\"\n" ;
        $cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        echo " - foreach cities\n" ;
        $cities = \Model\Game\City::GetFromWorld($this->world) ;
        
        foreach ($cities as $c) {
            
            $nv = 2 * abs($this->noiseValue($c)) ;
            $n = $nv * $c->albedo / 255;
            
            $v = floor(256 * min(1.0, $n) ) ;
            
            $c->name = "" . $v ;
            $c->update() ;
            
            $cnt->step() ;
        }
        $cnt->elapsed() ;
        echo " - done\n" ;
        
        
        return new \Quantyl\Answer\NullAnswer() ;
    }
    
}
