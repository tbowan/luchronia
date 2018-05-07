<?php

namespace Services\BackOffice\Blog ;

use Model\Blog\Category;
use Model\Identity\Group;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class EditCategory extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("category", new Id(Category::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("name",  new Text(\I18n::TITLE(), true))
             ->setValue($this->category->name);
        $form->addInput("description",  new Text(\I18n::DESCRIPTION(), true))
             ->setValue($this->category->description);
        $form->addInput("image",        new Text(\I18n::IMAGE(), true))
             ->setValue($this->category->image);
        $form->addInput("group", new Select(Group::getBddTable(), \I18n::TITLE(), true))
             ->setValue($this->category->group);
    }
    
    public function onProceed($data) {
        $this->category->name           = $data["name"] ;
        $this->category->group          = $data["group"] ;
        $this->category->description    = $data["description"] ;
        $this->category->image          = $data["image"] ;
        $this->category->update() ;
    }

}
