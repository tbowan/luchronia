<?php

namespace Answer\View\Help\Skill ;

class Teach extends Base {
    
    public function getSpecific($class) {
        $s = $this->_skill ;
        $learn = \Model\Game\Skill\Teach::GetFromSkill($s) ;
        $cha = $learn->characteristic ;

        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_TEACH_TITLE(),
                "",
                "",
                \I18n::HELP_SKILL_TEACH($cha->id, $cha->getName()),
                $class
                );
    }
    
}
