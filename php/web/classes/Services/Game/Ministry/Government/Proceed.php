<?php

namespace Services\Game\Ministry\Government ;

class Proceed extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("government", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Government::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->government->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::GOVERNMENT_PROCEED_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        $system = $this->government->system ;
        $system->proceedGovernmentProject($this->government) ;
    }
    
}
