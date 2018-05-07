<?php

namespace Services\Game\Ministry\Question ;

class Candidate extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("question", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Question::getBddTable())) ;
    }

    public function getCity() {
        return $this->question->system->city ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->getCharacter()->isCitizen($this->getCity())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        if (\Model\Game\Politic\Choice::hasVoted($this->question, $this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $formname = "\\Form\\Vote\\" . $this->question->vote->getValue() ;
        $form->addInput("vote", new $formname($this->question, \I18n::YOUR_VOTE()));
        
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
             ->setCallBack($this, "onProceed");
    }
    
    public function onProceed($data) {
        $me = $this->getCharacter() ;
        
        foreach ($data["vote"] as $choice) {
            \Model\Game\Politic\Choice::createFromValues(array(
                "candidate" => $choice[0],
                "character" => $me,
                "value" => $choice[1]
            )) ;
        }
        
    }
    
}
