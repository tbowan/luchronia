<?php

namespace Answer\Widget\Help\Characteristic;

class Skills extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Characteristic $c, $viewer, $classes = "") {    
            
        $res = "<ul class=\"itemList\">" ;
        $has = false ;
        foreach (\Model\Game\Skill\Skill::getFromCharacteristic($c) as $sk) {
            $has = true ;
            $res .= new \Answer\Widget\Help\Skill\SkillAsItem($sk, $viewer, "card-1-4", false) ;
        }
        $res .= "</ul>" ;
        
        if (! $has) {
            $res = \I18n::CHARACTERISTICS_NO_SKILLS() ;
        }
        
        parent::__construct(\I18n::SKILL_LIST(), "", "", $res, $classes);
    }

}
