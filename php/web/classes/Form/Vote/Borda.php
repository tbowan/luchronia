<?php

namespace Form\Vote ;

class Borda extends Base {
    
    public function parse($value) {
        $values = parent::parse($value) ;
        
        if (count($values) > $this->_question->point) {
            throw new \Exception(\I18n::EXP_VOTE_TOO_MANY($this->_question->point)) ;
        }
        
        $res = array() ;
        
        foreach ($values as $t) {
            if ($t[1] < 1 || $t[1] > $this->_question->point || isset($res[$t[1]])) {
                throw new \Exception(\I18n::EXP_WRONG_RANKING()) ;
            } else {
                $res[$t[1]] = array($t[0], 1 + $this->_question->point - $t[1]) ;
            }
        }
        
        return $res ;
    }
    
    public function unparse($value) {
        parent::unparse($value);
    }

    public function getDescription() {
        return \I18n::VOTE_DESCRIPTION_BORDA($this->_question->point);
    }
    
}
