<?php

namespace Answer\View\Game\Map ;

class Basic extends \Quantyl\Answer\Answer {
    
    private $_cities ;
    private $_me ;
    
    public function __construct($cities, \Model\Game\Character $me) {
        parent::__construct();
        $this->_cities = $cities ;
        $this->_me = $me ;
    }
    
    public function addCity(\Model\Game\City $c) {
        echo "  {\n" ;
        echo "    id       : {$c->id},\n" ;
        echo "    x        : {$c->x},\n" ;
        echo "    y        : {$c->y},\n" ;
        echo "    z        : {$c->z},\n" ;
        echo "    albedo   : {$c->albedo},\n" ;
        echo "    altitude : {$c->altitude},\n" ;
        echo "    sun      : {$c->sun},\n" ;
        echo "    has_townhall   : " . ($c->hasTownHall()         ? 1 : 0) . ",\n" ;
        echo "    has_ruin       : " . ($c->hasRuin()             ? 1 : 0) . ",\n" ;
        echo "    has_friend     : " . ($c->hasFriend($this->_me) ? 1 : 0) . ",\n" ;
        
        echo "    neighbours : [\n" ;
        foreach (\Model\Game\City\Neighbour::getFromA($c) as $n) {
            echo "      {$n->b->id}," ;
        }
        echo "    ]\n" ;
        echo "  },\n" ;
    }
    
    public function getContent() {
        echo "[\n" ;
        foreach ($this->_cities as $c) {
            $this->addCity($c) ;
        }
        echo "]\n" ;
    }

}
