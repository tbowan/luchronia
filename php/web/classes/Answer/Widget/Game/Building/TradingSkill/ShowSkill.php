<?php

namespace Answer\Widget\Game\Building\TradingSkill ;

class ShowSkill extends \Quantyl\Answer\Widget {
    
    private $_instance ;
    private $_skill ;
    private $_character ;
    
    public function __construct(
            \Model\Game\Building\Instance $i,
            \Model\Game\Skill\Skill $s,
            \Model\Game\Character $me
            ) {
        parent::__construct() ;
        $this->_instance  = $i ;
        $this->_skill     = $s ;
        $this->_character = $me ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::SELL_SKILL_LIST() . "</h2>" ;
        
        $res .= \I18n::SHOW_SKILL_TRADING_MESSAGE($this->_skill->getName()) ;
        
        $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->_instance) ;
        
        $city = $this->_instance->city ;
        $tax_citizen = \Model\Game\Tax\Complete::FactoryCitizen($this->_skill, $this->_instance->job, $city) ;
        $tax_stranger = \Model\Game\Tax\Complete::FactoryStranger($this->_skill, $this->_instance->job, $city) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTER(),
            \I18n::QUANTITY(),
            \I18n::PRICE_TTC(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Skill::getForSkill($this->_instance, $this->_skill) as $sell) {
            
            $price = $sell->price * (1.0 + $tax->trans) ;
            if ($sell->character->isCitizen($city)) {
                $price += $tax_citizen->getFix() * $sell->use ;
                $price += $tax_citizen->getVar() ;
            } else {
                $price += $tax_stranger->getFix() * $sell->use ;
                $price += $tax_stranger->getVar() ;
            }
            
            $table->addRow(array(
                $sell->character->getName(),
                $sell->remain,
                $price,
                new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Buy?sell={$sell->id}", \I18n::BUY())
            )) ;
        }
        $res .= $table ;
        
        $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->_character, $this->_skill) ;
        if ($cs != null) {
            $res .= new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Sell?instance={$this->_instance->id}&cs={$cs->id}", \I18n::ADD_SELL()) ;
        }
        
        return $res ;
    }
    
}
