<?php

namespace Answer\View\Help\Politic\Ministry ;

class ShowAll extends \Answer\View\Base {
    
    public function getTplContent() {
        
        $res  = \I18n::HELP_ALLMINISTRY_MESSAGE() ;
        
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Politic\Ministry::GetAll() as $m) {
            $res .= "<li class=\"card-1-2\"><div class=\"item\">"
                    . "<div class=\"icon\">" . $m->getImage("icone") . "</div>"
                    . "<div class=\"description\">"
                        . "<div class=\"name\">" . $m->getName() . "</div>"
                        . "<div class=\"links\">" . \I18n::HELP_MSG("/Help/Politic/Ministry?id={$m->id}") . "</div>"
                    . "</div>"
                    . "</div></li>";
        }
        $res .= "</ul>" ;

        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_LIST(), "", "", $res) ;
    }
    
}
