<?php

namespace Services\BackOffice\Blog ;

use I18n;
use Model\Blog\Category;
use Model\I18n\Lang;
use Model\Identity\Group;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Select;

class AddCategory extends \Services\Base\Admin {
    
    public function fillDataForm(Form &$form) {
        $form->addInput("lang",         new Select(Lang::getBddTable(), I18n::LANG(), true)) ;
        $form->addInput("name",         new Text(I18n::TITLE(), true)) ;
        $form->addInput("description",  new Text(I18n::DESCRIPTION(), true)) ;
        $form->addInput("image",        new Text(I18n::IMAGE(), true)) ;
        $form->addInput("group",        new Select(Group::getBddTable(), I18n::GROUP(), true)) ;
    }
    
    public function OnProceed($data) {
        
        $cat = Category::createFromValues(array(
            "name"          => $data["name"],
            "lang"          => $data["lang"],
            "group"         => $data["group"],
            "description"   => $data["description"],
            "image"         => $data["image"]
        )) ;
    }

}
