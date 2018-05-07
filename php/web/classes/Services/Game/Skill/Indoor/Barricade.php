<?php

namespace Services\Game\Skill\Indoor ;

class Barricade extends Base {
    
    public function getToolInput() {
        return new \Form\Tool\Barricade($this->cs, $this->getComplete()) ;
    }
    
    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($points, $data) {
        
        $given = min($points, $this->inst->health - $this->inst->barricade) ;
        
        $this->inst->barricade += $given ;
        $this->inst->update() ;
        
        if ($given != $points) {
            $msg = \I18n::BARRICADE_SKILL_ALMOST($given, $points, $this->inst->barricade) ;
        } else {
            $msg = \I18n::BARRICADE_SKILL_DONE($points, $this->inst->barricade) ;
        }
        $msg .= parent::doStuff($points, $data) ;
        return $msg ;
        
    }

}
