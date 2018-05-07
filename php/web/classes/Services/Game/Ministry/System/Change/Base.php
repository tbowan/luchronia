<?php

namespace Services\Game\Ministry\System\Change ;

use Model\Game\Politic\System;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Form;

abstract class Base extends \Services\Base\Character {
    
    private $_current ;
    
    public function getClass() {
        return preg_replace("/.*\\\\/", "", get_class($this)) ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
        $form->addInput("revolt", new \Quantyl\Form\Fields\Boolean()) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $this->_current = System::LastFromCity($this->city) ;
        
        if ($this->revolt && ! $this->getCharacter()->isCitizen($this->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REVOLUTION_NEED_CITIZENSHIP()) ;
        } else if ($this->_current->type->equals(\Model\Game\Politic\SystemType::Revolution())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if (! $this->revolt && ! $this->_current->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("general", $this->getGeneralFieldset()) ;
        $specific = $this->getSpecificFieldSet() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }
        
    public function getGeneralFieldset() {
        $fs = new FieldSet(\I18n::GENERAL_INFO()) ;
        $fs->addInput("name", new \Quantyl\Form\Fields\Text(\I18n::NAME())) ;
        $fs->addInput("message", new FilteredHtml(\I18n::MESSAGE())) ;
        return $fs ;
    }
    
    public function doRevolt(System $target, $data) {
        $char = $this->getCharacter() ;
        $city = $this->city ;
        
        $system = System::createFromValues(array(
            "city" => $city,
            "type" => \Model\Game\Politic\SystemType::Revolution(),
        )) ;
        
        $revolution = \Model\Game\Politic\Revolution::createFromValues(array(
            "system" => $system,
            "proposed" => $target,
            "character" => $char,
            "message" => $data["message"]
        )) ;
        
        \Model\Game\Politic\Support::doSupport($revolution, $this->getCharacter()) ;
        
        return $system ;
    }
    
    public function doChange(System $target, $data) {
        
        $current = System::LastFromCity($this->city) ;
        $change = $current->tryChange($target) ;
        
        return $change ;
    }
    
    public function onProceed($data) {
        
        if (isset($data["specific"])) {
            $spe = $data["specific"] ;
        } else {
            $spe = null ;
        }
        
        $target = $this->createTargetSystem($data["general"]["name"], $spe) ;
        
        if ($this->revolt) {
            $this->doRevolt($target, $data["general"]) ;
        } else {
            $this->doChange($target, $data["general"]) ;
        }
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/System/?system={$target->id}")) ;
    }
    
    public abstract function getSpecificFieldSet() ;
    
    public abstract function createTargetSystem($name, $specific) ;

}
