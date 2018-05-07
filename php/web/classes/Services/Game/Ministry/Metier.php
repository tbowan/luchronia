<?php

namespace Services\Game\Ministry ;

class Metier extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("metier", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Metier::getBddTable())) ;
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return $this->metier->ministry ;
    }
    
    public function getView() {
        
        
        $classpath = "\\Widget\\Game\\Ministry\\Metier\\" ;
        $classname = $classpath . $this->metier->name ;
        $defaut    = $classpath . "Base" ;
        
        if (class_exists($classname)) {
            return new $classname($this->city, $this->metier, $this->getCharacter()) ;
        } else {
            return new $defaut($this->city, $this->metier, $this->getCharacter()) ;
        }
        
    }
    
    public function getTitle() {
        return $this->metier->getName() ;
    }

}
