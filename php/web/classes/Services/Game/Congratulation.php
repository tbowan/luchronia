<?php

namespace Services\Game ;

class Congratulation extends \Services\Base\Character {
    
    public function getView() {
        return new \Widget\User\Congratulation($this->getCharacter());
    }
    
}
