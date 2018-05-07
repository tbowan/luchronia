<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Instance;

class Market extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        return ;
    }

    public function getSpecificFieldset() {
        return null ;
    }
    
    public function isTrading() {
        return true ;
    }
    
}
