<?php

namespace Answer\Widget\Game\Ministry\Dgap ;

class Question extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Politic\System $system, \Model\Game\Character $viewer, $classes = "") {
        parent::__construct(\I18n::DGAP_QUESTIONS(), "", "", $this->getQuestions($system, $viewer), $classes);
    }
    
    public function getQuestions(\Model\Game\Politic\System $system, \Model\Game\Character $viewer) {
        
        $res = "<ul class=\"itemList\">" ;
        $has = false ;
        foreach (\Model\Game\Politic\Question::GetFromSystem($system) as $question) {
            $res .= $this->getQuestion($question) ;
            $has = true ;
            
        }
        $res .= "</ul>" ;
        
        if (! $has) {
            $res = \I18n::DGAP_NO_QUESTION() ;
        }
        return $res ;
    }
    
    public function getQuestion(\Model\Game\Politic\Question $question) {
        $res = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . "</div>" ; // . $question->type->getImage() . "</div>" ;
        $res .= "<div class=\"description\">" ;
            $res .= "<div class=\"name\">" . $question->type->getName() . "</div>" ;
            $res .= "<div>" . \I18n::_date_time($question->start - DT) . "</div>" ;
            $res .= "<div>" . \I18n::_date_time($question->end - DT) . "</div>" ;
            $res .= "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Ministry/Question?question={$question->id}", \I18n::DETAILS()) . "</div>" ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
        
    }
    
}
