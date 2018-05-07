<?php

namespace Services\Game\Ministry ;

class GroupStock extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::GROUP_STOCK_MESSAGE(
                $this->stock->amount, $this->stock->item->getName()
                )) ;
    }
    
    public function onProceed($data) {
        
        $placed = $this->stock->group() ;
        
                
        $msg = \I18n::STOCK_GROUP_DONE($placed, $this->stock->item->getName(), $this->stock->amount) ;
        $this->stock->update() ; // May be deleted if amount = 0 (done by the object itself)
        $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        
    }
    
    public function getCity() {
        return $this->stock->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }
    
}
