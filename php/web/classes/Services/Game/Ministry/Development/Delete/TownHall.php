<?php

namespace Services\Game\Ministry\Development\Delete ;

class TownHall extends Base {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req) ;
        
        if (\Model\Game\Building\Instance::CountFromCityAndJob($this->instance->city, $this->instance->job) <= 1) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_DELETE_TOWNHALL()) ;
        }
    }
    
    public function doSpecificStuff($specific) {
        
    }

    public function getSpecificFieldset() {
        
    }

    public function getSpecificInformation() {
        
    }

}
