<?php

namespace Answer\View\Help\Skill ;

class Secondary extends Base {
    
    public function getSpecific($class) {
        
        $sk = $this->_skill ;
        
        $res = \I18n::HELP_SKILL_SECONDARY_MESSAGE() ;
        
        $res .= "<ul><strong>" . \I18n::SKILL_IN() . " : </strong> " ;
                       
        foreach (\Model\Game\Skill\In::GetFromSkill($sk) as $in) {
            $res .= "<li>". number_format($in->amount, 2) . " " . $in->item->getImage("icone") . " " . $in->item->getName() . \I18n::HELP("/Help/Item?id={$in->item->id}") . "</li>";
        }
        $res .= "</ul>";
        
        $res .= "<ul><strong>" . \I18n::SKILL_OUT() . " : </strong> " ;
                       
        foreach (\Model\Game\Skill\Out::GetFromSkill($sk) as $out) {
            $res .= "<li>".number_format($out->amount, 2) . " " . $out->item->getImage("icone") . " " . $out->item->getName() . \I18n::HELP("/Help/Item?id={$out->item->id}") . "</li>";
        }
        $res .= "</ul>";   
        
        return new \Answer\Widget\Misc\Section(
                \I18n::HELP_SKILL_SECONDARY(),
                "",
                "",
                $res,
                $class);
        
    }

}
