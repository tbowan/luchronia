<?php

namespace Services\Game\Ministry\System\Republic ;

class Uncandidate extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("candidate", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Candidate::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $now = time() ;
        if ($this->candidate->question->start < $now) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if (! $this->candidate->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::PRESIDENT_UNCANDIDATE_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        $this->candidate->delete() ;
    }
    
}
