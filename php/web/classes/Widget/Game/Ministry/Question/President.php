<?php

namespace Widget\Game\Ministry\Question ;

class President extends \Quantyl\Answer\Widget {

    private $_question ;
    private $_character ;
    
    public function __construct(\Model\Game\Politic\Question $q, \Model\Game\Character $c) {
        parent::__construct();
        $this->_question = $q ;
        $this->_character = $c ;
    }
    
    public function getContent() {
        
        return ""
                . $this->_question->type->getDescription()
                . $this->getCandidates()
                . $this->getVoting() ;
    }
    
    public function GetVoting() {
        $res = "<h2>" . \I18n::QUESTION_VOTING() . "</h2>" ;
        $now = time() ;
        if ($now < $this->_question->start) {
            $res .= $this->getBefore() ;
        } else if ($now < $this->_question->end) {
            $res .= $this->getWhile() ;
        } else {
            $res .= $this->getAfter() ;
        }
        return $res ;
    }
    
    public function getCandidates() {
        $res = "<h2>" . \I18n::CANDIDATES() . "</h2>" ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CANDIDATE(),
            \I18n::RANKING(),
            \I18n::RESULT()
        )) ;
        
        foreach (\Model\Game\Politic\Candidate::GetFromQuestion($this->_question) as $c) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->character->id}", $c->character->getName()),
                $c->rank,
                $c->answer
            )) ;
        }
        $res .= $table ;
        return $res ;
    }
    
    public function getBefore() {
        $candidate = \Model\Game\Politic\Candidate::GetCandidate($this->_question, $this->_character) ;
        if ($candidate != null) {
            return \I18n::QUESTION_PRESIDENT_UNCANDIDATE($candidate->id) ;
        } else if ($this->_question->system->city->equals($this->_character->citizenship)) {
            return \I18n::QUESTION_PRESIDENT_CANDIDATE($this->_question->id) ;
        } else {
            return \I18n::QUESTION_PRESIDENT_CANNOT_CANDIDATE() ;
        }
    }
    
    public function getWhile() {
        $res = "" ;
        
        if (\Model\Game\Politic\Choice::hasVoted($this->_question, $this->_character)) {
            $res .= \I18n::QUESTION_ALREADY_VOTE() ;
        } else if ($this->_question->system->city->equals($this->_character->citizenship)) {
            $form = new \Quantyl\Form\Form() ;
            $form->addInput("_referer", new \Quantyl\Form\Fields\Caller("/Game/Ministry/Question?question={$this->_question->id}")) ;

            $formname = "\\Form\\Vote\\" . $this->_question->vote->getValue() ;
            $form->addInput("vote", new $formname($this->_question, \I18n::YOUR_VOTE()));

            $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
            $res .= $form->getContent("/Game/Ministry/Question/Candidate?question={$this->_question->id}") ;
        } else {
            $res .= \I18n::QUESTION_CANNOT_VOTE() ;
        }
        return $res ;
        
    }
    
    public function getAfter() {
        $res = "" ;
        if ($this->_question->processed) {
            $chosen = \Model\Game\Politic\Candidate::getChosenOne($this->_question) ;
            if ($chosen != null) {
                $res .= \I18n::QUESTION_PRESIDENT_RESULT(
                        $chosen->character->id,
                        $chosen->character->getName()
                        ) ;
            } else {
                $res .= \I18n::QUESTION_PRESIDENT_NONE() ;
            }
        } else {
            $res .= \I18n::QUESTION_WAITING_PROCEED() ;
        }
        return $res ;
    }
    
}
