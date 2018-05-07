<?php

namespace Services\BackOffice\Ecom\Allopass ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("product", new \Quantyl\Form\Model\Id(\Model\Ecom\Allopass\Product::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("name", new \Quantyl\Form\Fields\Text(\I18n::NAME()))
             ->setValue($this->product->name);
        $form->addInput("idd", new \Quantyl\Form\Fields\Text(\I18n::ID()))
             ->setValue($this->product->idd);
        $form->addInput("amount", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT()))
             ->setValue($this->product->amount);
    }
    
    public function onProceed($data) {
        $this->product->name   = $data["name"] ;
        $this->product->idd    = $data["idd"] ;
        $this->product->amount = $data["amount"] ;
        $this->product->update() ;
    }
    
}
