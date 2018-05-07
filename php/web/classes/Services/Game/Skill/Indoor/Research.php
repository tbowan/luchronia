<?php

namespace Services\Game\Skill\Indoor ;

class Research extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }
    
    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($points, $data) {
        
        $architect = \Model\Game\Skill\Architect::GetFromSkill($this->cs->skill) ;
        
        $research = \Model\Game\Skill\Research::doResearch($this->getCharacter(), $architect->type, $points) ;
        
        $msg = \I18n::RESEARCH_DONE($points, $architect->type->getName(), $research->amount) ;
        $msg .= parent::doStuff($points, $data) ;
        return $msg ;
    }

}
