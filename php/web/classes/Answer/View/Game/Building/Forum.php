<?php

namespace Answer\View\Game\Building ;

class Forum extends Base {

    public function getSpecific() {
        
        $me = $this->getCharacter() ;
        
        return new \Answer\Widget\Misc\Section(\I18n::FORUM_TITLE_CATEGORIES(), "", "", new \Answer\Widget\Game\Forum\Root($this->_instance, $me)) ;
        
    }
    

    
    
}
