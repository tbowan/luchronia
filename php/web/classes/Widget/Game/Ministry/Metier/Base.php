<?php

namespace Widget\Game\Ministry\Metier ;

class Base extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_metier ;
    private $_character ;
    
    public function __construct(
            \Model\Game\City $city,
            \Model\Game\Skill\Metier $metier,
            \Model\Game\Character $character
            ) {
        $this->_city = $city ;
        $this->_metier = $metier ;
        $this->_character = $character ;
    }
    
    public function getDescription() {
        $res = $this->_metier->getMedalImg($_SESSION["char"], "left-illustr") ;
        $res .= "<h2>" . \I18n::DESCRIPTION() . "</h2>" ;
        $res .= $this->_metier->getDescription() ;
        return $res ;
    }
    
    public function getTax() {
        $res = "<h2>" . \I18n::TAX() . "</h2>" ;
        $tax = \Model\Game\Tax\Metier::GetFromCityAndMetier($this->_city, $this->_metier) ;
        $res .= \I18n::METIER_TAX_MESSAGE($this->_metier->ministry->getName(), $tax->fix, $tax->var) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeMetier?city={$this->_city->id}&metier={$this->_metier->id}", \I18n::CHANGE_TAX()) ;
        return $res ;
    }
    
    public function getSkills() {
        $res = "<h2>" . \I18n::SKILL_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::TAX_FIX(),
            \I18n::TAX_VAR(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Skill\Skill::getFromMetier($this->_metier) as $s) {
            $tax = \Model\Game\Tax\Skill::GetFromCityAndSkill($this->_city, $s) ;
            
            $table->addRow(array(
                $s->getImage("icone-med"),
                $s->getName(),
                $tax->fix,
                $tax->var,
                new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeSkill?city={$this->_city->id}&skill={$s->id}", \I18n::CHANGE_TAX())
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
    public function getContent() {
        return ""
                . $this->getDescription()
                . $this->getTax()
                . $this->getSkills() ;
    }
    
}
