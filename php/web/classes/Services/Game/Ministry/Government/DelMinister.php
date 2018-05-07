<?php

namespace Services\Game\Ministry\Government ;

class DelMinister extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("minister", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Minister::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->minister->government->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::GOVERNMENT_DELMINISTER_MESSAGE(
                $this->minister->character->getName(),
                $this->minister->ministry->getName()
                )) ;
    }
    
    public function onProceed($data) {
        $this->minister->delete() ;
        
    }
    
}
