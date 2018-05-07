<?php

namespace Services\Game\Building\TradingSkill\Buy ;

class Base extends \Services\Base\Door{
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Skill::getBddTable())) ;
    }
    
    protected $_tax_skill ;
    protected $_tax_trade ;
    
    protected $_price ;
    protected $_tax ;
    
    public function init() {
        parent::init();
        
        $this->_tax_skill = \Model\Game\Tax\Complete::Factory($this->getCharacter(), $this->sell->skill, $this->sell->instance->job, $this->sell->instance->city) ;
        $this->_tax_trade = \Model\Game\Tax\Tradable::GetFromInstance($this->sell->instance) ;
        
        $this->_price = $this->sell->price ;
        $this->_tax = 0.0
                + $this->sell->price * $this->_tax_trade->trans
                + $this->_tax_skill->getVar()
                + $this->_tax_skill->getFix() * $this->sell->use ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::BUY_TRADINGSKILL_MESSAGE(
                $this->sell->skill->getName(),
                $this->sell->remain,
                $this->_price + $this->_tax,
                $this->_price,
                $this->sell->price * $this->_tax_trade->trans,
                $this->_tax_skill->getVar() + $this->_tax_skill->getFix() * $this->sell->use
                )) ;
        
        $form->addInput("amount", new \Quantyl\Form\Fields\Integer(\I18n::AMOUNT(), true)) ;
        
        $this->specificForm($form) ;
        
    }
    
    public function onProceed($data) {
        $amount = $data["amount"] ;
        $cost   = $amount * $this->_price ;
        $tax    = $amount * $this->_tax ;
        $me     = $this->getCharacter() ;
        $city   = $this->getCity() ;
        
        // Checks
        if ($amount > $this->sell->remain) {
            throw new \Exception(\I18n::EXP_QUANTITY_TOO_HIGH($this->sell->remain)) ;
        } else if ($amount < 1) {
            throw new \Exception(\I18n::EXP_QUANTITY_TOO_LOW(1)) ;
        } else if ($me->getCredits() < $cost + $tax) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_MONEY($cost + $tax)) ;
        }
        
        // Specific proceed
        $this->specificProceed($data) ;
        
        // General proceed
        // 1. Me
        $me->addCredits(- ($cost + $tax)) ;
        $me->update() ;
        
        // 2. City
        $city->addCredits($tax) ;
        
        // 3. Sell
        $this->sell->remain -= $amount ;
        $this->sell->update() ;
        
        if($this->sell->remain == 0){
            \Model\Event\Listening::Social_Commerce_Skill($this->sell) ;
        }        
        
    }
    
    public function getCity() {
        return $this->sell->instance->city ;
    }
    
    
    public function specificForm(\Quantyl\Form\Form &$form) {
        return null ;
    }
    
    public function specificProceed($data) {
        return ;
    }
    
    public function getTitle() {
        if ($this->sell != null) {
            return \I18n::TITLE_BUY_SKILL($this->sell->skill->getName()) ;
        } else {
            return parent::getTitle();
        }
    }
    
}
