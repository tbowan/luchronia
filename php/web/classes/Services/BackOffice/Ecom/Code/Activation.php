<?php

namespace Services\BackOffice\Ecom\Code ;

class Activation extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("bonus", new \Quantyl\Form\Model\Id(\Model\Ecom\Code\Bonus::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        if ($this->bonus->active) {
            $form->addMessage(\I18n::PROMO_CODE_UNACTIVATE($this->bonus->code)) ;
        } else {
            $form->addMessage(\I18n::PROMO_CODE_ACTIVATE($this->bonus->code)) ;
        }
        
    }
    
    public function onProceed($data) {
        $this->bonus->active = ! $this->bonus->active ;
        $this->bonus->update() ;
    }
    
    
    
}
