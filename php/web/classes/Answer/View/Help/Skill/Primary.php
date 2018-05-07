<?php

namespace Answer\View\Help\Skill ;

class Primary extends Base {
    
    
    public function getSpecific($class) {
        $p = \Model\Game\Skill\Primary::GetFromSkill($this->_skill) ;
        
        $res = \I18n::HELP_SKILL_PRIMARY_MESSAGE() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::SKILL_IN() . " : </strong> " .
                $p->in->getImage("icone") . " "
                . $p->in->getName() . " "
                . \I18n::HELP("/Help/Item?id={$p->in->id}")
                . "</li>" ;
        $res .= "<li><strong>" . \I18n::SKILL_OUT() . " : </strong> "
                . $p->coef . " "
                . $p->out->getImage("icone") . " "
                . $p->out->getName() . " "
                . \I18n::HELP("/Help/Item?id={$p->out->id}")
                . "</li>" ;
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_PRIMARY(),
                "",
                "",
                $res,
                $class
                );
    }

}
