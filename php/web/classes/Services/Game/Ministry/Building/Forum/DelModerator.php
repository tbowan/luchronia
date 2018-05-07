<?php

namespace Services\Game\Ministry\Building\Forum ;

class DelModerator extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("moderator", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Moderator::getBddTable())) ;
    }
    
    public function getCity() {
        return $this->moderator->category->instance->city ;
    }

    public function getMinistry() {
        return $this->moderator->category->instance->job->ministry ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FORUM_DELMODERATOR_MESSAGE(
                $this->moderator->moderator->id,
                $this->moderator->moderator->getName(),
                $this->moderator->category->id,
                $this->moderator->category->getName()
                )) ;
    }
    
    public function onProceed($data) {
        $this->moderator->delete() ;
    }

}
