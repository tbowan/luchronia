<?php

namespace Services\Game\Inventory ;

use Exception;
use Model\Game\Ressource\Inventory;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Widget\Exception as Exception2;

class UnEquip extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Inventory::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        if (! $me->equals($this->id->character)) {
            // TODO Better exception
            throw new Exception2() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::INVENTORY_UNEQUIP_CONFIRM($this->id->item->getName(), $this->id->amount)) ;
    }
    
    public function onProceed($data) {
        
        $used = Inventory::InventoryUsed($this->id->character) ;
        $available = $this->id->character->inventory ;
        
        if ($used >= $available) {
            throw new Exception(\I18n::EXP_INVENTORY_FULL()) ;
        }
        
        $msg = \I18n::INVENTORY_UNEQUIP_DONE($this->id->item->getName(), $this->id->amount) ;
        $this->id->slot = null ;
        $this->id->update() ;
        $this->setAnswer(new Message($msg)) ;
    }
    
}
