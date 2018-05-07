<?php

namespace Answer\View\Game\Map ;

class Information extends \Quantyl\Answer\Answer {
    
    private $_city ;
    private $_me ;
    
    public function __construct(\Model\Game\City $city, \Model\Game\Character $me) {
        parent::__construct();
        $this->_city = $city ;
        $this->_me   = $me ;
    }
    
    public function getContent() {
        return "" . new \Answer\Widget\Game\City\MapCard($this->_city, $this->_me) ;
    }

}
