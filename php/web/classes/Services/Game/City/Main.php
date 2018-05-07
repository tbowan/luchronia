<?php

namespace Services\Game\City ;

use Model\Game\City as MCity;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Character {

    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(MCity::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        
        if ($this->id == null) {
            $this->id = $this->getCharacter()->position ;
        }
        
        return new \Answer\View\Game\City($this, $this->id, $this->getCharacter()) ;

    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return $this->id->getName() ;
        }
    }
    
}
