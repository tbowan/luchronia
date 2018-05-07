<?php

namespace Widget\Game\Ministry\System ;

class Parliamentary extends Base {
    
    public function getSpecific() {
        
        $parliamentary = \Model\Game\Politic\Parliamentary::GetFromSystem($this->_system) ;
        
        $res  = "<h3>" . ucfirst(\I18n::PARLIAMENTARY()) . " :</h3>" ;
        $res .= $this->_system->type->getDescription() ;
        
        $res .= \I18n::PARLIAMENTARY_SPECIFIC_MESSAGE(
                $parliamentary->duration,
                $parliamentary->seats,
                $parliamentary->parl_delay,
                $parliamentary->parl_type->getName(),
                $parliamentary->parl_point,
                $parliamentary->gov_delay,
                100 * $parliamentary->gov_quorum,
                100 * $parliamentary->gov_threshold,
                $parliamentary->sys_delay,
                100 * $parliamentary->sys_quorum,
                100 * $parliamentary->sys_threshold
                ) ;
        
        
        $res .= "<h3>" . \I18n::PARLIAMENTARIANS() . " :</h3>" ;
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
        
        
        return $res ;
        
    }
    
}
