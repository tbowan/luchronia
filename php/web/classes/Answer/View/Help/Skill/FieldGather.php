<?php

namespace Answer\View\Help\Skill ;

class FieldGather extends Base {
    
    public function getSpecific($class) {
        
        $p = \Model\Game\Skill\Field::GetFromSkill($this->_skill) ;
        
        $res = \I18n::HELP_SKILL_FIELD_MESSAGE() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::SKILL_IN() . " : </strong> " .
                $p->item->getImage("icone") . " "
                . $p->item->getName() . " "
                . \I18n::HELP("/Help/Item?id={$p->item->id}")
                . "</li>" ;
        $res .= "<li><strong>" . \I18n::SKILL_OUT() . " : </strong> "
                . $p->gain->getImage("icone") . " "
                . $p->gain->getName() . " "
                . \I18n::HELP("/Help/Item?id={$p->gain->id}")
                . "</li>" ;
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_FIELD(),
                "",
                "",
                $res,
                $class
                );
    }
}