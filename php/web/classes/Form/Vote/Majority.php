<?php

namespace Form\Vote ;

class Majority extends Base {
    
    public function getInputHTML($key, $value) {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::VOTE(),
            \I18n::CANDIDATE()
        )) ;
        
        foreach ($this->_candidates as $candidate) {
            $checked = (isset($value[$candidate->id]) ? " checked=\"\"" : "") ;
            $table->addRow(array(
                "<input type=\"checkbox\" id=\"{$key}-{$candidate->id}\" name=\"{$key}[{$candidate->id}]\" value=\"1\" $checked/>",
                "<label for=\"{$key}-{$candidate->id}\">" . $candidate->character->getName() . "</label>"
            )) ;
        }
        return "" . $table ;
    }
    
    
    public function parse($value) {
        $values = parent::parse($value) ;
        
        if (count($values) > $this->_question->point) {
            throw new \Exception(\I18n::EXP_VOTE_TOO_MANY($this->_question->point)) ;
        }
        
        foreach ($values as $t) {
            if ($t[1] != 1) {
               throw new \Exception() ;
            }
        }
        
        return $values ;
    }
    
    public function unparse($value) {
        parent::unparse($value);
    }

    public function getDescription() {
        return \I18n::VOTE_DESCRIPTION_MAJORITY($this->_question->point);
    }
    
}
