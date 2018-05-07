<?php

namespace Widget\Game\Ministry\Government ;

class Log extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $c) {
        $this->_city = $c ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::PAST_GOVERNMENTS() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SYSTEM(),
            \I18n::START_DATE(),
            \I18n::END_DATE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Politic\Government::getLog($this->_city) as $g) {
            $system = $g->system ;
            $name = ($system->name == "" ? $system->type->getName() : $system->name) ;
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Ministry/System?system={$g->system->id}&city={$this->_city->id}", $name),
                \I18n::_date_time($g->start - DT),
                ($g->end == null ? "-" : \I18n::_date_time($g->end - DT)),
                new \Quantyl\XML\Html\A("/Game/Ministry/Government/Show?government={$g->id}", \I18n::SEE())
            )) ;
        }
        $res .= $table ;
        
        return $res ;
        
        
    }
}
