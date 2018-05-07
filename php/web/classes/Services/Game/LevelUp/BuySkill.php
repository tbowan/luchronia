<?php

namespace Services\Game\LevelUp ;

class BuySkill extends \Services\Base\Character {
    
    public function getView() {
        return new \Widget\Game\Character\BuyableSkills($this->getCharacter()) ;
    }
    
    
}
