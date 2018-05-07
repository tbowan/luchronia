<?php

namespace Answer\View\Game\System ;

class Parliamentary extends Base {
    
    public function getSpecific() {
        $res = "" ;
        $parliamentary = \Model\Game\Politic\Parliamentary::GetFromSystem($this->_system) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTER(),
            \I18n::START(),
        )) ;
        
        $parliament = \Model\Game\Politic\Parliament::getLastFromParliamentary($parliamentary) ;
        if ($parliament != null) {
            foreach (\Model\Game\Politic\Parliamentarian::GetFromParliament($parliament) as $parliamentarian) {
                $char = $parliamentarian->character ;
                $table->addRow(array(
                    new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()),
                    \I18n::_date_time($parliament->start - DT)
                )) ;
            }
            $res .= $table ;
        } else {
            $res .= \I18n::NO_PARLIAMENT() ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::PARLIAMENTARIANS(), "", "", $res) ;
        
    }
    
}
