<?php

namespace Answer\Widget\Game\Notifications ;

class NearDeath extends Base {
    
    public function __construct(\Model\Game\Character $char) {
        
        if ($char->position->hasTownHall()){
            $msg =  \I18n::HINT_NEAR_DATH_HAS_TOWNHALL();
        } else {
            $msg =  \I18n::HINT_NEAR_DATH_HASNOT_TOWNHALL();
        }
        
        parent::__construct(
                \I18n::NEAR_DEATH(),
                $msg,
                "critical");
    }
    
}
