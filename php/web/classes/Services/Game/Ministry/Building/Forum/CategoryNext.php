<?php

namespace Services\Game\Ministry\Building\Forum ;

class CategoryNext extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("category", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Category::getBddTable())) ;
    }
    
        
    public function getCity() {
        return $this->category->instance->city ;
    }

    public function getMinistry() {
        return $this->category->instance->job->ministry ;
    }
    
    
    public function getView() {
        $this->category->goNext() ;
    }

}
