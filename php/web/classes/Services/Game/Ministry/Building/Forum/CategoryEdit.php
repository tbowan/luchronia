<?php

namespace Services\Game\Ministry\Building\Forum ;

class CategoryEdit extends \Services\Base\Minister {
    
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
        $form->addInput("title",        new \Quantyl\Form\Fields\Text(\I18n::TITLE()))
             ->setValue($this->category->title);
        $form->addInput("description",  new \Quantyl\Form\Fields\Text(\I18n::DESCRIPTION()))
             ->setValue($this->category->description);
        $form->addInput("rw",           new \Quantyl\Form\Model\EnumSimple(\Model\Game\Forum\Access::getBddTable(), \I18n::ACCESS(), true))
             ->setValue($this->category->rw);
    }
    
    public function onProceed($data) {
        $this->category->title          = $data["title"] ;
        $this->category->description    = $data["description"] ;
        $this->category->rw             = $data["rw"] ;
        $this->category->update() ;
    }

}
