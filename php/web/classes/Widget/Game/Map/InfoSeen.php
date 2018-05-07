<?php

namespace Widget\Game\Map ;

class InfoSeen extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_summary ;
    private $_message ;
    
    public function __construct(\Model\Game\City $c, \Model\Game\Character $char) {
        $this->_city = $c ;
        $this->_summary = new \Widget\Game\City\CitySummary($c, $char) ;
        $this->_message = new \Widget\Game\City\CityMessage($c, $char) ;
    }
    
    public function getContent() {
        $res = "" ;
        $res .= $this->_summary ;
        $res .= new \Quantyl\XML\Html\A("/Game/Map/?city={$this->_city->id}", \I18n::MAP_CENTER_HERE()) ;
        $res .= $this->_message ;
        return $res ;
    }
    
    public function isDecorable() {
        return false ;
    }
    
    
}