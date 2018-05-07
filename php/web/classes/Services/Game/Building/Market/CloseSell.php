<?php

namespace Services\Game\Building\Market ;

class CloseSell extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Character\Market\Sell::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->sell->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_CLOSE_OTHER_ORDER()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CLOSE_SELL_MESSAGE(
                $this->sell->ressource->getName(),
                $this->sell->remain,
                ($this->sell->total - $this->sell->remain) * $this->sell->price
                )) ;
    }
    
    public function onProceed($data) {
        
        $me = $this->getCharacter() ;
        $me->addCredits(($this->sell->total - $this->sell->remain) * $this->sell->price) ;
        $me->update() ;
        
        $rest = \Model\Game\Ressource\Inventory::AddItem($me, $this->sell->ressource, $this->sell->remain) ;
        if ($rest > 0) {
            $me = $this->getCharacter() ;
            $city = $me->position ;
            $item = $this->sell->ressource ;
            \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
            $msg = \I18n::INVENTORY_FULL_GIVE_CITY(
                    $rest, $item->getName(),
                    $city->id, $city->id, $city->getName()
                    ) ;
        }
        
        $this->sell->delete() ;
        
        if ($msg != null) {
            $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        }
    }
    
    public function getCity() {
        return $this->sell->market->city ;
    }

}
