<?php

namespace Answer\View\Game\Building ;

class Excavation extends Base {
    
    public function getSpecific() {
        return new \Answer\Widget\Game\Building\LostRuins($this->_instance->city) ;
    }
    
}
