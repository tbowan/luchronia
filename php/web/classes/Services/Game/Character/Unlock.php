<?php

namespace Services\Game\Character ;

class Unlock extends \Services\Base\Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->getCharacter()->locked) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_ALREADY_UNLOCKED()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CHARACTER_UNLOCK_MESSAGE()) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $this->getCharacter()->unlock() ;
    }
}
