<?php

namespace Answer\View\Help\Skill ;

class Move extends Base {
    
    public function getSpecific($class) {
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_MOVE(),
                "",
                "",
                \I18n::HELP_SKILL_MOVE_MESSAGE(),
                $class
                );
    }
}