<?php

namespace Services\Game\Building\Market ;

class CompleteSell extends \Services\Base\Door {
    
    private $_tax ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Character\Market\Sell::getBddTable())) ;
    }
    
    public function init() {
        parent::init();
        
        $this->_tax = \Model\Game\Tax\Tradable::GetFromInstance($this->sell->market) ;
    }

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        
        $form->addMessage(\I18n::COMPLETE_SELL_MESSAGE(
                $this->sell->character->getName(),
                $this->sell->ressource->getName(),
                $this->sell->remain,
                $this->sell->price,
                $this->_tax->trans,
                $this->sell->price * (1.0 + $this->_tax->trans))) ;
        $form->addInput("quantity", new \Quantyl\Form\Fields\Integer(\I18n::QUANTITY(), true)) ;
        
    }
    
    public function onProceed($data) {
        
        if ($data["quantity"] <= 0) {
            throw new \Exception(\I18n::EXP_QUANTITY_MUST_BE_POSITIVE()) ;
        } else if ($data["quantity"] > $this->sell->remain) {
            throw new \Exception(\I18n::EXP_QUANTITY_TOO_HIGH($this->sell->remain)) ;
        }
        
        $credits = $data["quantity"] * $this->sell->price ;
        $tax = $credits * $this->_tax->trans ;
        
        $me = $this->getCharacter() ;
        if ($me->getCredits() < $credits + $tax) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_MONEY($credits + $tax)) ;
        }
        
        // Check credits
        $me->addCredits( - ($credits + $tax)) ;
        $me->update() ;
        
        $this->sell->market->city->addCredits($tax) ;
        
        $rest = \Model\Game\Ressource\Inventory::AddItem($me, $this->sell->ressource, $data["quantity"]) ;
        if ($rest > 0) {
            $city = $this->sell->market->city ;
            $item = $this->sell->ressource ;
            \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
            $msg = \I18n::INVENTORY_FULL_GIVE_CITY(
                    $rest, $item->getName(),
                    $city->id, $city->id, $city->getName()
                    ) ;
        }
        
        $this->sell->remain -= $data["quantity"] ;
        $this->sell->update() ;
        
        if($this->sell->remain == 0){
            \Model\Event\Listening::Social_Commerce_Item($this->sell) ;
        }
        
        if ($msg != null) {
            $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        }
        
    }
    
    public function getCity() {
        return $this->sell->market->city ;
    }

}
