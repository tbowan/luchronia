<?php

namespace Widget\Game\Ministry\Government ;

class ProjectGovernments extends \Quantyl\Answer\Widget {
    
    private $_character ;
    
    public function __construct(\Model\Game\Character $character) {
        $this->_character = $character ;
    }
    
    public function getContent() {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::START_DATE(),
            \I18n::END_DATE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Politic\Government::GetMine($this->_character) as $g) {
            $act = "" ;
            if ($g->isProject()) {
                $act .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Show?government={$g->id}", \I18n::EDIT()) ;
                $act .= " " ;
                $act .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Delete?government={$g->id}", \I18n::DELETE()) ;
            }
            
            $table->addRow(array(
                ($g->start == null ? "-" : \I18n::_date_time($g->start - DT)),
                ($g->end == null ? "-" : \I18n::_date_time($g->end - DT)),
                $act
            )) ;
        }
        $res .= $table ;
        
        return $res ;
        
        
    }
}
