<?php

namespace Services\Game\Ministry\Development ;

class FixPriceNeeded extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
        $form->addInput("item", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Item::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Development") ;
    }

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FIX_PRICE_NEEDED_MESSAGE(
                $this->city->id, $this->city->getName(),
                $this->item->id, $this->item->getName()
                )) ;
        $form->addInput("price", new \Quantyl\Form\Fields\Float(\I18n::PRICE(), true))
             ->setValue(\Model\Game\Trading\Needed::GetPrice($this->item, $this->city));
    }
    
    public function onProceed($data) {

        if ($data["price"] <= 0) {
            throw new \Exception(\I18n::EXP_NEGATIVE_PRICE()) ;
        }
        
        \Model\Game\Trading\Needed::SetPrice($this->item, $this->city, $data["price"]) ;
    }
    
    
}
