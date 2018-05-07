<?php

namespace Widget\Game\Ministry\Question ;

class System extends \Quantyl\Answer\Widget {
    
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
                . $this->getSystem()
                . $this->getVoting() ;
    }
    
    public function getSystem() {
        $sys = \Model\Game\Politic\System::GetFromQuestion($this->_question) ;
        if ($sys != null) {
            $res = "<h2>" . \I18n::SYSTEM_INFORMATIONS() . "</h2>" ;
            $res .= $this->_question->system->type->getImage("left-illustr") ;
            $res .= "<ul>" ;
            if ($this->_question->system->name != "") {
                $res .= "<li><strong>" . \I18n::NAME()        . " :</strong> " . $this->_question->system->name . " </li>" ;
            }
            $res .= "<li><strong>"
                    . \I18n::CITY()
                    . " :</strong> "
                    . new \Quantyl\XML\Html\A("/Game/City?id={$this->_question->system->city->id}", $this->_question->system->city->getName())
                    . " </li>" ;

            $res .= "<li><strong>" 
                    . \I18n::SYSTEM_TYPE()
                    . " :</strong> "
                    . $this->_question->system->type->getName()
                    . " "
                    . \I18n::HELP("/Help/Politic/System?id={$this->_question->system->type->getId()}")
                    . " </li>" ;
            $res .= "</ul>" ;
            $res .= "<p>" . new \Quantyl\XML\Html\A("/Game/Ministry/System/?system={$sys->id}", \I18n::DETAILS()) . "</p>" ;
            return $res ;
        } else {
            return \I18n::QUESTION_NO_SYSTEM() ;
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
            $res .= $form->getContent("/Game/Ministry/Question/System?question={$this->_question->id}") ;
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
