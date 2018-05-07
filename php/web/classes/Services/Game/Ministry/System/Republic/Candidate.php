<?php

namespace Services\Game\Ministry\System\Republic ;

class Candidate extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("question", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Question::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $now = time() ;
        if ($this->question->start < $now) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if (! $this->question->system->city->equals($this->getCharacter()->citizenship)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        $candidate = \Model\Game\Politic\Candidate::GetCandidate($this->question, $this->getCharacter()) ;
        if ($candidate != null) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::PRESIDENT_CANDIDATE_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        
        \Model\Game\Politic\Candidate::createFromValues(array(
            "question" => $this->question,
            "character" => $this->getCharacter()
        )) ;
        
    }
    
}
