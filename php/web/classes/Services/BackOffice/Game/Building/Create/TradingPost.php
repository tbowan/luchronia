<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;

class TradingPost extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        \Model\Game\Building\Tradingpost::createFromValues(array(
                "instance" => $inst,
                "tax"      => 0.0,
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        return ;
    }
    
}
