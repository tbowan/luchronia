<?php

namespace Answer\View\Help\Skill ;

class Learn extends Base {
    
    public function getSpecific($class) {
        $s = $this->_skill ;
        $learn = \Model\Game\Skill\Learn::GetFromSkill($s) ;
        $cha = $learn->characteristic ;

        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_LEARN_TITLE(),
                "",
                "",
                \I18n::HELP_SKILL_LEARN($cha->id, $cha->getName()),
                $class
                );
    }
    
}
