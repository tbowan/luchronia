<?php

namespace Services\User\Quanta ;

class Level extends \Services\Base\Character {
    
    private $_cost ;
    
    public function init() {
        parent::init();
        
        $this->_cost = \Model\Quantyl\Config::ValueFromKey("QUANTA_BUY_LEVEL_POINT") ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $user = $this->getUser() ;
        
        $form->addMessage(\I18n::QUANTA_BUY_LEVEL_MESSAGE($user->quanta, $this->_cost, floor($user->quanta / $this->_cost))) ;
        $form->addInput("character", new \Form\MyCharacter($user, \I18n::CHARACTER(), true)) ;
        $form->addInput("amount", new \Quantyl\Form\Fields\Integer(\I18n::AMOUNT(), true)) ;
    }

    public function onProceed($data) {
        
        $user = $this->getUser() ;
        $char = $data["character"] ;
        
        // Check quantas
        $cost = $data["amount"] * $this->_cost ;
        
        if ($user->quanta < $cost) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_QUANTA($cost, $user->quanta)) ;
        }
        
        $user->quanta -= $cost ;
        $user->update() ;
        
        $char->point += $data["amount"] ;
        $char->update() ;
    }
}
