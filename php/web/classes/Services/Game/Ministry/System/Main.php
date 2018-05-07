<?php

namespace Services\Game\Ministry\System ;

class Main extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("system", new \Quantyl\Form\Model\Id(\Model\Game\Politic\System::getBddTable(), "")) ;
    }

    public function getView() {
        $name     = $this->system->type->getValue() ;
        $rfclass  = new \ReflectionClass("\\Answer\\View\\Game\\System\\$name") ;
        $instance = $rfclass->newInstance($this, $this->system, $this->getCharacter()) ;
        return $instance ;
    }

    
}
