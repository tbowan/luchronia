<?php

namespace Services\Game\Ministry\Development\Upgrade ;

use Form\MapChoser;
use Model\Game\Building\Instance;
use Model\Game\Politic\Ministry;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Development") ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("instance", new Id(Instance::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        
        if (! $this->instance->job->equals(\Model\Game\Building\Job::GetByName("Road"))) {
            $form->setMessage(\I18n::BUILDING_CREATION_CHOSEMAP_MESSAGE()) ;
            $form->addInput("map", new MapChoser($this->getCharacter(), \I18n::BUILDING_MAP()))
                 ->SetInstance($this->instance) ;
        }
        
    }
    
    public function getView() {
        $jobname    = $this->instance->job->name ;
        
        $params     = "?instance={$this->instance->id}" ;
        
        $classname  = "\\Services\\Game\\Ministry\\Development\\Upgrade\\$jobname" ;
        $best       = "/Game/Ministry/Development/Upgrade/$jobname" ;
        $defaut     = "/Game/Ministry/Development/Upgrade/Misc" ;
        
        if (class_exists($classname)) {
            return new Redirect("{$best}{$params}") ;
        } else {
            return new Redirect("{$defaut}{$params}") ;
        }
    }
    
    public function onProceed($data) {
        
        $inventory  = $data["map"] ;
        $jobname    = $this->instance->job->name ;
        
        $params     = "?instance={$this->instance->id}&inventory={$inventory->id}" ;
        
        $classname  = "\\Services\\Game\\Ministry\\Development\\Upgrade\\$jobname" ;
        $best       = "/Game/Ministry/Development/Upgrade/$jobname" ;
        $defaut     = "/Game/Ministry/Development/Upgrade/Defaut" ;
        
        if (class_exists($classname)) {
            $this->setAnswer(new Redirect("{$best}{$params}")) ;
        } else {
            $this->setAnswer(new Redirect("{$defaut}{$params}")) ;
        }
        
    }
}
