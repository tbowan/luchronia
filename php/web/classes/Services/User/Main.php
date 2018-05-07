<?php

namespace Services\User ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        return new \Answer\View\User\Show($this, $this->getUser()) ;
    }
    
}

