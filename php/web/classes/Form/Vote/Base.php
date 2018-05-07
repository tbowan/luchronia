<?php

namespace Form\Vote ;

abstract class Base extends \Quantyl\Form\Field {
    
    protected $_question ;
    protected $_candidates ;
    
    public function __construct(\Model\Game\Politic\Question $q, $label = null, $mandatory = false) {
        $this->_question = $q ;
        
        $this->_candidates = array() ;
        foreach (\Model\Game\Politic\Candidate::GetFromQuestion($q) as $c) {
            $this->_candidates[$c->id] = $c ;
        }
        
        parent::__construct($label, $mandatory, $this->getDescription());
    }
    
    public function parse($value) {
        // Muse be array : id => value
        if (!is_array($value)) {
            throw new \Exception() ;
        }
        $votes = array() ;
        foreach ($value as $id => $v) {
            if (! isset($this->_candidates[$id])) {
                throw new \Exception() ;
            } else if ($v > 0) {
                $votes[] = array($this->_candidates[$id], intval($v)) ;
            }
        }
        return $votes ;
    }
    
    public function unparse($value) {
        if (!is_array($value)) {
            throw new \Exception() ;
        }
        $res = array() ;
        foreach ($value as $temp) {
            $res[$temp[0]->id] = $temp[1] ;
        }
        return $res ;
        
    }
    
    public function getInputHTML($key, $value) {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::VOTE(),
            \I18n::CANDIDATE()
        )) ;
        
        foreach ($this->_candidates as $candidate) {
            $v = (isset($value[$candidate->id]) ? $value[$candidate->id] : "") ;
            $table->addRow(array(
                "<input type=\"text\" id=\"{$key}-{$candidate->id}\" name=\"{$key}[{$candidate->id}]\" value=\"$v\" />",
                "<label for=\"{$key}-{$candidate->id}\">" . $candidate->character->getName() . "</label>"
            )) ;
        }
        return "" . $table ;
    }

    public abstract function getDescription() ;
}
