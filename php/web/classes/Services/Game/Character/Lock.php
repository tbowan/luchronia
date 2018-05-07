<?php

namespace Services\Game\Character ;

class Lock extends \Services\Base\Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->getCharacter()->locked) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_ALREADY_LOCKED()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CHARACTER_LOCK_MESSAGE()) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $this->getCharacter()->lock() ;
    }
}
