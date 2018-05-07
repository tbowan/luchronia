<?php

namespace Services\Game\Forum ;

class Follow extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("thread", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Thread::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FOLLOW_THREAD(
                $this->thread->title)) ;
    }
    
    public function onProceed($data) {
        \Model\Game\Forum\Follow::createFromValues(array(
            "thread" => $this->thread,
            "character" => $this->getCharacter(),
            "last_post" => \Model\Game\Forum\Post::LastFromThread($this->thread)
        )) ;
    }
    
}
