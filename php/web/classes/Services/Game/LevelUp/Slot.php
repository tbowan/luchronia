<?php

namespace Services\Game\LevelUp ;

class Slot extends \Services\Base\Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // enough points
        if ($this->getCharacter()->point < $this->getCharacter()->inventory - 4) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_ENOUGH_LEVEL_POINT()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::INCREASE_SLOT_MESSAGE(
                $this->getCharacter()->inventory,
                $this->getCharacter()->inventory + 1,
                $this->getCharacter()->inventory - 4,
                $this->getCharacter()->point)) ;
    }
    
    public function onProceed($data) {
        $char = $this->getCharacter() ;
        $char->point -= $char->inventory - 4 ;
        $char->inventory += 1 ;
        $char->update() ;
    }
    
    
}
