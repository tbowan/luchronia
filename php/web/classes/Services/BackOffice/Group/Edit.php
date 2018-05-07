<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Group::getBddTable(), "", true)) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("name", new Text(\I18n::NAME()))
             ->setValue($this->id->name);
        $form->addInput("description", new FullHtml(\I18n::NAME()))
             ->setValue($this->id->description);
    }
    
    public function onProceed($data) {
        $this->id->name        = $data["name"] ;
        $this->id->description = $data["description"] ;
        $this->id->update() ;
    }
    
}
