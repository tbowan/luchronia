<?php

namespace Model\Game\Building\Jobs ;

class Field extends Base {
    
    protected $_field ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        
        $this->_field = \Model\Game\Building\Field::GetFromInstance($this->_instance) ;
    }
    
    public function getName() {
        $res = parent::getName() ;
        $res .= " (" . $this->_field->item->getName() . ")" ;
        return $res ;
    }
    
    public function iterate($dt) {
        
        // Equation Logistique
        $un     = $this->_field->amount ;
        $alpha  = max(0, $this->_instance->city->sun * $dt / (60 * 60 * 24)) ;
        $k      = $this->_field->getMaxAmount() ;
        $un1    = $un * (1.0 + $alpha * (1.0 - $un / $k) ) ;
        
        // Mise à jour
        $this->_field->amount = $un1 ;
        $this->_field->update() ;
        
        parent::iterate($dt);
    }
    
}

