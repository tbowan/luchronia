<?php

namespace Form ;

class InstanceStock extends \Quantyl\Form\Model\Select {
    
    private $_item ;
    private $_city ;
    
    public function __construct(
            \Model\Game\Ressource\Item $item,
            \Model\Game\City $city,
            $label = null, $mandatory = false, $description = null) {
        $this->_item = $item ;
        $this->_city = $city ;
        parent::__construct(\Model\Game\Building\Instance::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $choices = array() ;
        if (! $this->isMandatory()) {
            $choices[0] = \I18n::NONE() ;
        }
        foreach (\Model\Game\Building\Instance::GetFromCity($this->_city) as $inst) {
            if ($inst->acceptStock($this->_item)) {
                $choices[$inst->id] = $inst->getName() ;
            }
        }
        return $choices ;
    }
    
}
