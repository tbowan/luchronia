<?php

namespace Services\Game\Character ;

class Main extends \Services\Base\Character {

    public function getView() {
        return new \Quantyl\Answer\Redirect("/Game/Character/Show?id=" . $this->getCharacter()->id) ;
    }
    
    
}
