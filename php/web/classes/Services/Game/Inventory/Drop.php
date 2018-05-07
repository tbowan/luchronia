<?php

namespace Services\Game\Inventory ;

use Exception;
use Model\Game\Ressource\Inventory;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Drop extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Inventory::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        if (! $me->equals($this->id->character)) {
            // TODO Better exception
            throw new Exception() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::INVENTORY_DROP_CONFIRM($this->id->item->getName(), $this->id->amount)) ;
    }
    
    public function onProceed($data) {
        $item = $this->id->item ;
        $city = $this->id->character->position ;
        
        $msg = \I18n::INVENTORY_DROP_DONE(
                $this->id->amount,
                $item->getName(),
                $city->id,
                $city->getName()
                ) ;
        
        \Model\Game\Ressource\City::GiveToCity($city, $item, $this->id->amount, $this->getCharacter()) ;
        
        $this->id->delete() ;
        
        $this->setAnswer(new Message($msg)) ;
    }
    
}
