<?php

namespace Services\BackOffice\Game\Trading ;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("order", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Nation::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->order->item->getName())) ;
    }
    
    public function onProceed($data) {
        $this->order->delete() ;
    }
    
}
