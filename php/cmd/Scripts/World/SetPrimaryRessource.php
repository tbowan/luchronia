<?php

namespace Scripts\World ;

class SetPrimaryRessource extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
    }
    
    private $_noises ;
    private $_items ;
    
    public function initNoise() {
        $names = array(
            "Sand",
            "Clay",
            "Limestone",
            "Coal",
            "IronOre",
            "Water"
        ) ;
        
        $this->_items = array() ;
        $this->_noises = array() ;
        
        foreach ($names as $n) {
            $item = \Model\Game\Ressource\Item::GetByName($n) ;
            $this->_items[$item->id] = $item ;
            $this->_noises[$item->id] =  new \Quantyl\Misc\Noise\MultiScale(0.25, 8, "\\Quantyl\\Misc\\Noise\\Perlin") ;
            
            echo "    - " . $item->getName() . "\n" ;
        }
    }
    
    public function setData(\Model\Game\City $c) {
        
        foreach ($this->_items as $id => $item) {
            
            $noise = min(1.0, max(0.0, 0.5 + $this->_noises[$id]->noise_3d($c->x, $c->y, $c->z))) ;
            
            \Model\Game\Ressource\Natural::createFromValues(array(
                "city" => $c,
                "item" => $item,
                "coef" => $noise
            )) ;
        }
        
    }
    
    public function getView() {
        
        echo "Setting Ressources to world \"{$this->world->name}\"\n" ;
        $cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        $this->initNoise() ;
        
        echo " - foreach cities\n" ;
        $cities = \Model\Game\City::GetFromWorld($this->world) ;
        foreach ($cities as $c) {
            $this->setData($c) ;
            
            $cnt->step() ;
        }
        $cnt->elapsed() ;
        echo " - done\n" ;
        
        return new \Quantyl\Answer\NullAnswer() ;
    }
    
}
