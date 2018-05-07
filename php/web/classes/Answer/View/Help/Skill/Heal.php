<?php

namespace Answer\View\Help\Skill ;

class Heal extends Base {
    
    public function getSpecific($class) {
        $s = $this->_skill ;
        $heal = \Model\Game\Skill\Heal::GetFromSkill($s) ;
        
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_HEAL_TITLE(),
                "",
                "",
                \I18n::HELP_SKILL_HEAL($heal->race->getName()),
                $class);
    }
    
}
