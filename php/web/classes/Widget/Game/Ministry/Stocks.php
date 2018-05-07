<?php

namespace Widget\Game\Ministry ;

class Stocks extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city) {
        $this->_city = $city ;
    }
    
    public function getContent() {
        
        $res = "" ;
        $res .= new \Answer\Widget\Game\Ministry\Commerce\Stock\Outside($this->_city, true) ;
        
        foreach (\Model\Game\Building\Instance::GetFromCity($this->_city) as $inst) {
            $res .= new \Answer\Widget\Game\Ministry\Commerce\Stock\Building($inst, true) ;
        }
        
        return $res ;
        
    }
    
}
