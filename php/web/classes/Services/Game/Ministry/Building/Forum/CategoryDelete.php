<?php

namespace Services\Game\Ministry\Building\Forum ;

class CategoryDelete extends \Services\Base\Minister {
    
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
        $form->addMessage(\I18n::FORUM_CATEGORY_DELETE_MESSAGE(
                $this->category->getName(),
                \Model\Game\Forum\Thread::CountFromCategory($this->category)
                )) ;
    }
    
    public function onProceed($data) {
        $this->category->delete() ;
    }

}
