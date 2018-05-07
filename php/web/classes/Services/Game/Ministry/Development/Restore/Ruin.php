<?php

namespace Services\Game\Ministry\Development\Restore ;

class Ruin extends Base {
    
    public function doSpeficicStuff(\Model\Game\Building\Instance $i, $data) {
    }

    public function getSpecificFieldSet() {
        
    }

    public function getSpecificMessage() {
        $res = "<h2>" . \I18n::RESTORE_RUIN() . "</h2>" ;
        
        $ruin = \Model\Game\Building\Ruin::GetFromInstance($this->instance) ;
        $res .= \I18n::RESTORE_RUIN_MESSAGE($ruin->job->getName()) ;
        
        return $res ;
        
    }
    
    public function getSiteHealth(\Model\Game\Building\Instance $i) {
        return $i->getMaxHealth() / 10 ;
    }
    
    public function makeSite() {
        $ruin = \Model\Game\Building\Ruin::GetFromInstance($this->instance) ;
        $site = \Model\Game\Building\Site::createFromValues(array(
            "instance"      => $this->instance,
            "job"           => $ruin->job,
            "last_update"   => time()
        )) ;
        $ruin->delete() ;
        
        return $site ;
    }

}
