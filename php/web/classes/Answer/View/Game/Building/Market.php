<?php

namespace Answer\View\Game\Building ;

class Market extends Base {

    public function getSpecific() {
        
        return new \Answer\Widget\Game\Building\Market($this->_instance) ;
    }
    
}
