<?php

namespace Services\Game\Ministry\Government ;

class Delete extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("government", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Government::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $gov = $this->government ;
        if (! $gov->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::DELETE_GOVERNMENT()) ;
    }
    
    public function onProceed($data) {
        $this->government->delete() ;
    }
    
}
