<?php

namespace Services\Game\Ministry\Commerce ;

class SetTarif extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::STOCK_SETTARIF_COMMERCE($this->stock->item->getName())) ;
        $form->addInput("price", new \Quantyl\Form\Fields\Float(\I18n::PRICE()))
             ->setValue($this->stock->price);
        $form->addInput("published", new \Quantyl\Form\Fields\Boolean(\I18n::PUBLISHED()))
             ->setValue($this->stock->published);
    }

    public function onProceed($data) {
        $this->stock->price = $data["price"] ;
        $this->stock->published = $data["published"] ;
        $this->stock->update() ;
    }
    
    public function getCity() {
        return $this->stock->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }
    
}
