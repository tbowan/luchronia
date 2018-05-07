<?php

namespace Scripts\World ;

class SetBiome extends \Quantyl\Service\EnhancedService {
    
    private $_noises ;
    private $_biomes ;
    private $_ecosystems ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable())) ;
    }
    
    private function initNoises() {
        $this->_noises = array() ;
        foreach (\Model\Game\Ressource\Ecosystem::GetAll() as $eco) {
            $item = $eco->item ;
            $this->_noises[$item->id] = new \Quantyl\Misc\Noise\MultiScale(0.25, 8, "\\Quantyl\\Misc\\Noise\\Perlin") ;
        }
    }
    
    public function initBiomes() {
        $temp = array() ;
        foreach (\Model\Game\Biome::GetAll() as $biome) {
            $index = 256 + ($biome->tmin == 0 ? $biome->kmax : - $biome->kmin) ;
            $temp[$index] = $biome ;
        }
        ksort($temp) ;
        $this->_biomes = $temp ;
        foreach ($this->_biomes as $i => $b) {
            echo " - $i => " . $b->getName() . "\n" ;
        }
    }
    
    public function getValue (\Model\Game\City $c) {
        $warming =
                cos(deg2rad($c->latitude)) +
                0.03 * (128 - $c->altitude)
                - 0.91;
        return 256 + ($warming < 0 ? - $c->albedo : $c->albedo) ;
    }
    
    public function getBiome(\Model\Game\City $c) {
        $value = $this->getValue($c) ;
        $i = null ;
        foreach ($this->_biomes as $k => $b) {
            if ($k >= $value) {
                return $b ;
            }
            $i = $k ;
        }
        return $this->_biomes[$i] ;
    }
    
    
    public function initEcosystems() {
        $this->_ecosystems = array() ;
        foreach (\Model\Game\Biome::GetAll() as $biome) {
            $k = $biome->id ;
            $this->_ecosystems[$k] = array() ;
            foreach (\Model\Game\Ressource\Ecosystem::GetFromBiome($biome) as $eco) {
                $this->_ecosystems[$k][] = $eco ;
            }
        }
    }
    
    private function noiseValue($item, $city) {
        return $this->_noises[$item->id]->noise_3d($city->x, $city->y, $city->z) ;
    }
    
    public function initBiome(\Model\Game\City $c) {
        $biome = $this->getBiome($c) ;
        $c->biome = $biome ;
        $c->update() ;
        
        $percent = ($c->albedo - $biome->kmin) / ($biome->kmax - $biome->kmin) ;
        
        foreach ($this->_ecosystems[$biome->id] as $eco) {
            $noise   = $this->noiseValue($eco->item, $c) ;
            
            $coef = $percent + $noise / 10 ;
            $coef = $eco->min + $coef * ($eco->max - $eco->min) ;
            $coef = min($eco->max, max($eco->min, $coef)) ;
            
            \Model\Game\Ressource\Natural::createFromValues(array(
                "city" => $c,
                "item" => $eco->item,
                "coef" => $coef
            )) ;
        }
    }
    
    public function getView() {
        
        $this->initNoises() ;
        $this->initBiomes() ;
        $this->initEcosystems() ;

        echo "Setting Biomes to world \"{$this->world->name}\"\n" ;
        $cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        echo " - foreach cities\n" ;
        $cities = \Model\Game\City::GetFromWorld($this->world) ;
        foreach ($cities as $c) {
            $this->initBiome($c) ;
            
            $cnt->step() ;
        }
        $cnt->elapsed() ;
        echo " - done\n" ;
        
        return new \Quantyl\Answer\NullAnswer() ;
    }
    
}
