<?php

namespace Services\Game\Ministry\Commerce ;

class GrantNeeded extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("need", new \Quantyl\Form\Model\Id(\Model\Game\Building\Need::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::GRANT_NEEDED_MESSAGE(
                $this->need->needed - $this->need->provided,
                $this->need->item->getName())) ;
        $form->addInput("stock", new \Form\CityStock($this->need->item, $this->getCity(), \I18n::STOCK(), true)) ;
    }
    
    
    
    public function onProceed($data) {
        
        $qtty = min($this->need->needed - $this->need->provided, $data["stock"]->amount) ;
        $data["stock"]->amount -= $qtty ;
        $data["stock"]->update() ;
        
        $this->need->provided += $qtty ;
        $this->need->update() ;
        
    }

    public function getCity() {
        return $this->need->site->instance->city ;
    }
    
    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }

}
