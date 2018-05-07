<?php

namespace Services\Game\LevelUp ;

class NewSkill extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("skill", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Skill::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->getCharacter(), $this->skill) ;
        if ($cs != null && $cs->level > 0) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_ALREADY_KNOWN_SKILL($this->skill->getName())) ;
        }
        
        if ($this->skill->cost > $this->getCharacter()->point) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_ENOUGH_LEVEL_POINT($this->skill->getName(), $this->skill->cost)) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $s = $this->skill ;
        $c = $s->cost ;
        $h = $this->getCharacter()->point ;
        
        $form->setMessage(\I18n::NEW_SKILL_MESSAGE($s->getName(), $c, $h)) ;
    }
    
    public function onProceed($data) {
        $char = $this->getCharacter() ;
        $char->point -= $this->skill->cost ;
        $char->update() ;
        
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($this->getCharacter(), $this->skill, 100) ;
    }
    
    
    
    
}
