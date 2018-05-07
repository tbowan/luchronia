<?php

namespace Services\Game\Skill\Outdoor ;

class ProspectArcheo extends Base {
    
    public function getTime($tool) {
        return round(parent::getTime($tool) / $this->cs->level) ;
    }
    
    public function doStuff($amount, $data) {
        
        $losts = \Model\Game\Building\Instance::GetLostRuins($this->getCharacter()->position) ;
        
        $most = null ;
        $cnt = 0 ;
        
        foreach ($losts as $inst) {
            $cnt ++ ;
            if ($most === null || $most < $inst->health) {
                $most = $inst->health ;
            }
        }
        
        $msg = "" ;
        $msg .= \I18n::PROSPECTION_ARCHEO_DONE($cnt, $most) ;
        $msg .= parent::doStuff($amount, $data) ;
        
        return $msg ;
    }

    public function getToolInput() {
        return new \Form\Tool\Secondary($this->cs, $this->getComplete()) ;
    }

}
