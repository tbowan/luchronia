<?php

namespace Services\Game\Skill\Indoor ;

class StudyBuilding extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }
    
    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition);
    }
    
    public function doStuff($points, $data) {
        
        $instance   = $this->inst ;
        $map        = $instance->getMap() ;
        $skill      = $map->skill ;
        
        $cs = \Model\Game\Skill\Character::LearnFromCharacterAndSkill($this->getCharacter(), $skill, $points) ;
        
        $msg = \I18n::STUDY_BUILDING_DONE($points, $cs->skill->getName(), $cs->learning) ;
        $msg .= parent::doStuff($points, $data) ;
        return $msg ;
    }

}

