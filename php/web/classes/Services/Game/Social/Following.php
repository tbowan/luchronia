<?php

namespace Services\Game\Social ;

class Following extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Social\LongFollowing($this->id) ;
    }
    
}
