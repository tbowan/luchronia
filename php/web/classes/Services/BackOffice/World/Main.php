<?php

namespace Services\BackOffice\World ;

use Model\Game\World;
use Widget\BackOffice\World\ListWorld;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        $worlds = World::getAll() ;
        return new ListWorld($worlds) ;
    }
    
}
