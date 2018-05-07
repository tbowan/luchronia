<?php

namespace Services\Game\Ministry\Building\TradingPost ;

class Tax extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("tp", new \Quantyl\Form\Model\Id(\Model\Game\Building\Tradingpost::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CHANGE_TRADINGPOST_TAX_MESSAGE()) ;
        $form->addInput("tax", new \Quantyl\Form\Fields\Float(\I18n::TAX()))
             ->setValue($this->tp->tax * 100.0);
        return $form ;
    }
    
    public function onProceed($data) {
        
        if ($data["tax"] < 0) {
            throw new \Exception(\I18n::EXP_TAX_MUST_BE_POSITIVE()) ;
        } else if ($data["tax"] > 100.0) {
            throw new \Exception(\I18n::EXP_TAX_EXCEED()) ;
        }
        
        $this->tp->tax = $data["tax"] / 100.0 ;
        $this->tp->update() ;
    }

    public function getCity() {
        return $this->tp->instance->city ;
    }

    public function getMinistry() {
        return $this->tp->instance->job->ministry ;
    }

}
