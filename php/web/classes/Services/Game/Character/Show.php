<?php

namespace Services\Game\Character ;

use Model\Game\Character;
use Model\Game\Social\Post;
use Quantyl\Answer\ListAnswer;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\Game\Character\Friendship;
use Widget\Game\Character\PostList;

class Show extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Character::getBddTable())) ;
    }
    
    public function getView() {
        return new \Answer\View\Game\Character($this, $this->id, $this->getCharacter()) ;
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return $this->id->getName() ;
        }
    }
    
}
