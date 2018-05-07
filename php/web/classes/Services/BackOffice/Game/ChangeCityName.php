<?php

namespace Services\BackOffice\Game ;

use Model\Game\City;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class ChangeCityName extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(City::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("name", new Text(\I18n::NAME()))
             ->setValue($this->id->name);
    }
    
    public function onProceed($data) {
        $this->id->name = $data["name"] ;
        $this->id->update() ;
    }
    
}
