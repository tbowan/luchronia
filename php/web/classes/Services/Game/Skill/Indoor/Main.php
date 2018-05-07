<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Building\Instance;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Model\Game\Skill\Character;

class Main extends \Services\Base\Character {
      
    public function fillParamForm(Form &$form) {
        $form->addInput("cs", new Id(Character::getBddTable())) ;
        $form->addInput("inst", new Id(Instance::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        $sc = $this->cs ;
        $skname = $sc->skill->classname ;
        $b = ($this->inst == null ? "" : $this->inst->id) ;
        return new Redirect("/Game/Skill/Indoor/$skname?cs={$sc->id}&inst={$b}") ;
    }
    
}
