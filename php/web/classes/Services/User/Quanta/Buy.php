<?php

namespace Services\User\Quanta ;

class Buy extends \Services\Base\Connected {
    
    public function getView() {
        return new \Answer\Widget\User\Quanta\Buy() ;
    }
    
}
