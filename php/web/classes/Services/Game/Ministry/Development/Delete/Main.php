<?php

namespace Services\Game\Ministry\Development\Delete ;

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
        
        $jobname = $this->instance->job->name ;
        
        $params     = "?instance={$this->instance->id}" ;
        
        $classname  = "\\Services\\Game\\Ministry\\Development\\Delete\\$jobname" ;
        $best       = "/Game/Ministry/Development/Delete/$jobname" ;
        $defaut     = "/Game/Ministry/Development/Delete/Defaut" ;
        
        if (class_exists($classname)) {
            return new Redirect("{$best}{$params}") ;
        } else {
            return new Redirect("{$defaut}{$params}") ;
        }
        
    }
    
}
