<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;

class Prefecture extends Base {
    
    public function doSpeficicStuff(Instance $i, $data) {
        \Model\Game\Building\Prefecture::createFromValues(array(
                "instance"     => $i,
                "prestige_in"  => 0,
                "prestige_out" => 0
                )) ;
        
        return ;
    }


    public function getSpecificMessage() {
        
    }

    public function getSpecificFieldSet() {
        return null ;
    }

}
