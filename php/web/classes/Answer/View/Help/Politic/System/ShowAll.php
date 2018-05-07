<?php

namespace Answer\View\Help\Politic\System ;

class ShowAll extends \Answer\View\Base {
    
    public function getTplContent() {
        return ""
                . $this->getAllSystems()
                ;
    }
    
    public function getAllSystems() {
        
        $res  = \I18n::HELP_ALLSYSTEM_MESSAGE() ;
        
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Politic\SystemType::GetAll() as $s) {
            $res .= "<li class=\"card-1-4\"><div class=\"item\">"
                    . "<div class=\"icon\">" . $s->getImage("icone") . "</div>"
                    . "<div class=\"description\">"
                        . "<div class=\"name\">" . $s->getName() . "</div>"
                        . "<div class=\"links\">" . \I18n::HELP_MSG("/Help/Politic/System?id={$s->getId()}") . "</div>"
                    . "</div>"
                    . "</div></li>";
        }
        $res .= "</ul>" ;

        return new \Answer\Widget\Misc\Section(\I18n::SYSTEM_LIST(), "", "", $res) ;
    }
    
}
