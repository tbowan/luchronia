<?php

namespace Services\BackOffice\Treasure ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Treasure\AllTreasures() ;
    }
    
}
