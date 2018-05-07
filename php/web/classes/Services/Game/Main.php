<?php

namespace Services\Game ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        
        return new \Answer\View\Game\Main($this, $this->getCharacter()) ;
        
    }
}
