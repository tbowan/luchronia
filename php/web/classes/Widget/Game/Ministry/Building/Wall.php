<?php

namespace Widget\Game\Ministry\Building ;

class Wall extends Base {
    
    public function getSpecific() {
        $wall = \Model\Game\Building\Wall::GetFromInstance($this->_instance) ;
        
        $res = "<h2>" . \I18n::WALL_MESSAGE() . "</h2>" ;
        $res .= $wall->message ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Defense/ChangeWallMessage?wall={$wall->id}", \I18n::CHANGE_WALL_MESSAGE()) ;

        
        
        return $res ;
    }
    
}
