<?php

namespace Services\Game\Ministry\Government ;

class Create extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("system", new \Quantyl\Form\Model\Id(\Model\Game\Politic\System::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        if (! $this->system->canManage($char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }

    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::GOVERNMENT_CREATE_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        $char = $this->getCharacter() ;
        $system = $this->system ;
        
        $gov = $system->createGovernmentProject($char) ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/Government/Show?government={$gov->id}")) ;
    }
    
}
