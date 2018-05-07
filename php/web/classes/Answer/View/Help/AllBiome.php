<?php

namespace Answer\View\Help ;

class AllBiome extends \Answer\View\Base {
    
    public function getTplContent() {
        return ""
                . $this->getAllBiome("card-1-5") ;
    }
    
    public function getAllBiome($class_li = "", $classes_section = "") {
        
        $res = "" ;
        $res .= \I18n::HELP_ALLBIOME_MESSAGE() ;
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Biome::GetAll() as $b) {
            $res .= "<li class=\"$class_li\"><div class=\"item\">"
                    . "<div class=\"icon\">" . $b->getImage() . "</div>"
                    . "<div class=\"description\">"
                        . "<div class=\"name\">" . ucfirst($b->getName()) . "</div>"
                        . "<div class=\"help\">" . \I18n::HELP_MSG("/Help/Biome?id={$b->id}") . "</div>"
                    . "</div>"
                    . "</div></li>" ;
        }
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::BIOME_LIST(), "", "", $res, $classes_section) ;
    }
    
}
