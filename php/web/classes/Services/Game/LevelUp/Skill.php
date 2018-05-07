<?php

namespace Services\Game\LevelUp ;

class Skill extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("cs", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Character::getBddTable())) ;
        $form->addInput("pt", new \Quantyl\Form\Fields\Integer()) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // enough points
        if ($this->getCharacter()->point < $this->pt) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_ENOUGH_LEVEL_POINT()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::INCREASE_SKILL_MESSAGE(
                $this->cs->skill->getImage("icone") . " " . $this->cs->skill->getName(),
                \I18n::HELP("/Help/Skill?id={$this->cs->skill->id}"),
                $this->cs->level, $this->cs->learning,
                $this->cs->level + 1, $this->cs->learning + 100 * $this->pt,
                $this->pt,
                $this->getCharacter()->point
                ) ) ;
    }
    
    public function onProceed($data) {
        $char = $this->getCharacter() ;
        
        $this->cs->learn($this->pt * 100) ;
        
        $char->point -= $this->pt ;
        $char->update() ;
    }
    
    
}

