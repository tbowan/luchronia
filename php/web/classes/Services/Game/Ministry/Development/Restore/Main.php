<?php

namespace Services\Game\Ministry\Development\Restore ;

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
    
    public function getView() {
        
        $jobname    = $this->instance->job->name ;
        
        $params     = "?instance={$this->instance->id}" ;
        
        $classname  = "\\Services\\Game\\Ministry\\Development\\Restore\\$jobname" ;
        $best       = "/Game/Ministry/Development/Restore/$jobname" ;
        $defaut     = "/Game/Ministry/Development/Restore/Defaut" ;
        
        if (class_exists($classname)) {
            return new Redirect("{$best}{$params}") ;
        } else {
            return new Redirect("{$defaut}{$params}") ;
        }
        
    }
}
