<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;

class Defaut extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        return ;
    }

    public function getSpecificFieldset() {
        return null ;
    }
    
}
