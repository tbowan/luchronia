<?php

namespace Services\Game\Post ;

class Open extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("parcel", new \Quantyl\Form\Model\Id(\Model\Game\Post\Parcel::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->parcel->recipient->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if ($this->parcel->tf > time() || ! $this->parcel->destination->equals($this->getCharacter()->position)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {
        
        $me = $this->getCharacter() ;
        
        $msg = "" ;
        foreach (\Model\Game\Post\Good::GetFromParcel($this->parcel) as $good) {
            $rest = \Model\Game\Ressource\Inventory::AddItem($me, $good->item, $good->amount) ;
            if ($rest > 0) {
                $city = $me->position ;
                $item = $good->item ;
                \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
                $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                        $rest, $item->getName(),
                        $city->id, $city->id, $city->getName()
                        ) ;
            }
        }
        
        $me->addCredits($this->parcel->credits) ;
        $me->update() ;
        
        $this->parcel->delete() ;
        
        if ($msg != null) {
            $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        } else {
            return new \Quantyl\Answer\Redirect("/Game/Inventory") ;
        }
    }
    
    
}
