<?php

namespace Services\Game\Ministry\Government ;

class Mine extends \Services\Base\Character {
    
    public function getView() {
        return new \Widget\Game\Ministry\Government\ProjectGovernments($this->getCharacter()) ;
    }
    
}
