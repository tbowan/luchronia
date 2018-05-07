<?php

namespace Services\BackOffice\Forum ;

use Model\Forum\Category;
use Model\I18n\Lang;
use Model\Identity\Group;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Select;


class AddCategory extends \Services\Base\Admin {
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title",          new Text(\I18n::TITLE(), true)) ;
        $form->addInput("lang",           new Select(Lang::getBddTable(),     \I18n::LANG(), true)) ;
        $form->addInput("parent",         new Select(Category::getBddTable(), \I18n::PARENT(), false)) ;
        $form->addInput("view_group" ,    new Select(Group::getBddTable(),    \I18n::VIEW_GROUP(), false)) ;
        $form->addInput("moderate_group", new Select(Group::getBddTable(),    \I18n::MODERATE_GROUP(), false)) ;
        $form->addInput("description",    new Text(\I18n::DESCRIPTION(), false)) ;
        $form->addInput("image",          new Text(\I18n::IMAGE(), false)) ;
    }
    
    public function onProceed($data) {
        Category::createFromValues(array(
            "title"          => $data["title"],
            "lang"           => $data["lang"],
            "description"    => $data["description"],
            "parent"         => $data["parent"],
            "view_group"     => $data["view_group"],
            "moderate_group" => $data["moderate_group"],
            "image"          => $data["image"]
        )) ;
    }
    
    
}