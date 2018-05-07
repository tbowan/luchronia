<?php

namespace Services\Game\Ministry\Tax ;

class ChangeStranger extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("HomeLand") ;
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
    
    public function getMessage() {
        $res = \I18n::CHANGE_TAX_MESSAGE() ;
        $res .= \I18n::CHANGE_TAX_STRANGER_MESSAGE() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::TAX_PART(),
            \I18n::TAX_FIX(),
            \I18n::TAX_VAR()
        )) ;
        
        $fix = 0 ;
        $var = 0 ;
        
        $this->addCityTax($fix, $var, $table) ;
        $table->addRow(array(\I18n::TOTAL_CITIZEN(), $fix, $var)) ;
        $this->addStrangerTax($fix, $var, $table) ;
        $table->addRow(array(\I18n::TOTAL_STRANGERS(), $fix, $var)) ;
        
        $res .= $table ;
        return $res ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage($this->getMessage()) ;
        $tax = \Model\Game\Tax\Stranger::GetFromCity($this->city) ;
        $form->addInput("fix", new \Quantyl\Form\Fields\Float(\I18n::TAX_FIX()))
             ->setValue($tax->fix);
        $form->addInput("var", new \Quantyl\Form\Fields\Float(\I18n::TAX_VAR()))
             ->setValue($tax->var);
    }
    
    public function onProceed($data) {
        
        $tax = \Model\Game\Tax\Stranger::GetFromCity($this->city) ;
        $tax->fix = $data["fix"] ;
        $tax->var = $data["var"] ;
        $tax->update() ;
    }
    
    
}
