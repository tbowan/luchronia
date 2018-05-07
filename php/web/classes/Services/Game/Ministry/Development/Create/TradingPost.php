<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;

class TradingPost extends Base {
    
    public function doSpeficicStuff(Instance $i, $data) {
        \Model\Game\Building\Tradingpost::createFromValues(array(
                "instance" => $i,
                "tax"      => 0.0,
                )) ;
        
        return ;
    }

    public function getSpecificFieldSet() {
        return null ;
    }

    public function getSpecificMessage() {
        return null ;
    }
    
}
