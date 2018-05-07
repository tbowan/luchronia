<?php

namespace Widget\Game\Ministry\System ;

abstract class Base extends \Quantyl\Answer\Widget {
    
    protected $_system ;
    protected $_character ;
    protected $_canmanage ;
    
    public function __construct(\Model\Game\Politic\System $system, \Model\Game\Character $character) {
        $this->_system    = $system ;
        $this->_character = $character ;
        $this->_canmanage = $this->_system->canManage($this->_character) ;
    }
    
    public function getContent() {
        $res = ""
                . $this->getInformations()
                . $this->getSystem()
                . $this->getGovernment()
                . $this->getQuestion()
                . $this->getOpposition() ;
        return $res ;
    }
    
    public function getInformations() {
        $res = "<h2>" . \I18n::MINISTRY_SUMMARY() . "</h2>" ;
        $res .= \I18n::DGAP_IMAGE("illustration") ;
        $res .= \I18n::DGAP_DESCRIPTION() ;
        return $res ;
    }
    
    public function getSystem() {
        $res = "<h2>" . \I18n::SYSTEM_INFORMATIONS() . "</h2>" ;
        
        $res .= $this->_system->type->getImage("left-illustr") ;
        $res .= "<ul>" ;
        if ($this->_system->name != "") {
            $res .= "<li><strong>" . \I18n::NAME()        . " :</strong> " . $this->_system->name . " </li>" ;
        }
        $res .= "<li><strong>"
                . \I18n::CITY()
                . " :</strong> "
                . new \Quantyl\XML\Html\A("/Game/City?id={$this->_system->city->id}", $this->_system->city->getName())
                . " </li>" ;
                
        $res .= "<li><strong>" 
                . \I18n::SYSTEM_TYPE()
                . " :</strong> "
                . $this->_system->type->getName()
                . " "
                . \I18n::HELP("/Help/Politic/System?id={$this->_system->type->getId()}")
                . " </li>" ;
        $res .= "<li><strong>"
                . \I18n::START_DATE()
                . " :</strong> "
                . ($this->_system->start == null ? "-" : \I18n::_date_time($this->_system->start - DT))
                . "</li>" ;
        $res .= "<li><strong>"
                . \I18n::END_DATE()
                . " :</strong> "
                . ($this->_system->end == null ? "-" : \I18n::_date_time($this->_system->end - DT))
                . "</li>" ;
        $res .= "</ul>" ;
        
        $res .= "<div class=\"clear\"></div>" ;
        $res .= $this->getSpecific() ;
        
        if ($this->_canmanage) {
            $res .= \I18n::DGAP_SYSTEM_CHANGE() ;
        }
        
        return $res ;
    }
    
    public function getSpecific() {
        return "" ;
    }
    
    public function getGovernment() {
        $res = "<h2>" . \I18n::CURRENT_GOVERNMENT() . "</h2>" ;
        
        $res .= "<p>" . new \Quantyl\XML\Html\A("/Game/Ministry/Government/Log?city={$this->_system->city->id}", \I18n::GOVERNMENT_LOG()) . "</p>" ;
        
        $gov = \Model\Game\Politic\Government::CurrentFromSystem($this->_system) ;
        if ($gov == null) {
            $res .= \I18n::DGAP_NO_GOVERNMENT_MSG() ;
        } else {
            $table = new \Quantyl\XML\Html\Table() ;
            $table->addHeaders(array(\I18n::MINISTRY(), \I18n::MINISTER())) ;
            foreach (\Model\Game\Politic\Ministry::GetAll() as $ministry) {
                $ministers = array() ;
                foreach (\Model\Game\Politic\Minister::getMinistersForMinistry($gov, $ministry) as $minister) {
                    $ministers[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$minister->character->id}", $minister->character->getName()) ;
                }
                $table->addRow(array(
                    $ministry->getImage("icone-inline") . " " . $ministry->getName(),
                    implode("<br/>", $ministers)
                )) ;
            }

            $res .= $table ;
        }
        
        if ($this->_canmanage) {
            $res .= \I18n::DGAP_MYGOVERNMENTS() ;
        }
        
        return $res ;
    }
    
    
    public function getQuestion() {
        
        $res = "<h2>" . \I18n::DGAP_QUESTIONS() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::QUESTION_TYPE(),
            \I18n::START(),
            \I18n::END(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Politic\Question::GetFromSystem($this->_system) as $question) {
            $table->addRow(array(
                $question->type->getName(),
                \I18n::_date_time($question->start - DT),
                \I18n::_date_time($question->end - DT),
                new \Quantyl\XML\Html\A("/Game/Ministry/Question?question={$question->id}", \I18n::DETAILS())
            )) ;
        }
        
        if ($table->getRowsCount() > 0) {
            $res .= $table ;
        } else {
            $res .= \I18n::DGAP_NO_QUESTION() ;
        }
        
        return $res ;
        
    }
    
    public function getOpposition() {
        $res = "<h2>" . \I18n::DGAP_OPPOSITION() . "</h2>" ;
        
        $last = \Model\Game\Politic\System::LastFromCity($this->_system->city) ;
        if ($last->equals($this->_system)) {
            $res .= $this->getRevolution() ;
        } else {
            $res .= \I18n::NOT_CURRENT_SYSTEM($last->id, $last->type->getName());
        }
        
        return $res ;
        
    }
    
    public function getRevolution() {
        $revolution = \Model\Game\Politic\Revolution::GetFromCharacter($this->_character) ;
        $support    = \Model\Game\Politic\Support::GetFromCharacter($this->_character) ;
        
        $ended = \Model\Game\Politic\RevolutionStatus::Ended() ;
        $rev = $revolution != null && ! $revolution->getStatus()->equals($ended) ;
        $sup = $support    != null && ! $support->revolution->getStatus()->equals($ended) ;
        
        if (! $this->_system->city->equals($this->_character->citizenship)) {
            return \I18n::REVOLUTION_IMPOSSIBLE() ;
        } else if ($this->_system->type->equals(\Model\Game\Politic\SystemType::Revolution())) {
            return \I18n::REVOLUTION_ALREADY() ;
        } else if ($rev) {
            return \I18n::REVOLUTION_CREATED($revolution->system->id) ;
        } else if ($sup) {
            return \I18n::REVOLUTION_SUPPORTED($support->revolution->system->id) ;
        } else {
            return \I18n::REVOLUTION_TOBEMADE() ;
        }
    }
    
    
    
    
}
