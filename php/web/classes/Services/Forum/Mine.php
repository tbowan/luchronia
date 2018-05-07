<?php

namespace Services\Forum ;

class Mine extends \Services\Base\Character {
    
    public function getView() {
        return new \Answer\Widget\Forum\Mine($this->getCharacter()) ;
    }
    
}
