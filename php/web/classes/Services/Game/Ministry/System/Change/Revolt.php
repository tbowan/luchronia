<?php

namespace Services\Game\Ministry\System\Change ;

class Revolt extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        
        if (! $char->isCitizen($this->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REVOLUTION_NEED_CITIZENSHIP()) ;
        }
        
        $endimune = \Model\Game\Politic\System::ImuneUntil($this->city) ;
        if (time() < $endimune) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REVOOLUTION_IMUNE(\I18n::_date_time($endimune - DT))) ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CHANGE_SYSTEMTYPE_MESSAGE()) ;
        $form->addInput("systemtype", new \Form\SystemType($this->city, \I18n::SYSTEM_TYPE())) ;
    }
    
    public function onProceed($data) {
        $type = $data["systemtype"];
        $typename = $type->getValue() ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/System/Change/{$typename}?city={$this->city->id}&revolt=1")) ;
    }
    
}
