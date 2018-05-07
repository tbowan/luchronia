<?php

namespace Answer\View\Game\Building ;

class Library extends Base {
    
    public function getSpecific() {
        return new \Answer\Widget\Game\Building\LibraryStock($this->_instance, $this->_character) ;
        
    }
    
}
