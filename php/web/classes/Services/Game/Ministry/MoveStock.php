<?php

namespace Services\Game\Ministry ;

class MoveStock extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::MOVE_STOCK_MESSAGE(
                $this->stock->amount, $this->stock->item->getName()
                )) ;
        $form->addInput("instance", new \Form\InstanceStock($this->stock->item, $this->getCity(), \I18n::BUILDING())) ;
    }
    
    public function onProceed($data) {
        
        \Model\Game\City\Register::createFromValues(array(
            "character" => $this->getCharacter(),
            "city"      => $this->stock->city,
            "from"      => $this->stock->instance,
            "to"        => $data["instance"],
            "ressource"  => $this->stock->item,
            "amount"    => $this->stock->amount
        )) ;
        
        $this->stock->instance = $data["instance"] ;
        $this->stock->price = null ;
        $this->stock->published = null ;
        $this->stock->update() ;
        
    }
    
    public function getCity() {
        return $this->stock->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }
    
}
