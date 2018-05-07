<?php

namespace Services\User\Quanta ;

class Main extends \Services\Base\Connected {
    
    public function getView() {
        return new \Answer\Widget\User\Quanta\Main($this->getUser()) ;
    }
    
}
