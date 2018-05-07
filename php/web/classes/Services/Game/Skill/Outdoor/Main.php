<?php

namespace Services\Game\Skill\Outdoor ;

use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Model\Game\Skill\Character;

class Main extends \Services\Base\Character {
      
    public function fillParamForm(Form &$form) {
        $form->addInput("cs", new Id(Character::getBddTable())) ;
        $form->addInput("city", new Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        if (! $me->position->equals($this->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        }
    }
    
    public function getView() {
        $sc = $this->cs ;
        $skname = $sc->skill->classname ;
        return new Redirect("/Game/Skill/Outdoor/$skname?cs={$sc->id}&city={$this->city->id}") ;
    }
    
    
    
    
}
