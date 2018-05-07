<?php

namespace Answer\Widget\Help\Biome;

class Ressources extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Biome $b, $classes = "") {
        
        $res = \I18n::HELP_BIOME_RESSOURCES_MESSAGE() ;
        
        $res .= "<ul class=\"itemList\">" ;
        
        foreach (\Model\Game\Ressource\Ecosystem::GetFromBiome($b) as $eco) {
            $res .= "<li class=\"card-1-2\"><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $eco->item->getImage() . "</div>" ;
            $res .= "<div class=\"description\">" ;
                $res .= "<div class=\"name\">" . $eco->item->getName() . " (" . \I18n::HELP_MSG("/Help/Item?id={$eco->item->id}") . ")</div>" ;
                $res .= "<div>" . \I18n::AMOUNT() . " : " . $eco->min . " - " . $eco->max . "</div>" ;
            $res .= "</div>" ;
            $res .= "</div></li>" ;
        }
        
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::RESSOURCES(), "", "", $res, $classes);
    }

}
