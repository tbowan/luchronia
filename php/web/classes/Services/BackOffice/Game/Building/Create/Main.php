<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Job;
use Model\Game\City;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class Main extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new Id(City::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("job",    new Select(Job::getBddTable(), \I18n::BUILDING_JOB(), true)) ;
    }
    
    public function onProceed($data) {
        
        $city = $this->city->id ;
        $job  = $data["job"]->name ;
        
        if (class_exists("\\Services\\BackOffice\\Game\\Building\\Create\\$job")) {
            $this->setAnswer(new Redirect("/BackOffice/Game/Building/Create/$job?city=$city&job=$job")) ;
        } else {
            $this->setAnswer(new Redirect("/BackOffice/Game/Building/Create/Defaut?city=$city&job=$job")) ;
        }
        
    }
    
}
