<?php

namespace Answer\Widget\Game\City ;

class WallClosed extends \Answer\Widget\Misc\Card {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $c) {
        parent::__construct();
        $this->_city = $c ;
    }

    public function getBody() {
        return \I18n::WALL_CLOSED_MESSAGE() ;
    }

    public function getHead() {
        return \I18n::WALL_CLOSED() ;
    }
    
    public function getClasses() {
        return parent::getClasses() . " critical";
    }

}
