<?php

namespace Services\Game\Skill\Indoor ;

class Heal extends Base {
    
    public function getToolInput() {
        return new \Form\Tool\Heal($this->cs, $this->getComplete()) ;
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->cs->level ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        
        $form->addMessage(\I18n::SKILL_HEAL_MESSAGE()) ;
        
        $heal = \Model\Game\Skill\Heal::GetFromSkill($this->cs->skill) ;
        $form->addInput("target", new \Form\Healable($this->getCharacter(), $heal->race, false, \I18n::TARGET_STUDENT(), true)) ;
    }
    
    public function doStuff($points, $data) {
        
        $target    = $data["target"] ;
        
        // give health
        $before = $target->health ;
        $target->heal(100 * $points) ;
        $target->update() ;
        $after = $target->health ;
        
        $res = \I18n::SKILL_HEAL_CONCLUSION(
                $after - $before,
                $target->id, $target->getName()
                ) ;
        
        $res .= parent::doStuff($points, $data) ;
        return $res ;
    }
    
    
}
