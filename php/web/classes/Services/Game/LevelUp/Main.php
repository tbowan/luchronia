<?php

namespace Services\Game\LevelUp ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        return new \Widget\Game\Character\LevelUp($this->getCharacter());
    }
    
}
