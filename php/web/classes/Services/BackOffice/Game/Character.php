<?php

namespace Services\BackOffice\Game ;

use Model\Game\Character as MCharacter ;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\BackOffice\Game\CharacterDetail;

class Character extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(MCharacter::getBddTable())) ;
    }
    
    public function getView() {
        return new CharacterDetail($this->id) ;
    }
    
    public function getTitle() {
        if ($this->id === null) {
            return parent::getTitle();
        }else {
            return $this->id->getName() ;
        }
    }
    
    
}
