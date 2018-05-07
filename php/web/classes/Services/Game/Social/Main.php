<?php

namespace Services\Game\Social ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        return new \Answer\View\Game\Social($this, $this->getCharacter() ) ;
    }
    
}
