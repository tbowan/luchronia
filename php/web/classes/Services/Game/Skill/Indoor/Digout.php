<?php

namespace Services\Game\Skill\Indoor ;

class Digout extends Base {
    
    public function getAmount($munition) {
        return $this->cs->level * $this->inst->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($points, $data) {
        
        
        $msg = "" ;
        $msg .= \I18n::SKILL_DIGOUT_MESSAGE($points) ;
        
        foreach (\Model\Game\Building\Instance::GetLostRuins($this->inst->city) as $lost) {
            
            $lost->health += $points ;
            if ($lost->health > 0) {
                $msg .= \I18n::SKILL_DIGOUT_RUIN_AVAILABLE($lost->id, $lost->getName()) ;
                // New ruin available
            }
            $lost->update() ;
            
        }
        $msg .= parent::doStuff($points, $data) ;
        
        return $msg ;
    }
    
}
