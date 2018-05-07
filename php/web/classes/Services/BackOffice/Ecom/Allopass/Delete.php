<?php

namespace Services\BackOffice\Ecom\Allopass ;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("product", new \Quantyl\Form\Model\Id(\Model\Ecom\Allopass\Product::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::ALLOPASS_PRODUCT_DELETE($this->product->name)) ;
    }
    
    public function onProceed($data) {
        $this->product->delete() ;
    }
    
}
