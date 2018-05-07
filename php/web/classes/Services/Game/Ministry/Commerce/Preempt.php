<?php

namespace Services\Game\Ministry\Commerce ;

class Preempt extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Character\Market\Sell::getBddTable())) ;
    }
    
    public function getCity() {
        return $this->sell->market->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::PREEMPT_MESSAGE()) ;
        // Sell Part
        $sell = $form->addInput("sell", new \Quantyl\Form\FieldSet(\I18n::BUY())) ;
        $sell->addMessage(\I18n::COMPLETE_SELL_MESSAGE(
                $this->sell->character->getName(),
                $this->sell->ressource->getName(),
                $this->sell->remain,
                $this->sell->price,
                0,
                $this->sell->price )) ;
        $sell->addInput("quantity", new \Quantyl\Form\Fields\Integer(\I18n::QUANTITY(), true)) ;
        // Stock part
        $stock = $form->addInput("stock", new \Quantyl\Form\FieldSet(\I18n::STOCK())) ;
        $stock->addInput("instance", new \Form\InstanceStock($this->sell->ressource, $this->getCity(), \I18n::BUILDING())) ;
        
    }

    public function onProceed($data) {
        
        if ($data["sell"]["quantity"] <= 0) {
            throw new \Exception(\I18n::EXP_QUANTITY_MUST_BE_POSITIVE()) ;
        } else if ($data["sell"]["quantity"] > $this->sell->remain) {
            throw new \Exception(\I18n::EXP_QUANTITY_TOO_HIGH($this->sell->remain)) ;
        }
        
        $credits = $data["sell"]["quantity"] * $this->sell->price ;
        
        $city = $this->getCity() ;
        if ($city->credits < $credits ) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_MONEY($credits)) ;
        }
        
        // Pay :
        $city->addCredits(- $credits) ;
        
        // Get items
        $stock = \Model\Game\Ressource\City::createFromValues(array(
            "city" => $this->getCity(),
            "item" => $this->sell->ressource,
            "amount" => $data["sell"]["quantity"],
            "instance" => $data["stock"]["instance"],
            "price" => null,
            "published" => false
        )) ;
        
        // Remove from sell
        $this->sell->remain -= $data["sell"]["quantity"] ;
        $this->sell->update() ;

    }
    
}
