<?php

namespace Model\Game\Building\Jobs ;

class Wall extends Base {
    
    protected $_wall ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        
        $this->_wall = \Model\Game\Building\Wall::GetFromInstance($this->_instance) ;
    }
    
}
