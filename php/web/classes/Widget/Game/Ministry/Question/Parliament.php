<?php

namespace Widget\Game\Ministry\Question ;

class Parliament extends President {

        
    public function getAfter() {
        $res = "" ;
        if ($this->_question->processed) {
            $chosen = \Model\Game\Politic\Candidate::getChosen($this->_question) ;
            $names = array() ;
            foreach ($chosen as $cand) {
                $names[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$cand->character->id}", $cand->character->getName()) ;
            }
            $res .= \I18n::QUESTION_PARLIAMENT_RESULT(implode(", ", $names)) ;
        } else {
            $res .= \I18n::QUESTION_WAITING_PROCEED() ;
        }
        return $res ;
    }
    
}
