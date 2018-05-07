<?php

namespace Services\Forum ;

class Last extends \Services\Base\Character {
    
    public function getView() {
        return new \Answer\Widget\Forum\Last($this->getCharacter()) ;
    }
    
}
