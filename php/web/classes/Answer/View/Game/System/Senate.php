<?php

namespace Answer\View\Game\System ;

class Senate extends Base {
    
    public function getSpecific() {
        $res = "" ;
        $senate = \Model\Game\Politic\Senate::GetFromSystem($this->_system) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTER(),
            \I18n::START(),
            \I18n::SUPPORT(),
            \I18n::ACTIONS()
        )) ;
        
        $senators = max(1, \Model\Game\Politic\Senator::CountFromSenate($senate) - 1) ;
        
        foreach (\Model\Game\Politic\Senator::GetActive($senate) as $senator) {
            if ($this->_canmanage) {
                $act = ""
                        . new \Quantyl\XML\Html\A("/Game/Ministry/System/Senate/Support?senator={$senator->id}", \I18n::MOVEBEFORE())
                        . " "
                        . new \Quantyl\XML\Html\A("/Game/Ministry/System/Senate/Oppose?senator={$senator->id}", \I18n::MOVEAFTER())
                        ;
            } else {
                $act = "" ;
            }
            
            $char = $senator->character ;
            $supports = \Model\Game\Politic\Friend::CountSupport($senator) ;
            $opponents = \Model\Game\Politic\Friend::CountOpponent($senator) ;
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()),
                \I18n::_date_time($senator->start - DT),
                number_format(100 * $supports / $senators) . " % / " . 
                number_format(100 * $opponents / $senators) . " %",
                $act
            )) ;
        }
        $res .= $table ;
        
        if ($this->_canmanage) {
            $res .= \I18n::SENATE_COOPT($this->_system->id) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::SENATORS(), "", "", $res, "") ;
        
    }
    
}
