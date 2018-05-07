<?php

namespace Services\BackOffice\Ecom\Allopass ;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("name", new \Quantyl\Form\Fields\Text(\I18n::NAME())) ;
        $form->addInput("idd", new \Quantyl\Form\Fields\Text(\I18n::ID())) ;
        $form->addInput("amount", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
    }
    
    public function onProceed($data) {
        \Model\Ecom\Allopass\Product::createFromValues(array(
            "name" => $data["name"],
            "amount" => $data["amount"],
            "idd" => $data["idd"]
        )) ;
    }
    
}
