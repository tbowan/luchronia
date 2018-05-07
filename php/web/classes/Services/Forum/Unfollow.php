<?php

namespace Services\Forum ;

class Unfollow extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("follow", new \Quantyl\Form\Model\Id(\Model\Forum\Follow::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::UNFOLLOW_THREAD(
                $this->follow->thread->title)) ;
    }
    
    public function onProceed($data) {
        $this->follow->delete() ;
    }
    
}
