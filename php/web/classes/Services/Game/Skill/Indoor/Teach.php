<?php

namespace Services\Game\Skill\Indoor ;

class Teach extends Base {
    
    public function getToolInput() {
        return new \Form\Tool\Teach($this->cs, $this->getComplete()) ;
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->cs->level ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        
        $teach = \Model\Game\Skill\Teach::GetFromSkill($this->cs->skill) ;
        
        $form->addMessage(\I18n::SKILL_TEACH_MESSAGE($teach->characteristic->getName())) ;
        
        $form->addInput("target", new \Form\Target($this->getCharacter(), false, \I18n::TARGET_STUDENT(), true)) ;
        $form->addInput("skill", new \Form\Skill($teach->characteristic, $this->getCharacter(), \I18n::SKILL())) ;
    }
    
    public function doStuff($points, $data) {
        
        $student    = $data["target"] ;
        $mine       = $data["skill"] ;
        $skill      = $mine->skill ;
        $his        = \Model\Game\Skill\Character::GetFromCharacterAndSkill($student, $skill) ;
        
        // Check teacher is better
        if ($his != null && $his->uses >= $mine->uses && $his->learning >= $mine->learning) {
            throw new \Exception(\I18n::EXP_TEACHER_MUSTBE_BETTERTHAN_STUDENT()) ;
        }
        
        $teach = \Model\Game\Skill\Teach::GetFromSkill($this->cs->skill) ;
        $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($teach->characteristic) ;
        $learn_skill = \Model\Game\Skill\Character::GetFromCharacterAndSkill($data["target"], $learn->skill) ;
        
        // Teach the skill
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($student, $skill, $points * $learn_skill->level) ;
        
        $res = \I18n::SKILL_TEACH_CONCLUSION(
                $points * $learn_skill->level,
                $student->id, $student->getName(),
                $skill->getName()) ;
        
        $res .= parent::doStuff($points, $data) ;
        return $res ;
    }
    
    
}
