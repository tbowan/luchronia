<?php

namespace Answer\View\Game\Building ;

class Wall extends Base {
    
    public function getSpecific() {
        $wall = \Model\Game\Building\Wall::GetFromInstance($this->_instance) ;
        return new \Answer\Widget\Misc\Section(
                \I18n::CITYWALL(),
                "",
                "<strong>" . \I18n::DOOR_WALL() . " :</strong> " . $wall->door->getName(),
                ($wall->message == "" ? \I18n::NO_WALL_MESSAGE() : $wall->message),
                ""
                ) ;

    }
    
}
