<?php

namespace Widget\Game\Ministry\Building ;

class Base extends \Quantyl\Answer\Widget {
    
    protected $_instance ;
    protected $_character ;
    
    public function __construct(\Model\Game\Building\Instance $instance, \Model\Game\Character $character) {
        $this->_instance  = $instance ;
        $this->_character = $character ;
    }
    
    public function getBuildingSummary() {
        
        $res = "<h2>" . \I18n::BUILDING_SUMMARY() . "</h2>" ;
        $res .= $this->_instance->getImage("left-illustr") ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY() . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_instance->city->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_JOB() . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_instance->job->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_TYPE()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_instance->type->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_instance->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_instance->getHealth() . "</li>" ;
        $res .= "</ul>" ;
        
        return $res ;
    }
    
    public function getBuildingStock() {
        return "" ;
    }
    
        
    public function getTax() {
        $res = "<h2>" . \I18n::TAX() . "</h2>" ;
        
        $tax = \Model\Game\Tax\Building::GetFromCityAndJob($this->_instance->city, $this->_instance->job) ;
        $res .= new \Answer\Widget\Misc\Card(
                \I18n::SKILL_TAX(),
                ""
                . \I18n::BUILDING_TAX_MESSAGE($tax->fix, $tax->var)
                . new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeBuilding?city={$this->_instance->city->id}&job={$this->_instance->job->id}", \I18n::CHANGE_TAX())
                ) ;
        $res .= $this->getTradingTax() ;
        
        return $res ;
    }
    
    public function getTradingTax() {
        if ($this->_instance->job->tradable) {
            $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->_instance) ;
            return new \Answer\Widget\Misc\Card(
                \I18n::TRADING_TAX(),
                ""
                . \I18n::BUILDING_TRADING_TAX_MESSAGE($tax->order * 100, $tax->trans * 100)
                . new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeTradable?instance={$this->_instance->id}", \I18n::CHANGE_TAX())
                ) ;
        } else {
            return "" ;
        }
    }
    
    public function getSpecific() {
        return "" ;
    }
    
    public function getContent() {
        
        return ""
                . $this->getBuildingSummary()
                . $this->getTax()
                . $this->getBuildingStock()
                . $this->getSpecific() ;
    }
    
}
