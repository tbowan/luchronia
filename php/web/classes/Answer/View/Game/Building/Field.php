<?php

namespace Answer\View\Game\Building ;

class Field extends Base {
    
    public function getSpecific() {
        
        return new \Answer\Widget\Game\Building\Field($this->_instance) ;
        
    }
    
}
