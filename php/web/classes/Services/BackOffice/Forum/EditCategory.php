<?php

namespace Services\BackOffice\Forum ;

use Model\Forum\Category;
use Model\Identity\Group;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class EditCategory extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Category::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title",          new Text(\I18n::TITLE(), true))
             ->setValue($this->id->title);
        $form->addInput("view_group" ,    new Select(Group::getBddTable(),    \I18n::VIEW_GROUP(), false))
             ->setValue($this->id->view_group);
        $form->addInput("moderate_group", new Select(Group::getBddTable(),    \I18n::MODERATE_GROUP(), false))
             ->setValue($this->id->moderate_group);
        $form->addInput("description",    new Text(\I18n::DESCRIPTION(), false))
             ->setValue($this->id->description);
        $form->addInput("image",          new Text(\I18n::IMAGE(), false))
             ->setValue($this->id->image) ;
    }
    
    public function onProceed($data) {
        
        $this->id->title          = $data["title"] ;
        $this->id->description    = $data["description"] ;
        $this->id->view_group     = $data["view_group"] ;
        $this->id->moderate_group = $data["moderate_group"] ;
        $this->id->image          = $data["image"] ;
        $this->id->update() ;
    }
    
}
