<?php

namespace Services\BackOffice\Ecom\Code ;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::ADD_CODE_PROMO_MESSAGE()) ;
        
        $form->addInput("code",     new \Quantyl\Form\Fields\Text(\I18n::CODE_CODE())) ;
        $form->addInput("from",     new \Quantyl\Form\Fields\DateTime(\I18n::CODE_FROM())) ;
        $form->addInput("to",       new \Quantyl\Form\Fields\DateTime(\I18n::CODE_TO())) ;
        $form->addInput("amount",   new \Quantyl\Form\Fields\Integer(\I18n::CODE_AMOUNT())) ;
        $form->addInput("rate",     new \Quantyl\Form\Fields\Percentage(\I18n::CODE_RATE())) ;
        $form->addInput("max_u",    new \Quantyl\Form\Fields\Integer(\I18n::CODE_MAX_U())) ;
        $form->addInput("max_t",    new \Quantyl\Form\Fields\Integer(\I18n::CODE_MAX_T())) ;
        
    }
    
    public function onProceed($data) {
        \Model\Ecom\Code\Bonus::createFromValues(array(
            "code"      => $data["code"],
            "from"      => $data["from"],
            "to"        => $data["to"],
            "amount"    => $data["amount"],
            "rate"      => $data["rate"],
            "max_u"     => $data["max_u"],
            "max_t"     => $data["max_t"],
            "active"    => false
        )) ;
    }
    
}
