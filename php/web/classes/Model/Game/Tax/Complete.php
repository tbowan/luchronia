<?php

namespace Model\Game\Tax ;

class Complete {
    
    private $_fix ;
    private $_var ;
    
    public function __construct($fix, $var) {
        $this->_fix = $fix ;
        $this->_var = $var ;
    }
    
    public function getFix() {
        return $this->_fix ;
    }
    
    public function getVar() {
        return $this->_var ;
    }
    
    public function addFix($amount) {
        $this->_fix += $amount ;
    }

    public function addVar($amount) {
        $this->_var += $amount ;
    }

    
    public function getTax($amount) {
        return $this->_fix + $amount * $this->_var ;
    }
    
    private function _addTaxCity(\Model\Game\City $c) {
        $tax = City::GetFromCity($c) ;
        $this->_fix += $tax->fix ;
        $this->_var += $tax->var ;
    }
    
    private function _addTaxStranger(\Model\Game\City $c) {
        $tax = Stranger::GetFromCity($c) ;
        $this->_fix += $tax->fix ;
        $this->_var += $tax->var ;
    }
    
    private function _addTaxBuilding(\Model\Game\City $c, \Model\Game\Building\Job $j) {
        $tax = Building::GetFromCityAndJob($c, $j) ;
        $this->_fix += $tax->fix ;
        $this->_var += $tax->var ;
    }
    
    private function _addTaxSkill(\Model\Game\City $c, \Model\Game\Skill\Skill $s) {
        $tax = Skill::GetFromCityAndSkill($c, $s) ;
        $this->_fix += $tax->fix ;
        $this->_var += $tax->var ;
    }
    
    private function _addTaxMetier(\Model\Game\City $c, \Model\Game\Skill\Metier $m) {
        $tax = Metier::GetFromCityAndMetier($c, $m) ;
        $this->_fix += $tax->fix ;
        $this->_var += $tax->var ;
    }
    
    public static function Factory(
            \Model\Game\Character $char,
            \Model\Game\Skill\Skill $skill,
            \Model\Game\Building\Job $job,
            \Model\Game\City $city
            ) {
        
        if ($char->isCitizen($city)) {
            return self::FactoryCitizen($skill, $job, $city) ;
        } else {
            return self::FactoryStranger($skill, $job, $city) ;
        }
    }
    
    public static function FactoryCitizen(
            \Model\Game\Skill\Skill $skill,
            \Model\Game\Building\Job $job,
            \Model\Game\City $city) {
        
        $res = new Complete(0.0,0.0) ;
        $res->_addTaxCity($city) ;
        $res->_addTaxBuilding($city, $job) ;
        $res->_addTaxSkill($city, $skill) ;
        
        if ($skill->metier != null) {
            $res->_addTaxMetier($city, $skill->metier) ;
        }
        
        return $res ;
    }
    
    public static function FactoryStranger(
            \Model\Game\Skill\Skill $skill,
            \Model\Game\Building\Job $job,
            \Model\Game\City $city) {
        
        $res = self::FactoryCitizen($skill, $job, $city) ;
        $res->_addTaxStranger($city) ;
        
        return $res ;
    }
    
}
