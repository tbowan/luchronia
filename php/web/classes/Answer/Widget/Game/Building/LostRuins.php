<?php

namespace Answer\Widget\Game\Building ;

class LostRuins extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $city, $classes = "") {
        $res = "" ;
        $res .= \I18n::LOST_RUINS_MESSAGE() ;
        
        $res .= "<ul class=\"itemList\">" ;
        
        $losts = \Model\Game\Building\Instance::GetLostRuins($city) ;
        
        foreach ($losts as $l) {
            
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $l->getImage() . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">" . $l->getName() . "</div>"
                    . "<div class=\"level\">" . \I18n::LEVEL() . " : " . $l->level . "</div>"
                    . "<div class=\"health\">" . \I18n::HEALTH() . " : " . $l->health. "</div>"
                    . "</div>" ;

            $res .= "</div></li>" ;
            
        }
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::LOST_RUINS(), "", "", $res, $classes);
    }
    
}
