<?php

namespace Services\Game\Inventory ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        
        return new \Answer\View\Game\Ressource($this, $this->getCharacter()) ;
        
        $c = $this->getCharacter() ;
        
        $list = new \Quantyl\Answer\ListAnswer() ;
        $list->addAnswer(new \Widget\Game\Inventory\Items($c)) ;
        $list->addAnswer(new \Widget\Game\Inventory\Slots($c)) ;
        return $list ;
        
    }
    
    
}
