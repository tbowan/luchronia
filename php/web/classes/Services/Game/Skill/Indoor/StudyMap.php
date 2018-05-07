<?php

namespace Services\Game\Skill\Indoor ;

class StudyMap extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $architect = \Model\Game\Skill\Architect::GetFromSkill($this->cs->skill) ;
        $form->addInput("inv", new \Form\StudyMapChooser($this->getCharacter(), $architect->type, \I18n::BUILDING_MAP())) ;
    }
    
    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($points, $data) {
        
        $inv    = $data["inv"] ;
        $map    = \Model\Game\Building\Map::getFromItem($inv->item) ;
        $skill  = $map->skill ;
        
        $cs = \Model\Game\Skill\Character::LearnFromCharacterAndSkill($this->getCharacter(), $skill, $points) ;
        
        $msg = \I18n::STUDY_BUILDING_DONE($points, $cs->skill->getName(), $cs->learning) ;
        $msg .= parent::doStuff($points, $data) ;
        return $msg ;
    }

}

