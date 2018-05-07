<?php

namespace Form\Vote ;

class Cumulative extends Base {
    
    public function parse($value) {
        $values = parent::parse($value) ;
        
        if (count($values) > $this->_question->point) {
            throw new \Exception(\I18n::EXP_VOTE_TOO_MANY($this->_question->point)) ;
        }
        
        $total = 0 ;
        foreach ($values as $t) {
            $total += $t[1] ;
        }
        if ($total > $this->_question->point) {
            throw new \Exception(\I18n::EXP_VOTE_TOO_MANY($this->_question->point)) ;
        }
        
        return $values ;
    }
    
    public function unparse($value) {
        parent::unparse($value);
    }

    public function getDescription() {
        return \I18n::VOTE_DESCRIPTION_CUMULATIVE($this->_question->point);
    }
    
}
