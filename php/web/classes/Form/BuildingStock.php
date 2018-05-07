<?php

namespace Form ;

class BuildingStock extends \Quantyl\Form\Model\Select {
    
    private $_item ;
    private $_inst ;
    
    public function __construct(
            \Model\Game\Ressource\Item $item,
            \Model\Game\Building\Instance $inst,
            $label = null, $mandatory = false, $description = null) {
        $this->_item = $item ;
        $this->_inst = $inst ;
        parent::__construct(\Model\Game\Ressource\City::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $choices = array() ;
        if (! $this->isMandatory()) {
            $choices[0] = \I18n::NONE() ;
        }
        foreach (\Model\Game\Ressource\City::GetFromInstance($this->_inst) as $inst) {
            if ($inst->item->equals($this->_item)) {
                $choices[$inst->id] = ($inst->instance === null ? "" : $inst->instance->getName() . " - " ) . $inst->item->getName() . " x " . $inst->amount  ;
            }
        }
        return $choices ;
    }
    
}
