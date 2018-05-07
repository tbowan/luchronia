<?php

namespace Services\Game\Ministry\Health ;

class Repatriate extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::SETUP_REPATRIATE_MESSAGE()) ;
        $form->addInput("allowed", new \Quantyl\Form\Fields\Boolean(\I18n::ALLOWED()))
             ->setValue($this->city->repatriate_allowed);
        $form->addInput("cost", new \Quantyl\Form\Fields\Float(\I18n::COST_KM()))
             ->setValue($this->city->repatriate_cost);
    }
    
    public function onProceed($data) {
        
        if ($data["cost"] < 0) {
            throw new \Exception(\I18n::EXP_REPATRIATE_COST_NEGATIVE()) ;
        }
        
        $this->city->repatriate_allowed = $data["allowed"] ;
        $this->city->repatriate_cost = $data["cost"] ;
        $this->city->update() ;
    }
    
}
