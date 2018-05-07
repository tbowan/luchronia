<?php

namespace Answer\View\Game\Building ;

class Post extends Base {
    
    public function getSpecific() {
        
        return new \Answer\Widget\Game\Building\PostServices($this->_instance) ;
        
    }
    
}
