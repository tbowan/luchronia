<?php

namespace Services\Game\Inventory ;

use Exception;
use Form\SlotForItem;
use Model\Game\Ressource\Equipable;
use Model\Game\Ressource\Inventory;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Equip extends \Services\Base\Character {
    
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
        $form->addInput("slot", new SlotForItem($this->id->item, \I18n::SLOT())) ;
    }
    
    public function onProceed($data) {
        
        $slot = $data["slot"] ;
        
        $used = 0 ;
        foreach (Inventory::GetEquiped($this->id->character, $slot) as $inv) {
            if (! $inv->equals($this->id)) {
                $used += Equipable::GetNeededPlaces($inv->item, $slot) ;
            }
        }
        
        $need = Equipable::GetNeededPlaces($this->id->item, $slot) ;
        
        if ($used + $need > $slot->amount) {
            throw new \Exception(\I18n::EXP_EQUIP_NO_MORE_PLACE()) ;
        }
        
        $this->id->slot = $data["slot"] ;
        $this->id->update() ;
        $msg = \I18n::INVENTORY_DO_EQUIP_MESSAGE($this->id->item->getName(), $this->id->amount, $slot->getName()) ;
        
        $this->setAnswer(new Message($msg)) ;
    }
    
}
