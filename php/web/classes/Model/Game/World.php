<?php

namespace Model\Game ;

class World extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public static function getNameField() {
        return "name" ;
    }
    
    public function getGeode() {
        return new \Quantyl\Misc\Geode\Geode($this->size) ;
    }
    
    public function getCity($a, $b = null, $c = null, $i = 0, $j = 0) {
        $node = new \Quantyl\Misc\Geode\GeodeNode($this->getGeode(), $a, $b, $c, $i, $j) ;
        return City::GetFromCoord($this, $node) ;
    }
    
    public function getCityCount() {
        $s = $this->size ;
        return
            12 +
            30 * ($s-1) +
            10 * ($s-1) * ($s-2) ;
    }
}
