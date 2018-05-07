<?php

namespace Services\BackOffice\Game\Building\Delete ;

use Model\Game\Building\Instance;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Instance::getBddTable())) ;
    }
    
    public function getView() {
        
        $instance = $this->id->id ;
        $job      = $this->id->job->name ;
        
        if (class_exists("\\Services\\BackOffice\\Game\\Building\\Delete\\$job")) {
            return new Redirect("/BackOffice/Game/Building/Delete/$job?id=$instance") ;
        } else {
            return new Redirect("/BackOffice/Game/Building/Delete/Defaut?id=$instance") ;
        }
        
    }
    
}
