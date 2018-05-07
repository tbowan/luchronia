<?php

namespace Services\Game\Ministry\Tax ;

class ChangeTradable extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }
    
    private $_tax ;
    
    public function init() {
        parent::init();
        $this->_tax = \Model\Game\Tax\Tradable::GetFromInstance($this->instance) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::CHANGE_TRADING_TAX_MESSAGE()) ;
        $form->addInput("order", new \Quantyl\Form\Fields\Float(\I18n::TAX_ORDER()))
             ->setValue($this->_tax->order * 100.0);
        $form->addInput("trans", new \Quantyl\Form\Fields\Float(\I18n::TAX_TRANS()))
             ->setValue($this->_tax->trans * 100.0);
        return $form ;
    }
    
    public function onProceed($data) {
        $this->_tax->order = $data["order"] / 100 ;
        $this->_tax->trans = $data["trans"] / 100 ;
        $this->_tax->update() ;
    }
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return $this->instance->job->ministry ;
    }

}
