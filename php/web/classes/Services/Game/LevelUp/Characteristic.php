<?php

namespace Services\Game\LevelUp ;

class Characteristic extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("characteristic", new \Quantyl\Form\Model\Id(\Model\Game\Characteristic::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // enough points
        if ($this->getCharacter()->point < 1) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_ENOUGH_LEVEL_POINT()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::INCREASE_CHARACTERISTIC_MESSAGE($this->getCharacter()->point)) ;
    }
    
    public function onProceed($data) {
        $char = $this->getCharacter() ;
        $char->point -= 1 ;
        $char->update() ;
        
        \Model\Game\Characteristic\Character::IncForCharacterAndCharacteristic($char, $this->characteristic, 1) ;
    }
    
    
}
