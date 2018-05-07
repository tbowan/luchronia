<?php

namespace Services\Game\Ministry\Commerce ;

class Acquire extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::ACQUIRE_MESSAGE($this->stock->amount, $this->stock->item->getName())) ;
    }
    
    public function onProceed() {
        $before = $this->stock->amount ;
        $remain = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $this->stock->item, $this->stock->amount) ;
        
        if ($before > $remain) {
            \Model\Game\City\Register::createFromValues(array(
                "character" => $this->getCharacter(),
                "city" => $this->stock->city,
                "from" => $this->stock->instance,
                "ressource" => $this->stock->item,
                "amount" => $remain - $before
            )) ;
        }
        
        $this->stock->amount = $remain ;
        $this->stock->update() ;
    }

    public function getCity() {
        return $this->stock->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }

}
