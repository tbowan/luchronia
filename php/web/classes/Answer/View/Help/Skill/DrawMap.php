<?php

namespace Answer\View\Help\Skill ;

class DrawMap extends Base {
    
    public function getSpecific($class) {
        
        $res = \I18n::HELP_SKILL_DRAWMAP_MESSAGE() ;
        
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Building\Map::getFromSkill($this->_skill) as $m) {
            $res .= "<li><div class=\"item\">"
                    . "<div class=\"icon\">" . $m->item->getImage() . "</div>"
                    . "<div class=\"description\">"
                        . "<div class=\"name\">" . \I18n::BUILDING_MAP() . "</div>"
                        . "<div>" . $m->job->getName() . " - " . $m->type->getName() . " - " . \I18n::LEVEL() . " " . $m->level . "</div>"
                        . "<div>" . \I18n::BUILDING_TECH() . " : " . $m->tech . "</div>"
                        . "<div class=\"links\">" . \I18n::HELP_MSG("/Help/Item?id={$m->item->id}") . "</div>"
                    . "</div>"
                    . "</div></li>" ;
        }
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_DRAWMAP(),
                "",
                "",
                $res,
                $class
                );

    }

}
