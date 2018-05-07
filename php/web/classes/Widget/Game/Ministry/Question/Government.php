<?php

namespace Widget\Game\Ministry\Question ;

class Government extends \Quantyl\Answer\Widget {
    
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
                . $this->getGovernment()
                . $this->getVoting() ;
    }
    
    public function getGovernment() {
        $gov = \Model\Game\Politic\Government::GetFromQuestion($this->_question) ;
        if ($gov != null) {
            return new \Widget\Game\Ministry\Government\Details($gov) ;
        } else {
            return \I18n::QUESTION_NO_GOVERNMENT() ;
        }
    }
    
    public function getVoting() {
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
    
    public function getBefore() {
        $city = $this->_question->system->city ;
        $res = "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY() . " : </strong>" . new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::START() . " : </strong>" . \I18n::_date_time($this->_question->start - DT) . "</li>" ;
        $res .= "<li><strong>" . \I18n::END() . " : </strong>" . \I18n::_date_time($this->_question->end - DT) . "</li>" ;
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getWhile() {
        $res = "" ;
        $res .= $this->getBefore() ;
        
        if (! $this->_question->system->canManage($this->_character)) {
            $res .= \I18n::QUESTION_CANNOT_VOTE() ;
        } else if (\Model\Game\Politic\Vote::hasVoted($this->_question, $this->_character)) {
            $res .= \I18n::QUESTION_ALREADY_VOTE() ;
        } else {
            $form = new \Quantyl\Form\Form() ;
            $form->addInput("_referer", new \Quantyl\Form\Fields\Caller("/Game/Ministry/Question?question={$this->_question->id}")) ;
            $form->addInput("value", new \Form\Vote\System(\I18n::YOUR_VOTE()));
            $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
            $res .= $form->getContent("/Game/Ministry/Question/Government?question={$this->_question->id}") ;
        }
        
        return $res ;
    }
    
    public function getAfter() {
        $res = "" ;
        $res .= $this->getBefore() ;
        
        if ($this->_question->processed) {
            $res .= \I18n::QUESTION_GOVERNEMENT_RESULT(100 * $this->_question->turnout, 100 * $this->_question->answer) ;
        } else {
            $res .= \I18n::QUESTION_WAITING_PROCEED() ;
        }
        
        return $res ;
    }
    
}
