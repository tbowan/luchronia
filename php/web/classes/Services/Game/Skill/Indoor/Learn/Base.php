<?php

namespace Services\Game\Skill\Indoor\Learn ;

abstract class Base extends \Services\Game\Skill\Indoor\Base {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        parent::fillParamForm($form);
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;        
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // stocks and instance are same
        if (! $this->inst->equals($this->stock->instance)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_STOCK_INSTANCE_MUST_SAME()) ;
        }
        
        $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($this->getCharacteristic()) ;
        if (! $learn->skill->equals($this->cs->skill)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_MUST_BE_SAME_CHARACTERISTIC()) ;
        }
    }

    public function getComplete() {
        $tax = parent::getComplete();
        $tax->addFix($this->stock->price) ;
        return $tax ;
    }
    
    public function getToolInput() {
        return new \Form\Tool\Learn($this->cs, $this->getComplete());
    }
    
    public function doStuff($amount, $data) {
        
        $msg = "" ;
        $msg .= $this->proceedLearning($amount) ;
        $msg .= parent::doStuff($amount, $data) ;
        return $msg ;
        
    }
    
    public abstract function getCharacteristic() ;
    
    public abstract function proceedLearning($data) ;
    
}