<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(Form &$form) {
        $form->addInput("name",        new Text(\I18n::NAME())) ;
        $form->addInput("description", new FullHtml(\I18n::NAME())) ;
    }
    
    public function onProceed($data) {
        Group::createFromValues(array(
            "name" => $data["name"],
            "description" => $data["description"]
        )) ;
    }
    
}
