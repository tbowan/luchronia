<?php

namespace Answer\Widget\Game\City ;

class BuildingCard extends \Quantyl\Answer\Widget {
    
    private $_instance ;
    private $_manage ;
    
    public function __construct(\Model\Game\Building\Instance $i, $canmanage, $add_classe = "") {
        $this->_instance = $i ;
        $this->_manage = $canmanage ;
    }
    
    public function getContent() {
        
        $i = $this->_instance ;
        
        $msg  = "<div class=\"item\">" ;
        $msg .= "<div class=\"icon\">" ;
        $msg .= $this->_instance->getImage("full") ;
        $msg .= "</div>" ;
        
        $msg .= "<div class=\"description\">" ;
        $msg .= "<div class=\"name\">"
                    . $this->_instance->getName() 
                    . "</div>" ;
        $msg .= "<div class=\"type\">" 
                    . $this->_instance->type->getName() 
                    . " " . \I18n::LEVEL() . " " . $this->_instance->level
                    . "</div>" ;
        
        $msg .= "<div class=\"health\">"
                    . \I18n::HEALTH_ICO() . " " . new \Quantyl\XML\Html\Meter(0, $i->getMaxHealth(), $i->health)
                    . \I18n::WEAR_ICO()   . " " . new \Quantyl\XML\Html\Meter(0, $i->health, $i->barricade)
                    . "</div>" ;
        
        $msg .= "<div class=\"links\">" ;
        $msg .= new \Quantyl\XML\Html\A("/Game/Building/?id={$i->id}", \I18n::SEE()) ;
        if ($this->_manage) {
            $msg .= new \Quantyl\XML\Html\A("/Game/Ministry/Building?instance={$i->id}", \I18n::ADMINISTER()) ;
        }
        
        $msg .= "</div>" ;
        $msg .= "</div>" ;
        $msg .= "</div>" ;

        return $msg ;
    }
    
    
}
