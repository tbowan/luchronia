<?php

namespace Answer\View\Help\Skill ;

class Build extends Base {
    
    public function getSpecific($class) {
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_BUILD(),
                "",
                "",
                \I18n::HELP_SKILL_BUILD_MESSAGE(),
                $class
                );
    }
}
