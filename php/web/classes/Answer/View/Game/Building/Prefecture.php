<?php

namespace Answer\View\Game\Building ;

class Prefecture extends Base {
    
    public function getSpecific() {
        
        $pref = \Model\Game\Building\Prefecture::GetFromInstance($this->_instance) ;
        
        return ""
                . new \Answer\Widget\Game\Building\Prefecture\Cities($pref)
                . new \Answer\Widget\Game\Building\Prefecture\Sites($pref)
                ;

        
    }
    
}
