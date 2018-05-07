<?php

namespace Services\Game\Ministry\Building\Forum ;

class AddModerator extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("category", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Category::getBddTable())) ;
    }
    
    public function getCity() {
        return $this->category->instance->city ;
    }

    public function getMinistry() {
        return $this->category->instance->job->ministry ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FORUM_ADDMODERATOR_MESSAGE(
                $this->category->getName()
                )) ;
        
        $form->addInput("moderator", new \Form\Character(\I18n::MODERATOR())) ;
    }
    
    public function onProceed($data) {
        \Model\Game\Forum\Moderator::createFromValues(array(
            "category" => $this->category,
            "moderator" => $data["moderator"]
        )) ;
    }

}
