<?php

namespace Services\BackOffice\Game ;

class Control extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("character", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
    }
    
    public function getView() {
        
        if ($this->character->user->equals($this->getUser())) {
            $this->character->previous = $this->character->last ;
            $this->character->last = time() ;
            $this->character->update() ;
        }
        
        $_SESSION["char"] = $this->character ;
    }
    
}
