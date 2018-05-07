<?php

namespace Services\Game\Ministry\Tax ;

class ChangeSkill extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
        $form->addInput("skill", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Skill::getBddTable())) ;
    }
    
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return $this->skill->metier->ministry ;
    }

    public function addCityTax(&$fix, &$var, &$table) {
        $tax = \Model\Game\Tax\City::GetFromCity($this->city) ;
        $table->addRow(array(\I18n::CITY(), $tax->fix, $tax->var)) ;
        $fix += $tax->fix ;
        $var += $tax->var ;
    }
    
    public function addStrangerTax(&$fix, &$var, &$table) {
        $tax = \Model\Game\Tax\Stranger::GetFromCity($this->city) ;
        $table->addRow(array(\I18n::STRANGERS(), $tax->fix, $tax->var)) ;
        $fix += $tax->fix ;
        $var += $tax->var ;
    }
    
    public function addBuildingTax(&$fix, &$var, &$table) {
        if ($this->skill->building_job != null) {
            $tax = \Model\Game\Tax\Building::GetFromCityAndJob($this->city, $this->skill->building_job) ;
            $table->addRow(array(\I18n::BUILDING(), $tax->fix, $tax->var)) ;
            $fix += $tax->fix ;
            $var += $tax->var ;
        }
    }
    
    public function addMetierTax(&$fix, &$var, &$table) {
        if ($this->skill->metier != null) {
            $tax = \Model\Game\Tax\Metier::GetFromCityAndMetier($this->city, $this->skill->metier) ;
            $table->addRow(array(\I18n::METIER(), $tax->fix, $tax->var)) ;
            $fix += $tax->fix ;
            $var += $tax->var ;
        }
    }
    
    public function addSkillTax(&$fix, &$var, &$table) {
        $tax = \Model\Game\Tax\Skill::GetFromCityAndSkill($this->city, $this->skill) ;
        $table->addRow(array(\I18n::SKILL(), $tax->fix, $tax->var)) ;
        $fix += $tax->fix ;
        $var += $tax->var ;
    }
    
    public function getMessage() {
        $res = \I18n::CHANGE_TAX_MESSAGE() ;
        $res .= \I18n::CHANGE_TAX_SKILL_MESSAGE($this->skill->getName()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SOURCE(),
            \I18n::TAX_FIX(),
            \I18n::TAX_VAR()
        )) ;
        
        $fix = 0 ;
        $var = 0 ;
        
        $this->addCityTax($fix, $var, $table) ;
        $this->addBuildingTax($fix, $var, $table) ;
        $this->addMetierTax($fix, $var, $table) ;
        $this->addSkillTax($fix, $var, $table) ;
        $table->addRow(array(\I18n::TOTAL_CITIZEN(), $fix, $var)) ;
        $this->addStrangerTax($fix, $var, $table) ;
        $table->addRow(array(\I18n::TOTAL_STRANGERS(), $fix, $var)) ;
        
        $res .= $table ;
        return $res ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage($this->getMessage()) ;
        
        $tax = \Model\Game\Tax\Skill::GetFromCityAndSkill($this->city, $this->skill) ;
        
        $form->addInput("fix", new \Quantyl\Form\Fields\Float(\I18n::TAX_FIX()))
             ->setValue($tax->fix);
        $form->addInput("var", new \Quantyl\Form\Fields\Float(\I18n::TAX_VAR()))
             ->setValue($tax->var);
    }
    
    public function onProceed($data) {
        
        $tax = \Model\Game\Tax\Skill::GetFromCityAndSkill($this->city, $this->skill) ;
        $tax->fix = $data["fix"] ;
        $tax->var = $data["var"] ;
        $tax->update() ;
    }
    
}
