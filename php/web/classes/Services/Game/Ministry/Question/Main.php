<?php

namespace Services\Game\Ministry\Question ;

class Main extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("question", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Question::getBddTable())) ;
    }

    public function getCity() {
        return $this->question->system->city ;
    }
    
    public function getView() {
        
        $classname = "\\Widget\\Game\\Ministry\\Question\\" . $this->question->type->getValue() ;
        return new $classname($this->question, $this->getCharacter()) ;
        
    }

}
