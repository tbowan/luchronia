<?php

namespace Answer\Widget\Game\City ;

class OutdoorCard extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_job ;
    
    
    public function __construct(\Model\Game\City $c) {
        $this->_city = $c ;
        $this->_job = \Model\Game\Building\Job::GetByName("OutSide") ;
    }
    
    public function getContent() {
        
        
        $msg  = "<li><div class=\"item\">" ;
        
        $msg .= "<div class=\"icon\">" ;
        $msg .= $this->_city->biome->getImage("full") ;
        $msg .= "</div>" ;
        
        $msg .= "<div class=\"description\">" ;
            $msg .= "<div class=\"name\">" . $this->_job->getName() . "</div>" ;
            $msg .= "<div>" . \I18n::DAY_OR_NIGHT() . " : " . ($this->_city->sun < 0 ? \I18n::NIGHT() : \I18n::DAY()) . "</div>" ;
            $msg .= "<div>" . \I18n::MONSTERS() . " : " . number_format($this->_city->monster_out) . "</div>" ;
            $msg .= "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$this->_city->id}", \I18n::SEE()) . "</div>" ;
        $msg .= "</div>" ;
        
        $msg .= "</div></li>" ;
        

        return $msg ;
    }
    
}
