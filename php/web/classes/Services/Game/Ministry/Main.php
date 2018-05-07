<?php

namespace Services\Game\Ministry ;

use Model\Game\Politic\Ministry;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Model\Game\City;

class Main extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city" ,        new Id(City::getBddTable())) ;
        $form->addInput("ministry" ,    new \Quantyl\Form\Model\Name(Ministry::getBddTable())) ;
    }

    public function getView() {
        
        $classpath = "\\Answer\\View\\Game\\Ministry\\" ;
        $classname = $classpath . $this->ministry->name ;
        $defaut    = $classpath . "Base" ;
        
        if (class_exists($classname)) {
            return new $classname($this, $this->city, $this->ministry, $this->getCharacter()) ;
        } else {
            return new $defaut($this, $this->city, $this->ministry, $this->getCharacter()) ;
        }
        
    }
    
    public function getTitle() {
        return $this->ministry->getName() ;
    }
        
}
