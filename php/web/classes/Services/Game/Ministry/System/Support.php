<?php

namespace Services\Game\Ministry\System ;

class Support extends \Services\Base\Character {

    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("revolution", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Revolution::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $endimune = \Model\Game\Politic\System::ImuneUntil($this->revolution->system->city) ;
         
        if (time() < $endimune) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REVOOLUTION_IMUNE(\I18n::_date_time($endimune - DT))) ;
        } else if (! $this->getCharacter()->isCitizen($this->revolution->system->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REVOLUTION_NEED_CITIZENSHIP()) ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::REVOLUTION_SUPPORT_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        
        \Model\Game\Politic\Support::doSupport($this->revolution, $this->getCharacter()) ;
        
    }

}
