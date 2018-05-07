<?php

namespace Services\Game\Inventory ;

use Exception;
use Model\Game\Ressource\Inventory;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Modifier extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Inventory::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        if (! $me->equals($this->id->character)) {
            // TODO Better exception
            throw new Exception() ;
        } else if (!\Model\Game\Ressource\Modifier::CanUse($me, $this->id->item)) {
            // TODO Better exception
            throw new Exception() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $msg = \I18n::USE_FORM_MODIFIER_MESSAGE() ;
        foreach (\Model\Game\Ressource\Modifier::GetByItem($this->id->item) as $item_modifier) {
            $msg .= new \Answer\Widget\Game\Ressource\ModifierCard($item_modifier->modifier) ;
        }
        $form->setMessage($msg) ;
    }
    
    public function onProceed($data) {
        $me             = $this->getCharacter() ;
        
        $msg = \I18n::USE_FORM_MODIFIER_PROCEED() ;
        
        foreach (\Model\Game\Ressource\Modifier::GetByItem($this->id->item) as $item_modifier) {
            $base_modifier  = $item_modifier->modifier ;
            $base_modifier->giveTo($me) ;
            $msg .= new \Answer\Widget\Game\Ressource\ModifierCard($base_modifier) ;
        }

        $this->id->amount -= 1 ;
        $this->id->update() ;

        $this->setAnswer(new Message($msg)) ;
    }
    
}
