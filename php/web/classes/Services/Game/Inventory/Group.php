<?php

namespace Services\Game\Inventory ;

use Exception;
use I18n;
use Model\Game\Ressource\Inventory;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Group extends \Services\Base\Character {
    
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
        $form->setMessage(I18n::INVENTORY_GROUP_CONFIRM($this->id->item->getName(), $this->id->amount)) ;
    }
    
    public function onProceed($data) {
        $placed_sum = $this->id->group() ;
        
        $msg = \I18n::INVENTORY_GROUP_DONE($this->id->item->getName(), $placed_sum, $this->id->amount) ;
        $this->id->update() ; // May be deleted if amount = 0 (done by the object itself)
        $this->setAnswer(new Message($msg)) ;
    }
    
}
