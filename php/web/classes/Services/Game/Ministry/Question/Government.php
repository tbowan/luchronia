<?php

namespace Services\Game\Ministry\Question ;

class Government extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("question", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Question::getBddTable())) ;
    }

    public function getCity() {
        return $this->question->system->city ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->question->system->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        if (\Model\Game\Politic\Vote::hasVoted($this->question, $this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("value", new \Form\Vote\System(\I18n::YOUR_VOTE()));
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
             ->setCallBack($this, "onProceed");
    }
    
    public function onProceed($data) {
        \Model\Game\Politic\Vote::createFromValues(array(
            "question" => $this->question,
            "character" => $this->getCharacter(),
            "value" => $data["value"]
        )) ;
    }
    
}
