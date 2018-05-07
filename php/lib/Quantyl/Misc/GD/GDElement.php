<?php

namespace Quantyl\Misc\GD ;

class GDElement {
    
    private $_id ;
    
    protected function __construct($id) {
        $this->_id = $id ;
    }
    
    public function getId() {
        return $this->_id ;
    }
    
}
