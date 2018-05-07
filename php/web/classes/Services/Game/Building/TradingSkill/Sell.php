<?php

namespace Services\Game\Building\TradingSkill ;

class Sell extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
        $form->addInput("cs",       new \Quantyl\Form\Model\Id(\Model\Game\Skill\Character::getBddTable())) ;
    }
    
    public function getCity() {
        return $this->instance->city ;
    }
    
    private $_tax_trading ;
    
    public function init() {
        parent::init();
        $this->_tax_trading = \Model\Game\Tax\Tradable::GetFromInstance($this->instance) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->cs->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientUnauthorized() ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::SELL_SKILL_MESSAGE(
                $this->cs->skill->getImage("left-illustr"),
                $this->cs->skill->getName(),
                $this->_tax_trading->order * 100)) ;
        $form->addInput("tool", new \Form\Tool\Trading($this->cs)) ;
        $form->addInput("number", new \Quantyl\Form\Fields\Integer(\I18n::NUMBER_USE(), true)) ;
        $form->addInput("price", new \Quantyl\Form\Fields\Float(\I18n::PRICE(), true)) ;
    }
    
    public function getTime($tool) {
        $base = $this->cs->getTimeCost() ;
        $coef = ($tool == null ? $this->cs->skill->by_hand : $tool->getCoef()) ;
        return $base / $coef ;
    }
    
    public function getAmount($munition) {
        return ($munition == null ? 1.0 : 1.0 + $munition->getCoef()) * $this->cs->level ;
    }
    
    public function onProceed($data) {
        $char                               = $this->getCharacter() ;
        $city                               = $this->getCity() ;
        list($i1, $i2, $tool, $munition)    = $data["tool"] ;
        $time                               =       $data["number"] * $this->getTime($tool) ;
        $amount                             = floor($data["number"] * $this->getAmount($munition)) ;
        $price                              = $amount * $data["price"] ;
        
        $cost = $price * $this->_tax_trading->order ;
        
        if ($char->getTime() < $time) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_TIME()) ;
        } else if ($char->getCredits() < $cost) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_CREDITS($cost, $char->getCredits())) ;
        }
        
        // Then, proceed
        
        $offer = \Model\Game\Trading\Skill::createFromValues(array(
            "character" => $char,
            "instance"  => $this->instance,
            "skill"     => $this->cs->skill,
            "total"     => $amount,
            "remain"    => $amount,
            "price"     => $data["price"],
            "time"      => $this->getTime($tool) / $this->cs->level,
            "use"       => 1.0 / $this->cs->level
        )) ;
        
        // Pay tax and time
        $city->addCredits($cost) ;
        $char->addCredits(- $cost) ;
        $char->addTime( - $time) ;
        $char->update() ;
        
        // Tool
        if ($i1 != null) {
            $i1->amount -= 0.01 ;
            $i1->update() ;
        }
        
        if ($i2 != null) {
            $i2->amount -= $munition->amount ;
            $i2->update() ;
        }
        
    }
    
}
