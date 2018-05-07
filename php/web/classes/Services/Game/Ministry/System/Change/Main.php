<?php

namespace Services\Game\Ministry\System\Change ;

class Main extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $current = \Model\Game\Politic\System::LastFromCity($this->city) ;
        if (! $current->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::CHANGE_SYSTEMTYPE_MESSAGE()) ;
        $form->addInput("systemtype", new \Form\SystemType($this->city, \I18n::SYSTEM_TYPE())) ;
        
        return $form ;
    }
    
    public function onProceed($data) {
        $type = $data["systemtype"];
        $typename = $type->getValue() ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/System/Change/{$typename}?city={$this->city->id}&revolt=0")) ;
    }
    
}
